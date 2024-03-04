<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table]
class Task
{

    /**
     * @var ?int $id Identifier
     */
    #[ORM\Column()]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    /**
     * @var \DateTime $id Task creation time
     */
    #[ORM\Column()]
    private \DateTime $createdAt;

    /**
     * @var ?string $id Task title
     */
    #[ORM\Column()]
    #[Assert\NotBlank(message: 'Vous devez saisir un titre.')]
    private ?string $title = null;

    /**
     * @var ?string $content Task content
     */
    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'Vous devez saisir du contenu.')]
    private ?string $content = null;

    /**
     * @var bool $isDone Task done state
     */
    #[ORM\Column()]
    private bool $isDone = false;

    /**
     * @var ?User $owner Task owner
     */
    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function __construct()
    {
        $this->createdAt = new \Datetime();
    }

    /**
     * Id getter
     *
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * CreatedAt getter
     *
     * @return \Datetime
     */
    public function getCreatedAt(): \Datetime
    {
        return $this->createdAt;
    }

    /**
     * CreatedAt setter
     *
     * @param \Datetime $createdAt Task creation time
     * @return void
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Title getter
     *
     * @return ?string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Title setter
     *
     * @param String $title Title
     * @return void
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * Content getter
     *
     * @return ?string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Content setter
     *
     * @param string $content Content
     * @return void
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * isDone getter
     *
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->isDone;
    }

    /**
     * Toggle setter
     *
     * @param bool $flag Flag
     * @return void
     */
    public function toggle($flag): void
    {
        $this->isDone = $flag;
    }

    /**
     * Owner getter
     *
     * @return ?User
     */
    public function getOwner(): ?User
    {
        return $this->owner;
    }

    /**
     * Owner setter
     *
     * @param User $user User
     * @return static
     */
    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
