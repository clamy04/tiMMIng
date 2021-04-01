<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(TaskRepository $taskRepository): Response
    {

        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findBy(array('visible' => true)),
        ]);
    }
}