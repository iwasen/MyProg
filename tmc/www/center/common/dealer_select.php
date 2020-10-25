<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:事務局ページ共通販売店選択
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/list.php");

// 選択中販売店コード取得
if (isset($_GET['dealer_cd']))
	$ary = explode(',', $_GET['dealer_cd']);
elseif (isset($_POST['dealer_cd']))
	$ary = $_POST['dealer_cd'];

// ソート条件
$sort_col = $_POST['sort_col'];
$sort_dir = $_POST['sort_dir'];
$direction = $_POST['direction'];

// 販売店一覧取得
$order_by = order_by(1, 0, 'dlr_dealer_cd', 'dlr_dealer_name');
$sql = "SELECT dlr_dealer_cd,dlr_dealer_name"
		. " FROM t_dealer"
		. " $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title>ジョブ選択</title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
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
	var cd, n, i;
	cd = "";
<? if ($nrow == 1) { ?>
	if (f["dealer_cd[]"].checked)
		cd = f["dealer_cd[]"].value;
<? } elseif ($nrow > 1) { ?>
	n = f["dealer_cd[]"].length;
	for (i = 0; i < n; i++) {
		if (f["dealer_cd[]"][i].checked) {
			if (cd != "")
				cd = cd + ",";
			cd = cd + f["dealer_cd[]"][i].value;
		}
	}
<? } ?>
	opener.document.form1("<?=$_REQUEST['name']?>").value = cd;
	close();
}
//-->
</SCRIPT>
</head>
<body>
<form method="post" name="form1" action="dealer_select.php">
<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<tr>
		<td class="lt">■販売店選択</td>
		<td class="lb">
			<input type="button" value="　OK　" onclick="OnClick_ok()">
			<input type="button" value="ｷｬﾝｾﾙ" onclick="window.close()">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="direction" <?=value($direction)?>>
<input type="hidden" name="name" <?=value($_REQUEST['name'])?>>

<table border=1 cellpadding=0 cellspacing=1 width="100%">
	<tr class="tch">
<?
	sort_header(0, '選択');
	sort_header(1, '販売店CD');
	sort_header(2, '販売店名');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$dlr_dealer_cd2 = $fetch->dlr_dealer_cd;
	$checked = in_array($dlr_dealer_cd2, $ary) ? ' checked' : '';
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><input type="checkbox" name="dealer_cd[]" value="<?=$dlr_dealer_cd2?>"<?=$checked?>></td>
		<td align="center"><?=$fetch->dlr_dealer_cd?></td>
		<td><?=htmlspecialchars($fetch->dlr_dealer_name)?></td>
	</tr>
<?
}
?>
</table>
</form>

</body>
</html>
