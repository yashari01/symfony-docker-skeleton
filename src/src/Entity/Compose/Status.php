<?php


namespace App\Entity\Compose;

use Doctrine\ORM\Mapping as ORM;
use ReflectionClass;
trait Status
{

    /**
     * @var array
     */
    private static $places;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $status;

    public static function getPlaces(): array
    {
        if(null == self::$places){
            $reflexionClass = new ReflectionClass(static::class);
            $constants = $reflexionClass->getConstants();
            foreach ($constants as $constant => $value){
                if(false !== strpos($constant, 'PLACE_')){
                    self::$places[$constant] = $value;
                }
            }

        }
       return self::$places;

    }

    public static function getPlacesIndexes(): array
    {
        return array_values(self::getPlaces());
    }

    public function getPlace(): string
    {
        return $this->getPlaceForStatus($this->status);
    }

    private function getPlaceForStatus(int $status): string
    {
        $map = self::getPlaces();
        if (!isset($map[$status])) {
            return self::PLACE_DEPRECATED;
        }

        return $map[$status];
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }


}