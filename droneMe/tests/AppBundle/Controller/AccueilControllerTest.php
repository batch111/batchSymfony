<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccueilControllerTest extends WebTestCase
{
    public function testAccueilPage()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $client->getResponse()->getStatusCode();
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}