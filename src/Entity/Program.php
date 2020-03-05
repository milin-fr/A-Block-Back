<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProgramRepository")
 */
class Program
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"exercise", "program", "abloc_user", "mastery_level"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"exercise", "program", "abloc_user", "mastery_level"})
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("program")
     */
    private $description;

    /**
     * @ORM\Column(type="smallint")
     * @Groups("program")
     */
    private $time;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Groups("program")
     */
    private $img_path;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("program")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("program")
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Exercise", inversedBy="programs")
     * @Groups("program")
     */
    private $exercises;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProgramComment", mappedBy="program", orphanRemoval=true)
     * @Groups("program")
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MasteryLevel", inversedBy="programs")
     * @Groups("program")
     */
    private $mastery_level;

    public function __construct()
    {
        $this->exercises = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getImgPath(): ?string
    {
        return $this->img_path;
    }

    public function setImgPath(string $img_path): self
    {
        $this->img_path = $img_path;

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
        }

        return $this;
    }

    public function removeExercise(Exercise $exercise): self
    {
        if ($this->exercises->contains($exercise)) {
            $this->exercises->removeElement($exercise);
        }

        return $this;
    }

    /**
     * @return Collection|ProgramComment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(ProgramComment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setProgram($this);
        }

        return $this;
    }

    public function removeComment(ProgramComment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getProgram() === $this) {
                $comment->setProgram(null);
            }
        }

        return $this;
    }

    public function getMasteryLevel(): ?MasteryLevel
    {
        return $this->mastery_level;
    }

    public function setMasteryLevel(?MasteryLevel $mastery_level): self
    {
        $this->mastery_level = $mastery_level;

        return $this;
    }

}
