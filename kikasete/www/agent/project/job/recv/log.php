<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:アンケート回答情報
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/format.php");

function decode(&$s) {
	if ($s->charset == 'default')
		return $s->text;
	else
		return mb_convert_encoding($s->text, 'EUC-JP', $s->charset);
}

//メイン処理

set_global2('project', 'プロジェクト管理', 'アンケート回答情報', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<center>
<table border=1 cellspacing=0 cellpadding=3 bordercolor="navy">
	<tr>
		<td class="np">
<?
$sql = "SELECT rm_recv_date,rm_header,rm_body"
	. " FROM l_recv_mail JOIN t_enquete ON en_enquete_id=rm_enquete_id"
	. " WHERE en_job_id=$job_id"
	. " ORDER BY rm_seq_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$header = imap_rfc822_parse_headers($fetch->rm_header);
	$subject = imap_mime_header_decode($header->subject);
	$from = imap_mime_header_decode($header->fromaddress);

	$subject = htmlspecialchars(decode($subject[0]));
	$from = htmlspecialchars(decode($from[0]));
	$date = format_datetime($fetch->rm_recv_date);
	$body = nl2br(htmlspecialchars($fetch->rm_body));

	if ($i != 0)
		echo '<hr color="red">';
?>
				Subject: <?=$subject?><br>
				From: <?=$from?><br>
				Date: <?=$date?><br>
				<br>
				<?=$body?>
<?
}
?>
		</td>
	</tr>
</table>
<br>
<input type="button" value=" 戻る " onclick="history.back()">
</center>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
