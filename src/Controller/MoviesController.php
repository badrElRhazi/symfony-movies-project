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
    #[Route('/movies/edit/{id}' , name: 'edit_movie')]
    public function edit($id, Request $request): Response
{
    $movie = $this->movieRepository->find($id);
    $form = $this->createForm(MovieFormType::class, $movie);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $movie->setTitle($form->get('title')->getData());
        $movie->setReleaseYear($form->get('releaseYear')->getData());
        $movie->setDescription($form->get('description')->getData());

        $imageFile = $form->get('imagePath')->getData();
        if ($imageFile) {
            // Generate a unique filename for the uploaded file
            $newFilename = uniqid() . '.' . $imageFile->guessExtension();

            // Move the file to the target directory
            try {
                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads',
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle the exception if the file upload fails
                return new Response($e->getMessage());
            }

            // Update the movie with the new image path
            $movie->setImagePath('/uploads/' . $newFilename);
        }

        // Save the updated movie to the database
        $this->em->flush();

        return $this->redirectToRoute('app_movies');
    }

    return $this->render('movies/edit.html.twig', [
        'movie' => $movie,
        'form' => $form->createView()
    ]);
}
    #[Route('/movies/delete/{id}', methods:['GET', 'DELETE'], name: 'delete_movie')]
    public function delete($id): Response 
    {
        $movie=$this->movieRepository->find($id);
        $this->em->remove($movie);
        $this->em->flush();

        return $this->redirectToRoute('app_movies');
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