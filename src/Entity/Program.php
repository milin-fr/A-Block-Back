<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProgramRepository")
 */
class Program
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"exercise", "program", "abloc_user", "mastery_level", "program_comment"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"exercise", "program", "abloc_user", "mastery_level", "program_comment"})
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups{"program", "abloc_user"}
     */
    private $description;

    /**
     * @ORM\Column(type="smallint")
     * @Groups{"program", "abloc_user"}
     */
    private $time;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Groups{"program", "abloc_user"}
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="active_program")
     */
    private $users;

    public function __construct()
    {
        $this->exercises = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->created_at = new \DateTime();
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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setActiveProgram($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getActiveProgram() === $this) {
                $user->setActiveProgram(null);
            }
        }

        return $this;
    }

}
