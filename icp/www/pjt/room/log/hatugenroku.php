<?
/******************************************************
' System :ICP会議室ページ
' Content:発言録画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/format.php");
include("$inc/crypt.php");
include("$inc/define.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");

// 権限チェック
if (!check_staff_auth('発言録エクスポート'))
	redirect("$top/room/top.php");

// 入力パラメータ
$job_id = $_SESSION[SCD]['job_id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="../css/default.css">
	<title>スレッド部分</title>
<script type="text/javascript">
<!--
function record(id){
	parent.subject.location.href = "sub_list.php?id=" + id;
	parent.contents.location.href= "record.php?id=" + id;
}
//-->
</script>
</head>

<body bgcolor="#ffffff" leftmargin="3" marginwidth="3" topmargin="3" marginheight="3" rightmargin="3" bottommargin="3">
<a name="top"></a>

<form>

<div id="viewoption">

<table border="0" cellspacing="0" cellpadding="10" width="100%" class="text12">
<tr>
	<td class="text14b">発言録ダウンロード
	</td>
</tr>
</table>
<div align="center">
<table border="0" cellspacing="0" cellpadding="0" width="90%">
<tr>
	<td><p class="text12">説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明
	説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明
	説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明
	説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明説明
	説明説明</p></td>
</tr>
</table>
</div>

</form>

<hr size="1" width="100%" align="center" color="#c0c0c0" noshade>

<table border="0" cellspacing="0" cellpadding="10" width="100%" class="text12">
<tr>
	<td class="text14b">お題一覧</td>
</tr>
</table>

<table border="0" cellspacing="0" cellpadding="10" width="100%">
<tr>
	<td>
<pre>
<?
// 抽出条件
$where = sprintf("rm_job_id=%s AND rm_disp_type=1 AND rm_status=1 AND rm_marking_id=1", sql_number($job_id));
if ($_SESSION[SCD]['room_type'] == ROOM_PIR) {
	if ($_SESSION[SCD]['disp_member_id'])
		$where .= sprintf(" AND (rm_disp_member_id=%s OR rm_disp_member_id=0)", sql_number($_SESSION[SCD]['disp_member_id']));
	else
		$where = 'false';
}

$sql = "SELECT rm_remark_id,rm_seq_no,rm_date,rm_subject,rm_remark_type,rm_job_id,rm_member_id"
		. " FROM l_remark"
		. " WHERE $where"
		. " ORDER BY rm_remark_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	// ニックネーム取得（開始メールの場合は差出人名を取得）
	$nickname = get_nickname($fetch->rm_remark_type, $fetch->rm_job_id, $fetch->rm_member_id);
?>
<a href="javascript:record('<?=encrypt_id($fetch->rm_remark_id)?>')" title="このスレッドの一覧表示">[<?=$fetch->rm_seq_no?>] <?=htmlspecialchars($fetch->rm_subject)?>　<?=htmlspecialchars($nickname)?>　<?=format_datetime($fetch->rm_date)?></a>
<?
}
?>

<a href="download3.php?job_id=<?=$job_id?>">すべての話題をダウンロード</a>
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
