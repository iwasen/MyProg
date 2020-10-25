<?
/******************************************************
' System :ICP会議室ページ
' Contentスレッド移動入力
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/auth.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");

// 権限チェック
if (!check_participant_auth('司会者'))
	redirect("$top/room/top.php");

// 入力パラメータ
$reload_subject = $_GET['reload_subject'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="../css/default.css">
	<title>スレッド移動</title>
<script type="text/javascript">
<!--
<?
if ($reload_subject)
	echo "parent.subject.location.reload();\n";
?>
function is_number(value) {
	return (value.match(/[0-9]+/g) == value);
}
function onsubmit_form1(f) {
	if (f.move_from.value == "") {
		alert("移動元発言IDを入力してください。");
		f.move_from.focus();
		return false;
	}
	if (!is_number(f.move_from.value)) {
		alert("移動元発言IDには数字のみを入力してください。");
		f.move_from.focus();
		return false;
	}
	if (f.move_from.value == 0) {
		alert("移動元発言IDには0は入力できません。");
		f.move_from.focus();
		return false;
	}
	if (f.move_to.value == "") {
		alert("移動先発言IDを入力してください。");
		f.move_to.focus();
		return false;
	}
	if (!is_number(f.move_to.value)) {
		alert("移動先発言IDには数字のみを入力してください。");
		f.move_to.focus();
		return false;
	}
	if (f.move_from.value == f.move_to.value) {
		alert("移動元発言IDと移動先発言IDに同じIDは指定できません。");
		f.move_from.focus();
		return false;
	}
	if (!f.move_target[0].checked && !f.move_target[1].checked) {
		alert("オプションを選択してください。");
		f.move_target[0].focus();
		return false;
	}
	f.disp_type.value = top.current_data.disp_type.value;
	return confirm("スレッドを移動します。よろしいですか？");
}
//-->
</script>
</head>

<body bgcolor="#ffffff" leftmargin="3" marginwidth="3" topmargin="3" marginheight="3" rightmargin="3" bottommargin="3">
<a name="top"></a>

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#49c4c7" align="center" width="100%">
<tr>
	<td>
		<table border="0" cellspacing="1" cellpadding="2" bgcolor="#b8e8e9" align="center" width="100%">
		<tr>
			<td class="text12b">移動するスレッドを指定してください。</td>
		</tr>
		</table></td>
</tr>
</table>

<form method="post" name="form1" action="move_comp.php" onsubmit="return onsubmit_form1(this)">
<table border="0" cellspacing="1" cellpadding="0" bgcolor="#49c4c7" align="center" width="100%">
<tr>
	<td>
		<table border="0" cellspacing="1" cellpadding="2" bgcolor="#ffffff" align="center" width="100%" class="text12">
		<tr>
			<td valign="middle">

			<table border="0" cellspacing="0" cellpadding="0" width="100%" class="text12">
			<tr>
				<td>

				<table border="0" cellspacing="0" cellpadding="2" align="center" width="100%" class="text12">
				<tr>
					<td colspan="3" class="text14b">移動元発言IDと、移動先発言IDを指定してください。</td>
				</tr>
				<tr>
					<td colspan="3"><hr size="1" width="100%" align="center" color="#c0c0c0" noshade></td>
				</tr>
				<tr>
					<td width="25">&nbsp;</td>
					<td colspan="2"><input type="text" name="move_from" value="" size="10" class="button">（移動元発言ID）
						を
						<input type="text" name="move_to" value="" size="10" class="button">（移動先発言ID）
						の子に移動。（最上位に移動させたい場合は移動先発言IDに0を入力してください）
				</tr>
				<tr>
					<td width="25">&nbsp;</td>
					<td class="text12" align="center" bgcolor="#e7e7e7">オプション：
					<input type="radio" name="move_target" value="1" id="1"><label for="1">指定した発言のみ</label>
					<input type="radio" name="move_target" value="2" id="2"><label for="2">指定した発言の子スレッド全て</label></td>
					<td width="25">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" class="text12" align="center"><input type="submit" value="移動" class="button"></td>
				</tr>
				</table>
				</td>
			</tr>
			</table>

			</td>
		</tr>
		</table></td>
</tr>
</table>

<input type="hidden" name="disp_type">
</form>

</body>

</html>
