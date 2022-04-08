<?php

namespace App\Controller;


use App\Repository\TodoItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoItemController extends AbstractController
{
    /**
     * @Route("/", name="Todo")
     */
    public function index(TodoItemRepository $todoRepository): Response
    {
            $Todos = $todoRepository->findAll();
        return $this->render('todo_item/index.html.twig', ["todos"=>$Todos]);
    }
}
