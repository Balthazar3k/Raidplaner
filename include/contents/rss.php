<?php
defined ('main') or die ( 'no direct access' );
 
$title = $allgAr['title'].' ';
$hmenu = '';
$design = new design ( $title , $hmenu );
$design->header();
 
 $url = 'http://www.uoherald.com/rss.xml';
 $number = 6; //Maximale Anzahl
 
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
 $content = curl_exec ($ch);
 curl_close ($ch);
 
 echo $content;
 
 
$design->footer(0);
 
?>