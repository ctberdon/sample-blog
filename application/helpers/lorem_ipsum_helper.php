<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Lorem Ipsum Helpers
 *
 * This generates well formated randam text for quickly filling your
 * page in design phase.
 *
 */


/**
 * Lorem Ipsum Generator
 *
 * @param   integer Number of paragraphs to generate
 * @param   size of text in paras - short, medium, large
 * @param   options array - see readme
 * @return  html with Lorem Ipsum text
 */
if (!function_exists('lipsum')) {
    function lipsum($no_paras=1, $size='short', $options= array())
    {
        $contents = file_get_contents('http://loripsum.net/api/' . $no_paras . '/' . $size . join('/', $options));
        return $contents;
    }
}
