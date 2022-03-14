<?php

namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;

class HabitResourceTest extends ApiTestCase
{
    public function testCreateHabit()
    {
        $client = self::createClient();

        $client->request('POST', '/api/habits');
        $this->assertResponseStatusCodeSame(401);

        $user = new User();
        $user->setEmail('mari@eu.eu');
        $user->setUsername('Mari');
        $user->setPassword('$2y$13$bxiSvXLlvmOLaF0Ssk/3ZOoEh8TGZNRiCnPpojYzAfZZ9AOhONrkm'); //foo

        $em = self::getContainer()->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        $client->request('POST', '/login', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'mari@eu.eu',
                'password' => 'foo'
            ]
        ]);
        $this->assertResponseStatusCodeSame(204);
    }
}