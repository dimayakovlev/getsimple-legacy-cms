<?php
/**
 * Configuration File
 *
 * @package GetSimple Legacy
 * @subpackage Config
 */

define('GSNAME', 'GetSimple Legacy CMS');
define('GSVERSION', '2024.3');
define('GSURL', 'https://github.com/dimayakovlev/getsimple-legacy-cms');

// These variables are deprecated. Use constants GSNAME, GSVERSION and GSURL instead.
$site_full_name     = GSNAME;
$site_version_no    = GSVERSION;
$site_link_back_url = GSURL;

// cookie config
$cookie_name        = lowercase(str_replace(' ', '-', GSNAME)) . '_cookie_' . str_replace(array('.', '-'), '', GSVERSION); // non-hashed name of cookie
$cookie_login       = 'index.php'; // login redirect
$cookie_time        = '10800';     // in seconds, 3 hours
$cookie_path        = '/';         // cookie path
$cookie_domain      = '';        // cookie domain
$cookie_secure      = false;        // cookie secure only
$cookie_httponly    = true;        // cookie http only

$cookie_redirect = 'pages.php';
