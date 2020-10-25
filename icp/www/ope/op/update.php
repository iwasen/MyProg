<?
/******************************************************
' System :ICPオペレータページ
' Content:オペレータ更新
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// 入力パラメータ
$operator_id = $_GET['operator_id'];

$sql = "SELECT * FROM t_operator WHERE op_operator_id=" . sql_number($operator_id);
$result = db_exec($sql);
if (pg_num_rows($result))
	$fetch = pg_fetch_object($result, 0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title>icp.net オペレーターページ</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
	<script type="text/javascript">
<!--
function on_submit_form1(f) {
	if (f.name1.value == "") {
		alert("氏名（氏）を入力してください。");
		f.name1.focus();
		return false;
	}
	if (f.name2.value == "") {
		alert("氏名（名）を入力してください。");
		f.name2.focus();
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
	if (f.org_id.value == "") {
		alert("企業／組織を選択してください。");
		f.org_id.focus();
		return false;
	}
	return confirm("オペレーターを更新します。よろしいですか？");
}
//-->
	</script>
</head>

<body>
<div align="center">

<? ope_header() ?>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>

<? ope_menu() ?>

	<!-- 右コンテンツ -->
	<td valign="top">

		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
			<td>

				<form method="post" name="form1" action="comp.php" onsubmit="return on_submit_form1(this)">
				<input type="hidden" name="operator_id" <?=value($operator_id)?>>
				<input type="hidden" name="proc" value="update">
				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td>■オペレーター更新</td>
				</tr>
				</table>

				<table border="1" cellspacing="0" cellpadding="2" width="100%" class="card">
				<tr>
					<th width="30%">氏名</th>
					<td width="70%">氏：<input type="text" name="name1" <?=value($fetch->op_name1)?> size="20" class="form"><br>
						名：<input type="text" name="name2" <?=value($fetch->op_name2)?> size="20" class="form"></td>
				</tr>
				<tr>
					<th>氏名（フリガナ）</th>
					<td>氏：<input type="text" name="name1_kana" <?=value($fetch->op_name1_kana)?> size="20" class="form"><br>
						名：<input type="text" name="name2_kana" <?=value($fetch->op_name2_kana)?> size="20" class="form"></td>
				</tr>
				<tr>
					<th>メールアドレス</th>
					<td><input type="text" name="mail_addr" <?=value($fetch->op_mail_addr)?> size="50" class="form"></td>
				</tr>
				<tr>
					<th>ログインパスワード</th>
					<td><input type="password" name="password" <?=value($fetch->op_password)?> size="40" class="form"></td>
				</tr>
				<tr>
					<th>企業／組織</th>
					<td><select name="org_id"><? select_org(DEFAULT_SELECT, $fetch->op_org_id) ?></select></td>
				</tr>
				<tr>
					<th>ニックネーム</th>
					<td><input type="text" name="nickname" <?=value($fetch->op_nickname)?> size="40" class="form"></td>
				</tr>
				<tr>
					<th>管理者権限</th>
					<td><input type="checkbox" name="admin_auth" <?=value_checked('1', $fetch->op_admin_auth)?> id="f0-1"><label for="f0-1">ICP管理者権限</label></td>
				</tr>
				<tr>
					<th>状態</th>
					<td><input type="radio" name="status" <?=value_checked('1', $fetch->op_status)?> id="f1-1"><label for="f1-1">有効</label>
					<input type="radio" name="status" <?=value_checked('9', $fetch->op_status)?> id="f1-2"><label for="f1-2">無効</label></td>
				</tr>
				<tr>
					<th>プロジェクトメンバ情報更新</th>
					<td><input type="checkbox" name="pjt_update" value="1" id="f2-1"><label for="f2-1">自分のプロジェクトメンバ情報も更新する</label></td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%">
				<tr>
					<td align="center"><input type="submit" value="　更新　" class="btn">
					<input type="button" value="キャンセル" class="btn" onclick="history.back()"></td>
				</tr>
				</table>
				</form>

			</td>
		</tr>
		</table>

	</td>
	<!-- 右コンテンツ -->
</tr>
</table>

<? ope_footer() ?>

</div>
</body>

</html>
