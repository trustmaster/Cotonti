<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=admin.rights.modules
[END_COT_EXT]
==================== */

/**
 * Forum sections permissions
 *
 * @package Cotonti
 * @version 0.7.0
 * @author Neocrome, Cotonti Team
 * @copyright Copyright (c) Cotonti Team 2008-2010
 * @license BSD
 */

(defined('SED_CODE') && defined('SED_ADMIN')) or die('Wrong URL.');

sed_require('forums');

$sql = sed_sql_query("SELECT a.*, u.user_name, f.fs_id, f.fs_title, f.fs_category FROM $db_auth as a
	LEFT JOIN $db_users AS u ON u.user_id=a.auth_setbyuserid
	LEFT JOIN $db_forum_sections AS f ON f.fs_id=a.auth_option
	LEFT JOIN $db_forum_structure AS n ON n.fn_code=f.fs_category
	WHERE auth_groupid='$g' AND auth_code='forums' AND auth_option != 'a'
	ORDER BY fn_path ASC, fs_order ASC, fs_title ASC");
while ($row = sed_sql_fetcharray($sql))
{
	$link = sed_url('admin', 'm=forums&n=edit&id='.$row['auth_option']);
	$title = htmlspecialchars(sed_build_forums($row['fs_id'], sed_cutstring($row['fs_title'], 24), sed_cutstring($row['fs_category'], 32), FALSE));
	sed_rights_parseline($row, $title, $link);
}
sed_sql_freeresult($sql);
$t->assign('RIGHTS_SECTION_TITLE', $L['Forums']);
$t->parse('MAIN.RIGHTS_SECTION');

?>