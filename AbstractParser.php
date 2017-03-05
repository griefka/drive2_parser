<?php


abstract class AbstractParser implements Parsable
{

    /**
     * AbstractParser constructor.
     */
    public function __construct()
    {
    }

    abstract function getPageHtml();
}