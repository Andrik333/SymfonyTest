<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\LoginFormType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $user = new User();
        $user->setEmail($authenticationUtils->getLastUsername());

        $form = $this->createForm(LoginFormType::class, $user);

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('login.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }
}

