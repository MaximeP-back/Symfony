<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConferenceControllerTest extends WebTestCase
{
    public function testIndexConferences(): void
    {
        $client = static::createClient();
        $client->request('GET', '/conferences');

        $this->assertSelectorExists('h1', 'Conferences');
        $this->assertResponseIsSuccessful();
    }

    public function testCreateConferences(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/conferences/new');
        $form = $crawler->selectButton('Save')->form([
            'city' => 'Paris',
            'year' => '2078',
            'isInternational' => 'on',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/conferences');
    }

    public function testDeleteConferences(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/conferences');

        $deleteLink = $crawler->selectLink('Delete')->link();
        $client->click($deleteLink);

        $this->assertResponseRedirects('/conferences');
        $client->followRedirect();

        $this->assertSelectorNotExists('tr:contains("Chicago")');

    }


}