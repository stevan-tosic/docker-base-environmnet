<?php

namespace App\Tests\TestCase;

use App\Core\Domain\Entity\User\User;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Templating\EngineInterface;

abstract class ControllerTestCase extends TestCase
{
    /** @var  TokenStorage */
    protected $tokenStorage;

    /** @var  ContainerInterface */
    protected $container;

    /** @var TokenInterface */
    protected $token;

    /** @var User */
    protected $user;

    protected $authorizationChecker;

    protected $parameterBag;

    /** @var LoggerInterface */
    protected $logger;

    /** @var RouterInterface */
    private $router;

    protected function setUp()
    {
        $this->tokenStorage = $this->prophesize(TokenStorage::class);
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->user = $this->prophesize(User::class);
        $this->user->getRoles()->willReturn('ROLE_ADMIN');
        $this->user->getId()->willReturn(1);
        $this->token = $this->prophesize(TokenInterface::class);
        $this->token->getUser()->willReturn($this->user->reveal());
        $this->token->getRoles()->willReturn(["ROLE_USER"]);

        $this->tokenStorage->getToken()->willReturn($this->token->reveal());
        $this->container->has('security.token_storage')->willReturn(true);
        $this->container->get('security.token_storage')->willReturn($this->tokenStorage->reveal());

        $this->parameterBag = $this->prophesize(ParameterBagInterface::class);
        $this->container->has('parameter_bag')->willReturn(true);
        $this->container->get('parameter_bag')->willReturn($this->parameterBag->reveal());

        $this->container->has('security.authorization_checker')->willReturn(true);
        $this->authorizationChecker = $this->prophesize(AuthorizationCheckerInterface::class);
        $this->authorizationChecker->isGranted(Argument::any(), Argument::any())->willReturn(true);
        $this->container->get('security.authorization_checker')->willReturn($this->authorizationChecker->reveal());

        $this->logger = $this->prophesize(LoggerInterface::class);
        $this->logger->error(Argument::any())->willReturn(true);
        $this->logger->critical(Argument::any())->willReturn(true);
        $this->logger->warning(Argument::any())->willReturn(true);
        $this->logger->notice(Argument::any())->willReturn(true);
        $this->logger->debug(Argument::any())->willReturn(true);
        $this->logger->info(Argument::any())->willReturn(true);
        $this->container->get('logger')->willReturn($this->logger->reveal());

        $this->router = $this->prophesize(RouterInterface::class);
        $this->router->generate(Argument::any(), Argument::any(), Argument::any())->willReturn('https://url.example');
        $this->container->get('router')->willReturn($this->router->reveal());

        $templating = $this->prophesize(EngineInterface::class);
        $templating->render(Argument::any(), Argument::any())->willReturn(new Response());
        $this->container->has('templating')->willReturn(true);
        $this->container->get('templating')->willReturn($templating->reveal());
    }
}
