<?php

namespace VoyagerXWS\Fields;

use Symfony\Component\DomCrawler\Crawler;
use VoyagerXWS\Fields\Field;

class DataField extends Field
{
    private $indicator1;
    private $indicator2;
    private $subfields;


    public function __construct($domElement)
    {
        parent::__construct();
        parent::setTag($domElement->attr('tag'));
        $this->indicator1 = $domElement->attr('ind1');
        $this->indicator2 = $domElement->attr('ind2');
        $this->subfields = array();

        $subFieldElements = $domElement->filter('subfield')->each(function (Crawler $node){
            return [ 'code' => $node->attr('code'), 'text' => $node->text()];
        });

        foreach($subFieldElements as $element) {
            $this->subfields[$element['code']] = $element['text'];
        }

    }

    public function getSubfields()
    {
        return $this->subfields;
    }

    public function getSubfield($code)
    {
        return $this->subfields[$code];
    }
}