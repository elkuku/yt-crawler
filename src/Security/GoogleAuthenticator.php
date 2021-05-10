<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class GoogleAuthenticator extends SocialAuthenticator
{
    use TargetPathTrait;

    public function __construct(
        private ClientRegistry $clientRegistry,
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private UrlGeneratorInterface $urlGenerator,
        private SessionInterface $session
    ) {
    }

    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    public function getCredentials(Request $request): AccessToken
    {
        return $this->fetchAccessToken($this->getGoogleClient());
    }

    /**
     * @param AccessToken $credentials
     */
    public function getUser($credentials, UserProviderInterface $userProvider): User
    {
        /** @var GoogleUser $googleUser */
        $googleUser = $this->getGoogleClient()
            ->fetchUserFromToken($credentials);

        //		$email = $googleUser->getEmail();

        // 1) have they logged in with Google before? Easy!
        $user = $this->userRepository->findOneBy(
            ['googleId' => $googleUser->getId()]
        );

        if (!$user) {
            // Fetch user by email - @todo remove
            $user = $this->userRepository->findOneBy(
                ['email' => $googleUser->getEmail()]
            );
            if (!$user) {
                // Register new user
                $user = (new User())
                    ->setEmail($googleUser->getEmail())
                    ->setGoogleId($googleUser->getId());
            } else {
                // Update existing users google id - @todo remove
                $user->setGoogleId($googleUser->getId());
            }


            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return $user;
    }

    /**
     * @return GoogleClient
     */
    private function getGoogleClient()
    {
        return $this->clientRegistry->getClient('google');
    }

    /**
     * @param string $providerKey
     *
     * @return null|Response
     */
    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        $providerKey
    ) {
        if ($targetPath = $this->getTargetPath(
            $request->getSession(),
            $providerKey
        )
        ) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('default'));
    }

    /**
     * @return null|Response
     */
    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ) {
        $message = strtr(
            $exception->getMessageKey(),
            $exception->getMessageData()
        );
        $this->session->getFlashBag()->add('danger', $message);

        return new RedirectResponse($this->urlGenerator->generate('login'));
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     *
     * @param AuthenticationException|null $authException
     *
     */
    public function start(
        Request $request,
        AuthenticationException $authException = null
    ): RedirectResponse {
        return new RedirectResponse(
            '/connect/',
            // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }
}
