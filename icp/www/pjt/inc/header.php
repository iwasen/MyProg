<?
/******************************************************
' System :ICPプロジェクトページ
' Content:共通ヘッダ・フッタ
'******************************************************/

define('COPYRIGHT', 'Copyright &copy; 2004 xx xxxxx Inc. All Rights Reserved.');

// ヘッダ
function pjt_header($link = 'logoff') {
	global $top, $g_member_id;
?>
<table border="0" cellspacing="0" cellpadding="0" width="800" class="header">
<tr>
<?
	if ($link == 'login') {
?>
	<td width="551" class="pjt" align="center">icp.net</td>
	<td width="49"><img src="<?=$top?>/img/title_2.gif" width="49" height="50" border="0"></td>
	<td width="250" class="name">ようこそ<br>ログインしてください</td>
<?
	} else {
?>
	<td width="551" class="pjt"><?=htmlspecialchars($_SESSION[SCD]['pjt_name'])?>（<?=htmlspecialchars($_SESSION[SCD]['pjt_cd'])?>）</td>
	<td width="49"><img src="<?=$top?>/img/title_2.gif" width="49" height="50" border="0"></td>
	<td width="170" class="name" valign="bottom">ようこそ<br><?=htmlspecialchars($_SESSION[SCD]['org_name'])?><br><?=htmlspecialchars($_SESSION[SCD]['member_name'])?>　様</td>
	<td width="80" class="name" align="right">
<?
	if ($link == 'logoff') {
?>
		<a href="<?=$top?>/login/logoff.php" title="ログオフ">ログオフ</a>
<?
 } elseif ($link == 'close') {
?>
		<a href="javascript:window.close()" title="このウィンドウを閉じる">閉じる</a>
<?
	}
?>
	</td>
<?
}
?>
</tr>
</table>
<?
}

// フッタ
function pjt_footer() {
?>
<table border="0" cellspacing="0" cellpadding="0" width="800" class="copyright">
<tr>
	<td align="center"><?=COPYRIGHT?></td>
</tr>
</table>
<?
}
?>