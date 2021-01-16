<?php


namespace App\Tests\Func;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfilsTest extends WebTestCase
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

    public function testGetProfil():void
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', 'admin/profils');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}