<?php

class HTML {
	function sanitize($data) 
	{
		return mysql_real_escape_string($data);
	}

	function link($text,$path) 
	{
		$path = str_replace(' ','-',$path);
		return '<a href="' . BASE_PATH . '/' . $path . '">' . $text . '</a>';
	}

	function includeJs($fileName) 
	{
		return '<script src="' . BASE_PATH . '/js/' . $fileName . '.js"></script>';
	}

	function includeCss($fileName) 
	{
		return '<style href="' . BASE_PATH . '/css/' . $fileName . '.css"></script>';
	}
}