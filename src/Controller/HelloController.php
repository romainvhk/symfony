<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/hello')]
class HelloController extends AbstractController
{

   

    #[Route('/hello', name: 'app_hello_index')]
    public function index(): Response
    {
        return $this->render('hello/index.html.twig', [
            // transmission de variables au template twig
            'controller_name' => 'HelloController',
        ]);
    }

    #[Route('/age/{birthYear}', name: 'app_hello_age', methods: ['GET'])]
    public function age(int $birthYear): Response {

        $year = 2023;
        $age = $year - $birthYear;

        return $this->render('hello/age.html.twig', [
            'birthYear' => $birthYear,
            'year' => $year,
            'age' => $age,
        ]);
    }
}
