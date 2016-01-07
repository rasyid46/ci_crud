<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function formatUang($uang)
{
	return number_format($uang,0,',','.');

}