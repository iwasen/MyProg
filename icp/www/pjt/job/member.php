<?
/******************************************************
' System :ICPプロジェクトページ
' Content:ジョブメンバ詳細ページ
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
$member_id = $_POST['member_id'];

// ジョブ名（ジョブコード）取得
$job_name_cd = get_job_name_cd($job_id);

// メンバ情報取得
$sql = "SELECT jm_member_cd,COALESCE(jm_nickname,pm_nickname) AS pm_nickname,pm_name1,pm_name2,pm_name1_kana,pm_name2_kana,pm_mail_addr,pm_password,pm_org_id,pm_operator_flag,jm_participant_auth,or_name,pj_pjt_cd,jb_job_cd"
		. " FROM t_job_member"
		. " JOIN t_pjt_member ON pm_member_id=jm_member_id"
		. " JOIN m_org ON or_org_id=pm_org_id"
		. " JOIN t_job ON jb_job_id=jm_job_id"
		. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
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
function auth_add(msg) {
	var f = document.form1;
	var i = f.auth_off.selectedIndex;
	if (i == -1) {
		if (msg) {
			if (f.auth_off.length == 0)
				alert("追加可能な権限はありません。");
			else {
				alert("追加可能な権限を選択してください。");
				f.auth_off.focus();
			}
		}
		return;
	}
	f.auth_on.options[f.auth_on.length] = new Option(f.auth_off.options[i].text, f.auth_off.options[i].value);
	f.auth_off.options[i] = null;
	set_auth();
}
function auth_del(msg) {
	var f = document.form1;
	var i = f.auth_on.selectedIndex;
	if (i == -1) {
		if (msg) {
			if (f.auth_on.length == 0)
				alert("削除できる現在の権限はありません。");
			else {
				alert("削除する現在の権限を選択してください。");
				f.auth_on.focus();
			}
		}
		return;
	}
	f.auth_off.options[f.auth_off.length] = new Option(f.auth_on.options[i].text, f.auth_on.options[i].value);
	f.auth_on.options[i] = null;
	set_auth();
}
function set_auth() {
	var f = document.form1;
	var auth = "";
	for (var i = 0; i < f.auth_on.length; i++) {
		if (auth != "")
			auth += ",";
		auth += f.auth_on.options[i].value;
	}
	f.participant_auth.value = auth;
}
function onsubmit_form1(f) {
	if (f.nickname.value == "") {
		alert("ニックネームを入力してください。");
		f.nickname.focus();
		return false;
	}
	if (f.mail_addr.value == "") {
		alert("メールアドレスを入力してください。");
		f.mail_addr.focus();
		return false;
	}
	if (f.password.value == "") {
		alert("ログインパスワードを入力してください。");
		f.password.focus();
		return false;
	}
	return confirm("メンバー情報を更新します。よろしいですか？");
}
function delete_member() {
	document.goto2.submit();
}
//-->
	</script>
</head>

<body onload="set_auth()">
<div align="center">

<form method="post" name="goto1">
<input type="hidden" name="job_id" <?=value($job_id)?>>
</form>

<form method="post" name="goto2" action="member_delete.php">
<input type="hidden" name="job_id" <?=value($job_id)?>>
<input type="hidden" name="member_id" <?=value($member_id)?>>
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
		詳細
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
<form method="post" name="form1" action="member_update.php" onsubmit="return onsubmit_form1(this)">
<input type="hidden" name="job_id" <?=value($job_id)?>>
<input type="hidden" name="member_id" <?=value($member_id)?>>
<input type="hidden" name="participant_auth">
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="0" width="760" class="contents">
		<tr>
			<td>■メンバー情報詳細</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card">
		<tr>
			<th width="200">ID</th>
			<td width="560"><?=$member_id?></td>
		</tr>
		<tr>
			<th>氏名</th>
			<td>氏：<input type="text" name="name1" <?=value($fetch->pm_name1)?> size="20" class="form"><br>
				名：<input type="text" name="name2" <?=value($fetch->pm_name2)?> size="20" class="form"></td>
		</tr>
		<tr>
			<th>氏名（フリガナ）</th>
			<td>氏：<input type="text" name="name1_kana" <?=value($fetch->pm_name1_kana)?> size="20" class="form"><br>
				名：<input type="text" name="name2_kana" <?=value($fetch->pm_name2_kana)?> size="20" class="form"></td>
		</tr>
		<tr>
			<th>ニックネーム</th>
			<td><input type="text" name="nickname" <?=value($fetch->pm_nickname)?> size="40" class="form"></td>
		</tr>
		<tr>
			<th>メールアドレス</th>
			<td><input type="text" name="mail_addr" <?=value($fetch->pm_mail_addr)?> size="40" class="form"></td>
		</tr>
		<tr>
			<th>置換後メールアドレス</th>
			<td><?=htmlspecialchars(get_member_mail_addr($fetch->pj_pjt_cd, $fetch->jb_job_cd, $fetch->jm_member_cd))?></td>
		</tr>
		<tr>
			<th>ログインパスワード</th>
			<td><input type="password" name="password" <?=value($fetch->pm_password)?> size="40" class="form"></td>
		</tr>
		<tr>
			<th>企業／組織</th>
			<td><?=htmlspecialchars($fetch->or_name)?></td>
		</tr>
		<tr>
			<th>権限</th>
			<td align="center"><table border="0" cellspacing="0" cellpadding="2" width="300" class="contents">
			<tr>
				<td align="center" width="40%">追加可能な権限</td>
				<td align="center" width="20%">&nbsp;</td>
				<td align="center" width="40%">現在の権限</td>
			</tr>
			<tr>
				<td align="right">
				<select name="auth_off" size="7" multiple class="form" ondblclick="auth_add(false)">
<?
foreach ($g_participant_auth as $auth_key => $auth_cd) {
	if (!check_participant_auth($auth_key, $auth)) {
?>
					<option value="<?=$auth_cd?>"><?=$auth_key?></option>
<?
	}
}
?>
				</select>
				</td>
				<td align="center">
					<input type="button" value="追加　→" class="btn" onclick="auth_add(true)"><br>
					<input type="button" value="←　削除" class="btn" onclick="auth_del(true)">
				</td>
				<td>
				<select name="auth_on" size="7" multiple class="form" ondblclick="auth_del(false)">
<?
foreach ($g_participant_auth as $auth_key => $auth_cd) {
	if (check_participant_auth($auth_key, $auth)) {
?>
					<option value="<?=$auth_cd?>"><?=$auth_key?></option>
<?
	}
}
?>
				</select>
				</td>
			</tr>
			</table>
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
	<td width="780" align="center"><input type="submit" value="　更新　" class="form">
<?
if ($fetch->pm_operator_flag == 0) {
?>
	<input type="button" value="　削除　" class="form" onclick="delete_member()">
<?
}
?>
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
