<?php

namespace VoyagerXWS\Fields;

abstract class Field
{
    private $tagName;
    private $value;

    public function __construct()
    {
        $this->tagName = null;
        $this->value = null;

    }

    public function setTag($tagName)
    {
        $this->tagName = $tagName;

    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getTag()
    {
        return $this->tagName;
    }

    public function getValue()
    {
        return $this->value;
    }
}