<?php
/**
 * Simple AJAX previewer for MarkItUp!
 *
 * @package Seditio-N
 * @version 0.0.1
 * @author Trustmaster
 * @copyright (c) 2008 Cotonti Team
 * @license BSD license
 */

define('SED_CODE', true);
require_once './system/functions.php';
require_once './datas/config.php';
define('SED_NO_ANTIXSS', true);
require_once './system/common.php';

header('Content-type: text/html; charset='.$cfg['charset']);

$text = sed_import('text', 'P', 'HTM');
echo sed_post_parse(sed_parse($text));
ob_end_flush();
?>