<?php

namespace MinkPageObject;

use Behat\Mink\Session;
use Symfony\Component\Yaml\Yaml;

/**
 * BaseClass for Page Object Mink Tests
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Session
     */
    protected static $session;

    /**
     * @var array
     */
    protected static $config;

    /**
     * @var \Pimple
     */
    protected static $container;

    /**
     * Charge le driver
     */
    public static function setUpBeforeClass()
    {
        self::$config    = Yaml::parse(realpath(getcwd().'/mink-page-object.yml'));
        self::$container = new \Pimple();

        self::loadDriver();
        self::loadContainer();
    }

    /**
     * Démare la session
     */
    public function setUp()
    {
        self::$session->start();
    }

    /**
     * Arrete la session
     */
    public function tearDown()
    {
        self::$session->stop();
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return self::$session;
    }

    /**
     * @return \Behat\Mink\Element\DocumentElement
     */
    public function getPage()
    {
        return $this->getSession()->getPage();
    }

    /**
     * @param  string $view
     *
     * @return View\View
     */
    public function get($view)
    {
        return self::$container[$view];
    }

    private static function loadDriver()
    {
        $loaderClass  = sprintf('%s\Loader\%sLoader', __NAMESPACE__, self::$config['driver']['name']);
        $loader = new $loaderClass;

        self::$session = new Session($loader->load(self::$config['driver']));
    }

    private static function loadContainer()
    {
        $session = self::$session;
        $baseUrl = self::$config['baseUrl'];

        foreach (self::$config['views'] as $view => $class) {
            self::$container[$view] = self::$container->share(function($c) use ($session, $baseUrl, $class) {

                return new $class($c, $session, $baseUrl);
            });
        }
    }
}
