<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:ポイント入力処理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/list.php");
include("$inc/decode.php");
include("$inc/point.php");

set_global('monitor', 'モニター管理', 'ポイント入力', BACK_TOP);

// CSVからデータ取得とチェック
if ($fp = fopen($_FILES['point_file']['tmp_name'], 'r')) {
	$count = 0;
	while ($data = fgetcsv($fp, 10000, ",")) {
		mb_convert_variables('EUC-JP', 'SJIS', $data);

		$monitor_id = $data[0];
		$point = $data[1];
		$valid_point = $data[2];
		$date = $data[3];
		$err = '';

		if ($monitor_id != '' && is_numeric($monitor_id) && is_numeric($point) && is_numeric($valid_point)) {
			$sql = "SELECT mn_status,mp_curr_point,mn_status"
					. " FROM t_monitor JOIN t_monitor_point ON mp_monitor_id=mn_monitor_id"
					. " WHERE mn_monitor_id=$monitor_id";
			$result = db_exec($sql);
			if (pg_numrows($result) == 0)
				$err = 'モニターIDが未登録';
			else {
				$fetch = pg_fetch_object($result, 0);
				if ($fetch->mn_status == 9 && $taikai_flag == 1)
					$err = '退会';
				elseif ($valid_point < 0 && $valid_point + $fetch->mp_curr_point < 0)
					$err = 'ポイント不足';
			}

			$import['monitor_id'] = $monitor_id;
			$import['point'] = $point;
			$import['valid_point'] = $valid_point;
			$import['date'] = $date;
			$import['status'] = $fetch->mn_status;
			$import['err'] = $err;

			if ($err == '')
				$ok_ary[] = $import;
			else
				$ng_ary[] = $import;
		}
	}
}

// ポイント付与処理
if (!$ng_ary || $error_flag == 1) {
	$fuyo_flag = true;
	if (is_array($ok_ary)) {
		db_begin_trans();

		foreach ($ok_ary as $data) {
			monitor_point($data['monitor_id'], $point_type, $data['point'], $data['valid_point'], $data['date']);
		}

		db_commit_trans();
	}
} else
	$fuyo_flag = false;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■ポイント付与者一覧<?=!$fuyo_flag ? '<font color="red" class="small">　※エラーがあったため付与は行っていません</font>' : ''?></td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="location.href='import.php'">
		</td>
	</tr>
</table>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
		sort_header(0, 'モニターID', 'width="33%"');
		sort_header(0, '付与ポイント数', 'width="33%"');
		sort_header(0, '状態', 'width="33%"');
?>
	</tr>
<?
if (is_array($ok_ary)) {
	foreach ($ok_ary as $i => $data) {
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$data['monitor_id']?></td>
		<td align="right"><?=number_format($data['point'])?></td>
		<td align="center"><?=decode_monitor_status($data['status'])?></td>
	</tr>
<?
	}
}
?>
</table>

<br>
<form method="post" name="form2">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■ポイント付与者エラー一覧</td>
	</tr>
</table>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
		sort_header(0, 'モニターID', 'width="33%"');
		sort_header(0, '付与ポイント数', 'width="33%"');
		sort_header(0, '状態', 'width="33%"');
?>
	</tr>
<?
if (is_array($ng_ary)) {
	foreach ($ng_ary as $i => $data) {
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$data['monitor_id']?></td>
		<td align="right"><?=number_format($data['point'])?></td>
		<td align="center"><?=$data['err']?></td>
	</tr>
<?
	}
}
?>
</table>

</div>

<? page_footer() ?>
</body>
</html>
