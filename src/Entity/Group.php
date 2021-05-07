<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 * @ORM\Table(name="`group`")
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $users = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUsers(): ?array
    {
        return $this->users;
    }

    public function setUser(string $name): self
    {
        array_push($this->users, $name);
        return $this;
    }

    public function removeUser(int $index)
    {
        unset($this->users[$index]);
        $this->users = array_values($this->users);
        return $this;
    }

    public function findUser(string $name)
    {
        for ($i = 0; $i < count($this->users); $i++) {
            if ($this->users[$i] === $name) {
                return true;
            }
        }
        return false;
    }
}
