<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー管理・会議室進行状況
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/list.php");
include("$inc/format.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// モニター状態選択肢
function select_mypartner_status($sel) {
	echo "<option ", value_selected('', $sel), ">- 指定なし -</option>";
	echo "<option ", value_selected(MPS_REC_JUNBI, $sel), ">リクルート準備中</option>";
	echo "<option ", value_selected(MPS_REC_KANRYOU, $sel), ">リクルート作成完了</option>";
	echo "<option ", value_selected(MPS_REC_SHINSEI, $sel), ">リクルート申請中</option>";
	echo "<option ", value_selected(MPS_REC_CHECK, $sel), ">リクルート事務局チェック中</option>";
	echo "<option ", value_selected(MPS_REC_SHOUNIN, $sel), ">リクルート承認済</option>";
	echo "<option ", value_selected(MPS_REC_JISSHI, $sel), ">リクルート実施中</option>";
	echo "<option ", value_selected(MPS_MEM_SENTEI, $sel), ">対象者選定中</option>";
	echo "<option ", value_selected(MPS_MEM_CHECK, $sel), ">対象者事務局チェック中</option>";
	echo "<option ", value_selected(MPS_MEM_SHOUNIN, $sel), ">対象者選定承認済</option>";
	echo "<option ", value_selected(MPS_ROOM_JUNBI, $sel), ">会議室準備中</option>";
	echo "<option ", value_selected(MPS_ROOM_JISSHI, $sel), ">会議室開催中</option>";
	echo "<option ", value_selected(MPS_ROOM_END, $sel), ">会議室終了</option>";
}

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'Myパートナー　会議室進行状況', BACK_TOP);

// 戻り先設定
set_return_page('edit.php');
set_return_page('update.php');
set_return_page('index.php');
set_return_page('active.php');
set_return_page('pjt_detail.php');
set_return_page('r_job_edit.php');

// セッション登録
get_session_vars($pset, 'pjt_list', 'marketer_id', 'kinmu_name', 'status', 'displine', 'sort_col', 'sort_dir', 'page');

if (SERVICE == 'agent')
	and_join($where, "mr_agent_id=$agent_id");

if ($marketer_id != '')
	and_join($where, "ppj_marketer_id=" . sql_number($marketer_id));

If ($mr_kinmu_name != '')
	and_join($where, "mr_kinmu_name=" . sql_char($kinmu_name));

if ($status != '')
	and_join($where, "ppj_status=" . sql_number($status));

if ($where)
	$where = "WHERE $where";

// ソート条件
$order_by = order_by(1, 1, 'ppj_pjt_id', 'ppj_room_name', 'ppj_room_start', 'ppj_member_num', 'mr_kinmu_name', 'mr_name1_kana,mr_name2_kana', 'ppj_status');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT ppj_pjt_id,ppj_room_name,ppj_recruit_start,ppj_room_end,COALESCE(ppj_icp_member_num,ppj_member_num) AS ppj_member_num,ppj_theme_num,mr_marketer_id,mr_kinmu_name,mr_name1,mr_name2,ppj_status,ppj_point_timing,ppj_icp_member_num"
		. " FROM t_mp_pjt"
		. " JOIN t_marketer ON mr_marketer_id=ppj_marketer_id"
		. " $where $order_by $limit";
$result = db_exec($sql);
$nrow = pg_numrows($result);
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
function OnChange_client_id() {
	document.form1.tantousha_id.value = '';
	document.form1.submit();
}
function onclick_project(project_id) {
	var win;
	win = window.open("project_info.php?project_id=" + project_id, "project_info", "resizable=yes,width=600,height=400");
	win.focus();
}

function marketer_info(){
	window.open('marketer_info.php','mi','wi')
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■会議室一覧</td>
		<td class="lb">
<?
if (SERVICE == 'admin') {
?>
			<input type="button" value="実施中会議室一覧" onclick="location.href='active.php'">
<?
}
?>
			<input type="button" value="会議室新規登録" onclick="location.href='pjt_new1.php'">
			<input type="button" value="　戻る　" onclick="location.href='../../menu.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>表示行数<select name="displine" onchange="submit()"><? select_displine($displine) ?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>&nbsp</nobr>
			<nobr>マーケター<select name="marketer_id" onchange="submit()"><? select_marketer('- 指定なし -', $marketer_id, 3, $agent_id) ?></select>&nbsp</nobr>
			<nobr>ステータス<select name="status" onchange="submit()"><? select_mypartner_status($status) ?></select>&nbsp</nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>


<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
<?
sort_header(1, 'ID');
sort_header(2, '会議室名');
sort_header(0, '詳細');
sort_header(3, '期限');
sort_header(4, 'ﾒﾝﾊﾞｰ数');
sort_header(5, '企業名');
sort_header(6, 'ﾏｰｹﾀｰ名');
sort_header(7, 'ステータス');
if (SERVICE == 'admin') {
	sort_header(0, 'リクルート');
	sort_header(0, '会議室');
	sort_header(0, 'ポイント付与');
}
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$pjt_id = $fetch->ppj_pjt_id;
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="pjt_new2.php?pjt_id=<?=$pjt_id?>" title="会議室の内容を表示・変更します"><?=$pjt_id?></a></td>
		<td><a href="pjt_detail.php?pjt_id=<?=$pjt_id?>" title="会議室の内容を表示・変更します"><?=htmlspecialchars($fetch->ppj_room_name)?></a></td>
		<td align="center"><input type="button" value="詳細" onclick="location.href='pjt_design.php?pjt_id=<?=$pjt_id?>'"></td>
		<td align="center"><?=format_date($fetch->ppj_recruit_start)?>〜<?=format_date($fetch->ppj_room_end)?></td>
		<td align="right"><?=$fetch->ppj_member_num?>（<?=$fetch->ppj_icp_member_num == 0 ? '目標' : '確定'?>）</td>
		<td><?=htmlspecialchars($fetch->mr_kinmu_name)?></td>
		<td><a href="marketer_info.php?marketer_id=<?=$fetch->mr_marketer_id?>" target="_blank" title="マーケターの情報を表示します"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></a></td>
		<td align="center"><?=decode_mypartner_status($fetch->ppj_status)?></td>
<?
	if (SERVICE == 'admin') {
		if ($fetch->ppj_status == MPS_REC_JUNBI) {
?>
		<td align="center"><input type="button" value="登録" onclick="location.href='r_job_edit.php?pjt_id=<?=$pjt_id?>'"></td>
<?
		} else {
?>
		<td align="center"><a href="r_job.php?pjt_id=<?=$pjt_id?>"><?=decode_recruit_status($fetch->ppj_status)?></a></td>
<?
		}
		if ($fetch->ppj_status < MPS_ROOM_JISSHI) {
?>
		<td align="center">未</td>
<?
		} else {
?>
		<td align="center"><a href="pjt_job.php?pjt_id=<?=$pjt_id?>"><?=decode_room_status($fetch->ppj_status)?></a></td>
<?
		}
		if (!$fetch->ppj_point_timing) {
?>
		<td align="center"><input type="button" value="登録" onclick="location.href='../point/edit.php?pjt_id=<?=$pjt_id?>'"></td>
<?
		} else {
?>
		<td align="center"><a href="../point/index.php?pjt_id=<?=$pjt_id?>">設定済み</a></td>
<?
		}
	}
?>
	</tr>
<?
}
?>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
