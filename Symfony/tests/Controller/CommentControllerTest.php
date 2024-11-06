<?php

namespace App\Tests\Controller;



use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Conference;
use App\Repository\ConferenceRepository;

class CommentControllerTest extends ApiTestCase
{
    public function testIndexComment(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/comments');
        $this->assertResponseIsSuccessful();

        $responseContent = $client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('comments', $responseData);
    }

    public function testCreateComment(): void
    {
        $client = static::createClient();

        $conferenceRepository = self::getContainer()->get(ConferenceRepository::class);
        $conference = $conferenceRepository->find(1);

        if (!$conference) {
            $conference = new Conference();
            $conference->setCity('Test City');
            $conference->setYear(2024);
            $conference->setIsInternational(true);
            $conferenceRepository->save($conference);
        }

        $response = $client->request('POST', '/comments/create', [
            'json' => [
                'author' => 'Testerboy',
                'text' => 'TestComment',
                'email' => 'test@test.fr',
                'photo_filename' => 'test',
                'conference_id' => $conference->getId(),
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);

        $responseContent = $response->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('author', $responseData);
        $this->assertArrayHasKey('text', $responseData);
        $this->assertArrayHasKey('email', $responseData);
        $this->assertArrayHasKey('photo_filename', $responseData);
        $this->assertArrayHasKey('conference', $responseData);
    }

}