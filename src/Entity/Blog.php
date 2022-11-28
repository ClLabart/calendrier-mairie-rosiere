<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Repository\BlogRepository;

#[ORM\Entity(repositoryClass: BlogRepository::class)]
#[Vich\Uploadable]
class Blog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text = null;

    #[Vich\UploadableField(mapping: 'blog', fileNameProperty: 'imageName')]
    private ?File $image = null;

    #[ORM\OneToOne(mappedBy: 'blog', cascade: ['persist', 'remove'])]
    private ?Date $date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $imageDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName = null;

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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function setImage(?File $image = null): void
    {
        $this->image = $image;

        if (null !== $image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->imageDate = new \DateTimeImmutable();
        }
    }

    public function getImage(): ?File
    {
        return $this->image;
    }

    public function getDate(): ?Date
    {
        return $this->date;
    }

    public function setDate(?Date $date): self
    {
        // unset the owning side of the relation if necessary
        if ($date === null && $this->date !== null) {
            $this->date->setBlog(null);
        }

        // set the owning side of the relation if necessary
        if ($date !== null && $date->getBlog() !== $this) {
            $date->setBlog($this);
        }

        $this->date = $date;

        return $this;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }
}
