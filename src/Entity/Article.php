<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    public const ARTICLE_STATUS_DRAFT = 'draft';

    public const ARTICLE_STATUS_TO_REVIEW = 'review';

    public const ARTICLE_STATUS_REJECTED = 'rejected';

    public const ARTICLE_STATUS_PUBLISHED = 'published';

    public const ARTICLE_STATUS_ARCHIVED = 'archived';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    private $slug;

    #[ORM\Column(type: 'text')]
    private $body;

    #[ORM\Column(type: 'boolean')]
    private $public;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'articles')]
    private Category $category;

    #[ORM\Column(type: 'string', length: 20)]
    private string $status = self::ARTICLE_STATUS_DRAFT;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $publishAt;

    #[ORM\Column(type: 'date', nullable: true)]
    private $date;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    public function getCategory(): ?category
    {
        return $this->category;
    }

    public function setCategory(?category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }


    public function getPublishAt(): ?\DateTime
    {
        return $this->publishAt;
    }

    public function setPublishAt(?\DateTime $publishAt): self
    {
        $this->publishAt = $publishAt;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
