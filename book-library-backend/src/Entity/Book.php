<?php

namespace App\Entity;

use App\Repository\BookRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    use DatedEntityTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(type: Types::STRING, length: 255)]
    /**
     * @Assert\NotBlank
     */
    private $name;

    #[ORM\Column(type: Types::STRING, length: 255)]
    /**
     * @Assert\NotBlank
     */
    private $author;

    #[ORM\Column(type: Types::DATE_MUTABLE, length: 255)]
    /**
     * @Assert\NotBlank
     */
    private $yearPublishing;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getYearPublishing(): ?string
    {
        return $this->yearPublishing->format('Y');
    }

    public function setYearPublishing(DateTime $yearPublishing): self
    {
        $this->yearPublishing = $yearPublishing;

        return $this;
    }
}
