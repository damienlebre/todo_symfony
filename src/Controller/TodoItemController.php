<?php

namespace App\Controller;

use DateTime;
use Exception;
use App\Form\TodoType;
use App\Entity\TodoItem;
use App\Form\FilterType;
use App\Entity\SearchData;
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
    public function index(TodoItemRepository $todoRepository, Request $request): Response
    {
        $data = new SearchData();
        $data->setDone = $request->get('null');
        $filter = $this->createForm(FilterType::class, $data);
        $filter->handleRequest($request);
        $Todos = $todoRepository->findAll($data);

        if($filter->isSubmitted() && $filter->isValid()){
            
            $done = $data->getDone();
            $Todos = $todoRepository->findBy(['is_done' => $done]);
            if($data->getDone()==null){
                $Todos = $todoRepository->findAll($data);
            }             
        }
        return $this->render('todo_item/index.html.twig', ["todos" => $Todos, "filter" => $filter->createView()]);
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
          
            // $todo->setCreatedAt(new \DateTimeImmutable());
            if($todo->getIsDone() == TRUE){
                $todo->setDoneAt(new \DateTimeImmutable());
            }
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
    

     /**
     * @Route("/delete/{id}", name="todo_delete")
     */
    public function delete(EntityManagerInterface $em, TodoItem $todo): Response
    {
        $em->remove($todo);
        $em->flush();
        return $this->redirectToRoute("Todo");
    }

    
}



