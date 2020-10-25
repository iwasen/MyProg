<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Myパートナー・承認処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/list.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global('mypartner', 'Myパートナー管理', 'Myパートナー承認処理', BACK_TOP);

// 戻り先設定
set_return_page('r_job.php');
set_return_page('pjt_detail.php');
set_return_page('select_monitor.php');
set_return_page('select_update.php');
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function sort_list(sort, dir, index) {
	var f = document.form1;
	f["sort_col[" + index + "]"].value = sort;
	f["sort_dir[" + index + "]"].value = dir;
	f.submit();
}
function onclick_project(project_id) {
	var win;
	win = window.open("../project_info.php?project_id=" + project_id, "project_info", "resizable=yes,width=600,height=400");
	win.focus();
}
function onclick_job(pjt_id, status) {
	var check = "";
	if (status == <?=MPS_REC_SHINSEI?>) {
		if (confirm("このＭｙパートナーを「事務局チェック中」としますか？"))
			check = "&check=1";
	}
	location.href = "../pjt/r_job.php?pjt_id=" + pjt_id + check;
}

function onclick_shounin(pjt_id) {
	var f = document.form2;
	if (showModalDialog("shounin.php?pjt_id=" + pjt_id,f,"status:off;help:off;dialogWidth:350px;dialogHeight:150px")) {
		f.pjt_id.value = pjt_id;
		f.flag.value = 1;
		f.submit();
	}
}
function onclick_return(pjt_id) {
	var f = document.form2;
	if (confirm("プロジェクトID=" + pjt_id + "のリクルートジョブを差し戻します。よろしいですか？")) {
		f.pjt_id.value = pjt_id;
		f.flag.value = 2;
		f.submit();
	}
}
//-->
</script>
</head>
<body>

<? page_header() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■未承認のリクルートジョブ一覧</td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="location.href='../../menu.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col[0]" <?=value($sort_col[0])?>>
<input type="hidden" name="sort_dir[0]" <?=value($sort_dir[0])?>>
<input type="hidden" name="pset" value=1>

<?
// ソート条件
$order_by = order_by2(1, 0, 0, 'ppj_pjt_id', 'ppj_room_name', 'mr_name1_kana,mr_name2_kana', 'ppj_recruit_start', 'ppj_recruit_end', 'ppj_send_num');

$sql = "SELECT ppj_pjt_id,ppj_room_name,mr_name1,mr_name2,ppj_recruit_start,ppj_recruit_end,ppj_send_num,ppj_status,mr_marketer_id"
		. " FROM t_mp_pjt"
		. " JOIN t_marketer ON mr_marketer_id=ppj_marketer_id"
		. " WHERE ppj_status=" . MPS_REC_SHINSEI . " OR ppj_status=" . MPS_REC_CHECK
		. " $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
<?
sort_header2(1, 'ID', 0);
sort_header2(2, '会議名', 0);
sort_header2(3, 'マーケター名', 0);
sort_header2(4, '送信日', 0);
sort_header2(5, '締切り', 0);
sort_header2(6, '最大発信数', 0);
sort_header2(0, '承認', 0);
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$pjt_id = $fetch->ppj_pjt_id;
?>
	<tr class="tc<?=$i?>">
		<td align="center"><a href="../pjt/pjt_detail.php?pjt_id=<?=$pjt_id?>" title="プロジェクト内ジョブ一覧を表示します"><?=$fetch->ppj_pjt_id?></a></td>
		<td align="center"><a href="javascript:onclick_job(<?=$pjt_id?>,<?=$fetch->ppj_status?>)" title="ジョブ内容を表示・変更します"><?=htmlspecialchars($fetch->ppj_room_name)?></a></td>
		<td align="center"><a href="../pjt/marketer_info.php?marketer_id=<?=$fetch->mr_marketer_id?>" title="マーケターの情報を表示します" target="_blank"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></a></td>
		<td align="center"><?=format_date($fetch->ppj_recruit_start)?></td>
		<td align="center"><?=format_date($fetch->ppj_recruit_end)?></td>
		<td align="right"><?=number_format($fetch->ppj_send_num)?></td>
		<td align="center">
<?
	if ($fetch->ppj_status == MPS_REC_SHINSEI) {
?>
			事務局チェック待ち
<?
	} else {
?>
			<input type="button" value="承認" onclick="onclick_shounin(<?=$pjt_id?>)">
			<input type="button" value="差し戻す" onclick="onclick_return(<?=$pjt_id?>)">
<?
	}
?>
		</td>
	</tr>
<?
}
?>
</table>

<br>
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■対象者事務局チェック中のジョブ一覧</td>
	</tr>
</table>
<input type="hidden" name="sort_col[1]" <?=value($sort_col[1])?>>
<input type="hidden" name="sort_dir[1]" <?=value($sort_dir[1])?>>
<?
// ソート条件
$order_by = order_by2(1, 0, 1, 'ppj_pjt_id', 'ppj_room_name', 'mr_name1_kana,mr_name2_kana', 'ppj_decision_date', 'ppj_room_start', 'ppj_room_end');

$sql = "SELECT ppj_pjt_id,ppj_room_name,mr_name1,mr_name2,ppj_decision_date,ppj_room_start,ppj_room_end"
		. " FROM t_mp_pjt"
		. " JOIN t_marketer ON mr_marketer_id=ppj_marketer_id"
		. " WHERE ppj_status=" . MPS_MEM_CHECK
		. " $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
<?
sort_header2(1, 'ID', 1);
sort_header2(2, '会議室名', 1);
sort_header2(3, 'マーケター名', 1);
sort_header2(4, '決定日', 1);
sort_header2(5, '開始日', 1);
sort_header2(6, '終了日', 1);
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$pjt_id = $fetch->ppj_pjt_id;
?>
	<tr class="tc<?=$i?>">
		<td align="center"><a href="../pjt/pjt_detail.php?pjt_id=<?=$pjt_id?>" title="プロジェクト内ジョブ一覧を表示します"><?=$fetch->ppj_pjt_id?></a></td>
		<td align="center"><a href="../pjt/select_monitor.php?pjt_id=<?=$pjt_id?>" title="該当者選定を行います"><?=htmlspecialchars($fetch->ppj_room_name)?></a></td>
		<td align="center"><a href="../pjt/marketer_info.php?marketer_id=<?=$fetch->mr_marketer_id?>" title="マーケターの情報を表示します" target="_blank"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></a></td>
		<td align="center"><?=format_date($fetch->ppj_decision_date)?></td>
		<td align="center"><?=format_date($fetch->ppj_room_start)?></td>
		<td align="center"><?=format_date($fetch->ppj_room_end)?></td>
	</tr>
<?
}
?>
</table>
</form>

<form method="post" name="form2" action="update.php">
<input type="hidden" name="pjt_id">
<input type="hidden" name="flag">
<input type="hidden" name="start_date_y">
<input type="hidden" name="start_date_m">
<input type="hidden" name="start_date_d">
<input type="hidden" name="start_date_h">
</form>

<? page_footer() ?>

</body>
</html>
