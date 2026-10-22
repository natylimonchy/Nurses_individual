<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\NurseRepository;

#[Route('/nurse')]
class NurseController extends AbstractController
{
    #[Route('/login', name: 'nurse_login', methods: ['POST'])]
    public function login(Request $request, NurseRepository $nurseRepository): JsonResponse
    {
        
        $data = json_decode($request->getContent(), true);
        if (!is_array($data) || empty($data['user']) || empty($data['password'])) {
            return $this->json([
                'success' => false,
                'message' => 'El usuario y la contraseña son obligatorios',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        
        $nurse = $nurseRepository->findOneBy(['username' => $data['user']]);


        if ($nurse === null || $nurse->getPassword() !== $data['password']) {
            return $this->json([
                'success' => false,
                'message' => 'Usuario o contraseña incorrectos',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

         return $this->json([
            'success' => true,
            'message' => 'Login correcto',
        ]);
    }
}