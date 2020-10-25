<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:連絡事項未既読確認表示
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
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body>
<div align="center">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td>■連絡事項未既読確認</td>
		<td align="right">
			<input type="button" value=" 閉じる " onclick="window.close()">
		</td>
	</tr>
</table>
<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
		<th>名前</th>
		<th>未既読</th>
	</tr>
<?
$message_id = $_GET['message_id'];

$sql = "SELECT stf_staff_id,stf_name,msr_staff_id" .
			" FROM t_staff LEFT JOIN t_message_read ON msr_message_id=$message_id AND msr_staff_id=stf_staff_id,t_message" .
			" WHERE msg_message_id=$message_id AND stf_status=0 AND (msg_to_staff_id is null OR stf_staff_id=msg_to_staff_id)" .
			" ORDER BY stf_staff_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	if ($fetch->msr_staff_id)
		$check = "<font color='blue'>既読</font>";
	else
		$check = "<font color='red'>未読</font>";
?>
	<tr class="tc<?=$i % 2?>">
		<td><?=htmlspecialchars($fetch->stf_name)?></td>
		<td align="center"><?=$check?></td>
	</tr>
<?
}
?>
</table>
</div>

</body>
</html>
