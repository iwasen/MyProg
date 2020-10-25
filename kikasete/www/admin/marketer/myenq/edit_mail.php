<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｍｙアンケート変更処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/my_enquete.php");
include("$inc/mye_temp.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('myenquete', 'Ｍｙアンケート管理', 'Ｍｙアンケート情報変更', BACK_TOP);

// セッション処理
if ($edit) {
	$myenq = &$_SESSION['ss_my_enq'];
	$my_enq_no = $myenq->my_enq_no;
} else {
	$_SESSION['ss_my_enq'] = new my_enquete_class;
	$myenq = &$_SESSION['ss_my_enq'];
	$myenq->read_db($marketer_id, $my_enq_no);
}

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
<form method="post" name="form1" action="update_mail.php">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0">■メール文のカスタマイズ</td>
	</tr>
	<tr>
		<td class="m1" width="20%">件名</td>
		<td class="n1"><input type="text" name="subject" size=60 maxlength=100 <?=value($subject)?>> （全角１００文字まで）</td>
	</tr>
	<tr>
		<td class="m1">ヘッダ</td>
		<td class="n1"><textarea name="header" cols=72 rows=10><?=htmlspecialchars($header)?></textarea></td>
	</tr>
	<tr>
		<td class="m1"0>本文</td>
		<td class="n1">※差込み文字が使えます。（モニター氏名= %MONITOR_NAME% ）<br>
			<textarea name="body" cols=72 rows=25><?=htmlspecialchars($body)?></textarea></td>
	</tr>
	<tr>
		<td class="m1">フッタ</td>
		<td class="n1"><textarea name="footer" cols=72 rows=8><?=htmlspecialchars($footer)?></textarea></td>
	</tr>
</table><br>
<div align="center">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="リセット" onclick="document.form1.next_action.value='reset'">
<input type="button" value="　戻る　" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>'">
<input type="hidden" name="next_action">
<input type="hidden" name="marketer_id" <?=value($marketer_id)?>>
<input type="hidden" name="my_enq_no" <?=value($my_enq_no)?>>
</form>
</div>

<? page_footer() ?>
</body>
</html>
