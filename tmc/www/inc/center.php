<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:事務局ページ共通関数
'******************************************************/

define('MUST_ITEM', '<sup><font color="green">*</font></sup>');
define('LIST_TABLE', 'border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box"');

define('PRIV_DHSTAFF', 'D');
define('PRIV_OPERATOR', 'O');

// 共通ヘッダ
function center_header($title_text) {
	global $g_staff_id;

	if ($g_staff_id)
		$login = "Login: {$_SESSION['s_mail_addr']}";
?>
<table cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td align=left style="font-size:x-small;font-weight:bold;color:#888888"><?=$login?></td>
		<td align=right style="font-size:x-small;font-weight:bold"><?=SYSTEM_NAME?></td>
	</tr>
</table>
<table border=0 width="100%" cellspacing=1 cellpadding=2 bgcolor="<?=staff_color()?>">
	<tr>
		<td onclick="location.reload()" style="color:white;font-weight:bold"><?=$title_text?></td>
	</tr>
</table>
<hr class="header">
<?
}

// 共通フッタ
function center_footer() {
?>
<hr class="footer">
<div align="center"><font size="-1">Copyright(c) <?=date(Y)?> xx xxxxx Inc. All rights reserved.</font></div>
<?
}

// 権限別の色を取得
function staff_color() {
	switch ($_SESSION['s_privilege']) {
	case PRIV_DHSTAFF:
		return '#44aa66';
	case PRIV_OPERATOR:
		return '#4466aa';
	default:
		return 'steelblue';
	}
}

// システムエラー処理
function system_error($msg, $fname) {
	global $top;
	redirect("$top/common/system_error.php");
}
?>