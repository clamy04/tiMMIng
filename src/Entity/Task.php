<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $deadline;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visible;

    /**
     * @ORM\ManyToOne(targetEntity=Teacher::class, inversedBy="tasks")
     */
    private $teacher;

    /**
     * @ORM\ManyToOne(targetEntity=Module::class, inversedBy="tasks")
     */
    private $module;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class, inversedBy="tasks")
     */
    private $classgroup;

    public function __construct()
    {
        $this->classgroup = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(?\DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): self
    {
        $this->module = $module;

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getClassgroup(): Collection
    {
        return $this->classgroup;
    }

    public function addClassgroup(Group $classgroup): self
    {
        if (!$this->classgroup->contains($classgroup)) {
            $this->classgroup[] = $classgroup;
        }

        return $this;
    }

    public function removeClassgroup(Group $classgroup): self
    {
        $this->classgroup->removeElement($classgroup);

        return $this;
    }
    public function __toString(): string
 {
 return $this->name;
 }

}