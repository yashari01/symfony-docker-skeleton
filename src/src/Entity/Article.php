<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Compose\Metadata;
use App\Entity\Compose\MetadataInterface;
use App\Entity\Compose\Status;
use App\Entity\Compose\StatusInterface;
use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Component\Serializer\Annotation\SerializedName;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"article:read", "article:item:get"}},
 *          },
 *          "put"
 *     },
 *     attributes={
 *          "formats"={"jsonld", "json", "html", "jsonhal", "csv"={"text/csv"}}
 *     },
 *     shortName="article",
 *     normalizationContext={"groups"={"article:read"}, "swagger_definition_name"="Read"},
 *     denormalizationContext={"groups"={"article:write"}, "swagger_definition_name"="write"},
 *
 * )
 * @ApiFilter(BooleanFilter::class, properties={"status"})
 * @ApiFilter(SearchFilter::class, properties={"title": "partial"})
 * @ApiFilter(PropertyFilter::class)
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article implements MetadataInterface,StatusInterface
{
    use Metadata;
    use Status;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"article:read","article:write"})
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"article:read","article:write"})
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     * @Groups({"article:read","article:write","user:write","user:read"})
     * @ApiSubresource()
     */
    private $user;

    /**
     * Article constructor.
     * @param $id
     */
    public function __construct()
    {
        $this->status = 0;
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

    /**
     * The description of the article as raw text.
     * @SerializedName("description")
     * @Groups("article:write")
     */
    public function setTextDescription(string $description): self
    {
        $this->content = nl2br($description);
        return $this;
    }

    /**
     * @Groups("article:read")
     */
    public function getShortDescription(): ?string
    {
        if (strlen($this->content) < 40) {
            return $this->content;
        }
        return substr($this->content, 0, 40).'...';
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
