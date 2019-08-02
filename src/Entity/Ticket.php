<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 * @ApiResource(
 *     normalizationContext={"groups"={"ticket"}},
 *     denormalizationContext={"groups"={"ticket"}}
 * )
 */
class Ticket
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"ticket"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Many", mappedBy="ticket", cascade={"persist"})
     * @ApiSubresource()
     * @Groups({"ticket"})
     */
    private $many;

    public function __construct()
    {
        $this->many = new ArrayCollection();
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

    /**
     * @return Collection|Many[]
     */
    public function getMany(): Collection
    {
        return $this->many;
    }

    public function addMany(Many $many): self
    {
        if (!$this->many->contains($many)) {
            $this->many[] = $many;
            $many->setTicket($this);
        }

        return $this;
    }

    public function removeMany(Many $many): self
    {
        if ($this->many->contains($many)) {
            $this->many->removeElement($many);
            // set the owning side to null (unless already changed)
            if ($many->getTicket() === $this) {
                $many->setTicket(null);
            }
        }

        return $this;
    }
}
