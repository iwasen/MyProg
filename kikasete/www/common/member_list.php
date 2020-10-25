<?
/******************************************************
' System :きかせて・net共通処理
' Content:メンバーリスト選択
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$ary = explode(',', $id);

// 検索条件
if ($group_id != '')
	and_join($where, "WHERE mg_group_id<>$group_id");

if ($job_id != '') {
	// 企業ID，企業名取得
	$sql = "SELECT cl_client_id,cl_name"
			. " FROM t_job"
			. " JOIN t_project ON pj_project_id=jb_project_id"
			. " JOIN m_tantousha ON ts_tantousha_id=pj_tantousha_id"
			. " JOIN m_client ON cl_client_id=ts_client_id"
			. " WHERE jb_job_id=$job_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		and_join($where, "(mg_client_id=$fetch->cl_client_id OR mg_client_id IS NULL)");
		$client_name = "　（{$fetch->cl_name}　企業ID：{$fetch->cl_client_id}）";
	}
}

if ($where)
	$where = "WHERE $where";

$sql = "SELECT mg_group_id,mg_group_name FROM t_member_grp $where ORDER BY mg_group_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title>メンバーリスト選択</title>
<link rel="stylesheet" type="text/css" href="common.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_ok() {
	var f = document.form2;
	var id, n, i;
	id = "";
<? if ($nrow == 1) { ?>
	if (f.group_id.checked)
		id = f.group_id.value;
<? } elseif ($nrow > 1) { ?>
	n = f.group_id.length;
	for (i = 0; i < n; i++) {
		if (f.group_id[i].checked) {
			if (id != "")
				id = id + ",";
			id = id + f.group_id[i].value;
		}
	}
<? } ?>
	opener.document.form1("<?=$name?>").value = id;
	close();
}
//-->
</SCRIPT>
</head>
<body>
<form method="post" name="form1">
<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<tr>
		<td class="lt">■メンバーリスト選択<?=htmlspecialchars($client_name)?></td>
		<td class="lb">
			<input type="button" value="　OK　" onclick="OnClick_ok()">
			<input type="button" value="ｷｬﾝｾﾙ" onclick="window.close()">
		</td>
	</tr>
</table>
</form>

<form method="post" name="form2">
<table border=1 cellpadding=0 cellspacing=1 width="100%">
	<tr class="tch">
		<th><nobr>選択</nobr></th>
		<th><nobr>メンバーリストID</nobr></th>
		<th><nobr>メンバーリスト名</nobr></th>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$group_id2 = $fetch->mg_group_id;
	$checked = in_array ($group_id2, $ary) ? ' checked' : '';
?>
	<tr class="tc<?=$i % 2?>">
		<td width="10%" align="center"><input type="checkbox" name="group_id" value="<?=$group_id2?>"<?=$checked?>></td>
		<td width="10%" align="center"><?=$group_id2?></td>
		<td width="80%"><?=htmlspecialchars($fetch->mg_group_name)?></td>
	</tr>
<?
}
?>
</table>
</form>

</body>
</html>
