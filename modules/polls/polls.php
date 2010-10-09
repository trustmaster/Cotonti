<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=module
[END_COT_EXT]
==================== */

/**
 * Polls
 *
 * @package polls
 * @version 0.7.0
 * @author esclkm, Cotonti Team
 * @copyright Copyright (c) Cotonti Team 2008-2010
 * @license BSD License
 */

defined('COT_CODE') or die('Wrong URL');

// Environment setup
define('COT_POLLS', TRUE);
$env['location'] = 'polls';

/* === Hook === */
foreach (cot_getextplugins('polls.first') as $pl)
{
	include $pl;
}
/* ===== */

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('polls', 'a');
cot_block($usr['auth_read']);

$mode = cot_import('mode', 'G', 'ALP');

if ($mode == 'ajax')
{
	$theme = cot_import('poll_skin', 'G', 'TXT');
	$id = cot_import('poll_id', 'P', 'INT');
	cot_sendheaders();
	cot_poll_vote();
	list($polltitle, $poll_form) = cot_poll_form($id, '', $theme);
	echo $poll_form;
	exit;
}

$id = cot_import('id', 'G', 'ALP', 8);
$vote = cot_import('vote', 'G', 'TXT');
if (!empty($vote))
{
	$vote = explode(" ", $vote);
}
if (empty($vote))
{
	$vote = cot_import('vote', 'P', 'ARR');
}

$ratings = cot_import('ratings', 'G', 'BOL');

$out['subtitle'] = $L['Polls'];

cot_online_update();

/* === Hook === */
foreach (cot_getextplugins('polls.main') as $pl)
{
	include $pl;
}
/* ===== */

require_once $cfg['system_dir'] . '/header.php';

$t = new XTemplate(cot_skinfile('polls'));

if (cot_check_messages())
{
	cot_display_messages($t);
}
elseif ((int)$id > 0)
{
	$id = cot_import($id, 'D', 'INT');
	if ((int) cot_db_result(cot_db_query("SELECT COUNT(*) FROM $db_polls WHERE poll_id=$id AND poll_type='index' "), 0, 0) != 1)
	{
		cot_redirect(cot_url('message', 'msg=404', '', TRUE));
	}
	cot_poll_vote();
	$poll_form = cot_poll_form($id);

	$t->assign(array(
		"POLLS_TITLE" => cot_parse($poll_form['poll_text'], $cfg['module']['polls']['markup']),
		"POLLS_FORM" => $poll_form['poll_block'],
		"POLLS_VIEWALL" => cot_rc_link(cot_url('polls', 'id=viewall'), $L['polls_viewarchives'])
	));

	/* === Hook === */
	foreach (cot_getextplugins('polls.view.tags') as $pl)
	{
		include $pl;
	}
	/* ===== */

	$t->parse("MAIN.POLLS_VIEW");

	$extra = $L['polls_notyetvoted'];
	if ($alreadyvoted)
	{
		$extra = ($votecasted) ? $L['polls_votecasted'] : $L['polls_alreadyvoted'];
	}

	$t->assign(array(
		"POLLS_EXTRATEXT" => $extra,
	));

	$t->parse("MAIN.POLLS_EXTRA");
}
else
{
	$jj = 0;
	$sql = cot_db_query("SELECT * FROM $db_polls WHERE poll_state = 0 AND poll_type = 'index' ORDER BY poll_id DESC");

	/* === Hook - Part1 === */
	$extp = cot_getextplugins('polls.viewall.tags');
	/* ===== */
	while ($row = cot_db_fetcharray($sql))
	{
		$jj++;
		$t->assign(array(
			"POLL_DATE" => date($cfg['formatyearmonthday'], $row['poll_creationdate'] + $usr['timezone'] * 3600),
			"POLL_HREF" => cot_url('polls', 'id='.$row['poll_id']),
			"POLL_TEXT" => cot_parse($row['poll_text'], $cfg['module']['polls']['markup']),
			"POLL_NUM" => $jj,
			"POLL_ODDEVEN" => cot_build_oddeven($jj)
		));

		/* === Hook - Part2 === */
		foreach ($extp as $pl)
		{
			include $pl;
		}
		/* ===== */

		$t->parse("MAIN.POLLS_VIEWALL.POLL_ROW");
	}

	if ($jj == 0)
	{
		$t->parse("MAIN.POLLS_VIEWALL.POLL_NONE");
	}
	$t->parse("MAIN.POLLS_VIEWALL");
}

/* === Hook === */
foreach (cot_getextplugins('polls.tags') as $pl)
{
	include $pl;
}
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");
require_once $cfg['system_dir'] . '/footer.php';

?>