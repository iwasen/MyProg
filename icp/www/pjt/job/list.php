<?
/******************************************************
' System :ICPプロジェクトページ
' Content:ジョブメンバ一覧ページ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/room.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// 権限チェック
if (!check_staff_auth('ジョブ管理'))
	redirect("$top/index.php");

// 入力パラメータ
$job_id = $_POST['job_id'];

// ジョブ名（ジョブコード）取得
$job_name_cd = get_job_name_cd($job_id);
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
function member(member_id) {
	var f = document.goto2;
	f.member_id.value = member_id;
	f.submit();
}
function import_member() {
	var f = document.goto3;
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

<form method="post" name="goto2" action="member.php">
<input type="hidden" name="job_id" <?=value($job_id)?>>
<input type="hidden" name="member_id">
</form>

<form method="post" name="goto3" action="import.php" target="_blank">
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
		メンバーリスト
		</td>
</tr>
</table>
<!-- pankuzu -->

<!-- 新規ジョブ -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="790" align="right"><input type="button" value="メンバーインポート" onclick="import_member()"></td>
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
			<td>■<?=htmlspecialchars($job_name_cd)?>に関連づいているメンバーのリストです。</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="list" frame="box">
		<tr>
			<th width="20" rowspan="2">ID</th>
			<th width="170" rowspan="2">ニックネーム</th>
			<th width="320" rowspan="2">置換後メールアドレス</th>
			<th width="250" colspan="8">権限</th>
		</tr>
		<tr style="font-size:10px;">
			<th width="50">司会者</th>
			<th width="50">WEB<br>発言</th>
			<th width="50">メール<br>送信</th>
			<th width="50">メール<br>受信</th>
			<th width="50">発言<br>ON/OFF</th>
		</tr>
<?
$sql = "SELECT jm_member_cd,pm_member_id,COALESCE(jm_nickname,pm_nickname) AS pm_nickname,pj_pjt_cd,jb_job_cd,jm_participant_auth"
		. " FROM t_job_member"
		. " JOIN t_pjt_member ON pm_member_id=jm_member_id"
		. " JOIN t_pjt ON pj_pjt_id=pm_pjt_id"
		. " JOIN t_job ON jb_job_id=jm_job_id"
		. " WHERE jm_job_id=$job_id AND jm_status=1"
		. " ORDER BY pm_member_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$auth = $fetch->jm_participant_auth;
?>
		<tr class="line<?=$i % 2?>">
			<td align="center"><?=$fetch->pm_member_id?></td>
			<td><a href="javascript:member(<?=$fetch->pm_member_id?>)" title="このメンバーの情報を表示します"><?=htmlspecialchars($fetch->pm_nickname)?></a></td>
			<td><?=htmlspecialchars(get_member_mail_addr($fetch->pj_pjt_cd, $fetch->jb_job_cd, $fetch->jm_member_cd))?></td>
			<td align="center"><?=check_participant_auth('司会者', $auth) ? '○' : '-'?></td>
			<td align="center"><?=check_participant_auth('Web発言', $auth) ? '○' : '-'?></td>
			<td align="center"><?=check_participant_auth('メール送信', $auth) ? '○' : '-'?></td>
			<td align="center"><?=check_participant_auth('メール受信', $auth) ? '○' : '-'?></td>
			<td align="center"><?=check_participant_auth('発言ON/OFF', $auth) ? '○' : '-'?></td>
		</tr>
<?
}
?>
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
	<td width="780" align="center"><a href="../index.php" title="ホームに戻る">ホームに戻る</a></td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- 戻る -->

<? pjt_footer() ?>

</div>
</body>

</html>
