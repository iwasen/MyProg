<?
/******************************************************
' System :きかせて・net共通処理
' Content:My&Proメンバーリスト選択
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$ary = explode(',', $id);

// 検索条件
if ($group_id != '')
	and_join($where, "WHERE mmg_group_id<>$group_id");

if ($where)
	$where = "WHERE $where";

$sql = "SELECT mmg_group_id,mmg_group_name FROM t_myenq_member_grp $where ORDER BY mmg_group_id";
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
		<td class="lt">■メンバーリスト選択</td>
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

	$group_id2 = $fetch->mmg_group_id;
	$checked = in_array ($group_id2, $ary) ? ' checked' : '';
?>
	<tr class="tc<?=$i % 2?>">
		<td width="10%" align="center"><input type="checkbox" name="group_id" value="<?=$group_id2?>"<?=$checked?>></td>
		<td width="10%" align="center"><?=$group_id2?></td>
		<td width="80%"><?=htmlspecialchars($fetch->mmg_group_name)?></td>
	</tr>
<?
}
?>
</table>
</form>

</body>
</html>
