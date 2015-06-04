<?php

namespace EXS\ErrorProvider\Services\Utils;

/**
 * Description Flattener
 * Class used to flatten arrays into strings
 *
 * Created      03/09/2015
 * @author      Charles Weiss
 * @copyright   Copyright 2015 ExSitu Marketing.
 */
class Flattener
{
    /**
     * Function used to echo out an array as a string (useful when saving to db or trying to display arrays in a friendly way)
     *
     * @param array $params The array we would like to flatten
     *
     * @return string The flattened array
     * @access public
     */
    public static function flattenArrayToString($params = array())
    {
        $string = '';
        if (!empty($params)) {
            \ob_start();
            print_r($params);
            $string = \ob_get_clean();
            \ob_end_clean();
        }

        return $string;
    }

}
