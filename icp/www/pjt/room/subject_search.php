<?
/******************************************************
' System :ICP会議室ページ
' Content:検索結果表示
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/format.php");
include("$inc/crypt.php");
include("$inc/define.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/search.php");
include("$inc/login_check.php");
include("$inc/subject_common.php");

// 入力パラメータ
$job_id = $_SESSION[SCD]['job_id'];
$page_size = $_GET['page_size'];
$page_no = $_GET['page_no'];

// 検索条件を取得
if (isset($_GET['search_keyword'])) {
	$search = new search_class();
	$search->get_form();
	$_SESSION[SCD]['s_search'] = $search;
} else
	$search = $_SESSION[SCD]['s_search'];

// 表示件数のデフォルト値
if ($page_size == '') {
	$page_size = $_SESSION[SCD]['s_page_size'];
	if ($page_size == '')
		$page_size = 50;
}
$_SESSION[SCD]['s_page_size'] = $page_size;

// 表示ページのデフォルト値
if ($page_no == '' || $page_size != $_GET['page_size_old'])
	$page_no = 1;

// 検索条件編集
if ($_SESSION[SCD]['room_type'] == ROOM_PIR) {
	if ($_SESSION[SCD]['disp_member_id']) {
		$where = sprintf("rm_job_id=%s AND rm_disp_type=1 AND (rm_disp_member_id=%s OR rm_disp_member_id=0) AND rm_status=1",
				sql_number($job_id),
				sql_number($_SESSION[SCD]['disp_member_id']));
	} else
		$where = 'false';
} else
	$where = sprintf("rm_job_id=%s AND rm_disp_type=1 AND rm_status=1", sql_number($job_id));

$cond = $search->get_condition();
if ($cond != '')
	$where .= " AND $cond";

// 検索件数取得
$sql = "SELECT COUNT(*)  FROM l_remark WHERE $where";
$remark_num = db_fetch1($sql);
if ($page_size == 0)
	$page_max = 1;
else
	$page_max = (int)(($remark_num + $page_size - 1) / $page_size);

// マーキング色読み込み
if (check_participant_auth('司会者'))
	get_marking_data();

$offset = ($page_no - 1) * $page_size;
$limit = ($page_size == 0) ? $remark_num : $page_size;

$sql = "SELECT rm_remark_id,rm_seq_no,rm_date,rm_subject,rm_remark_type,rm_job_id,rm_member_id,rm_marking_id,COALESCE(jm_nickname,pm_nickname) AS pm_nickname"
		. " FROM l_remark"
		. " LEFT JOIN t_job_member ON jm_job_id=rm_job_id AND jm_member_id=rm_member_id"
		. " LEFT JOIN t_pjt_member ON pm_member_id=rm_member_id"
		. " WHERE $where"
		. " ORDER BY rm_remark_id"
		. " OFFSET $offset LIMIT $limit";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="css/default.css">
	<title>検索結果</title>
<script type="text/javascript">
<!--
top.current_data.disp_type.value = 1;
//-->
</script>
</head>

<body bgcolor="#ffffff" leftmargin="3" marginwidth="3" topmargin="3" marginheight="3" rightmargin="3" bottommargin="3">
<a name="top"></a>

<form method="get" name="form1" action="subject_search.php">

<div id="viewoption">

<table border="0" cellspacing="5" cellpadding="0" bgcolor="#b8e8e9" width="100%" class="text14b">
<tr>
	<td>【<?=htmlspecialchars($search->keyword)?>】での検索結果：<?=number_format($remark_num)?>件の発言があります。</td>
</tr>
</table>

<table border="0" cellspacing="5" cellpadding="0" width="100%" class="text12">
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="0" width="100%" class="text12">
		<tr height="30">
			<td><a href="subject_top.php">通常の表示に戻す</a></td>
		</tr>
		</table></td>
	<td>
		<table border="0" cellspacing="0" cellpadding="0" width="100%" class="text12">
		<tr height="30">
			<td><select name="page_size" class="textbox" onchange="submit()">
						<option <?=value_selected('10', $page_size)?>>10件表示</option>'
						<option <?=value_selected('50', $page_size)?>>50件表示</option>
						<option <?=value_selected('100', $page_size)?>>100件表示</option>
						<option <?=value_selected('0', $page_size)?>>全件表示</option>
					</select></td>
		</tr>
		</table></td>
	<td>
		<table border="0" cellspacing="0" cellpadding="0" width="100%" class="text12">
		<tr height="30">
			<td><select name="page_no" class="textbox" onchange="submit()">
					<? select_page($page_max, $page_no) ?></select>
					/
					<?=$page_max?>ページ
			</td>
		</tr>
		</table></td>
	<td align="center"><input type="submit" value="更新" class="button"></td>
</tr>
</table>

</div>

<input type="hidden" name="page_size_old" <?=value($page_size)?>>
</form>

<hr size="1" width="100%" align="center" color="#c0c0c0" noshade>

<table border="0" cellspacing="0" cellpadding="10" width="100%">
<tr>
	<td>
<pre>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	// ニックネーム取得（開始メールの場合は差出人名を取得）
	if ($fetch->rm_remark_type == 2) {
		$sql = "SELECT jb_start_from_name FROM t_job WHERE jb_job_id=$fetch->rm_job_id";
		$nickname = db_fetch1($sql);
	} else
		$nickname = $fetch->pm_nickname;

	if ($fetch->rm_marking_id != '')
		echo '<span style="background-color:', $marking_ary[$fetch->rm_marking_id], '">';

	echo '<a href="contents_top.php?id=', encrypt_id($fetch->rm_remark_id), '" target="contents" title="この投稿を表示">[', $fetch->rm_seq_no, '] ', htmlspecialchars($fetch->rm_subject), '　', htmlspecialchars($nickname), '　', format_datetime($fetch->rm_date), '</a>';

	if ($fetch->rm_marking_id != '')
		echo '</span>';

	echo "\n";
}
?>
</pre>

	</td>
</tr>
</table>

<hr size="1" width="100%" align="center" color="#c0c0c0" noshade>

<table border="0" cellspacing="0" cellpadding="10" width="100%" id="backtotop">
<tr>
	<td align="right"><a href="#top" title="このページのトップへ戻る">▲このページのトップへ戻る</a></td>
</tr>
</table>

</body>

</html>
