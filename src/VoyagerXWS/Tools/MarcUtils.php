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

    /**
     * Inspects a marc record and return an array for formats based on various fields
     *
     * @param \VoyagerXWS\Response\MarcRecordResponse $record
     *
     * return array;
     */
    public static function getFormat(MarcRecordResponse $record)
    {
        $formats = array();

        $formatField = null;
        $formatString = null;
        $formatCode = '';
        $formatCode2 = '';
        $formatCode4 = '';

        $leader = $record->getLeader()->getValue();

        $fixedField = $record->getControlField("008");
        $fixedField = (!is_null($fixedField) && is_array($fixedField)) ? $fixedField[0] : null;

        $titleField = $record->getDataField("245");
        if(count($titleField)) {
            $titleField = $titleField[0];
            $subFieldH = $titleField->getSubfield('h');
            if($subFieldH != null) {
                if(strpos($subFieldH, '[electronic resource]')) {
                    $formats[] = 'Electronic';
                    return $formats;
                }
            }
        }

        // check the 007 - this is a repeating field
        $fields = $record->getControlField("007");
        if ($fields != null) {

            foreach($fields as $formatField) {
                $formatString = strtoupper($formatField->getValue());
                $formatCode  = (strlen($formatString)) > 0 ? substr($formatString, 0, 1): ' ';
                $formatCode2 = (strlen($formatString)) > 1 ? substr($formatString, 1, 1): ' ';
                $formatCode4 = (strlen($formatString)) > 4 ? substr($formatString, 4, 1): ' ';

                switch ($formatCode) {
                    case 'A':
                        switch($formatCode2) {
                            case 'D':
                                $formats[] = 'Atlas';
                                break;
                            default:
                                $formats[] = 'Map';
                                break;
                        }
                        break;
                    case 'C':
                        switch($formatCode2) {
                            case 'A':
                                $formats[] = 'TapeCartridge';
                                break;
                            case 'B':
                                $formats[] = 'ChipCartridge';
                                break;
                            case 'C':
                                $formats[] = 'DiscCartridge';
                                break;
                            case 'F':
                                $formats[] = 'TapeCassette';
                                break;
                            case 'H':
                                $formats[] = 'TapeReel';
                                break;
                            case 'J':
                                $formats[] = 'FloppyDisk';
                                break;
                            case 'M':
                            case 'O':
                                $formats[] = 'CDROM';
                                break;
                            case 'R':
                                // Do not return - this will cause anything with an
                                // 856 field to be labeled as "Electronic"
                                break;
                            default:
                                $formats[] = 'Software';
                                break;
                        }
                        break;
                    case 'D':
                        $formats[] = 'Globe';
                        break;
                    case 'F':
                        $formats[] = 'Braille';
                        break;
                    case 'G':
                        switch($formatCode2) {
                            case 'C':
                            case 'D':
                                $formats[] = 'Filmstrip';
                                break;
                            case 'T':
                                $formats[] = 'Transparency';
                                break;
                            default:
                                $formats[] = 'Slide';
                                break;
                        }
                        break;
                    case 'H':
                        $formats[] = 'Microfilm';
                        break;
                    case 'K':
                        switch($formatCode2) {
                            case 'C':
                                $formats[] = 'Collage';
                                break;
                            case 'D':
                            case 'L':
                                $formats[] = 'Drawing';
                                break;
                            case 'E':
                                $formats[] = 'Painting';
                                break;
                            case 'F':
                            case 'J':
                                $formats[] = 'Print';
                                break;
                            case 'G':
                                $formats[] = 'Photonegative';
                                break;
                            case 'O':
                                $formats[] = 'FlashCard';
                                break;
                            case 'N':
                                $formats[] = 'Chart';
                                break;
                            default:
                                $formats[] = 'Photo';
                                break;
                        }
                        break;
                    case 'M':
                        switch($formatCode2) {
                            case 'F':
                                $formats[] = 'VideCassette';
                                break;
                            case 'R':
                                $formats[] = 'Filmstrip';
                                break;
                            default:
                                $formats[] = 'MotionPicture';
                                break;
                        }
                        break;
                    case 'O':
                        $formats[] = 'Kit';
                        break;
                    case 'Q':
                        $formats[] = 'MusicScore';
                        break;
                    case 'R':
                        $formats[] = 'SensorImage';
                        break;
                    case 'S':
                        switch($formatCode2) {
                            case 'D':
                                $formats[] = 'SoundDisc';
                                break;
                            case 'S':
                                $formats[] = 'SoundCassette';
                                break;
                            default:
                                $formats[] = 'SoundRecording';
                                break;
                        }
                        break;
                    case 'V':
                        switch($formatCode2) {
                            case 'C':
                                $formats[] = 'VideoCartridge';
                                break;
                            case 'D':
                                switch($formatCode4) {
                                    case 'S':
                                        $formats[] = 'BRDisc';
                                        break;
                                    case 'V':
                                    default:
                                        $formats[] = 'VideoDisc';
                                        break;
                                }
                                break;
                            case 'F':
                                $formats[] = 'VideoCassette';
                                break;
                            case 'R':
                                $formats[] = 'VideoReel';
                                break;
                            default:
                                $formats[] = 'Video';
                                break;
                        }
                        break;
                }
            }
            if (count($formats) > 0) {
                return $formats;
            }
        }



        $leaderBit1 = strtoupper(substr($leader, 6, 1));
        $leaderBit2 = strtoupper(substr($leader, 7, 1));

        switch($leaderBit1){
            case 'C':
            case 'D':
                $formats[] = 'MusicalScore';
                break;
            case 'E':
            case 'F':
                $formats[] = 'Map';
                break;
            case 'G':
                $formats[] = 'Slide';
                break;
            case 'I':
                $formats[] = 'SoundRecording';
                break;
            case 'J':
                $formats[] = 'MusicRecording';
                break;
            case 'K':
                $formats[] = 'Photo';
                break;
            case 'M':
                $formats[] = 'Electronic';
                break;
            case 'O':
            case 'P':
                $formats[] = 'Kit';
                break;
            case 'R':
                $formats[] = 'Physical Object';
                break;
            case 'T':
                $formats[] = 'Manuscript';
                break;
        }

        switch ($leaderBit2) {
            // Monograph
            case 'M':
                if ($formatCode == 'C') {
                    $formats[] = 'eBook';
                } else {
                    $formats[] = 'Book';
                }
                break;
            // Component parts
            case 'A':
                $formats[] = 'BookComponentPart';
                break;
            case 'B':
                $formats[] = 'SerialComponentPart';
                break;
            // Serial
            case 'S':
                // Look in 008 to determine what type of Continuing Resource
                $formatCode = substr($fixedField->getValue(), 21, 1);

                switch ($formatCode) {
                    case 'N':
                        $formats[] = "Newspaper";
                        break;
                    case 'P':
                        $formats[] = "Journal";
                        break;
                    default:
                        $formats[] = "Serial";
                        break;
                }
        }

        return $formats;
    }

}