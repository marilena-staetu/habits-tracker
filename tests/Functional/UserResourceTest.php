<?php

namespace App\Tests\Functional;

use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use App\Entity\User;

class UserResourceTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    public function testCreateUser()
    {
        $client = self::createClient();

        $client->request('POST', '/api/users', [
            'json' => [
                'email' => 'mari@example.com',
                'username' => 'Mari',
                'password' => 'foo'
            ]
        ]);
        $this->assertResponseStatusCodeSame(201);

        $this->logIn($client, 'mari@example.com', 'foo');
    }

    public function testUpdateUser()
    {
        $client = self::createClient();
        $user = $this->createUserAndLogIn($client, 'mari@example.com', 'foo');
        $client->request('PUT', '/api/users/'.$user->getId(), [
            'json' => [
                'username' => 'newusername',
                'roles' => ['ROLE_ADMIN'] // will be ignored
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'username' => 'newusername'
        ]);
        $em = $this->getEntityManager();
        /** @var User $user */
        $user = $em->getRepository(User::class)->find($user->getId());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    public function testIsMeField()
    {
        $client = self::createClient();
        $user = $this->createUserAndLogIn($client, 'mari@example.com', 'foo');
        $client->request('GET', '/api/users/'.$user->getId());
        $this->assertJsonContains([
            'isMe' => true
        ]);
    }
}