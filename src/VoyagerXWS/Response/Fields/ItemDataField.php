<?php

namespace VoyagerXWS\Response\Fields;

use Symfony\Component\DomCrawler\Crawler;
use \DOMNode;

class ItemDataField extends Crawler
{
    public function __construct ($domElement) {

        if ($domElement instanceof DOMNode) {
            parent::__construct($domElement);
        } else {
            // throw exception
        }
    }
}