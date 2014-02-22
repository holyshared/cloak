<?php

namespace CodeAnalyzer;

class Configuration
{

    private $collect = null;
    private $includeFiles = array();
    private $excludeFiles = array();

    public function __construct()
    {
        $this->collect = XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE;
    }

    public function collect($collect = null)
    {
        if (is_null($collect)) {
            return $this->collect;
        }
        return $this;
    }

    public function includeFile(\Closure $filter = null)
    {
        if (is_null($filter)) {
            return $this->includeFiles;
        }
        return $this;
    }

    public function excludeFile(\Closure $filter = null)
    {
        if (is_null($filter)) {
            return $this->excludeFiles;
        }
        return $this;
    }

}
