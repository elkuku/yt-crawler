<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use UnexpectedValueException;

class UserAdminCommand extends Command
{
    protected static $defaultName = 'user-admin';// Type must be defined in base class :(

    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    protected function configure(): void
    {
        $this->setDescription('Administer Users');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $io = new SymfonyStyle($input, $output);

        $io->title('KuKu\'s User Admin');

        $this->showMenu($input, $output);

        return 0;
    }

    private function showMenu(
        InputInterface $input,
        OutputInterface $output
    ): void {
        $io = new SymfonyStyle($input, $output);

        $users = $this->entityManager->getRepository(User::class)->findAll();

        $io->text(
            sprintf(
                '<fg=cyan>There are %d users in the database.</>',
                count($users)
            )
        );

        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Please select an option (defaults to exit)',
            [
                'List Users',
                'Create User',
                'Edit User',
                'Delete User',
                'Exit',
            ],
            4
        );
        $question->setErrorMessage('Choice %s is invalid.');

        $answer = $helper->ask($input, $output, $question);
        $output->writeln($answer);

        try {
            switch ($answer) {
                case 'List Users':
                    $this->renderUsersTable($output, $users);
                    $this->showMenu($input, $output);
                    break;
                case 'Create User':
                    $email = $this->askEmail($input, $output);
                    $role = $this->askRole($input, $output);

                    $this->createUser($email, $role);
                    $io->success('User created');
                    $this->showMenu($input, $output);
                    break;
                case 'Edit User':
                    $io->text('Edit not implemented yet :(');
                    $this->showMenu($input, $output);
                    break;
                case 'Delete User':
                    $id = $helper->ask(
                        $input,
                        $output,
                        new Question('User ID to delete: ')
                    );
                    $this->deleteUser($id);
                    $io->success('User has been removed');
                    $this->showMenu($input, $output);
                    break;
                case 'Exit':
                    $io->text('have Fun =;)');
                    break;
                default:
                    throw new UnexpectedValueException(
                        'Unknown answer: '.$answer
                    );
            }
        } catch (Exception $exception) {
            $io->error($exception->getMessage());
            $this->showMenu($input, $output);
        }
    }

    private function renderUsersTable(
        OutputInterface $output,
        array $users
    ): void {
        $table = new Table($output);
        $table->setHeaders(['ID', 'Username', 'email', 'Role']);

        /* @type User $user */
        foreach ($users as $user) {
            $table->addRow(
                [
                    $user->getId(),
                    $user->getUsername(),
                    $user->getEmail(),
                    implode(", ", $user->getRoles()),
                ]
            );
        }
        $table->render();
    }

    private function askEmail(
        InputInterface $input,
        OutputInterface $output
    ): string {
        $email = null;
        $io = new SymfonyStyle($input, $output);
        do {
            $email = $this->getHelper('question')->ask(
                $input,
                $output,
                new Question('Email: ')
            );
            if (!$email) {
                $io->warning('e-mail is required :(');
            } else {
                $emailConstraint = new Email();
                $emailConstraint->message = 'Invalid email address';

                $errors = $this->validator->validate(
                    $email,
                    $emailConstraint
                );

                if (count($errors)) {
                    $io->warning($errors[0]->getMessage());

                    $email = null;
                }
            }
        } while ($email === null);

        return $email;
    }

    private function askRole(InputInterface $input, OutputInterface $output)
    {
        return $this->getHelper('question')->ask(
            $input,
            $output,
            (new ChoiceQuestion(
                'User role (ROLE_USER)',
                [
                    'ROLE_USER',
                    'ROLE_ADMIN',
                ],
                0
            ))
                ->setErrorMessage('Choice %s is invalid.')
        );
    }

    private function createUser(string $email, string $role): void
    {
        $user = (new User())
            ->setEmail($email)
            ->setRole($role);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function deleteUser(int $id): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(
            ['id' => $id]
        );

        if (!$user) {
            throw new UnexpectedValueException('User not found!');
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
