<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=TodoItem::class, mappedBy="category")
     */
    private $todoItems;

    public function __construct()
    {
        $this->todoItems = new ArrayCollection();
    }

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, TodoItem>
     */
    public function getTodoItems(): Collection
    {
        return $this->todoItems;
    }

    public function addTodoItem(TodoItem $todoItem): self
    {
        if (!$this->todoItems->contains($todoItem)) {
            $this->todoItems[] = $todoItem;
            $todoItem->setCategory($this);
        }

        return $this;
    }

    public function removeTodoItem(TodoItem $todoItem): self
    {
        if ($this->todoItems->removeElement($todoItem)) {
            // set the owning side to null (unless already changed)
            if ($todoItem->getCategory() === $this) {
                $todoItem->setCategory(null);
            }
        }

        return $this;
    }
}
