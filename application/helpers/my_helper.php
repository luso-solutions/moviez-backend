<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function changetodb($date)
{
	list($day, $month, $year) = explode("/", $date);
	$newdate = $year ."-". $month ."-". $day;
	return $newdate;
}

function changetoview($date)
{
	list($year, $month, $day) = explode("-", $date);
	$newdate = $day ."/". $month ."/". $year;
	return $newdate;
}
