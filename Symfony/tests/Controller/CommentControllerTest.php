<?php

namespace App\Tests\Controller;



use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class CommentControllerTest extends ApiTestCase
{
    public function testIndexComment(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/comments');
        $this->assertResponseIsSuccessful();
    }

    public function testCreateComment(): void
    {
        $client = static::createClient();
        $response = $client->request('POST', '/Comment/new', [
            'json' => [
                'author' => 'Testerboy',
                'text' => 'TestComment',
                'email' => 'test@test.fr',
                'photo_filename' => 'test',
                'conference_id' => '1',
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);
    }

}