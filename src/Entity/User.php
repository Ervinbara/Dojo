<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *  fields= {"email"},
 *  message="Cette adresse e-mail est déjà attribué à un compte" 
 * )
 */
class User implements UserInterface
{
    // /**
    // * toString
    // * @return string
    // */
    // public function __toString()
    // {
    //     return $this->getUsername();
    // }
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="6", minMessage="Le mot de passe doit faire au moins 6 caractères")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Les mots de passe ne correspondent pas")
     */
    public $confirm_password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExerciceLike", mappedBy="user")
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PostLike", mappedBy="user")
     */
    private $postLikes;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->postLikes = new ArrayCollection();
        $this->to_id = new ArrayCollection();
        $this->from_id = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->message_from = new ArrayCollection();
        $this->message_to = new ArrayCollection();
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function eraseCredentials() {}

    public function getSalt() {}

     /**
      * @ORM\Column(type="json")
      */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Messages", mappedBy="from_id")
     */
    private $message_from;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Messages", mappedBy="to_id")
     */
    private $message_to;

  
    
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }
 


    /**
     * @return Collection|ExerciceLike[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(ExerciceLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(ExerciceLike $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PostLike[]
     */
    public function getPostLikes(): Collection
    {
        return $this->postLikes;
    }

    public function addPostLike(PostLike $postLike): self
    {
        if (!$this->postLikes->contains($postLike)) {
            $this->postLikes[] = $postLike;
            $postLike->setUser($this);
        }

        return $this;
    }

    public function removePostLike(PostLike $postLike): self
    {
        if ($this->postLikes->contains($postLike)) {
            $this->postLikes->removeElement($postLike);
            // set the owning side to null (unless already changed)
            if ($postLike->getUser() === $this) {
                $postLike->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Messages[]
     */
    public function getMessageFrom(): Collection
    {
        return $this->message_from;
    }

    public function addMessageFrom(Messages $messageFrom): self
    {
        if (!$this->message_from->contains($messageFrom)) {
            $this->message_from[] = $messageFrom;
            $messageFrom->setFromId($this);
        }

        return $this;
    }

    public function removeMessageFrom(Messages $messageFrom): self
    {
        if ($this->message_from->contains($messageFrom)) {
            $this->message_from->removeElement($messageFrom);
            // set the owning side to null (unless already changed)
            if ($messageFrom->getFromId() === $this) {
                $messageFrom->setFromId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Messages[]
     */
    public function getMessageTo(): Collection
    {
        return $this->message_to;
    }

    public function addMessageTo(Messages $messageTo): self
    {
        if (!$this->message_to->contains($messageTo)) {
            $this->message_to[] = $messageTo;
            $messageTo->setToId($this);
        }

        return $this;
    }

    public function removeMessageTo(Messages $messageTo): self
    {
        if ($this->message_to->contains($messageTo)) {
            $this->message_to->removeElement($messageTo);
            // set the owning side to null (unless already changed)
            if ($messageTo->getToId() === $this) {
                $messageTo->setToId(null);
            }
        }

        return $this;
    }



}
