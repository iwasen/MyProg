<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:Ｍｙアンケートメールプレビュー
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/pro_enquete.php");
include("$inc/enq_mail.php");
include("$inc/mye_temp.php");

set_global2('marketer', 'マーケター管理｜Ｍｙアンケート管理', 'メールプレビュー', BACK_CLOSE);

$marketer_id = $_GET['id'];
if (isset($_GET['no'])) {
	$my_enq_no = $_GET['no'];

	$myenq = new my_enquete_class;
} elseif (isset($_GET['pno'])) {
	$my_enq_no = $_GET['pno'];

	$myenq = new pro_enquete_class;
}
$myenq->read_db($marketer_id, $my_enq_no);
$enquete = &$myenq->enquete;

if ($myenq->mail_title == '')
	$subject = $enquete->title;
else
	$subject = $myenq->mail_title;

if ($myenq->mail_contents == '')
	get_enq_body($myenq, $header, $body, $footer);
else {
	$header = $myenq->mail_header;
	$body = $myenq->mail_contents;
	$footer = $myenq->mail_footer;
}
$mail_text = make_enq_mail($enquete, $header, $body, $footer);
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
<br>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>■メールプレビュー</td>
	</tr>
	<tr>
		<td>
			<table width="520" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="100%" valign="top" bgcolor="#ffffff"><pre><?=htmlspecialchars($subject)?></pre></td>
				</tr>
				<tr>
					<td width="100%" valign="top" bgcolor="#ffffff">
						<pre><?=htmlspecialchars($mail_text)?></pre>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<input type="button" value="　閉じる　" onclick="window.close()">
</div>

<? page_footer() ?>
</body>
</html>
