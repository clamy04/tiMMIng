<?php

namespace App\Controller;

use App\Repository\TeacherRepository;
use App\Repository\ModuleRepository;
use App\Form\TeacherType;
use App\Entity\Teacher;
use App\Entity\Module;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TeacherController extends AbstractController
{
    /**
     * @Route("/backoffice/teachers", name="teachers")
     */
    public function listTeachers(TeacherRepository $teacherRepository): Response
    {
        $teachers = $teacherRepository->findAll();
        foreach ($teachers as $teacher) {
            $id = $teacher->getId();
            $modules = $teacher->getModules();
            foreach ($modules as $mod) {
                $module = $mod->getName();
                $table[] = ["teacher_id" => $id, "module_name" => $module];
            }
        }
        return $this->render('backoffice/list_teacher.html.twig', [
            'teachers' => $teachers,
            'modules' => $table,
        ]);
    }
    /**
     * @Route("/backoffice/teacher/add", name="teacher_add", methods="get")
     */
    public function addTeacher(ModuleRepository $moduleRepository)
    {

        return $this->render('backoffice/add_teacher.html.twig', [
            'modules' => $moduleRepository->findAll(),
        ]);
    }
    /**
     * Enregistrer un nouveau professeur
     * @Route("/backoffice/teacher/add", name="teacher_add_save", methods="post")
     */
    public function save(TeacherRepository $teacherRepository, EntityManagerInterface $em, Request $request)
    {
        $teacher = new Teacher();
        $teacher->setName($request->request->get('name'));
        foreach ($_POST["module"] as $id_module) {
            $id = $id_module;
            $module = $this->getDoctrine()->getRepository(Module::class)->find($id);
            $teacher->addModule($module);
        };
        $em->persist($teacher);
        $em->flush();
        dump($_FILES);
        dump($_POST);
        return $this->redirectToRoute('teachers');
    }
    /**
     * @Route("/teacher/{id}/delete", name="teacher_delete")
     */
    public function delete(Teacher $teacher, EntityManagerInterface $em): Response
    {
        dump($teacher);
        $em->remove($teacher);
        $em->flush();
        return $this->redirectToRoute('teachers');
    }
    /**
     * @Route("/teacher/{id}/edit", name="teacher_edit")
     */
    public function edit(Request $request, Teacher $teacher, ModuleRepository $moduleRepository): Response
    {
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('teachers');
        }
        return $this->render('backoffice/edit_teacher.html.twig', [
            'teacher' => $teacher,
            'form' => $form->createView(),
            'modules' => $moduleRepository->findAll(),
        ]);
    }
    /**
     * @Route("/teacher/{id}", name="teacher_show")
     */
    public function show(Teacher $teacher)
    {
        $modules = $teacher->getModules();
        $tasks = $teacher->getTasks();
        $taches = [];
        $mods = [];
        foreach ($modules as $mod) {
            $module = $mod->getName();
            $mods[] = ["module_name" => $module];
        }
        foreach ($tasks as $task) {
            $tache = $task->getDescription();
            $deadline = $task->getDeadline();
            $active = $task->getVisible();
            $module = $task->getModule();
            $taches[] = ["tache" => $tache, "visible" => $active, "deadline" => $deadline, "module" => $module];
        }
        return $this->render('backoffice/show_teacher.html.twig', [
            'teacher' => $teacher,
            'tasks' => $taches,
            'modules' => $mods,
        ]);
    }
}