<?php

namespace App\Controller;


use App\Repository\TodoItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TodoItemController extends AbstractController
{
    /**
     * @Route("/", name="Todo")
     */
    public function index(TodoItemRepository $todoRepository): Response
    {
            $Todos = $todoRepository->findAll();
        return $this->render('todo_item/index.html.twig', ["todos" => $Todos]);
    }

    /**
     * @Route("/{id}", name="todo_show")
     */
    public function show(TodoItemRepository $todoRepository, $id): Response
    {
            $Todo = $todoRepository->findOneBy(['id' => $id]);
        return $this->render('todo_item/show.html.twig', ["todo"=>$Todo]);
    }

      /**
     * @Route("/{id}", name="todo_modify")
     */
    public function edit(TodoItemRepository $todoRepository, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
          
        }
    }
}
