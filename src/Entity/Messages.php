<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessagesRepository")
 */
class Messages
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="message_from")
     */
    private $from_id;
   

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="message_to")
     */
    private $to_id;
   
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Content;


    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromId(): ?User
    {
        return $this->from_id;
    }


    public function setFromId(?User $from_id): self
    {
        $this->from_id = $from_id;

        return $this;
    }

    public function getToId(): ?User
    {
        return $this->to_id;
    }

    public function setToId(?User $to_id): self
    {
        $this->to_id = $to_id;

        return $this;
    }
    
    public function from(){
        return $this->getFromId(User::class,'from_id');
    }

    public function getContent(): ?string
    {
        return $this->Content;
    }

    public function setContent(?string $Content): self
    {
        $this->Content = $Content;

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

}
