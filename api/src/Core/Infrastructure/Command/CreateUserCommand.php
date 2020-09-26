<?php

namespace App\Core\Infrastructure\Command;

use App\Core\Domain\Entity\User\User;
use App\Core\Domain\Repository\User\UserRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class CreateUserCommand
 */
class CreateUserCommand extends Command
{
    private $encoder;

    /** @var UserRepositoryInterface */
    private $userRepository;

    /**
     * CreateUserCommand constructor.
     *
     * @param null|string                  $name
     * @param UserPasswordEncoderInterface $encoder
     * @param UserRepositoryInterface      $userRepository
     */
    public function __construct(
        ?string $name = null,
        UserPasswordEncoderInterface $encoder,
        UserRepositoryInterface $userRepository
    ) {
        parent::__construct($name);

        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
    }

    /**
     * Configuring console command, required inputs and help info.
     */
    protected function configure()
    {
        $this->setName('create:user')
            ->setDescription('Create admin user');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = new User();
        $user->setEmail('test.admin@local');
        $user->setFirstName('Admin');
        $user->setLastName('Ministrator');
        $user->setJobTitle('CEO');
        $user->setEnabled(true);
        $user->addRole(User::ROLE_SUPER_ADMIN);

        $encoded = $this->encoder->encodePassword($user, 'Admin123~');

        $user->setPassword($encoded);
        $this->userRepository->save($user);

        $output->writeln('<info>User created. username: test.admin@local, password: Admin123~</info>');
    }
}
