<?php

// Based on https://github.com/solrmarc/stanford-solr-marc/blob/6b068e268de3287761b2b731dc7f24510bbebe8d/core/src/org/solrmarc/tools/MarcUtils.java

namespace VoyagerXWS\Tools;

use VoyagerXWS\Response\MarcRecordResponse;
use VoyagerXWS\Tools\TextUtils;

class MarcUtils
{

    public static function getLeader(MarcRecordResponse $record)
    {
        $leader = null;
        $leader = $record->getLeader();
        return $leader->getValue();

    }


    /**
     * Get the 245a (and 245b, if it exists, concatenated with a space between
     *  the two subfield values), with trailing punctuation removed.
     *
     * @param \VoyagerXWS\Response\MarcRecordResponse $record
     * @return string
     */
    public static function getTitle(MarcRecordResponse $record)
    {
        $titleFields = $record->getDataField('245');
        $titleText = '';

        foreach($titleFields as $titleField) {
            $sfA = $titleField->getSubfield('a');
            $sfB = $titleField->getSubfield('b');
            $sfK = $titleField->getSubfield('k');

            $titleText .= ($sfA) ? " $sfA" : "";
            $titleText .= ($sfB) ? " $sfB" : "";
            $titleText .= ($sfK) ? " $sfK" : "";
        }

        return TextUtils::cleanData($titleText);
    }

    /**
     * Returns an array of possible values for ISBN
     *
     * @param \VoyagerXWS\Response\MarcRecordResponse $record
     * @return array
     */
    public static function getISBN(MarcRecordResponse $record) {
        $isbnFields = $record->getDataField('020');

        $values = array();
        foreach($isbnFields as $isbnField) {
            $isbn = $isbnField->getSubfield('a');
            if($isbn){
                $isbn = TextUtils::cleanISBN($isbn);
                if(strlen($isbn) === 13){
                    $values['eisbn'] = $isbn;
                }else{
                    $values['isbn'] = $isbn;
                }


            }
        }

        return $values;
    }
}