<?php
declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserTest extends WebTestCase
{
    protected function createAuthenticatedClient(): KernelBrowser
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
               "username":"seneelhadjibabacar@gmail.com",
               "password":"password"
           }'
        );
        $data = json_decode($client->getResponse()->getContent(), true);
        //dd($data);
        $client->setServerParameter('HTTP_AUTHORIZATION', \sprintf('Bearer %s', $data['token']));
        $client->setServerParameter('CONTENT_TYPE', 'application/json');
        //dd($client);
        return $client;
    }
    //collectionOperation
    public function testGetUsers(): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', 'admin/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}