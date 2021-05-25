<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use App\Service\YTHandler;
use App\YTApi\YTApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/video')]
class VideoController extends AbstractController
{
    #[Route('/', name: 'video_index', methods: ['GET'])]
    public function index(
        VideoRepository $videoRepository,
    ): Response {
        return $this->render(
            'video/index.html.twig',
            [
                'videos' => $videoRepository->findAll(),
            ]
        );
    }

    #[Route('/showcase', name: 'video_showcase', methods: ['GET'])]
    public function showcase(
        VideoRepository $videoRepository,
    ): Response {
        return $this->render(
            'video/showcase.html.twig',
            [
                'videos' => $videoRepository->findAll(),
            ]
        );
    }

    #[Route('/new', name: 'video_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request
    ): Response {
        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($video);
            $entityManager->flush();

            return $this->redirectToRoute('video_index');
        }

        return $this->render(
            'video/new.html.twig',
            [
                'video' => $video,
                'form'  => $form->createView(),
            ]
        );
    }

    #[Route('/{id<\d+>}', name: 'video_show', methods: ['GET'])]
    public function show(
        Video $video,
    ): Response {
        return $this->render(
            'video/show.html.twig',
            [
                'video' => $video,
            ]
        );
    }

    #[Route('/{id<\d+>}/edit', name: 'video_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Video $video
    ): Response {
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('video_index');
        }

        return $this->render(
            'video/edit.html.twig',
            [
                'video' => $video,
                'form'  => $form->createView(),
            ]
        );
    }

    #[Route('/{id}', name: 'video_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Video $video
    ): Response {
        if ($this->isCsrfTokenValid(
            'delete'.$video->getId(),
            $request->request->get('_token')
        )
        ) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($video);
            $entityManager->flush();
        }

        return $this->redirectToRoute('video_index');
    }

    #[Route('/parse-yt-url', name: 'parse_yt_url', methods: ['GET'])]
    public function parseYTURL(
        Request $request,
        YTHandler $handler
    ) {
        $q = $request->get('q');

        $id = $handler->extractYTId($q);

        if ($id) {
            return $this->json(['q' => $q, 'id' => $id]);
        } else {
            return $this->json(['error' => 'Invalid YT URL :(']);
        }
    }

    #[Route('/fetch-yt-info', name: 'fetch_yt_info', methods: ['GET'])]
    public function fetchYTInfo(
        Request $request,
        // YTHandler $handler,
        YTApi $YTApi,
    ) {
        try {
            $q = $request->get('q');

            if (!$q) {
                throw new \UnexpectedValueException('No value received');
            }

            // $info = $handler->fetchInfo($q);
            $info = $YTApi->video->info($q);

            if (!$info->title) {
                throw new \UnexpectedValueException('Video not found :(');
            }
        } catch (\Exception $exception) {
            return $this->json(['error' => $exception->getMessage()]);
        }

        return $this->json(
            [
                'id'          => $q,
                'title'       => $info->title,
                'description' => $info->description,
            ]
        );
    }

    #[Route('/search', name: 'yt_search', methods: ['GET'])]
    public function search(
        Request $request,
        YTApi $YTApi,
    ) {
        $searchResult = [];
        $q = '';

        try {
            $q = $request->get('q');

            if ($q) {
                $pageToken = $request->get('pageToken');
                $searchResult = $YTApi->search->list(
                    $q,
                    maxResults: 25,
                    pageToken: $pageToken,
                );
            }
        } catch (\Exception $exception) {
            $searchResult = ['error' => $exception->getMessage()];
        }

        return $this->render(
            'video/search.html.twig',
            [
                'result' => $searchResult,
                'q'      => $q,
            ]
        );
    }
}
