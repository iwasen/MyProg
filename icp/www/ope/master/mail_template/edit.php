<?
/******************************************************
' System :ICPオペレータページ
' Content:送信メールテンプレート変更画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// メイン処理
$sql = "SELECT * FROM m_mail_template WHERE mt_template_id='{$_GET['template_id']}'";
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	program_err(__FILE__, __LINE__);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>icp.net オペレーターページ</title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	return confirm("送信メールテンプレートを更新します。よろしいですか？");
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
			<td align="center">

<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
	<tr>
		<td colspan=2>■送信メールテンプレート情報を入力してください。</td>
	</tr>
</table>
<table border="1" cellspacing="0" cellpadding="2" width="100%" class="card">
	<tr>
		<th>件名</th>
		<td>
			<input type="text" name="subject" size=70 maxlength=100 <?=value($fetch->mt_subject)?>>
		</td>
	</tr>
	<tr>
		<th>Fromアドレス</th>
		<td>
			<input type="text" name="from" size=70 maxlength=100 <?=value($fetch->mt_from)?>>
		</td>
	</tr>
<?
/*
?>
	<tr>
		<th>Ccアドレス</th>
		<td>
			<input type="text" name="cc" size=70 maxlength=100 <?=value($fetch->mt_cc)?>>
		</td>
	</tr>
	<tr>
		<th>Bccアドレス</th>
		<td>
			<input type="text" name="bcc" size=70 maxlength=100 <?=value($fetch->mt_bcc)?>>
		</td>
	</tr>
<?
*/
?>
	<tr>
		<th>Reply-Toアドレス</th>
		<td>
			<input type="text" name="reply_to" size=70 maxlength=100 <?=value($fetch->mt_reply_to)?>>
		</td>
	</tr>
	<tr>
		<th>本文</th>
		<td><textarea name="body" cols=78 rows=20><?=htmlspecialchars($fetch->mt_body)?></textarea></td>
	</tr>
</table>
<br>
<input type="submit" value="　更新　">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="template_id" <?=value($fetch->mt_template_id)?>>
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
