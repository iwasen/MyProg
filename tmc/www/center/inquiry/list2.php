<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:問い合わせ対応・未対応一覧表示
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/list.php");
include("$inc/select.php");
include("$inc/thread.php");

// 展開チェック
function open_checked($comm_id) {
	global $open;

	if (is_array($open))
		return in_array($comm_id, $open);
	return false;
}

// セッション変数処理
get_session_vars($_GET['pset'], 'inquiry_list2', 'contents', 'staff_id', 'mitaiou', 'open', 'displine', 'sort_col', 'sort_dir', 'page');

// フレーム処理
if ($contents && !$_GET['frame']) {
		include('frame.php');
		exit;
}

$target = $_GET['frame'] ? 'parent' : 'self';

$where = "WHERE cmt_status<>9 AND cmt_thread_type=2";

// 未対応のみ
if ($mitaiou)
	and_join($where, "cmt_answer_flag=true");

// スタッフID
if ($staff_id)
	and_join($where, "cmt_staff_id=$staff_id");

// ソート条件
$order_by = order_by(1, 0, 'not cmt_answer_flag;cml_comm_id', 'cml_date', 'cmc_comm_type', 'cml_comm_id', 'cmc_subject', 'ups_name_kana', 'mail_addr', 'cmt_staff_id');

// 表示行数条件
$limit = disp_limit();

// 対応中の問い合わせ取得
$sql = "SELECT cml_comm_id,cmc_comm_type,cmc_subject,cml_date,cml_send_recv,cmt_staff_id,cmt_answer_flag,COALESCE(ups_mail_addr,cml_from_addr) AS mail_addr,COALESCE(ups_name_kanji,cml_from_name) AS name_kanji,stf_name"
		. " FROM t_comm_thread"
		. " JOIN t_comm_log ON cml_comm_id=cmt_comm_id"
		. " JOIN t_comm_contents ON cml_contents_id=cmc_contents_id"
		. " LEFT JOIN t_user_personal ON cml_user_id=ups_user_id"
		. " LEFT JOIN t_staff ON stf_staff_id=cmt_staff_id"
		. " $where $order_by $limit";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function disp_user_info(user_id) {
}
function check_contents(c) {
	var f = document.form1;
	if (!c) {
		f.frame.value = "";
		f.target = "_parent";
	}
	f.submit();
}
function change_mode(url) {
	<?=$target?>.location.href = url;
}
function onclick_subject(comm_id) {
	setTimeout("disp_comm(" + comm_id + ")", 0);
}
function disp_comm(comm_id) {
	thread_sel("");
<? if ($contents) { ?>
	parent.contents.location.href = "contents.php?comm_id=" + comm_id + "&all=1&url=list2.php";
<? } else { ?>
	location.href = "show.php?comm_id=" + comm_id + "&url=list2.php";
<? } ?>
}
function change_staff(comm_id, staff_id) {
  var win = window.open("change_staff.php?comm_id=" + comm_id + "&staff_id=" + staff_id, "chage_staff", "resizable=yes,scrollbars=yes,width=500");
  win.focus();
}
function onclick_end(comm_id) {
	if (confirm("このスレッドを終了します。よろしいですか？"))
		location.href = "status.php?comm_id=" + comm_id + "&status=9&url=list2.php?frame=<?=$_GET['frame']?>";
}
function open_thread() {
	var f = document.form1;
	f.scroll.value = document.body.scrollTop;
	f.submit();
}
function scrool_top() {
<? if ($_GET['scroll']) { ?>
	var f = document.form1;
	scrollTo(0, <?=$_GET['scroll']?>);
<? } ?>
}
//-->
</script>
<? thread_script('list2.php', $contents) ?>
<? list_script() ?>
</head>
<body onload="scrool_top();thread_sel(document.form1.sel_id.value)">

<? center_header('問い合わせ対応') ?>

<form method="get" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■対応中の問い合わせ一覧</td>
		<td style="color:navy;font-size:85%" align="center">
			<nobr><input type="checkbox" name="contents" onclick="check_contents(checked)" <?=value_checked('1', $contents)?>>簡易内容表示</nobr>
		</td>
		<td class="lb" style="color:navy;font-size:85%">
			<input type="radio" onclick="change_mode('list1.php')">未対応
			<input type="radio" checked>対応中
			<input type="radio" onclick="change_mode('list3.php')">対応履歴
		</td>
	</tr>
</table>
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lc">
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>&nbsp</nobr>
			<nobr>オペレータ<select name="staff_id" onchange="submit()"><? select_staff('- 全て -', $staff_id) ?></select>&nbsp</nobr>
			<nobr><input type="checkbox" name="mitaiou" <?=value_checked('1', $mitaiou)?> onclick="submit()">未回答のスレッドのみ&nbsp</nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
<input type="hidden" name="frame" <?=value($_GET['frame'])?>>
<input type="hidden" name="sel_id">
<input type="hidden" name="scroll" value="0">

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, 'ｽﾚｯﾄﾞ');
		sort_header(0, '対応');
		sort_header(2, '日時');
		sort_header(3, '種類');
		sort_header(4, '通番');
		sort_header(5, '件名');
		sort_header(6, '名前');
		sort_header(7, 'メールアドレス');
		sort_header(8, 'オペレータ');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$comm_id = $fetch->cml_comm_id;

	if ($contents)
		$title = 'スレッド全体の内容を表示します';
	else
		$title = 'メールの内容を表示します';

	if (!$_GET['pset'] && $fetch->cmt_answer_flag == DBTRUE)
		$open[] = $comm_id;
?>
	<tr class="tc0">
		<td align="center"><input type="checkbox" name="open[]" value="<?=$comm_id?>" <?=checked(open_checked($comm_id))?> style="height:15" onclick="open_thread()"><?if ($fetch->cmt_answer_flag == DBTRUE) echo '<br><font color="red">【未回答】</font>'?></td>
		<td align="center"><input type="button" value="終了" style="height:20" onclick="onclick_end(<?=$comm_id?>)"></td>
		<td align="center"><?=format_datetime($fetch->cml_date)?></td>
		<td><?=decode_comm_type2($fetch->cmc_comm_type)?><?=decode_send_recv($fetch->cml_send_recv)?></td>
		<td align="center"><?=$comm_id?></td>
		<td><a href="javascript:onclick_subject(<?=$comm_id?>)" title="<?=$title?>"><?=$fetch->cmc_subject != '' ? htmlspecialchars($fetch->cmc_subject) : '（件名無し）'?></a></td>
		<td><?=htmlspecialchars($fetch->name_kanji)?></td>
		<td><?=htmlspecialchars($fetch->mail_addr)?></td>
		<td><?=htmlspecialchars($fetch->stf_name)?></td>
	</tr>
<?
	if (open_checked($comm_id)) {
?>
	<tr><td colspan=9><pre><? output_thread($comm_id) ?></pre></td></tr>
<?
	}
}
?>
</table>
</form>

<? center_footer() ?>

</body>
</html>
