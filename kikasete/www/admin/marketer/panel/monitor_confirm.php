<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:スペシャルパネル管理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/list.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global('myenquete', 'Ｍｙアンケート管理', 'パネルモニター追加', BACK_TOP);

$sql = sprintf("SELECT pnl_panel_name FROM t_panel WHERE pnl_panel_id=%s", $_REQUEST['panel_id']);
$panel_name = db_fetch1($sql);

$n = 0;
$ok = 0;
$data_ary = array();
$monitor_id_ary = array();

if ($_REQUEST['monitor_list'] != '') {
	$ary = explode("\n", str_replace(',', "\n", $_REQUEST['monitor_list']));
	foreach ($ary as $addr) {
		$addr = trim($addr);

		if ($addr == '')
			continue;

		$data_ary[$n]['input'] = $addr;
		$data_ary[$n]['monitor'] = '-';

		$sql = '';
		if (strpos($addr, '@'))
			$sql = sprintf("SELECT mn_monitor_id,mn_mail_addr,mn_name1,mn_name2 FROM t_monitor WHERE mn_mail_addr_low=%s AND mn_status=0", sql_char(strtolower($addr)));
		elseif (is_numeric($addr))
			$sql = sprintf("SELECT mn_monitor_id,mn_mail_addr,mn_name1,mn_name2 FROM t_monitor WHERE mn_monitor_id=%s AND mn_status=0", sql_number($addr));
		else
			$data_ary[$n]['msg'] = '<font color="red">不正なデータです</font>';

		if ($sql != '') {
			$result = db_exec($sql);
			if (pg_numrows($result) == 0)
				$data_ary[$n]['msg'] = '<font color="red">該当するモニターが存在しません</font>';
			else {
				$fetch = pg_fetch_object($result, 0);
				$monitor_id = $fetch->mn_monitor_id;

				$data_ary[$n]['monitor'] = "$fetch->mn_name1 $fetch->mn_name2 <$fetch->mn_mail_addr>";

				$sql = sprintf("SELECT COUNT(*) FROM t_panel_monitor_list WHERE pnm_panel_id=%s AND pnm_monitor_id=%s", sql_number($_REQUEST['panel_id']), sql_number($monitor_id));;
				if (db_fetch1($sql) != 0)
					$data_ary[$n]['msg'] = '<font color="red">既にこのメンバーリストに登録されています</font>';
				else {
					$data_ary[$n]['msg'] = '<font color="blue">登録ＯＫ</font>';
					$ok++;
					$monitor_id_ary[] = $monitor_id;
				}
			}
		}

		$n++;
	}
}
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<form method="post" name="form1" action="monitor_update.php">
<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<tr>
		<td class="lt">■<?=htmlspecialchars($panel_name)?></td>
		<td class="lb">
			【入力：<?=number_format($n)?>件　登録OK：<?=number_format($ok)?>件　登録NG：<?=number_format($n - $ok)?>件】
<?
if ($ok) {
?>
			<input type="submit" value="　登録　">
<?
}
?>
			<input type="button" value="　戻る　" onclick="history.back()">
		</td>
	</tr>
</table>
<input type="hidden" name="next_action" value="add">
<input type="hidden" name="category_id" value="<?=$_REQUEST['category_id']?>">
<input type="hidden" name="panel_id" <?=value($_REQUEST['panel_id'])?>>
<input type="hidden" name="monitor_id_ary" <?=value(join(',', $monitor_id_ary))?>>
</form>

<table border=1 cellpadding=0 cellspacing=1 width="100%">
	<tr class="tch">
<?
		sort_header(0, '入力データ');
		sort_header(0, '該当モニター');
		sort_header(0, '登録状況');
?>
	</tr>
<?
foreach ($data_ary as $data) {
?>
	<tr class="tc<?=$i++ % 2?>">
		<td align="center"><?=htmlspecialchars($data['input'])?></td>
		<td><?=htmlspecialchars($data['monitor'])?></td>
		<td align="center"><?=$data['msg']?></td>
	</tr>
<?
}
?>
</table>

<? page_footer() ?>
</body>
</html>
