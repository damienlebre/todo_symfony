<?php

namespace App\Controller;

use Exception;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/liste-des-catégorie", name="category_list")
     */
    public function listCategory(CategoryRepository $repo): Response
    {
        $categories = $repo->findAll();

        return $this->render('category/list.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/categorie/{id}", name="category_show")
     */
    public function show(CategoryRepository $repo, int $id): Response
    {
        $category = $repo->findOneBy(['id' => $id]);

        return $this->render('category/show.html.twig', [
            'category' => $category
        ]);
    }

       /**
     * @Route("/admin/supprimer-categorie/{id}", name="category_delete")
     */
    public function delete(EntityManagerInterface $em, Category $cat): Response
    {
        $em->remove($cat);
        try{
            $em->flush();
            $this->addFlash('success', 'Catégorie supprimée.');
        }catch(Exception $e){
            $this->addFlash('danger', 'Echec lors de la suppression de la catégorie.');
        }

        return $this->redirectToRoute("category_list");
    }

         /**
     * @Route("/new-category", name="new_category")
     */
    public function new( Upload $fileUploader, CategoryRepository $repo, EntityManagerInterface $em, Request $request): Response
    {   
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if($category->getImage()=== null){
                $category->setImage('default.png');
            }else{
                $imageFile = $form->get('avatar')->getData();
                $imageFileName= $fileUploader->Upload($imageFile);
                $category->setimage($imageFileName);
            }            
            $category->setName($form->getData()->getName());
           
            $em->persist($category);
            try{
                $em->flush();
                return $this->redirectToRoute('category_list');
            }catch(Exception $e){
                return $this->redirectToRoute('new_category');
            }

        }

        return $this->render('category/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("modifier-categorie/{id}", name="category_edit")
     */
    public function edit(Category $category, Request $request, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $category->getId().'-'.rand(100,500);
            try{
                $em->flush();
                $this->addFlash('success', 'Catégorie modifiée.');
            }catch(Exception $e){
                $this->addFlash('danger', 'Echec lors de la modification de la catégorie.');
            }

            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

   