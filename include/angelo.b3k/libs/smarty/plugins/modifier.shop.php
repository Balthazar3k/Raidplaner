<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty date_format modifier plugin
 *
 * Type:     modifier<br>
 * Name:     date_format<br>
 * Purpose:  format datestamps via strftime<br>
 * Input:<br>
 *          - string: input date string
 *          - format: strftime format for output
 *          - default_date: default date if $string is empty
 *
 * @link http://www.smarty.net/manual/en/language.modifier.date.format.php date_format (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com>
 * @param string $string       input date string
 * @param string $format       strftime format for output
 * @param string $default_date default date if $string is empty
 * @param string $formatter    either 'strftime' or 'auto'
 * @return string |void
 * @uses smarty_make_timestamp()
 */
function smarty_modifier_shop($string)
{
    return $string;
}
