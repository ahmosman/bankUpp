<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BankerController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/banker', name: 'app_banker')]
    public function index(): Response
    {
        return $this->render('banker/index.html.twig', [
            'controller_name' => 'BankerController',
        ]);
    }

    public function userManagement(): Response
    {
//        $bankUsers = $this->userRepository->
        return $this->render('banker/index.html.twig', [
            'controller_name' => 'BankerController',
        ]);
    }
}
