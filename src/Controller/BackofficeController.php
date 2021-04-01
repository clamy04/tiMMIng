<?php

namespace App\Controller;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;




class BackofficeController extends AbstractController
{
     /**
     * @Route("/backoffice", name="backoffice")
     */
    public function backoffice_index(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', 'ROLE_EDITOR');
        return $this->render('backoffice/index.html.twig');
        
    }
     /**
     * @Route("/backoffice/tasks", name="backoffice_tasks")
     */
    public function index(TaskRepository $taskRepository): Response
    {

        return $this->render('backoffice/task.html.twig', [
            'tasks' => $taskRepository->findAll()
        ]);
        
    }
      /**
     * @Route("/backoffice/tasks/add", name="add_task")
     */
    public function add_task(Request $request, EntityManagerInterface $em): Response
    {
        
        $this->denyAccessUnlessGranted('ROLE_ADMIN', 'ROLE_EDITOR');
         
            $task = new Task();
            // préparer l'objet formulaire
            $form = $this->createForm(TaskType::class, $task);
            // Mettre en place le gestionnaire de formulaire
            $form->handleRequest($request);
            // Si le formulaire a été soumis et que les données sont valides
            if ($form->isSubmitted() && $form->isValid()) {
            // récupérer les données soumises
            $task = $form->getData();
            // enregistrer les données dans la base
            $em->persist($task);
            $em->flush();
            // stocker un message flash de succès
            $this->addFlash('info', 'tâche ' . $task->getDescription() . ' ajoutée');
            // rediriger vers l’accueil
            return $this->redirectToRoute('backoffice_tasks');
            }
            // sinon afficher le formulaire
            return $this->render('backoffice/task_add.html.twig', [
            'form' => $form->createView()
            ]) ;
    }
       /**
     * Effacer une tâche.
 
     * @Route("backoffice/tasks/{id}/delete", name="delete_task", methods="DELETE")
     */
    public function delete(Task $task, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', 'ROLE_EDITOR');

        $em->remove($task);
        $em->flush();
        return $this->redirectToRoute('backoffice_tasks');
    }
    /**
     * Editer un groupe
     * 
     * @Route("backoffice/{id}/edit", name="edit_task", methods={"GET","POST"})
     */
    public function edit(Request $request, Task $task): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', 'ROLE_EDITOR');

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backoffice_tasks');
        }

        return $this->render('backoffice/task_edit.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }
}