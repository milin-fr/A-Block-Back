<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $account_name;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $img_path;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $available_time;

    /**
     * @ORM\Column(type="integer")
     */
    private $score;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Program")
     * @JoinTable(name="program_bookmark")
     */
    private $program_bookmarks;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Exercise")
     * @JoinTable(name="exercise_bookmark")
     */
    private $exercise_bookmarks;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Program")
     * @JoinTable(name="followed_program_bookmark")
     */
    private $followed_programs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExerciseComment", mappedBy="user", orphanRemoval=true)
     */
    private $exercise_comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProgramComment", mappedBy="user", orphanRemoval=true)
     */
    private $program_comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AccessLevel")
     * @ORM\JoinColumn(nullable=false)
     */
    private $access_level;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MasteryLevel")
     */
    private $mastery_level;

    public function __construct()
    {
        $this->program_bookmarks = new ArrayCollection();
        $this->exercise_bookmarks = new ArrayCollection();
        $this->followed_programs = new ArrayCollection();
        $this->exercise_comments = new ArrayCollection();
        $this->program_comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getAccountName(): ?string
    {
        return $this->account_name;
    }

    public function setAccountName(string $account_name): self
    {
        $this->account_name = $account_name;

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

    public function getAvailableTime(): ?int
    {
        return $this->available_time;
    }

    public function setAvailableTime(?int $available_time): self
    {
        $this->available_time = $available_time;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

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
     * @return Collection|Program[]
     */
    public function getProgramBookmarks(): Collection
    {
        return $this->program_bookmarks;
    }

    public function addProgramBookmark(Program $programBookmark): self
    {
        if (!$this->program_bookmarks->contains($programBookmark)) {
            $this->program_bookmarks[] = $programBookmark;
        }

        return $this;
    }

    public function removeProgramBookmark(Program $programBookmark): self
    {
        if ($this->program_bookmarks->contains($programBookmark)) {
            $this->program_bookmarks->removeElement($programBookmark);
        }

        return $this;
    }

    /**
     * @return Collection|Exercise[]
     */
    public function getExerciseBookmarks(): Collection
    {
        return $this->exercise_bookmarks;
    }

    public function addExerciseBookmark(exercise $exerciseBookmark): self
    {
        if (!$this->exercise_bookmarks->contains($exerciseBookmark)) {
            $this->exercise_bookmarks[] = $exerciseBookmark;
        }

        return $this;
    }

    public function removeExerciseBookmark(exercise $exerciseBookmark): self
    {
        if ($this->exercise_bookmarks->contains($exerciseBookmark)) {
            $this->exercise_bookmarks->removeElement($exerciseBookmark);
        }

        return $this;
    }

    /**
     * @return Collection|Program[]
     */
    public function getFollowedPrograms(): Collection
    {
        return $this->followed_programs;
    }

    public function addFollowedProgram(Program $followedProgram): self
    {
        if (!$this->followed_programs->contains($followedProgram)) {
            $this->followed_programs[] = $followedProgram;
        }

        return $this;
    }

    public function removeFollowedProgram(Program $followedProgram): self
    {
        if ($this->followed_programs->contains($followedProgram)) {
            $this->followed_programs->removeElement($followedProgram);
        }

        return $this;
    }

    /**
     * @return Collection|ExerciseComment[]
     */
    public function getExerciseComments(): Collection
    {
        return $this->exercise_comments;
    }

    public function addExerciseComment(ExerciseComment $exerciseComment): self
    {
        if (!$this->exercise_comments->contains($exerciseComment)) {
            $this->exercise_comments[] = $exerciseComment;
            $exerciseComment->setUser($this);
        }

        return $this;
    }

    public function removeExerciseComment(ExerciseComment $exerciseComment): self
    {
        if ($this->exercise_comments->contains($exerciseComment)) {
            $this->exercise_comments->removeElement($exerciseComment);
            // set the owning side to null (unless already changed)
            if ($exerciseComment->getUser() === $this) {
                $exerciseComment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProgramComment[]
     */
    public function getProgramComments(): Collection
    {
        return $this->program_comments;
    }

    public function addProgramComment(ProgramComment $programComment): self
    {
        if (!$this->program_comments->contains($programComment)) {
            $this->program_comments[] = $programComment;
            $programComment->setUser($this);
        }

        return $this;
    }

    public function removeProgramComment(ProgramComment $programComment): self
    {
        if ($this->program_comments->contains($programComment)) {
            $this->program_comments->removeElement($programComment);
            // set the owning side to null (unless already changed)
            if ($programComment->getUser() === $this) {
                $programComment->setUser(null);
            }
        }

        return $this;
    }

    public function getAccessLevel(): ?AccessLevel
    {
        return $this->access_level;
    }

    public function setAccessLevel(?AccessLevel $access_level): self
    {
        $this->access_level = $access_level;

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
