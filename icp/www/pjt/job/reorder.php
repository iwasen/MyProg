<?
/******************************************************
' System :ICPプロジェクトページ
' Content:ジョブ再申請確認ページ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/status.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// 権限チェック
if (!check_staff_auth('ジョブ管理'))
	redirect("$top/index.php");

// 入力パラメータ
$job_id = $_POST['job_id'];
$name = $_POST['name'];
$room_type = $_POST['room_type'];
$start_date = "{$_POST['start_year']}/{$_POST['start_month']}/{$_POST['start_day']}";
$end_date = "{$_POST['end_year']}/{$_POST['end_month']}/{$_POST['end_day']}";
$member_max = $_POST['member_max'];
$open_mail_addr = $_POST['open_mail_addr'];
$attachment_file = $_POST['attachment_file'];

// ジョブ名（ジョブコード）取得
$job_name_cd = get_job_name_cd($job_id);

// ジョブ情報取得
$sql = "SELECT jb_description FROM t_job WHERE jb_job_id=" . sql_number($job_id);
$result = db_exec($sql);
if (pg_num_rows($result) > 0)
	$fetch = pg_fetch_object($result, 0);
else
	program_err(__FILE__, __LINE__);
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
		再申請確認
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
			<td>■下記の設定で申請します。</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card">
		<tr>
			<th width="200">ジョブ名（ジョブID）</th>
			<td width="560"><?=htmlspecialchars($name)?>（<?=$job_id?>）</td>
		</tr>
		<tr>
			<th>ジョブ詳細</th>
			<td><?=nl2br(htmlspecialchars($fetch->jb_description))?></td>
		</tr>
		<tr>
			<th>会議室タイプ</th>
			<td><?=decode_room_type($room_type)?></td>
		</tr>
		<tr>
			<th>実施期間</th>
			<td><?=format_date_j($start_date)?> 〜 <?=format_date_j($end_date)?></td>
		</tr>
		<tr>
			<th>最大使用人数</th>
			<td><?=$member_max ? number_format($member_max) . '人' : '制限なし'?></td>
		</tr>
		<tr>
			<th>メールアドレス開示</th>
			<td><?=$open_mail_addr == DBFALSE ? 'メールアドレスを開示しない（置換する）' : 'メールアドレスを開示する（置換しない）'?></td>
		</tr>
		<tr>
			<th>添付ファイル</th>
			<td><?=$attachment_file == DBFALSE ? '使用しない' : '使用する'?></td>
		</tr>
		</table>
	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
<!-- main -->

<!-- 戻る -->
<form method="post" name="form1" action="reapply_comp.php">
<input type="hidden" name="job_id" <?=value($job_id)?>>
<input type="hidden" name="name" <?=value($name)?>>
<input type="hidden" name="room_type" <?=value($room_type)?>>
<input type="hidden" name="start_date" <?=value($start_date)?>>
<input type="hidden" name="end_date" <?=value($end_date)?>>
<input type="hidden" name="member_max" <?=value($member_max)?>>
<input type="hidden" name="open_mail_addr" <?=value($open_mail_addr)?>>
<input type="hidden" name="attachment_file" <?=value($attachment_file)?>>

<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center"><input type="submit" value="再申請" class="form">
	<input type="button" value="キャンセル" class="form" onclick="history.back()"></td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
</form>
<!-- 戻る -->

<? pjt_footer() ?>

</div>
</body>

</html>
