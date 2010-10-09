<?php
/* ====================
Seditio - Website engine
Copyright Neocrome
http://www.neocrome.net
[BEGIN_SED]
File=users.auth.inc.php
Version=102
Updated=2006-apr-19
Type=Core
Author=Neocrome
Description=User authication
[END_SED]
==================== */

defined('COT_CODE') or die('Wrong URL');

$v = cot_import('v','G','PSW');

/* === Hook === */
foreach (cot_getextplugins('users.auth.first') as $pl)
{
	include $pl;
}
/* ===== */

if ($a=='check')
{
	cot_shield_protect();

	/* === Hook for the plugins === */
	foreach (cot_getextplugins('users.auth.check') as $pl)
	{
		include $pl;
	}
	/* ===== */

	$rusername = cot_import('rusername','P','TXT', 100, TRUE);
	$rpassword = cot_import('rpassword','P','PSW', 16, TRUE);
	$rcookiettl = cot_import('rcookiettl', 'P', 'INT');
	$rremember = cot_import('rremember', 'P', 'BOL');
	if(empty($rremember) && $rcookiettl > 0 || $cfg['forcerememberme'])
    {
        $rremember = true;
    }
	$rmdpass  = md5($rpassword);

	$login_param = preg_match('#^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]{2,})+$#i', $rusername) ?
		'user_email' : 'user_name';

	/**
	 * Sets user selection criteria for authentication. Override this string in your plugin
	 * hooking into users.auth.check.query to provide other authentication methods.
	 */
	$user_select_condition = "user_password='$rmdpass' AND $login_param='".cot_db_prep($rusername)."'";

	/* === Hook for the plugins === */
	foreach (cot_getextplugins('users.auth.check.query') as $pl)
	{
		include $pl;
	}
	/* ===== */

	$sql = cot_db_query("SELECT user_id, user_name, user_maingrp, user_banexpire, user_theme, user_scheme, user_lang FROM $db_users WHERE $user_select_condition");

	if ($row = cot_db_fetcharray($sql))
	{
		$rusername = $row['user_name'];
		if ($row['user_maingrp']==-1)
		{
			cot_log("Log in attempt, user inactive : ".$rusername, 'usr');
			cot_redirect(cot_url('message', 'msg=152', '', true));
		}
		if ($row['user_maingrp']==2)
		{
			cot_log("Log in attempt, user inactive : ".$rusername, 'usr');
			cot_redirect(cot_url('message', 'msg=152', '', true));
		}
		elseif ($row['user_maingrp']==3)
		{
			if ($sys['now'] > $row['user_banexpire'] && $row['user_banexpire']>0)
			{
				$sql = cot_db_query("UPDATE $db_users SET user_maingrp='4' WHERE user_id={$row['user_id']}");
			}
			else
			{
				cot_log("Log in attempt, user banned : ".$rusername, 'usr');
				cot_redirect(cot_url('message', 'msg=153&num='.$row['user_banexpire'], '', true));
			}
		}

		$ruserid = $row['user_id'];
		$rdeftheme = $row['user_theme'];
		$rdefscheme = $row['user_scheme'];

		$token = cot_unique(16);
		$sid = cot_unique(32);

		cot_db_query("UPDATE $db_users SET user_lastip='{$usr['ip']}', user_lastlog = {$sys['now_offset']}, user_logcount = user_logcount + 1, user_token = '$token', user_sid = '$sid' WHERE user_id={$row['user_id']}");

		$u = $ruserid.':'.$sid;

		if($rremember)
		{
			cot_setcookie($sys['site_id'], $u, time()+$cfg['cookielifetime'], $cfg['cookiepath'], $cfg['cookiedomain'], $sys['secure'], true);
		}
		else
		{
			$_SESSION[$sys['site_id']] = $u;
		}

		/* === Hook === */
		foreach (cot_getextplugins('users.auth.check.done') as $pl)
		{
			include $pl;
		}
		/* ===== */

		$sql = cot_db_query("DELETE FROM $db_online WHERE online_userid='-1' AND online_ip='".$usr['ip']."' LIMIT 1");
		cot_uriredir_apply($cfg['redirbkonlogin']);
		cot_uriredir_redirect(empty($redirect) ? cot_url('index') : base64_decode($redirect));
	}
	else
	{
		cot_shield_update(7, "Log in");
		cot_log("Log in failed, user : ".$rusername,'usr');
		cot_redirect(cot_url('message', 'msg=151', '', true));
	}
}

/* === Hook === */
foreach (cot_getextplugins('users.auth.main') as $pl)
{
	include $pl;
}
/* ===== */

$out['subtitle'] = $L['aut_logintitle'];
$out['head'] .= $R['code_noindex'];
require_once $cfg['system_dir'] . '/header.php';
$t = new XTemplate(cot_skinfile('users.auth'));

cot_require_api('forms');

if ($cfg['maintenance'])
{
	$t->assign(array("USERS_AUTH_MAINTENANCERES" => $cfg['maintenancereason']));
	$t->parse("MAIN.USERS_AUTH_MAINTENANCE");
}

$t->assign(array(
	"USERS_AUTH_TITLE" => $L['aut_logintitle'],
	"USERS_AUTH_SEND" => cot_url('users', 'm=auth&a=check' . (empty($redirect) ? '' : "&redirect=$redirect")),
	"USERS_AUTH_USER" => cot_inputbox('text', 'rusername', $rusername, array('size' => '16', 'maxlength' => '32')),
	"USERS_AUTH_PASSWORD" => cot_inputbox('password', 'rpassword', '', array('size' => '16', 'maxlength' => '32')),
	"USERS_AUTH_REGISTER" => cot_url('users', 'm=register')
));

/* === Hook === */
foreach (cot_getextplugins('users.auth.tags') as $pl)
{
	include $pl;
}
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require_once $cfg['system_dir'] . '/footer.php';
?>