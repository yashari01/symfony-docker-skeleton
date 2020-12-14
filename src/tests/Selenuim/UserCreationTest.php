<?php

namespace App\Tests\Selenuim;

use App\Core\Serializer;
use Facebook\WebDriver\WebDriverBy;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserCreationTest extends GridTest
{

    public function testCreate()
    {
        $this->driver->get("https://www.google.com/ncr");

        $element = $this->driver->findElement(WebDriverBy::name("q"));
        if($element) {
            $element->sendKeys("LambdaTest");
            $element->submit();
        }

        print $this->driver->getTitle();
    }

}
