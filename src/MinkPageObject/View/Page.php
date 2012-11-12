<?php

namespace MinkPageObject\View;

use Behat\Mink\Session;

/**
 *
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
abstract class Page extends AbstractView
{
    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $path;

    /**
     * @param \Pimple $container
     * @param Session $session
     * @param string  $baseUrl
     */
    public function __construct(\Pimple $container, Session $session, $baseUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->setRoot('body');

        parent::__construct($container, $session);
    }

    /**
     * @return Page
     */
    public function visit()
    {
        $this->init();
        $this->session->visit($this->getUrl());

        return $this;
    }

    /**
     * @param string $path
     *
     * @return Page
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return rtrim($this->baseUrl, '/') . '/' . ltrim($this->path, '/');
    }
}