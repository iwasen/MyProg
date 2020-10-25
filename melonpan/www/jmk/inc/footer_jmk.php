<?
function footer_jmk($title_flg) {
?>
<center>
<hr class="footer">
<?
	if ($title_flg == 1) {
	} elseif ($title_flg == 2) {
	//	SetCookie("cookie_admin_id","", time() - 3600, "/melonpan/jmk/");
	  print "<div align='right'><small><font color=blue>■</font><A href=\"login/logoff.php\">ログオフ</a></small></div>";
	} else {
	  print "<div align='right'><small><font color=blue>■</font><a href=\"../menu.php\">トップへ戻る</a></small></div>";
	}
?>
Copyright (C) <? print date(Y); ?> xx xxxxx CO,LTD. All rights reserved.
</center>
<?
}
?>