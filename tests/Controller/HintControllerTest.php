<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HintControllerTest extends WebTestCase
{
    public function testGetList()
    {
        $client = static::createClient();
        $client->request('GET', '/api/hint');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}