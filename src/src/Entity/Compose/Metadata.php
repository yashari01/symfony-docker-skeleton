<?php


namespace App\Entity\Compose;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use DateTime;

trait Metadata
{

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @return mixed
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): MetadataInterface
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;

    }

    /**
     * @param mixed $modifiedAt
     */
    public function setUpdateAt(DateTime $updatedAt): MetadataInterface
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

}