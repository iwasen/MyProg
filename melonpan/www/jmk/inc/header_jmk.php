<?php
function header_jmk($title_text,$title_color,$title_flg) {
?>
<center>
<table cellspacing=0 cellpadding=0 width="100%">
  <tr>
    <td align=left valign=bottom norap><small><B>めろんぱん 事務局用ページ</B></small></td>
<?
	if ($title_flg == 1) {
	} elseif ($title_flg == 2) {
	  print "<td align=right valign=bottom norap><small><font color=blue>■</font><A href=\"login/logoff.php\">ログオフ</A></small></td>";
	} else {
	  print "<td align=right valign=bottom norap><small><font color=blue>■</font><A href=\"../menu.php\">トップへ戻る</A></small></td>";
	}
?>
  </tr>
</table>
<hr class="header">
<table border=2 width="90%" bgcolor='<?= $title_color ?>' cellspacing=1 cellpadding=6>
  <tr>
    <th><font color=white size=4><b><? print("$title_text"); ?></b></font></th>
  </tr>
</table>
<hr class="header">
</center>
<?
}
?>