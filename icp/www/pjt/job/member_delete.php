<?
/******************************************************
' System :ICPプロジェクトページ
' Content:ジョブメンバ削除ページ
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

// メンバ情報取得
$sql = "SELECT pm_name1,pm_name2,pm_name1_kana,pm_name2_kana,pm_mail_addr,pm_password,pm_org_id,jm_participant_auth,or_name"
		. " FROM t_job_member"
		. " JOIN t_pjt_member ON pm_member_id=jm_member_id"
		. " JOIN m_org ON or_org_id=pm_org_id"
		. " WHERE jm_job_id=" . sql_number($job_id) . " AND jm_member_id=" . sql_number($member_id);
$result = db_exec($sql);
if (pg_num_rows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

	$auth = $fetch->jm_participant_auth;
} else
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
function onsubmit_form1(f) {
	return confirm("このメンバーを削除します。よろしいですか？");
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
		削除確認
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
<form method="post" name="form1" action="member_delete_comp.php" onsubmit="return onsubmit_form1(this)">
<input type="hidden" name="job_id" <?=value($job_id)?>>
<input type="hidden" name="member_id" <?=value($member_id)?>>
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="0" width="760" class="contents">
		<tr>
			<td>■このメンバーの情報を削除します。よろしければ削除ボタンを押してください。</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card">
		<tr>
			<th width="200">ID</th>
			<td width="560"><?=$member_id?></td>
		</tr>
		<tr>
			<th>氏名</th>
			<td><?=htmlspecialchars($fetch->pm_name1)?>　<?=htmlspecialchars($fetch->pm_name2)?></td>
		</tr>
		<tr>
			<th>氏名（フリガナ）</th>
			<td><?=htmlspecialchars($fetch->pm_name1_kana)?>　<?=htmlspecialchars($fetch->pm_name2_kana)?></td>
		</tr>
		<tr>
			<th>メールアドレス</th>
			<td><?=htmlspecialchars($fetch->pm_mail_addr)?></td>
		</tr>
		<tr>
			<th>ログインパスワード</th>
			<td><?=htmlspecialchars($fetch->pm_password)?></td>
		</tr>
		<tr>
			<th>企業／組織</th>
			<td><?=htmlspecialchars($fetch->or_name)?></td>
		</tr>
		<tr>
			<th>権限</th>
			<td>
<?
foreach ($g_participant_auth as $auth_key => $auth_cd) {
	if (check_participant_auth($auth_key, $auth)) {
?>
			<?=$auth_key?><br>
<?
	}
}
?>
			</td>
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
	<td width="780" align="center"><input type="submit" value="　削除　" class="form">
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
