<?php

namespace App\Tests\TestCase;

use App\Core\Domain\Entity\User\User;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use ReflectionClass;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

abstract class VoterTestCase extends TestCase
{

    /** @var  TokenStorage */
    protected $tokenStorage;

    /** @var TokenInterface */
    protected $token;

    /** @var User */
    protected $user;

    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;

    protected function setUp()
    {
        $this->token = $this->prophesize(TokenInterface::class);
        $this->tokenStorage = $this->prophesize(TokenStorage::class);
        $this->user = $this->prophesize(User::class);
        $this->user->hasRole('ROLE_ADMIN')->willReturn(true);

        $this->token->getUser()->willReturn($this->user->reveal());
        $this->tokenStorage->getToken()->willReturn($this->token->reveal());

        $this->authorizationChecker = $this->prophesize(AuthorizationCheckerInterface::class);
        $this->authorizationChecker->isGranted(Argument::any(), Argument::any())->willReturn(true);

    }

    protected function callMethod($obj, $methodName, array $methodArgs) {
        $class = new ReflectionClass($obj);

        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($obj, $methodArgs);
    }
}
