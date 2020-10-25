<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｍｙアンケートメールプレビュー
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/enq_mail.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('myenquete', 'Ｍｙアンケート管理', 'メールプレビュー', BACK_TOP);

$myenq = new my_enquete_class;
$myenq->read_db($marketer_id, $my_enq_no);

$header = $myenq->mail_header;
$body = $myenq->mail_contents;
$footer = $myenq->mail_footer;

$sql = "SELECT mr_mail_addr,mr_name1 FROM t_marketer WHERE mr_marketer_id=$marketer_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$header = str_replace('%MAIL_ADDR%', $fetch->mr_mail_addr, $header);
	$body = str_replace('%MONITOR_NAME%', $fetch->mr_name1, $body);
}

$mail_text = make_enq_mail($myenq->enquete, $header, $body, $footer);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<div align="center">
<table width="520" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
	<tr>
		<td width="100%" valign="top" bgcolor="#ffffff"><pre><?=htmlspecialchars($myenq->mail_title)?></pre></td>
	</tr>
	<tr>
		<td width="100%" valign="top" bgcolor="#ffffff">
			<pre><?=$mail_text?></pre>
		</td>
	</tr>
</table>
<br>
<input type="button" value="　戻る　" onclick="history.back()">
</div>

<? page_footer() ?>
</body>
</html>
