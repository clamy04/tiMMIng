<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Group;
use App\Entity\Module;
use App\Entity\Task;
use App\Entity\Teacher;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadTeacher($manager);
        $this->loadModule($manager);
        $this->loadGroup($manager);
        $this->loadTask($manager);
        $manager->flush();
    }

    /**
     * Alimenter l'entité Teacher.
     */
    public function loadTeacher(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $teacher = new Teacher();
            $teacher->setName('teacher ' . $i);
            $manager->persist($teacher);
            $this->addReference("teacher-$i", $teacher);
        }
        $manager->flush();
    }
    /**
     * Alimenter l'entité module.
     */
    public function loadModule(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $module = new Module();
            $module->setName('module ' . $i)
                ->setYear('2020')
                ->setCampain('blabla')
                ->addTeacher($this->getReference("teacher-" . random_int(1, 10)));
            $manager->persist($module);
            $this->addReference("module-$i", $module);
        }
        $manager->flush();
    }
    /**
     * Alimenter l'entité group.
     */
    public function loadGroup(ObjectManager $manager)
    {
        for ($i = 1; $i <= 14; $i++) {
            $group = new Group();
            $group->setName('group ' . $i)
                ->setType('blabla')
                ->setSemester('blabla')
                ->setCampain('blibli');
            $manager->persist($group);
            $this->addReference("group-$i", $group);
        }
        $manager->flush();
    }
    /**
     * Alimenter l'entité task.
     */
    public function loadTask(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $task = new Task();
            $task->setDescription('task-description')
                ->setDeadline(new \DateTime("2021-05-01 00:00:00"))
                ->setVisible(true)
                ->setTeacher($this->getReference("teacher-" . random_int(1, 10)))
                ->setModule($this->getReference("module-" . random_int(1, 10)))
                ->addClassgroup($this->getReference("group-" . random_int(1, 14)));
            $manager->persist($task);
            $this->addReference("task-$i", $task);
        }
        $manager->flush();
    }
}