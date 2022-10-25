<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Question;
use App\Entity\Utilisateur;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnoncesController extends AbstractController
{
    /**
     * @param AnnonceRepository $annonceRepository
     * @return Response
     */
    #[Route('/',name:'app_index')]
    public function index(AnnonceRepository $annonceRepository) : Response
    {
        $annonces = $annonceRepository->findAllPublished();

        return $this->render('home/index.html.twig');
    }

    /**
     * @return Response
     */
    #[Route('annonce/add', name:'app_add_annonce')]
    public function addAnnonce(): Response
    {
        return $this->render('annonces/addAnnonce.html.twig');
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('annonce/handle-form', name:'app_add_annonce_form', methods: ['POST'])]
    public function annonceFormHandler(Request $request, EntityManagerInterface $entityManager): Response
    {
        $newAnnonce = (new Annonce())
            ->setTitle($request->request->get("title"))
            ->setDescription($request->request->get("desc"))
            ->setPrice($request->request->get("price"))
            ->setAuthor()
            ->setPhotos(["pasdurl","toujourspas"])
            ->setCreatedAt(new \DateTime())
            ->setSlug(strtolower(str_replace(" ","-",$request->request->get("title"))));

        $entityManager->persist($newAnnonce);
        $entityManager->flush();

        return $this->redirectToRoute("app_index");
    }

/**
     * @return Response
     */
    #[Route('annonce/{id}', name:'app_add_annonce', methods: ['GET'])]
    public function annonceById(AnnonceRepository $annonceRepository, $id): Response
    {
        $annonce = $annonceRepository->findAllPublishedById($id);
        
        if (sizeof($annonce) === 1) {
            return $this->render('annonces/annonceById.html.twig', [
                'annonce' => $annonce[0]
            ]);
        } else {
            return $this->redirectToRoute('app_index');
        }
    }



    /**
     * @throws ORMException
     * @return Response
     */
    #[Route('/annonce/{id}', name:'app_annonce_delete', methods: ['DELETE'])]
    public function deleteAnnonce($id, EntityManagerInterface $entityManager): Response
    {
        $annonce = $entityManager->getReference(Annonce::class, $id);

        $entityManager->remove($annonce);
        $entityManager->flush();

        return $this->redirectToRoute("app_index");
    }
}