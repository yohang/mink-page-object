<?php

namespace MinkPageObject\View;

/**
 *
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
abstract class AbstractView extends View
{
    /**
     * @return View
     */
    public function init()
    {
        $this->configure();

        return parent::init();
    }

    public abstract function configure();
}
