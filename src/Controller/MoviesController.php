<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoviesController extends AbstractController
{
    #[Route('/movies', name: 'app_movies')]
    public function index(EntityManagerInterface $em): Response
    {

        $repository = $em -> getRepository(Movie::class);
        $movies =$repository -> findAll();

        dd($movies);

        return $this->render('movies/index.html.twig');
    }    
    
     
}
