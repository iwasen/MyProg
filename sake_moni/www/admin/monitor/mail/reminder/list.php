<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:督促メール配信一覧処理
'******************************************************/

$top = '../../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/list.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

unset($_SESSION['ss_enq_rm']);
session_unregister('ss_enq_rm');

//メイン処理
set_global('monitor', '条件指定メール配信', '督促メール配信', BACK_TOP);

// セッション登録
get_session_vars($pset, 'my_enquete', 'mail_addr', 'name', 'start_date_y', 'start_date_m', 'start_date_d', 'end_date_y', 'end_date_m', 'end_date_d', 'title', 'status', 'displine', 'sort_col', 'sort_dir', 'page');

// 表示条件
$where = "WHERE st_status<>9";

if ($mail_addr != '')
	and_join($where, "st_mail_addr LIKE '%$mail_addr%'");

if ($name != '')
	and_join($where, "(st_name1 LIKE '%$name%' OR st_name2 LIKE '%$name%')");

if ($start_date_y != '')
	and_join($where, "en_start_date=" . sql_date("$start_date_y/$start_date_m/$start_date_d"));

if ($end_date_y != '')
	and_join($where, "en_end_date=" . sql_date("$end_date_y/$end_date_m/$end_date_d"));

if ($title != '')
	and_join($where, "en_title LIKE '%$title%'");

	and_join($where, "en_status=5");

// ソート条件
$order_by = order_by(1, 1, 'me_enquete_id', 'en_title', 'en_enq_type','en_start_date', 'ac_ans_count', 'en_status');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT me_staff_id,me_my_enq_no,me_regist_date,en_start_date,en_end_date,en_title,en_enq_type,st_mail_addr,st_name1,st_name2,en_status,me_enquete_id,sr_chain,me_reminder_id,me_real_send,ac_ans_count"
		. " FROM (t_my_enquete JOIN t_enquete ON me_enquete_id=en_enquete_id) JOIN t_staff ON me_staff_id=st_staff_id"
		. " JOIN t_search ON me_search_id=sr_search_id LEFT JOIN t_ans_count ON en_enquete_id=ac_enquete_id"
		. " $where $order_by $limit";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
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

function onclick_edit_mail(){
	var f = document.form1;
	var ln = f.count.value;
	var check = false;

	for (var i = 0; i < ln; i++){
		if(f("enquete_id[" + i + "]").checked)
			check = true;
	}

	if (!check) {
		alert('アンケートを選択してください');
		return false;
	}

	f.action = "edit.php";
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
		<td class="lt">■実施中 Ｍｙアンケート一覧</td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="location.href='<?=$top?>/menu.php'">
		</td>
	</tr>
	<tr>
		<td class="lc">
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>　</nobr>
			<nobr>メールアドレス<input type="text" name="mail_addr" <?=value($mail_addr)?>>　</nobr>
			<nobr>名前<input type="text" name="name" size=10 <?=value($name)?>>　</nobr>
			<nobr>開始日<input type="text" name="start_date_y" size=4 <?=value($start_date_y)?>>年
			<select name="start_date_m"><? select_month('', $start_date_m) ?></select>月
			<select name="start_date_d"><? select_day('', $start_date_d) ?></select>日　</nobr>
			<nobr>終了日<input type="text" name="end_date_y" size=4 <?=value($end_date_y)?>>年
			<select name="end_date_m"><?select_month('', $end_date_m)?></select>月
			<select name="end_date_d"><?select_day('', $end_date_d)?></select>日　</nobr>
			<nobr>お題<input type="text" name="title" <?=value($title)?>>　</nobr>
			<nobr>状態 実施中　</nobr>
			<input type="button" value="検索" onclick="submit()">
		</td>
		<td class="lc">
			<nobr><input type="button" value="　設定　" onclick="onclick_edit_mail()"></nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<!-- 2003.12.17　井上 IDを３にして追加 -->
<?
		sort_header(0, '選択');
		sort_header(1, 'ID');
		sort_header(2, 'お題');
		sort_header(3, '種別');
		sort_header(4, '公開期間');
		sort_header(5, '返信数/配信数(率)');
		sort_header(0, 'エリア');
		sort_header(0, '業態');
		sort_header(6, '状態');
?>
	</tr>
<?
$n = 0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
<? if($fetch->me_reminder_id) { ?>
		<td align="center">済</td>
<? } else { ?>
		<td align="center"><input type="checkbox" name="enquete_id[<?=$n++?>]" value="<?=$fetch->me_enquete_id?>"></td>
<? } ?>
<!-- 2003.12.17　井上 IDを追加 -->
		<td align="center"><?=$fetch->me_enquete_id?></td>
		<td><a href="<?=$top?>/common/enquete_info.php?staff_id=<?=$fetch->me_staff_id?>&my_enq_no=<?=$fetch->me_my_enq_no?>" target="_blank" title="アンケート内容を表示します"><?=htmlspecialchars($fetch->en_title)?></a></td>
		<td align="center"><?=decode_enq_type2($fetch->en_enq_type)?></td>
		<td align="center"><?=format_date($fetch->en_start_date)?>〜<?=format_date($fetch->en_end_date)?></td>
		<td align="center"><?=number_format($fetch->ac_ans_count)?> /  <?=number_format($fetch->me_real_send)?>(<?=($fetch->ac_ans_count? round(($fetch->ac_ans_count/$fetch->me_real_send)*100):'0')?>%)</td>
		<td align="center"><?=decode_chain2area($fetch->sr_chain)?></td>
		<td align="center"><?=decode_chain2gyoutai($fetch->sr_chain)?></td>
		<td align="center"><?=decode_enq_status($fetch->en_status)?></td>
	</tr>
<?
}
?>
<input type="hidden" name="count" <?=value($n)?>>
</table>
</form>
</div>

<? page_footer() ?>
</body>
</html>
