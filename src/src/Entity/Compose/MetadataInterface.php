<?php


namespace App\Entity\Compose;

use DateTime;
use Symfony\Component\Validator\Constraints\Date;

interface MetadataInterface
{

    public function getCreatedAt(): DateTime;
    public function setCreatedAt(DateTime $modifiedAt): MetadataInterface;
    public function getUpdatedAt(): DateTime;
    public function setUpdateAt(DateTime $updatedAt): MetadataInterface;
}