<?php

use App\Core\Infrastructure\Command\CreateUserCommand;
use App\Core\Infrastructure\Persistence\Doctrine\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommandTest extends TestCase
{
    private $commandName;
    private $command;
    private $container;

    public function setUp()
    {
        $this->commandName = 'create:user';
        $encoder = $this->prophesize(UserPasswordEncoderInterface::class);
        $encoder->encodePassword(Argument::any(), Argument::any())->willReturn('hashed_password');
        $userRepository = $this->prophesize(UserRepository::class);

        $command = new CreateUserCommand(null, $encoder->reveal(), $userRepository->reveal());
        $application = new Application();

//        $this->container = $this->prophesize(ContainerInterface::class);
        $application->add($command);

        $this->command = $application->find($this->commandName);
//        $this->command->setContainer($this->container->reveal());
    }

    public function testExecute()
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute(['command' => $this->commandName]);

        $output = $commandTester->getDisplay();
        $this->assertEquals('User created. username: test.admin@local, password: Admin123~', trim($output));
    }
}
