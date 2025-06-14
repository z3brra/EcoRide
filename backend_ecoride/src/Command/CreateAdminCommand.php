<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Service\StringHelper;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Créer le premier administrateur du système.'
)]
final class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,

        private StringHelper $stringHelper,

        private string $defaultPassword,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $questionPseudo = new Question("Veuillez entrer le pseudo de l'administrateur : ");
        $pseudo = $helper->ask($input, $output, $questionPseudo);

        $email = $this->stringHelper->generateEmail($pseudo);
        $password = $this->defaultPassword;

        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser) {
            $output->writeln('<error>Un administrateur existe déjà.</error>');
            return Command::FAILURE;
        }

        $user = new User();
        $user->setPseudo($pseudo)
            ->setEmail($email)
            ->setRoles(['ROLE_ADMIN'])
            ->setIsBanned(false)
            ->setCreatedAt(new \DateTimeImmutable());

        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('<info>Administrateur créé avec succès.</info>');
        $output->writeln("Email : $email");
        $output->writeln("Mot de passe : $password");

        return Command::SUCCESS;
    }
}
