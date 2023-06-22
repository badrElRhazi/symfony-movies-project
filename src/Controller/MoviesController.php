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

    private $em;
    
    public function __construct(EntityManagerInterface $em){
        
        $this->em = $em;
    }
    
    #[Route('/movies', name: 'app_movies')]
    public function index(): Response
    {

        // findAll - SELECT * FROM movies
        // find(x) - SELECT * FROM movies where id =x
        //findBy() - SELECT * FROM movies ORDER BY id DESC
        //findOneBy() - SELECT * FROM movies WHERE id= and title='' ORDER BY id DESC
        // count([]) - SELECT * FROM movies where id =x

        // $repository = $this ->em -> getRepository(Movie::class);
        // $movies =$repository -> count([]);

        // dd($movies);

        return $this->render('movies/index.html.twig');
    }    
     
}
