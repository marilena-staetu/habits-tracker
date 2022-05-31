<?php

namespace App\Tests\Functional;

use App\Entity\Habit;
use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class HabitResourceTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    public function testCreateHabit()
    {
        $client = self::createClient();

        $client->request('POST', '/api/habits', [
            'json' => [],
        ]);
        $this->assertResponseStatusCodeSame(401);

        $authenticatedUser = $this->createUserAndLogIn($client, 'mari@eu.eu', 'foo');
        $otherUser = $this->createUser('otheruser@eu.eu', 'foo');

        $habitData = [
            "name" => "Dance"
        ];

        $client->request('POST', '/api/habits', [
        'json' => $habitData,
    ]);
        $this->assertResponseStatusCodeSame(201);



        $client->request('POST', '/api/habits', [
            'json' => $habitData + ["owner" => "/api/users/".$otherUser->getId()],
        ]);
        $this->assertResponseStatusCodeSame(422, 'not passing the correct owner');


        $client->request('POST', '/api/habits', [
            'json' => $habitData + ["owner" => "/api/users/".$authenticatedUser->getId()],
        ]);
        $this->assertResponseStatusCodeSame(201);
    }

    public function testUpdateHabit()
    {
        $client = self::createClient();
        $user1 = $this->createUser('user1@eu.eu', 'foo');
        $user2 = $this->createUser('user2@eu.eu', 'foo');
        $user3 = $this->createUser('user3@eu.eu', 'foo');
        $user3->setRoles(['ROLE_ADMIN']);

        $habit = new Habit();
        $habit->setName('Read');
        $habit->setOwner($user1);

        $em = $this->getEntityManager();
        $em->persist($habit);
        $em->flush();

        $this->logIn($client, 'user2@eu.eu', 'foo');
        $client->request('PUT', '/api/habits/'.$habit->getId(),[
            'json' => ['name' => 'updated by another user', 'owner' => '/api/users/'.$user2->getId()],
        ]);
        $this->assertResponseStatusCodeSame(404);

        $this->logIn($client, 'user3@eu.eu', 'foo');
        $client->request('PUT', '/api/habits/'.$habit->getId(),[
            'json' => ['name' => 'updated'],
        ]);
        $this->assertResponseStatusCodeSame(200);

        $this->logIn($client, 'user1@eu.eu', 'foo');
        $client->request('PUT', '/api/habits/'.$habit->getId(),[
            'json' => ['name' => 'updated by owner'],
        ]);
        $this->assertResponseStatusCodeSame(200);

    }

    public function testGetHabitCollection()
    {
        $client = self::createClient();
        $user = $this->createUserAndLogIn($client,'mari@example.com', 'foo');
        $habit1 = new Habit();
        $habit1->setName('habit1');

        $habit2 = new Habit();
        $habit2->setName('habit2');

        $habit3 = new Habit();
        $habit3->setName('habit3');

        $em = $this->getEntityManager();
        $em->persist($habit1);
        $em->persist($habit2);
        $em->persist($habit3);
        $em->flush();

        $user2 = $this->createUserAndLogIn($client,'mari2@example.com', 'foo');

        $client->request('GET', '/api/habits');
        $this->assertJsonContains(['hydra:totalItems' => 0]);
    }
    public function testGetHabitItem()
    {
        $client = self::createClient();
        $user = $this->createUserAndLogIn($client,'mari@example.com', 'foo');
        $habit1 = new Habit();
        $habit1->setName('habit1');

        $em = $this->getEntityManager();
        $em->persist($habit1);
        $em->flush();

        $user2 = $this->createUserAndLogIn($client,'mari2@example.com', 'foo');

        $client->request('GET', '/api/habits/'.$habit1->getId());
        $this->assertResponseStatusCodeSame(404);
    }



}