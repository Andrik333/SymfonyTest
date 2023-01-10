<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Form\LoginFormType;
use App\Form\RegistrationFormType;

class AuthController extends AbstractController
{
    /**
     * @Route("/auth", name="app_auth")
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $userRegistration = new User();
        $formRegistration = $this->createForm(RegistrationFormType::class, $userRegistration);

        if ($formRegistration->isSubmitted() && $formRegistration->isValid()) {
            $userRegistration->setPassword(
            $userPasswordHasher->hashPassword(
                    $userRegistration,
                    $formRegistration->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($userRegistration);
            $entityManager->flush();

            return $this->redirectToRoute('app_index');
        }

        $userLogin = new User();
        $userLogin->setEmail($authenticationUtils->getLastUsername());
        $formLogin = $this->createForm(LoginFormType::class, $userLogin);

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('login.html.twig', [
            'formLogin' => $formLogin->createView(),
            'formRegistration' => $formRegistration->createView(),
            'error' => $error,
        ]);
    }
}
