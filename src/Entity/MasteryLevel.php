<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MasteryLevelRepository")
 */
class MasteryLevel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"mastery_level", "abloc_user", "exercise", "program"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"mastery_level", "abloc_user", "exercise", "program"})
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("mastery_level")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("mastery_level")
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Exercise", mappedBy="mastery_level")
     * @Groups("mastery_level")
     */
    private $exercises;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Program", mappedBy="mastery_level")
     * @Groups("mastery_level")
     */
    private $programs;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"mastery_level", "exercise", "program"})
     */
    private $level_index;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("mastery_level")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Groups("mastery_level")
     */
    private $img_path;

    public function __construct()
    {
        $this->exercises = new ArrayCollection();
        $this->programs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|Exercise[]
     */
    public function getExercises(): Collection
    {
        return $this->exercises;
    }

    public function addExercise(Exercise $exercise): self
    {
        if (!$this->exercises->contains($exercise)) {
            $this->exercises[] = $exercise;
            $exercise->setMasteryLevel($this);
        }

        return $this;
    }

    public function removeExercise(Exercise $exercise): self
    {
        if ($this->exercises->contains($exercise)) {
            $this->exercises->removeElement($exercise);
            // set the owning side to null (unless already changed)
            if ($exercise->getMasteryLevel() === $this) {
                $exercise->setMasteryLevel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Program[]
     */
    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    public function addProgram(Program $program): self
    {
        if (!$this->programs->contains($program)) {
            $this->programs[] = $program;
            $program->setMasteryLevel($this);
        }

        return $this;
    }

    public function removeProgram(Program $program): self
    {
        if ($this->programs->contains($program)) {
            $this->programs->removeElement($program);
            // set the owning side to null (unless already changed)
            if ($program->getMasteryLevel() === $this) {
                $program->setMasteryLevel(null);
            }
        }

        return $this;
    }

    public function getLevelIndex(): ?int
    {
        return $this->level_index;
    }

    public function setLevelIndex(int $level_index): self
    {
        $this->level_index = $level_index;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImgPath(): ?string
    {
        return $this->img_path;
    }

    public function setImgPath(?string $img_path): self
    {
        $this->img_path = $img_path;

        return $this;
    }
}
