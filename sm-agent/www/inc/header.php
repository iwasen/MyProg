<?
/******************************************************
' System :メール配信サービス
' Content:ヘッダーフッタ
'******************************************************/

// 戻り先
define('BACK_NONE', 0);
define('BACK_LOGOFF', 1);
define('BACK_TOP', 2);
define('BACK_CLOSE', 3);
define('BACK_ADMIN', 4);

// ヘッダ
function page_header() {
	global $g_title;

	$section = 'メール配信サービス';
?>
<table cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td align="left" style="font-size:x-small;font-weight:bold;color:#666666"><?=$section?></td>
		<td align="right" valign=bottom norap><?=back_link()?></td>
	</tr>
</table>
<table border=0 width="100%" cellspacing=1 cellpadding=3 class="title">
	<tr onclick="location.reload()">
		<td align="center"><nobr><?=$g_title?></nobr></td>
	</tr>
</table>
<hr class="header">
<?
}

// 共通フッタ
function page_footer() {
	global $user_login_id, $user_mail_addr;

	if ($user_login_id)
		$login = "Login: $user_mail_addr";
?>
<hr class="footer">
<table cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td align="left" style="font-size:x-small;font-weight:bold;color:#666666"><?=$login?></td>
		<td align="right" valign=bottom norap><?=back_link()?></td>
	</tr>
</table>
<div align="center">Copyright(c) <?=date(Y)?> xx xxxxx Inc. All rights reserved.</div>
<?
}

// 戻りリンク
function back_link() {
	global $g_back, $top;

	switch ($g_back) {
	case BACK_LOGOFF:
		$back = "<small><font color=blue>■</font><a href=\"$top/login/logoff.php\">ログオフ</a></small>";
		break;
	case BACK_TOP:
		$back = "<small><font color=blue>■</font><a href=\"$top/index.php\">トップへ戻る</a></small>";
		break;
	case BACK_CLOSE:
		$back = "<small><font color=blue>■</font><a href=\"javascript:window.close()\">閉じる</a></small>";
		break;
	case BACK_ADMIN:
		$back = "<small><font color=blue>■</font><a href=\"$top/admin/index.php\">トップへ戻る</a></small>";
		break;
	}

	return $back;
}

// グローバル変数設定
function set_global($title, $back) {
	global $g_title, $g_back;

	$g_title = $title;
	$g_back = $back;
}
?>