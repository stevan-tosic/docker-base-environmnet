<?php

namespace App\Tests\TestCase;

use App\Core\Domain\Entity\User\User;
use DateTime;
use PHPUnit\Framework\TestCase;

abstract class EntityTestCase extends TestCase
{
    protected $id;
    protected $int;
    protected $ipAddress;
    protected $email;
    protected $url;
    protected $firstname;
    protected $lastname;
    protected $logo;
    protected $password;
    protected $jobTitle;
    protected $token;
    protected $date;
    protected $time;
    protected $entityName;
    protected $string;

    /** @var User */
    protected $user;

    /**
     *
     */
    protected function setUp()
    {
        $this->id = 1;
        $this->int = 1;
        $this->ipAddress = '127.0.0.1';
        $this->email = 'user@test.test';
        $this->url = 'elite-network.com';
        $this->firstname = 'John';
        $this->lastname = 'Doe';
        $this->logo = 1;
        $this->password = 'password';
        $this->jobTitle = 'CEO';
        $this->token = 'token';
        $this->entityName = 'User';
        $this->string = 'string';
        $this->date = new DateTime();
        $this->time = new DateTime();
    }
}
