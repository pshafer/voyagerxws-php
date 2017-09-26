<?php

namespace VoyagerXWS\Fields;

use VoyagerXWS\Fields\Field;

class ControlField extends Field
{
    public function __construct($domElement)
    {
        parent::__construct();
        parent::setTag($domElement->attr('tag'));
        parent::setValue($domElement->text());

    }
}