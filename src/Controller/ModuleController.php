<?php

namespace App\Controller;

use App\Repository\TeacherRepository;
use App\Repository\ModuleRepository;
// use App\Form\ModuleType;
use App\Entity\Teacher;
use App\Entity\Module;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ModuleController extends AbstractController
{
    /**
     * @Route("/backoffice/modules", name="modules")
     */
    public function listModules(ModuleRepository $modulerepository): Response
    {
        $modules = $modulerepository->findAll();
        foreach ($modules as $module) {
            $id = $module->getId();
            $teachers = $module->getTeachers();
            foreach ($teachers as $teach) {
                $teacher = $teach->getName();
                $table_teach[] = ["module_id" => $id, "teacher_name" => $teacher];
            }
        }
        return $this->render('backoffice/list_modules.html.twig', [
            'modules' => $modules,
            'teachers' => $table_teach,
        ]);
    }
    /**
     * @Route("/backoffice/module/add", name="module_add", methods="get")
     */
    public function addModule(TeacherRepository $teacherRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('backoffice/add_module.html.twig', [
            'teachers' => $teacherRepository->findAll(),
        ]);
    }
    /**
     * Enregistrer un nouveau professeur
     * @Route("/backoffice/module/add", name="module_add_save", methods="post")
     */
    public function save(ModuleRepository $moduleRepository, EntityManagerInterface $em, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $module = new Module();
        $module->setName($request->request->get('name'));
        foreach ($_POST["teacher"] as $id_teacher) {
            $id = $id_teacher;
            $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);
            $module->addTeacher($teacher);
        };
        $em->persist($module);
        $em->flush();
        dump($_FILES);
        dump($_POST);
        return $this->redirectToRoute('modules');
    }
    /**
     * @Route("/module/{id}/delete", name="module_delete")
     */
    public function delete(Module $module, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        dump($module);
        $em->remove($module);
        $teachers = $module->getTeachers();
        foreach ($teachers as $teacher) {
            $module->removeTeacher($teacher);
        }
        $em->flush();
        return $this->redirectToRoute('modules');
    }
    // /**
    //  * @Route("/teacher/{id}/edit", name="teacher_edit")
    //  */
    // public function edit(Request $request, Teacher $teacher, ModuleRepository $moduleRepository): Response
    // {
    //     $form = $this->createForm(TeacherType::class, $teacher);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();
    //         return $this->redirectToRoute('teachers');
    //     }
    //     return $this->render('backoffice/edit_teacher.html.twig', [
    //         'teacher' => $teacher,
    //         'form' => $form->createView(),
    //         'modules' => $moduleRepository->findAll(),
    //     ]);
    // }
    // /**
    //  * @Route("/teacher/{id}", name="teacher_show")
    //  */
    // public function show(Teacher $teacher)
    // {
    //     $modules = $teacher->getModules();
    //     $tasks = $teacher->getTasks();
    //     $taches = [];
    //     $mods = [];
    //     foreach ($modules as $mod) {
    //         $module = $mod->getName();
    //         $mods[] = ["module_name" => $module];
    //     }
    //     foreach ($tasks as $task) {
    //         $tache = $task->getDescription();
    //         $deadline = $task->getDeadline();
    //         $active = $task->getVisible();
    //         $module = $task->getModule();
    //         $taches[] = ["tache" => $tache, "visible" => $active, "deadline" => $deadline, "module" => $module];
    //         // dd($taches);
    //     }
    //     return $this->render('backoffice/show_teacher.html.twig', [
    //         'teacher' => $teacher,
    //         'tasks' => $taches,
    //         'modules' => $mods,
    //     ]);
    // }
}