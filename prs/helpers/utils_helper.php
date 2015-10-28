<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('outputError')) {
    function outputError($errno, $errmsg) {
        $rtn = array(
            'errno' => $errno,
            'errmsg' => $errmsg
        );
        return json_encode($rtn);
    }
}


