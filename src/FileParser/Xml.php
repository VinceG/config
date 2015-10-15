<?php

namespace Noodlehaus\FileParser;

use Noodlehaus\Exception\ParseException;

/**
 * XML file parser
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Xml implements FileParserInterface
{
    /**
     * {@inheritDoc}
     * Parses an XML file or string as an array
     *
     * @throws ParseException If there is an error parsing the XML file
     */
    public function parse($pathOrString)
    {
        libxml_use_internal_errors(true);
    
        if(is_file($pathOrString)) {
            $data = simplexml_load_file($pathOrString, null, LIBXML_NOERROR);
        } else {
            $data = simplexml_load_string($pathOrString, 'SimpleXMLElement', LIBXML_NOERROR | LIBXML_PARSEHUGE);
        }

        if ($data === false) {
            $errors      = libxml_get_errors();
            $latestError = array_pop($errors);
            $error       = array(
                'message' => $latestError->message,
                'type'    => $latestError->level,
                'code'    => $latestError->code,
                'file'    => $latestError->file,
                'line'    => $latestError->line,
            );
            throw new ParseException($error);
        }

        $data = json_decode(json_encode($data), true);

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedExtensions()
    {
        return array('xml');
    }
}
