<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ImageController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function admin(Request $request, EntityManagerInterface $entityManager): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($image);
            $entityManager->flush();

            $this->addFlash('success', 'Image ajoutée avec succès !');

            // Redirection vers la page /admin/images après avoir ajouté l'image
            return $this->redirectToRoute('admin_images');
        }

        return $this->render('admin/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/', name: 'home')]
    public function adminImages(EntityManagerInterface $entityManager): Response
    {
        $images = $entityManager->getRepository(Image::class)->findAll();

        return $this->render('admin/images.html.twig', [
            'images' => $images,
        ]);
    }
}
