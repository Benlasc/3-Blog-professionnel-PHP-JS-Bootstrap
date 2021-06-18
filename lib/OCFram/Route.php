<?php

namespace OCFram;

class Route
{
    protected $action;
    protected $module;
    protected $url;
    protected $varsNames;
    protected $vars = [];
    protected $name;

    public function __construct($url, $module, $action, array $varsNames, $name)
    {
        $this->setUrl($url);
        $this->setModule($module);
        $this->setAction($action);
        $this->setVarsNames($varsNames);
        $this->setName($name);
    }

    public function hasVars()
    {
        return !empty($this->varsNames);
    }

    public function match($url)
    {
        if (preg_match('`^' . $this->url . '$`', $url, $matches)) {  //  "`^url_blabla$`"
            return $matches;
        } else {
            return false;
        }
    }

    public function setAction($action)
    {
        if (is_string($action)) {
            $this->action = $action;
        }
    }

    public function setModule($module)
    {
        if (is_string($module)) {
            $this->module = $module;
        }
    }

    public function setUrl($url)
    {
        if (is_string($url)) {
            $this->url = $url;
        }
    }

    public function setVarsNames(array $varsNames)
    {
        $this->varsNames = $varsNames;
    }

    public function setVars(array $vars)
    {
        $this->vars = $vars;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function action()
    {
        return $this->action;
    }

    public function module()
    {
        return $this->module;
    }

    public function vars()
    {
        return $this->vars;
    }

    public function varsNames()
    {
        return $this->varsNames;
    }

    public function name()
    {
        return $this->name;
    }

    public function url()
    {
        return $this->url;
    }
}
