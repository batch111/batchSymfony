<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccueilControllerTest extends WebTestCase
{
    public function testAccueilRoute()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $client->getResponse()->getStatusCode();
        $this->assertSame(200, $client->getResponse()->getStatusCode()); //Requête traitée avec succès. La réponse dépendra de la méthode de requête utilisée. 
    }

    public function testAdminRoute()
    {
        $client = static::createClient();
        $client->request('GET', '/admin');
        $client->getResponse()->getStatusCode();
        $this->assertSame(200, $client->getResponse()->getStatusCode()); //Requête traitée avec succès. La réponse dépendra de la méthode de requête utilisée. 
    }

    public function testContactRoute()
    {
        $client = static::createClient();
        $client->request('GET', '/contact');
        $client->getResponse()->getStatusCode();
        $this->assertSame(200, $client->getResponse()->getStatusCode()); //Requête traitée avec succès. La réponse dépendra de la méthode de requête utilisée. 
    }
}