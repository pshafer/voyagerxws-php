<?php

namespace VoyagerXWS\Response;

use Symfony\Component\DomCrawler\Crawler;
use VoyagerXWS\Fields\LeaderField;
use VoyagerXWS\Fields\ControlField;
use VoyagerXWS\Fields\DataField;

class MarcRecordResponse
{
    private $domCrawler;

    /**
     * MarcRecordResponse constructor.
     * @param $xmldoc text of xml document returned VoyagerXWS Record Service
     */
    public function __construct($xmldoc)
    {
        if(isset($xmldoc))
        {
            $this->domCrawler = new Crawler($xmldoc);
        }
    }

    public function getStatus()
    {
        $statusText = $this->domCrawler->filter('response > reply-text');
        $statusCode = $this->domCrawler->filter('response > reply-code');

        if($statusText->count() === 1 && $statusCode->count() === 1){
            return array('status-code' => intval($statusCode->text()), 'status-text'=> $statusText->text());
        }

        return array('status-code' => -1, 'status-text' => 'Document Not Retrieved');
    }

    /**
     * @return \VoyagerXWS\Fields\LeaderField
     */
    public function getLeader()
    {
        $leader = $this->domCrawler->filter('response > record > marcRecord > leader')->first();

        return new LeaderField($leader);

    }



    /**
     * Returns a control field for the given tagName
     *
     * @param $tagName the name of the tag to retreive such as '001' or '006', etc
     *
     * @return array|null
     */
    public function getControlField($tagName)
    {
        $query = '//response/record/marcRecord/controlfield[@tag="' . $tagName . '"]';
        $controlField = $this->domCrawler->filterXPath($query)->first();

        $controlFields = $this->domCrawler->filterXPath($query)->each(function(Crawler $node) {
           return new ControlField($node);
        });

        if(count($controlFields) > 0 ){
            return $controlFields;
        }

        return null;
    }

    /**
     * Returns an array of all control fields in the Response Objects
     *
     * @return array of ControlField objects
     */
    public function getControlFields()
    {
        $fields = array();
        $query = 'response > record > marcRecord > controlfield';
        $controlFields = $this->domCrawler->filter($query)->each(function(Crawler $node) {
            return new ControlField($node);
        });


        return $controlFields;
    }

    /**
     * Returns an array of DataField objects
     *
     * @param $tagName - name of the field to retrieve
     * @param string $indicator1 - the first indicator value of the field to retrieve
     * @param string $indicator2 - the second indicator value of the field to retieve
     * @return array of DataField objects
     */
    public function getDataField($tagName, $indicator1 = NULL, $indicator2 = NULL)
    {
        $query = "//response/record/marcRecord/datafield{$this->dataFieldTagQueryFilter($tagName,$indicator1,$indicator2)}";

        $dataFields = $this->domCrawler->filterXPath($query)->each(function(Crawler $node){
            return new DataField($node);
        });

        return $dataFields;
    }

    /**
     * Returns all the datafields in the response record
     *
     * @return array of DataField objects
     */
    public function getDataFields()
    {
        $query = 'response > record > marcRecord > datafield';
        $dataFields = $this->domCrawler->filter($query)->each(function (Crawler $node) {
            return new DataField($node);
        });


        return $dataFields;
    }

    public function getXML()
    {
        return $this->domCrawler->html();
    }


    private function dataFieldTagQueryFilter($tag, $ind1 = NULL, $ind2 = NULL)
    {
        $filter = "@tag='$tag'";

        $filter .= ($ind1) ? " and @ind1='$ind1'" : "";
        $filter .= ($ind2) ? " and @ind2='$ind2'" : "";

        return "[$filter]";
    }




}
