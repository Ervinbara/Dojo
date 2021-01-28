<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExerciceRepository")
 */
/**
 * @ORM\Entity(repositoryClass="App\Repository\ExerciceRepository")
 * @Vich\Uploadable
 */
class Exercice
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=5, max=255)
     */
    private $title;

    /**
 * @ORM\Column(type="string", length=255)
 * @var string
 */
private $featured_image;

/**
 * @Vich\UploadableField(mapping="featured_images", fileNameProperty="featured_image")
 * @var File
 */
private $imageFile;


// Dans les Getters/setters
public function setImageFile(File $image = null)
{
    $this->imageFile = $image;

    // if ($image) {
    //     $this->createdAt = new \DateTime('now');
    // }
}

public function getImageFile()
{
    return $this->imageFile;
}

public function getFeaturedImage()
{
    return $this->featured_image;
}

public function setFeaturedImage($featured_image)
{
    $this->featured_image = $featured_image;

    return $this;
}

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=5)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExerciceComment", mappedBy="exercice", orphanRemoval=true)
     */
    private $exerciceComments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CategoryExercice", inversedBy="exercices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoryExercice;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExerciceLike", mappedBy="exercice")
     */
    private $likes;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=10, max=700, minMessage="Au moins 10 caractére attendu")
     */
    private $slug;





    public function __construct()
    {
        $this->exerciceComments = new ArrayCollection();
        $this->likes = new ArrayCollection();
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



    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getExerciceCategory()
    {
        return $this->exerciceCategory;
    }

    public function setExerciceCategory(?ExerciceCategory $exerciceCategory): self
    {
        $this->exerciceCategory = $exerciceCategory;

        return $this;
    }

    /**
     * @return Collection|ExerciceComment[]
     */
    public function getExerciceComments(): Collection
    {
        return $this->exerciceComments;
    }

    public function addExerciceComment(ExerciceComment $exerciceComment): self
    {
        if (!$this->exerciceComments->contains($exerciceComment)) {
            $this->exerciceComments[] = $exerciceComment;
            $exerciceComment->setExercice($this);
        }

        return $this;
    }

    public function removeExerciceComment(ExerciceComment $exerciceComment): self
    {
        if ($this->exerciceComments->contains($exerciceComment)) {
            $this->exerciceComments->removeElement($exerciceComment);
            // set the owning side to null (unless already changed)
            if ($exerciceComment->getExercice() === $this) {
                $exerciceComment->setExercice(null);
            }
        }

        return $this;
    }

    public function getCategoryExercice(): ?CategoryExercice
    {
        return $this->categoryExercice;
    }

    public function setCategoryExercice(?CategoryExercice $categoryExercice): self
    {
        $this->categoryExercice = $categoryExercice;
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
            $like->setExercice($this);
        }

        return $this;
    }

    public function removeLike(ExerciceLike $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getExercice() === $this) {
                $like->setExercice(null);
            }
        }

        return $this;
    }

    /**
     * Permet de savoir si l'exo est liké par un user
     *
     * @param User $user
     * @return boolean
     */
    public function isLikedByUser(User $user) : bool //Fonction qui va retourner un booléen
    {
        foreach($this->likes as $like) {
            if($like->getUser() === $user) return true;//Si l'utilisateur connecté et l'user qui à liké on retourne true

        }
        return false;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

}
