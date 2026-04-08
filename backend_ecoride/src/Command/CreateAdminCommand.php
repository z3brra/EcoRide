<?php

namespace App\Command;

use App\Entity\{
    User,
    MailAccount,
};
use App\Enum\MailAccountTypeEnum;

use App\DTO\Mcs\CreateMcsUserDTO;
use App\Exception\McsException;

use App\Service\Mcs\McsUserService;
use App\Service\StringHelper;

use DateTimeImmutable;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



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
        private McsUserService $mcsUserService,

        private string $defaultPassword,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $questionPseudo = new Question("Veuillez entrer le pseudo de l'administrateur : ");
        $pseudo = $helper->ask($input, $output, $questionPseudo);

        if (!is_string($pseudo) || trim($pseudo) === '') {
            $output->writeln('<error>Le pseudo est requis.</error>');
            return Command::FAILURE;
        }
        
        $pseudo = trim($pseudo);
        $email = $this->stringHelper->generateEmail($pseudo);
        $password = $this->defaultPassword;

        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser) {
            $output->writeln('<error>Un administrateur existe déjà.</error>');
            return Command::FAILURE;
        }

        $existingMailAccount = $this->entityManager->getRepository(MailAccount::class)->findOneBy(['email' => $email]);
        if ($existingMailAccount instanceof MailAccount) {
            $output->writeln('<error>Une boîte mail localae existe déjà avec cette addresse.</error>');
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

        $this->entityManager->beginTransaction();

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $mcsUser = $this->mcsUserService->create(
                new CreateMcsUserDTO(
                    email: $email,
                    plainPassword: $password,
                    active: true
                )
            );

            $mailAccount = new MailAccount();
            $mailAccount->setUser($user)
                ->setMcsUuid($mcsUser->uuid)
                ->setDomainUuid($mcsUser->domainUuid)
                ->setEmail($mcsUser->email)
                ->setType(MailAccountTypeEnum::MAILBOX)
                ->setActive($mcsUser->active);

            $this->entityManager->persist($mailAccount);
            $this->entityManager->flush();


            $this->entityManager->commit();
        } catch (McsException $e) {
            $this->entityManager->rollback();

            $output->writeln('<error>Erreur lors de la création de la boîte mail MCS.</error>');
            $output->writeln(sprintf('Message : %s', $e->getMessage()));

            if ($e->getCodeValue() !== null) {
                $output->writeln(sprintf('Code MCS : %s', $e->getCodeValue()));
            }

            if ($e->getRequestId() !== null) {
                $output->writeln(sprintf('Request ID : %s', $e->getRequestId()));
            }

            if ($e->getDetails() !== null) {
                $output->writeln('Details :');
                $output->writeln(json_encode($e->getDetails(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }

            return Command::FAILURE;
        } catch (\Throwable $e) {
            $this->entityManager->rollback();

            $output->writeln('<error>Erreur interne lors de la création de l\'administrateur.</error>');
            $output->writeln(sprintf('Message : %s', $e->getMessage()));

            return Command::FAILURE;
        }

        $output->writeln('<info>Administrateur créer avec succès.</info>');
        $output->writeln("Email : $email");
        $output->writeln("Mot de passe : $password");
        $output->writeln('<info>Boîte mail MCS créer et liée localement.</info>');

        return Command::SUCCESS;
    }
}
