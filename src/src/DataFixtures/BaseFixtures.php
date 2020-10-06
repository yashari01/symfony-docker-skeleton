<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

abstract class BaseFixtures extends Fixture
{
    /**
     * @var ObjectManager
     */
    protected $manager;
    /**
     * @var \Generator
     */
    protected $facker;

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        $this->manager = $manager;
        $this->loadData($this->manager);
    }

    abstract protected function loadData(ObjectManager $manager);

    protected function createMany(string $sClassName, int $iCount, callable $factory,$i=null)
    {
        for ($i = 0; $i < $iCount; $i++) {
            $oEntity = new $sClassName();
            $factory($oEntity, $i);
            $this->manager->persist($oEntity);
            // store for usage later as App\Entity\ClassName_#COUNT#
            $this->addReference($sClassName . '_' . $i, $oEntity);
        }
    }

}