<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieFormType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class MoviesController extends AbstractController
{
    private $em;
    private $movieRepository;
    public function __construct(EntityManagerInterface $em, MovieRepository $movieRepository) 
    {
        $this->em = $em;
        $this->movieRepository = $movieRepository;
    }

    #[Route('/movies', name: 'app_movies')]
    public function index(): Response
    {
        $movies = $this->movieRepository->findAll();

        return $this->render('movies/index.html.twig', [
            'movies' => $movies
        ]);
    }
    #[Route('/movies/create', name:'create_movie')]
    public function create(Request $request): Response
    {
        $movie= new Movie();
        $form=$this->createForm(MovieFormType::class, $movie);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $newMovie = $form->getData();

            $imagePath= $form->get('imagePath')->getData();
            if($imagePath){
                $newFileName=uniqid() . '.' . $imagePath->guessExtension();

                Try{
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                }catch (FileException $e){
                    return new Response($e->getMessage());
                }

                $newMovie->setImagePath('/uploads/' . $newFileName);
            }

            $this->em->persist($newMovie);
            $this->em->flush();

            return $this->redirectToRoute('app_movies');

        }
        return $this->render('movies/create.html.twig',[
            'form' => $form->createView()
        ]);
    }
    #[Route('/movies/{id}', methods: ['GET'], name: 'show_movie')]
    public function show($id): Response
    {
        $movie = $this->movieRepository->find($id);
        $act=$movie->getActors();
        
        return $this->render('movies/show.html.twig', [
            'movie' => $movie,
            'act' => $act->getIterator()
        ]);
    }

}