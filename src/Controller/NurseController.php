<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/nurse')]
class NurseController extends AbstractController
{
    // Datos estáticos (todavía sin base de datos)
    private array $nurses = [
        ['id' => 1, 'name' => 'Ana',   'user' => 'ana',   'password' => '1234'],
        ['id' => 2, 'name' => 'Pedro', 'user' => 'pedro', 'password' => 'abcd'],
        ['id' => 3, 'name' => 'Laura', 'user' => 'laura', 'password' => 'pass'],
    ];

    #[Route('/login', name: 'nurse_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        // Leemos el JSON que nos envían
        $data = json_decode($request->getContent(), true);
        $user = $data['user'] ?? null;
        $password = $data['password'] ?? null;

        // Buscamos si existe un enfermero con ese usuario y contraseña
        foreach ($this->nurses as $nurse) {
            if ($nurse['user'] === $user && $nurse['password'] === $password) {
                return $this->json(true);
            }
        }

        return $this->json(false, JsonResponse::HTTP_UNAUTHORIZED);
    }
}