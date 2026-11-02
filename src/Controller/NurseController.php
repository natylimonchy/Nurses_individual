<?php

namespace App\Controller;

use App\Entity\Nurse;
use App\Form\NurseType;
use App\Repository\NurseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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

    #[Route('/{id}', name: 'nurse_update', methods: ['PUT'])]
    public function edit(Request $request, Nurse $nurse, EntityManagerInterface $entityManager): JsonResponse
    {
        
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['error' => 'Datos no válidos'], 400);
        }

        
        if (isset($data['internalId'])) {
            return $this->json(['error' => 'El ID interno no se puede modificar'], 400);
        }

        
        $form = $this->createForm(NurseType::class, $nurse);
        $form->submit($data, false);

        
        if ($form->isValid()) {
            $entityManager->flush();

            return $this->json([
                'id' => $nurse->getId(),
                'internalId' => $nurse->getInternalId(),
                'firstName' => $nurse->getFirstName(),
                'lastName' => $nurse->getLastName(),
                'licenseNumber' => $nurse->getLicenseNumber(),
                'username' => $nurse->getUsername(),
                'email' => $nurse->getEmail(),
                'shift' => $nurse->getShift(),
            ], 200);
        }

        
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[$error->getOrigin()->getName()] = $error->getMessage();
        }

        return $this->json(['errors' => $errors], 400);
    }
}