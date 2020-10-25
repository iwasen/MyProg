<?
/******************************************************
' System :きかせて・net共通処理
' Content:ジョブ選択
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/list.php");

if (isset($id)) {
	if (is_array($id))
		$ary = $id;
	else
		$ary = explode(',', $id);
}

// 検索条件
if ($job_id != '') {
	$where = "WHERE jb_job_id<>$job_id";

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
		$where .= " AND ts_client_id=$fetch->cl_client_id";
		$client_name = "　（{$fetch->cl_name}　企業ID：{$fetch->cl_client_id}）";
	}
}

$order_by = order_by(3, 1, 'ts_tantousha_id', 'ts_name1_kana||ts_name2_kana', 'pj_project_id,jb_job_id', 'pj_project_name', 'jb_job_id', 'jb_job_name');

$sql = "SELECT jb_job_id,jb_job_name,ts_tantousha_id,ts_name1,ts_name2,pj_project_id,pj_project_name"
		. " FROM t_job"
		. " JOIN t_project ON pj_project_id=jb_project_id"
		. " JOIN m_tantousha ON ts_tantousha_id=pj_tantousha_id"
		. " $where $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title>ジョブ選択</title>
<link rel="stylesheet" type="text/css" href="common.css">
<SCRIPT LANGUAGE=javascript>
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
function OnClick_ok() {
	var f = document.form1;
	var id, n, i;
	id = "";
<? if ($nrow == 1) { ?>
	if (f["id[]"].checked)
		id = f["id[]"].value;
<? } elseif ($nrow > 1) { ?>
	n = f["id[]"].length;
	for (i = 0; i < n; i++) {
		if (f["id[]"][i].checked) {
			if (id != "")
				id = id + ",";
			id = id + f["id[]"][i].value;
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
		<td class="lt">■<?=$title?><?=htmlspecialchars($client_name)?></td>
		<td class="lb">
			<input type="button" value="　OK　" onclick="OnClick_ok()">
			<input type="button" value="ｷｬﾝｾﾙ" onclick="window.close()">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="direction" <?=value($direction)?>>

<table border=1 cellpadding=0 cellspacing=1 width="100%">
	<tr class="tch">
<?
	sort_header(0, '選択');
	sort_header(1, '担当者ID');
	sort_header(2, '担当者名');
	sort_header(3, 'ﾌﾟﾛｼﾞｪｸﾄID');
	sort_header(4, 'プロジェクト名');
	sort_header(5, 'ジョブID');
	sort_header(6, 'ジョブ名');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$job_id2 = $fetch->jb_job_id;
	$checked = in_array ($job_id2, $ary) ? ' checked' : '';
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><input type="checkbox" name="id[]" value="<?=$job_id2?>"<?=$checked?>></td>
		<td align="center"><?=$fetch->ts_tantousha_id?></td>
		<td><?=htmlspecialchars("$fetch->ts_name1 $fetch->ts_name2")?></td>
		<td align="center"><?=$fetch->pj_project_id?></td>
		<td><?=htmlspecialchars($fetch->pj_project_name)?></td>
		<td align="center"><?=$job_id2?></td>
		<td><?=htmlspecialchars($fetch->jb_job_name)?></td>
	</tr>
<?
}
?>
</table>
</form>

</body>
</html>
