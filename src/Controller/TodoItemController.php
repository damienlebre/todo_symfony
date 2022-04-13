<?php

namespace App\Controller;


use DateTime;
use Exception;
use App\Form\TodoType;
use App\Entity\TodoItem;
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
     * @Route("/detail/{id}", name="todo_show")
     */
    public function show(TodoItemRepository $todoRepository, $id): Response
    {
            $Todo = $todoRepository->findOneBy(['id' => $id]);
        return $this->render('todo_item/show.html.twig', ["todo"=>$Todo]);
    }

      /**
     * @Route("/todo_modify/{id}", name="todo_modify")
     */
    public function edit(TodoItem $todo, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
          
            $todo->setCreatedAt(new \DateTimeImmutable());
            $em->flush();
            return $this->redirectToRoute('Todo');
        }
            return $this->render('todo_item/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/new-todo", name="new_todo")
     */
    public function new(Request $request, EntityManagerInterface $em ): Response
    {
            $todo = new TodoItem();
            $form = $this->createForm(TodoType::class, $todo);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $todo->setCreatedAt(new \DateTimeImmutable());
                $todo->setIsDone(false);
                $todo->setDoneAt(null);
                // dump($todo);
                $em->persist($todo);
                
                try{
                    $em->flush($todo);
                }catch(Exception $e){
                    return $this->redirectToRoute('new_todo');
                    dump($todo);
                }
                return $this->redirectToRoute('Todo');
            }

            return $this->render('todo_item/new.html.twig', [
                'form' => $form->createView()
            ]);
    }
    
}
