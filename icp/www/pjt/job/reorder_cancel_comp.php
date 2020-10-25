<?
/******************************************************
' System :ICPプロジェクトページ
' Content:ジョブ再申請取消完了ページ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/status.php");
include("$inc/mail.php");
include("$inc/job_mail.php");
include("$inc/variable.php");
include("$inc/room.php");
include("$inc/decode.php");
include("$inc/format.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// 権限チェック
if (!check_staff_auth('ジョブ管理'))
	redirect("$top/index.php");

// 入力パラメータ
$job_id = $_POST['job_id'];
$status = $_POST['status'];

// ジョブ名（ジョブコード）取得
$job_name_cd = get_job_name_cd($job_id);

db_begin_trans();

// ジョブの再申請フラグをOFF
$rec['jb_reorder_flag'] = sql_bool(false);
db_update('t_job', $rec, "jb_job_id=$job_id");

// 再申請テーブルから削除
db_delete('t_job_reorder', "jr_job_id=$job_id");

// 通知メール送信
send_job_notify_mail($job_id, 'job_reorder_c');

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
		再申請取消完了
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
		<table border="0" cellspacing="0" cellpadding="3" width="760" class="contents">
		<tr>
			<td>■ジョブの再申請を取り消しました。</td>
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
	<a href="javascript:goto('detail.php')" title="ジョブ詳細に戻る">ジョブ詳細に戻る</a></td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- 戻る -->

<? pjt_footer() ?>

</div>
</body>

</html>
