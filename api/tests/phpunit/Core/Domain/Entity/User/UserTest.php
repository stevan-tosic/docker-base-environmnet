<?php

use App\Core\Domain\Entity\User\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUser()
    {
        $id = 1;
        $email = 'user@test.test';
        $firstname = 'John';
        $lastname = 'Doe';
        $password = 'password';
        $jobTitle = 'CEO';
        $token = 'token';
        $date = new DateTime();
        $lastAccess = $date;
        $uuid = call_user_func('Ramsey\Uuid\Uuid::uuid1');

        $instance = new User();
        $instance->setId($id);
        $instance->setEmail($email);
        $instance->setFirstName($firstname);
        $instance->setLastName($lastname);
        $instance->setModified($date);
        $instance->setCreated($date);
        $instance->setEnabled(User::ENABLED);
        $instance->setPassword($password);
        $instance->setJobTitle($jobTitle);
        $instance->setConfirmationToken($token);
        $instance->setPlainPassword($password);
        $instance->setConfirmationTokenIssueDate(new DateTime());
        $instance->setConfirmationTokenValid(true);
        $instance->serialize();
        $instance->setLastAccess($lastAccess);
        $instance->setUuid($uuid);

        static::assertEquals(1, $instance->getId());
        static::assertEquals($email, $instance->getEmail());
        static::assertEquals($email, $instance->getUsername());
        static::assertEquals($firstname, $instance->getFirstname());
        static::assertEquals($lastname, $instance->getLastname());
        static::assertEquals($firstname .' '. $lastname, $instance->getFullName());
        static::assertEquals($jobTitle, $instance->getJobTitle());
        static::assertEquals($token, $instance->getConfirmationToken());
        static::assertInstanceOf(DateTime::class, $instance->getConfirmationTokenIssueDate());
        static::assertTrue($instance->getConfirmationTokenValid());
        static::assertEquals($date, $instance->getCreated());
        static::assertEquals($date, $instance->getModified());
        static::assertEquals(User::ENABLED, $instance->isEnabled());
        static::assertEquals($password, $instance->getPlainPassword());
        static::assertEquals($password, $instance->getPassword());
        static::assertEquals($uuid, $instance->getUuid());
        static::assertInternalType('string',$instance->getUuidToString());
        static::assertFalse($instance->hasAdminRole());
        static::assertEquals(['ROLE_USER'], $instance->getRoles());
        static::assertEquals(null, $instance->getSalt());
        static::assertEquals(null, $instance->eraseCredentials());
        static::assertEquals(null, $instance->unserialize(serialize([
            $id = 1,
            $email = 2,
            $password = 3,
        ])));
        static::assertEquals($lastAccess, $instance->getLastAccess());

        // Add Roles
        $instance->addRole('ROLE_USER');
        $instance->addRole('ROLE_ADMIN');
        static::assertEquals(true, $instance->hasRole('ROLE_ADMIN'));

        // Clear Roles
        $instance->clearRoles();
        $this->assertEquals(['ROLE_USER'], $instance->getRoles());

        static::assertEmpty($instance->getSalt());
        static::assertEmpty($instance->eraseCredentials());
    }

    public function testGetHighestRole() {
        $instance = new User();
        $instance = $instance->addRole("ROLE_PROGRAMME_ADMIN");
        $result = $instance->getHighestRole();
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
    }

    public function testGetHighestRoleWithoutNonDefaultRoles() {
        $instance = new User();
        $instance = $instance->addRole("ROLE_USER");
        $result = $instance->getHighestRole();
        $this->assertEquals('ROLE_USER', $result);
    }
}
