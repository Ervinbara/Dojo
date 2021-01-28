<?php

namespace App\Entity;

use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\LogoRepository")
 */
/**
 * @ORM\Entity(repositoryClass="App\Repository\LogoRepository")
 * @Vich\Uploadable
 */
class Logo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

        /**
     * @ORM\Column(type="string", length=255)
     * @var string|null
     */
    private $featured_image;

    /**
     * @Vich\UploadableField(mapping="featured_images", fileNameProperty="featured_image")
     * @var File|null
     */
    private $imageFile;


    // Dans les Getters/setters
    public function setimageFile(File $image = null)
    {
        $this->imageFile = $image;

        // if ($image) {
        //     $this->createdAt = new \DateTime('now');
        // }
    }

    public function getimageFile()
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

    public function getId(): ?int
    {
        return $this->id;
    }
}
