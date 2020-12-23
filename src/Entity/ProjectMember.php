<?php

namespace App\Entity;

use App\Repository\ProjectMemberRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_project", indexes={@ORM\Index(name="FK_project_userID", columns={"user_id"}), @ORM\Index(name="FK_project_projectID", columns={"project_id"})})
 * @ORM\Entity(repositoryClass=ProjectMemberRepository::class)
 */
class ProjectMember
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * })
     */
    private $project;

    /**
     * @ORM\Column(type="string", name="project_role", length=255)
     */
    private $projectRole;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project): void
    {
        $this->project = $project;
    }

    /**
     * @return mixed
     */
    public function getProjectRole()
    {
        return $this->projectRole;
    }

    /**
     * @param mixed $projectRole
     */
    public function setProjectRole($projectRole): void
    {
        $this->projectRole = $projectRole;
    }

    public function __toString()
    {
        return ''.$this->project;
    }


}
