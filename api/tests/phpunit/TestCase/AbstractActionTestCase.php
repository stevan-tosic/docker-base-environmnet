<?php

namespace App\Tests\TestCase;

use App\Core\Domain\Entity\User\User;
use App\Utils\Logger\Logger;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

abstract class AbstractActionTestCase extends TestCase
{
    /** @var  TokenStorage */
    protected $tokenStorage;

    /** @var TokenInterface */
    protected $token;

    /** @var User */
    protected $user;

    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;

    /** @var LoggerInterface */
    protected $logger;

    /**
     *
     */
    protected function setUp()
    {
        $this->tokenStorage = $this->prophesize(TokenStorage::class);
        $this->user         = $this->prophesize(User::class);
        $this->user->getRoles()->willReturn('ROLE_ADMIN');
        $this->user->getId()->willReturn(1);
        $this->token = $this->prophesize(TokenInterface::class);
        $this->token->getUser()->willReturn($this->user->reveal());
        $this->token->getRoles()->willReturn(["ROLE_USER"]);

        $this->tokenStorage->getToken()->willReturn($this->token->reveal());

        $this->authorizationChecker = $this->prophesize(AuthorizationCheckerInterface::class);

        $this->authorizationChecker->isGranted(Argument::any(), Argument::any())->willReturn(true);

        $this->logger = $this->prophesize(Logger::class);
//        $this->logger->log(Argument::any(), Argument::any())->willReturn();
    }
}
