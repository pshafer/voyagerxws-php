<?php

namespace VoyagerXWS\Fields;

class LeaderField extends Field
{
    public function __construct($domElement)
    {
        parent::__construct();
        parent::setTag('000');
        parent::setValue($domElement->text());
    }
}