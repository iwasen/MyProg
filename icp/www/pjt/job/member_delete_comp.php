<?
/******************************************************
' System :ICPプロジェクトページ
' Content:ジョブメンバ削除完了ページ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// 権限チェック
if (!check_staff_auth('ジョブ管理'))
	redirect("$top/index.php");

// 入力パラメータ
$job_id = $_POST['job_id'];
$member_id = $_POST['member_id'];

// ジョブ名（ジョブコード）取得
$job_name_cd = get_job_name_cd($job_id);

// メンバ名取得
$sql = "SELECT pm_name1||pm_name2"
		. " FROM t_pjt_member"
		. " WHERE pm_member_id=" . sql_number($member_id);
$name = db_fetch1($sql);

db_begin_trans();

// メンバ削除
$rec['jm_status'] = sql_number(9);
db_update('t_job_member', $rec, sprintf("jm_job_id=%s AND jm_member_id=%s", sql_number($job_id), sql_number($member_id)));

// ジョブの参加メンバ数設定
$rec['jb_member_num'] = "(SELECT COUNT(*) FROM t_job_member JOIN t_pjt_member ON pm_member_id=jm_member_id WHERE jm_job_id=$job_id AND pm_operator_flag=0 AND jm_status=1)";
db_update('t_job', $rec, "jb_job_id=$job_id");

db_commit_trans();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title><?=htmlspecialchars($_SESSION[SCD]['pjt_name'])?>　icp.net</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
	<script type="text/javascript">
<!--
function goto(page) {
	var f = document.goto1;
	f.action = page;
	f.submit();
}
//-->
	</script>
</head>

<body>
<div align="center">

<form method="post" name="goto1">
<input type="hidden" name="job_id" <?=value($job_id)?>>
</form>

<? pjt_header() ?>

<!-- pankuzu -->
<table border="0" cellspacing="0" cellpadding="3" bgcolor="#fdc92b" width="800" class="pankuzu">
<tr>
	<td><a href="../index.php" title="ホーム">ホーム</a>
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		<a href="javascript:goto('detail.php')" title="このジョブの詳細を表示します"><?=htmlspecialchars($job_name_cd)?>情報</a>
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		<a href="javascript:goto('list.php')" title="メンバーリスト">メンバーリスト</a>
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		削除完了
		</td>
</tr>
</table>
<!-- pankuzu -->

<!-- 新規ジョブ -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="790" align="right"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- 新規ジョブ -->

<!-- main -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="0" width="760" class="contents">
		<tr>
			<td><?=htmlspecialchars($name)?>様の情報を削除しました。</td>
		</tr>
		</table>

	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
<!-- main -->

<!-- 戻る -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center"><a href="../index.php" title="ホームに戻る">ホームに戻る</a>　｜　
	<a href="javascript:goto('list.php')" title="リストに戻る">リストに戻る</a></td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- 戻る -->

<? pjt_footer() ?>

</div>
</body>

</html>
