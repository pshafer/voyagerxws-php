<?php

namespace VoyagerXWS\Response\Fields;

use Symfony\Component\DomCrawler\Crawler;
use VoyagerXWS\Response\Fields\ItemDataField;
use \DOMNode;

class ItemField extends Crawler
{
    private $itemField; //DomCrawler object for this element

    /**
     * ItemField constructor.
     * @param Symfony\Component\DomCrawler/Crawler $domElement
     */
    public function __construct ($domElement) {

        if ($domElement instanceof DOMNode) {
            parent::__construct($domElement);
        } else {
            // throw error
        }
    }

    public function getItemData($fieldName) {
        $query = "//itemData[@name='$fieldName']";

        $itemData = $this->filterXPath($query)->each(function(Crawler $node, $i) {
            $element = $node->getNode(0);
            return new ItemDataField($element);
        });

        return $itemData;
    }
}