<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:オペレータ別問い合わせ対応中件数
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=SYSTEM_NAME?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body>
<div align="center">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td>■オペレータ別問い合わせ対応中件数</td>
		<td align="right">
			<input type="button" value=" 閉じる " onclick="window.close()">
		</td>
	</tr>
</table>
<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
		<th>オペレータ名</th>
		<th>メールアドレス</th>
		<th>件数</th>
	</tr>
<?
$sub1 = "SELECT cmt_staff_id,count(*) AS cml_count"
		. " FROM t_comm_log"
		. " JOIN t_comm_thread ON cmt_comm_id=cml_thread_comm_id"
		. " WHERE cml_answer_flag=true AND cmt_thread_type={$_GET['thread_type']} AND cmt_staff_id is not null"
		. " GROUP BY cmt_staff_id";
$sql = "SELECT stf_mail_addr,stf_name,cml_count"
		. " FROM t_staff JOIN ($sub1) AS sub1 ON cmt_staff_id=stf_staff_id"
		. " ORDER BY stf_staff_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><?=htmlspecialchars($fetch->stf_name)?></td>
		<td><?=htmlspecialchars($fetch->stf_mail_addr)?></td>
		<td align="right"><?=$fetch->cml_count?></td>
	</tr>
<?
}
?>
</table>
</div>

</body>
</html>
