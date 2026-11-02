<?php

namespace App\Tests\Controller;

use App\Entity\Nurse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NurseControllerTest extends WebTestCase
{
    // Función de ayuda: crea un enfermero válido en la BBDD de tests
    private function createNurse(): Nurse
    {
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $nurse = new Nurse();
        $nurse->setInternalId('INT' . rand(1000, 9999));
        $nurse->setFirstName('Ana');
        $nurse->setLastName('García');
        $nurse->setLicenseNumber('LIC' . rand(1000, 9999));
        $nurse->setUsername('ana' . uniqid());
        $nurse->setPassword('password123');
        $nurse->setEmail('ana@hospital.com');
        $nurse->setShift('M');

        $entityManager->persist($nurse);
        $entityManager->flush();

        return $nurse;
    }

    // Función de ayuda: hace una petición con JSON
    private function sendJson($client, string $method, string $url, string $body): void
    {
        $client->request($method, $url, [], [], ['CONTENT_TYPE' => 'application/json'], $body);
    }

    // ========== UPDATE ==========

    // 200: actualización correcta
    public function testUpdateOk(): void
    {
        $client = static::createClient();
        $nurse = $this->createNurse();

        $this->sendJson($client, 'PUT', '/nurse/' . $nurse->getId(), json_encode(['firstName' => 'Laura', 'shift' => 'T']));

        $this->assertResponseStatusCodeSame(200);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame('Laura', $response['firstName']);
        $this->assertSame('T', $response['shift']);
    }

    // 404: el enfermero no existe
    public function testUpdateNotFound(): void
    {
        $client = static::createClient();

        $this->sendJson($client, 'PUT', '/nurse/99999', json_encode(['firstName' => 'Laura']));

        $this->assertResponseStatusCodeSame(404);
    }

    // 400: el body no es JSON
    public function testUpdateInvalidJson(): void
    {
        $client = static::createClient();
        $nurse = $this->createNurse();

        $this->sendJson($client, 'PUT', '/nurse/' . $nurse->getId(), 'esto no es json');

        $this->assertResponseStatusCodeSame(400);
    }

    // 400: un dato no cumple las reglas
    public function testUpdateInvalidShift(): void
    {
        $client = static::createClient();
        $nurse = $this->createNurse();

        $this->sendJson($client, 'PUT', '/nurse/' . $nurse->getId(), json_encode(['shift' => 'X']));

        $this->assertResponseStatusCodeSame(400);
    }

    // 400: intentar cambiar el ID interno
    public function testUpdateInternalIdNotAllowed(): void
    {
        $client = static::createClient();
        $nurse = $this->createNurse();

        $this->sendJson($client, 'PUT', '/nurse/' . $nurse->getId(), json_encode(['internalId' => 'HACK']));

        $this->assertResponseStatusCodeSame(400);
    }

    // ========== LOGIN ==========

    // 200: usuario y contraseña correctos
    public function testLoginOk(): void
    {
        $client = static::createClient();
        $nurse = $this->createNurse();

        $this->sendJson($client, 'POST', '/nurse/login', json_encode(['user' => $nurse->getUsername(), 'password' => 'password123']));

        $this->assertResponseStatusCodeSame(200);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertTrue($response['success']);
    }

    // 400: faltan datos
    public function testLoginMissingData(): void
    {
        $client = static::createClient();

        $this->sendJson($client, 'POST', '/nurse/login', json_encode(['user' => 'ana']));

        $this->assertResponseStatusCodeSame(400);
    }

    // 401: contraseña incorrecta
    public function testLoginWrongPassword(): void
    {
        $client = static::createClient();
        $nurse = $this->createNurse();

        $this->sendJson($client, 'POST', '/nurse/login', json_encode(['user' => $nurse->getUsername(), 'password' => 'incorrecta']));

        $this->assertResponseStatusCodeSame(401);
    }
}