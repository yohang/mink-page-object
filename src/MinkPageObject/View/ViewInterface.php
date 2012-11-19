<?php

namespace MinkPageObject\View;

/**
 * Base View Interface
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
interface ViewInterface
{
    /**
     * Init the view and DOM elements
     *
     * @return View
     */
    public function init();

    /**
     * Returns the Page
     *
     * @return \Behat\Mink\Element\DocumentElement
     */
    public function getPage();

    /**
     * Defines the Root DOM element of View
     *
     * @param string $root
     *
     * @return View
     */
    public function setRoot($root);

    /**
     * Return the Root DOM element
     *
     * @return string
     */
    public function getRoot();

    /**
     * Adds a DOM element to view
     *
     * @param string $name
     * @param string $locator
     * @param string $type
     *
     * @return View
     */
    public function add($name, $locator, $type = 'css');

    /**
     * Adds a child view
     *
     * @param string $name
     * @param string $root
     *
     * @return View
     */
    public function addChild($name, $root);

    /**
     * Returns the parent view
     *
     * @return View
     */
    public function end();

    /**
     * Returns a defined DOM element
     * @param string $name
     *
     * @return \Behat\Mink\Element\NodeElement
     */
    public function get($name);

    /**
     * Returns all the defined DOM elements for $name
     *
     * @param string $name
     *
     * @return array<\Behat\Mink\Element\NodeElement>
     */
    public function all($name);

    /**
     * Returns a child view
     *
     * @param string $name
     *
     * @return View
     */
    public function getChild($name);

    /**
     * Returns an indexed child view
     *
     * @param string $name
     * @param int    $index
     *
     * @return View
     */
    public function getChildByIndex($name, $index);

    /**
     * Returns a shared view
     *
     * @param string $name
     *
     * @return View
     */
    public function getShared($name);

    /**
     * Wait for a defined element to be visible
     *
     * @param string $main
     * @param int    $timeout
     */
    public function waitFor($name, $timeout = 20000);
}
