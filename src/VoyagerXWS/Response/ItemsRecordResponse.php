<?php

namespace VoyagerXWS\Response;

use Symfony\Component\DomCrawler\Crawler;
use VoyagerXWS\Fields\ItemField;

class ItemsRecordResponse
{
    private $domCrawler;

    /**
     * ItemsRecordResponse constructor.
     * @param $xmldoc text of xml document returned from VoyagerXWS Item Service
     */
    public function __construct($xmldoc)
    {
        if(isset($xmldoc))
        {
            $this->domCrawler = new Crawler($xmldoc);
        }else{
            // Eventually Throw an exception
        }
    }

    /**
     * Return an array of ItemField objects
     *
     * @return array of ItemFields
     */
    public function getItems()
    {
        $items = $this->domCrawler->filter('items > institution > item')->each(function(Crawler $node, $i) {
            $element = $node->getNode(0);
            return new ItemField($element);
        });

        return $items;
    }

    /**
     *
     * @param array $locationCodes
     */
    public function getItemsByLocation($locationCodes = array())
    {

    }

    /**
     * Returns the status of the Item Respnonse from the VXWS server
     *
     * @return array of status code and status-text
     */
    public function getStatus()
    {
        $statusText = $this->domCrawler->filter('response > reply-text');
        $statusCode = $this->domCrawler->filter('response > reply-code');

        if($statusText->count() === 1 && $statusCode->count() === 1){
            return array('status-code' => intval($statusCode->text()), 'status-text'=> $statusText->text());
        }

        return array('status-code' => -1, 'status-text' => 'Document Not Retrieved');
    }
}