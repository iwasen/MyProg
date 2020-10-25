<?
/******************************************************
' System :ICP会議室ページ
' Content:問い合わせ表示
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/format.php");
include("$inc/crypt.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");
include("$inc/subject_common.php");
include("$inc/thread.php");

// 権限チェック
if (!check_participant_auth('司会者'))
	redirect("$top/room/top.php");

// 入力パラメータ
$job_id = $_SESSION[SCD]['job_id'];
$page_size = $_GET['page_size'];
$page_no = $_GET['page_no'];

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

// 発言件数取得
$sql = "SELECT COUNT(*) FROM l_remark WHERE rm_job_id=$job_id AND rm_disp_type=2 AND rm_status=1";
$remark_num = db_fetch1($sql, 0);
if ($page_size == 0)
	$page_max = 1;
else
	$page_max = (int)(($remark_num + $page_size - 1) / $page_size);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="css/default.css">
	<title>問い合わせ</title>
<script type="text/javascript">
<!--
top.current_data.disp_type.value = 2;
//-->
</script>
</head>

<body bgcolor="#ffffff" leftmargin="3" marginwidth="3" topmargin="3" marginheight="3" rightmargin="3" bottommargin="3">
<a name="top"></a>

<form method="get" name="form1" action="helplist.php">

<div id="viewoption">
<table border="0" cellspacing="5" cellpadding="0" width="100%" class="text12">
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="0" width="100%" class="text12">
		<tr height="30">
			<td><a href="subject_top.php">発言の表示に戻す</a></td>
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
		</tr>
		</table></td>
	<td align="center"><input type="submit" name="" value="更新" class="button"></td>
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
// マーキング色読み込み
if (check_participant_auth('司会者'))
	get_marking_data();

// スレッド表示
$offset = ($page_no - 1) * $page_size;
$limit = ($page_size == 0) ? $remark_num : $page_size;
output_thread($job_id, 2, check_participant_auth('司会者') ? 0 : $_SESSION[SCD]['disp_member_id'], $offset, $limit);
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
