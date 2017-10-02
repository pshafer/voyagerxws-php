<?php

namespace VoyagerXWS\Fields;

use Symfony\Component\DomCrawler\Crawler;

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

    /**
     * Returns the array of subfields
     *
     * @return array of subfields
     */
    public function getSubfields()
    {
        return $this->subfields;
    }

    /**
     * Returns the value of the provided subfield code
     *
     * @param $code the code of the subfield to retrieve
     *
     * @return mixed|null returns the value of the subfield or null if its not found
     */
    public function getSubfield($code)
    {
        if(array_key_exists($code, $this->subfields)){
            return $this->subfields[$code];
        }else{
            return null;
        }

    }
}