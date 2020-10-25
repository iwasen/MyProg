<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:問い合わせ対応・問い合わせ内容表示
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");

// 入力パラメータ
$comm_id = $_GET['comm_id'];
$url = $_GET['url'];

// 問い合わせ内容取得
if ($comm_id) {
	$sql = "SELECT cml_comm_id,cml_date,cml_send_recv,cml_answer_flag,cmc_comm_type,cmc_subject,cmc_body"
			. " FROM t_comm_log"
			. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id";
	if ($_GET['all'])
		$sql .= " WHERE cml_thread_comm_id=$comm_id ORDER BY cml_comm_id";
	else
		$sql .= " WHERE cml_comm_id=$comm_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onclick_answer(comm_id) {
	parent.location.href = "answer.php?comm_id=" + comm_id + "&url=<?=$url?>";
}
function onclick_taiou(comm_id) {
	if (confirm('このメールを対応済み扱いとします。よろしいですか？')) {
		parent.location.href = "taiou.php?comm_id=" + comm_id + "&url=<?=$url?>";
	}
}
//-->
</script>
</head>
<body style="margin:8">
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
<table border=0 width="100%" cellspacing=0 cellpadding=1 bgcolor="#d0e0ff">
	<tr>
		<td>
<?
	if ($fetch->cml_answer_flag == DBTRUE)
		echo '<font color="red">【未回答】</font>';
?>
			&nbsp;<font color="blue"><?=format_datetime($fetch->cml_date)?></font>
			&nbsp;<font color="<?=$fetch->cml_send_recv == 'R' ? "brown" : "green"?>"><?=decode_comm_type2($fetch->cmc_comm_type)?><?=decode_send_recv($fetch->cml_send_recv)?></font>
			&nbsp;<font color="#555555">[<?=$fetch->cml_comm_id?>]</font>
			&nbsp;<font color="#333388"><b><?=htmlspecialchars($fetch->cmc_subject)?></b></font>
		</td>
<?
	if ($url) {
?>
		<td align="right">
<?
		if ($fetch->cml_send_recv == 'R') {
			if ($fetch->cml_answer_flag == DBTRUE) {
?>
			<input type="button" value="対応扱い" onclick="onclick_taiou(<?=$fetch->cml_comm_id?>)">
<?
			}
?>
			<input type="button" value="返信する" onclick="onclick_answer(<?=$fetch->cml_comm_id?>)">
<?
		}
?>
		</td>
<?
	}
?>
	</tr>
</table>

<pre style="margin-top=5">
<?=htmlspecialchars(rtrim($fetch->cmc_body))?>
</pre>
<?
	$sql = "SELECT mat_file_no,mat_file_size,mat_file_name FROM t_mail_attachment WHERE mat_comm_id=$fetch->cml_comm_id";
	$result2 = db_exec($sql);
	$nrow2 = pg_num_rows($result2);
	if ($nrow2) {
		for ($j = 0; $j < $nrow2; $j++) {
			$fetch2 = pg_fetch_object($result2, $j);
?>
	<a class="small" href="<?=$top?>/common/show_attach.php?comm_id=<?=$fetch->cml_comm_id?>&file_no=<?=urlencode($fetch2->mat_file_no)?>" target="_blank"><?=htmlspecialchars($fetch2->mat_file_name)?></a>&nbsp;
<?
		}
?>
<br>
<?
	}
?>
<br>
<?
}
?>

</body>
</html>
