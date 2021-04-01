<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\GroupType;
use App\Entity\Group;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\GroupRepository;

class GroupController extends AbstractController
{
    /**
     * @Route("/backoffice/groups", name="group")
     */
    public function index(GroupRepository $GroupRepository): Response
    {
        return $this->render('backoffice/group.html.twig', [
            'groups' => $GroupRepository->findAll(),
           
        ]);
    }
     /**
     * @Route("/backoffice/groups/add", name="add_group")
     */
    public function add_group(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
         
            $group = new Group();
            // préparer l'objet formulaire
            $form = $this->createForm(GroupType::class, $group);
            // Mettre en place le gestionnaire de formulaire
            $form->handleRequest($request);
            // Si le formulaire a été soumis et que les données sont valides
            if ($form->isSubmitted() && $form->isValid()) {
            // récupérer les données soumises
            $group = $form->getData();
            // enregistrer les données dans la base
            $em->persist($group);
            $em->flush();
            
            // rediriger vers l’accueil
            return $this->redirectToRoute('group');
            }
            // sinon afficher le formulaire
            return $this->render('backoffice/add_group.html.twig', [
            'form' => $form->createView()
            ]) ;
    }
     /**
     * Effacer une tâche.
 
     * @Route("backoffice/group/{id}/delete", name="delete_group", methods="DELETE")
     */
    public function delete(Group $group, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em->remove($group);
        $em->flush();
        return $this->redirectToRoute('group');
    }
     /**
     * Editer un groupe
     * 
     * @Route("backoffice/{id}/group", name="edit_group", methods={"GET","POST"})
     */
    public function edit(Request $request, Group $group): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('group');
        }

        return $this->render('backoffice/edit_group.html.twig', [
            'groups' => $group,
            'form' => $form->createView(),
        ]);
    }
}