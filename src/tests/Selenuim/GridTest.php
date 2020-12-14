<?php

namespace App\Tests\Selenuim;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GridTest extends WebTestCase
{

    private const  LT_BROWSER = "chrome";
    private const LT_BROWSER_VERSION ="63.0";
    private const LT_PLATFORM = "windows 10";
    private const URL = "https://a.msouber:3OiBqWYfG1hG61vqfnuYGEEIpBLXhaH44tzPwJSVxfmSzUvRPB@hub.lambdatest.com/wd/hub";
    /**
     * @var RemoteWebDriver
     */
    protected $driver;
    /**
     * @var DesiredCapabilities
     */
    private $desired_capabilities;

    /**
     * @required
     */
    public function setRequirdDependencies(
        RemoteWebDriver $driver,
        DesiredCapabilities $desired_capabilities
    ): void {
        $this->driver = $driver;
        $this->desired_capabilities = $desired_capabilities;
    }

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->desired_capabilities = new DesiredCapabilities();
        $this->desired_capabilities->setCapability('browserName',self::LT_BROWSER);
        $this->desired_capabilities->setCapability('version', self::LT_BROWSER_VERSION);
        $this->desired_capabilities->setCapability('platform', self::LT_PLATFORM);
        $this->desired_capabilities->setCapability('name', "Php");
        $this->desired_capabilities->setCapability('build', "Php Build");
        $this->desired_capabilities->setCapability('network', true);
        $this->desired_capabilities->setCapability('visual', true);
        $this->desired_capabilities->setCapability('video ', true);
        $this->desired_capabilities->setCapability('console', true);
        $this->driver = RemoteWebDriver::create(
            self::URL,
            $this->desired_capabilities
        );

    }

}
