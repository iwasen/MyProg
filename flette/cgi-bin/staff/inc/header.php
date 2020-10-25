<?
/******************************************************
' System :fletteスタッフページ
' Content:事務局用ヘッダーフッタ
'******************************************************/

// ヘッダ
function page_header($title, $link, $logout) {
	global $top;
?>
<a name="#top"></a>
<table cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td align="left" style="font-size:x-small;font-weight:bold;color:#666666">fletteスタッフページ</td>
		<td align="right" valign=bottom norap>
<?
	if ($logout) {
?>
			<small><font color="blue">■</font><a href="<?=$top?>/login/logout.php">ログアウト</a></small></a>
<?
	}
?>
		</td>
	</tr>
</table>
<table border=0 width="100%" cellspacing=1 cellpadding=3 class="title">
	<tr onclick="location.reload()">
		<td align="center"><nobr><?=$title?></nobr></td>
	</tr>
</table>
<hr class="header">
<?
}

// 共通フッタ
function page_footer() {
	global $s_staff_id, $s_staff_mail_addr;

	if ($s_staff_id)
		$login = "Login: $s_staff_mail_addr";
?>
<hr class="footer">
<table cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td align="left" style="font-size:x-small;font-weight:bold;color:#666666"><?=$login?></td>
		<td align="right" valign=bottom norap><small><font color=blue>■</font><a href="#top">このページの先頭へ</a></small></a></td>
	</tr>
</table>
<div align="center"><?=COPYRIGHT?></div>
<?
}
?>