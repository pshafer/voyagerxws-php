<?php

namespace VoyagerXWS\Fields;

class ControlField extends Field
{
    public function __construct($domElement)
    {
        parent::__construct();
        parent::setTag($domElement->attr('tag'));
        parent::setValue($domElement->text());

    }
}