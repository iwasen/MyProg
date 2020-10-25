<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:事務局用ヘッダーフッタ
'******************************************************/

define('SERVICE', 'admin');

// ヘッダ
function page_header() {
	global $g_class, $g_section, $g_title;

	$section = 'お酒と買物のアンケートモニター 事務局ページ';
	if ($g_section)
		$section .= "｜$g_section";
?>
<table cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td align="left" style="font-size:x-small;font-weight:bold;color:#666666"><?=$section?></td>
		<td align="right" valign=bottom norap><?=back_link()?></td>
	</tr>
</table>
<table border=0 width="100%" cellspacing=1 cellpadding=3 class="<?=$g_class?>">
	<tr onclick="location.reload()">
		<td align="center"><nobr><?=$g_title?></nobr></td>
	</tr>
</table>
<hr class="header">
<?
}

// 共通フッタ
function page_footer() {
	global $admin_login_id, $admin_mail_addr;

	if ($admin_login_id)
		$login = "Login: $admin_mail_addr";
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
		$back = '<small><font color=blue>■</font><a href="login/logoff.php">ログオフ</a></small>';
		break;
	case BACK_TOP:
		$back = "<small><font color=blue>■</font><a href=\"$top/menu.php\">トップへ戻る</a></small>";
		break;
	case BACK_CLOSE:
		$back = "<small><font color=blue>■</font><a href=\"javascript:window.close()\">閉じる</a></small>";
		break;
	}

	return $back;
}

// グローバル変数設定
function set_global($class, $section, $title, $back) {
	global $g_class, $g_section, $g_title, $g_back;

	$g_class = $class;
	$g_section = $section;
	$g_title = $title;
	$g_back = $back;
}
function set_global2($class, $section, $title, $back) {
	set_global($class, $section, $title, $back);
}

function menu_script() {
}

function menu_start() {
	echo '<div align="center">';
}

function menu_end() {
	echo '</div>';
}

// テーブルの横幅
function width($w) {
	echo $w;
}
?>