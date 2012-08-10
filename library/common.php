<?php

// $newline = "\n";

/** Autoload any classes that are required **/

function __autoload($className) {
	if (file_exists(ROOT . '/library/' . strtolower($className) . '.class.php')) 
	{
		require_once(ROOT . '/library/' . strtolower($className) . '.class.php');
	} 
	else if (file_exists(ROOT . '/application/controllers/' . strtolower($className) . '.php')) 
	{
		require_once(ROOT . '/application/controllers/' . strtolower($className) . '.php');
	} 
	else if (file_exists(ROOT . '/application/models/' . strtolower($className) . '.php')) 
	{
		require_once(ROOT . '/application/models/' . strtolower($className) . '.php');
	} 
	else 
	{
		/* Error Generation Code Here */
	}
}

/** Strip out from input string **/

function cleanInput($input) 
{
 
	$search = array(
	    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
	    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
	    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
	    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
	);
	
	$output = preg_replace($search, '', $input);
	return $output;
}

function sanitize($input) 
{
	if (is_array($input)) 
	{
		foreach($input as $var=>$val) 
		{
			$output[$var] = sanitize($val);
		}
	}
	else {
		if (get_magic_quotes_gpc()) 
		{
			$input = stripslashes($input);
		}
		$input  = cleanInput($input);
		$output = mysql_real_escape_string($input);
	}
	return $output;
}

/** Check for Magic Quotes and remove them **/

function stripSlashesDeep($value) 
{
	$value = is_array($value) ? array_map('stripSlashesDeep', $value) : cleanInput(stripslashes($value));
	return $value;
}

function removeMagicQuotes() 
{
	if ( get_magic_quotes_gpc() ) 
	{
		$_GET    = stripSlashesDeep($_GET   );
		$_POST   = stripSlashesDeep($_POST  );
		$_COOKIE = stripSlashesDeep($_COOKIE);
	}
}

/** Check register globals and remove them **/

function unregisterGlobals() {
    if (ini_get('register_globals')) 
	{
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value) 
		{
            foreach ($GLOBALS[$value] as $key => $var) 
			{
                if ($var === $GLOBALS[$key]) 
				{
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}

/** GZip Output **/

function gzipOutput() {
    $ua = $_SERVER['HTTP_USER_AGENT'];

    if (0 !== strpos($ua, 'Mozilla/4.0 (compatible; MSIE ')
        || false !== strpos($ua, 'Opera')) 
	{
        return false;
    }

    $version = (float)substr($ua, 30); 
    return (
        $version < 6
        || ($version == 6  && false === strpos($ua, 'SV1'))
    );
}

?>