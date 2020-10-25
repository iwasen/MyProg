<?
/******************************************************
' System :ICPオペレータページ
' Content:マーキングマスタ変更画面
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
$sql = "SELECT * FROM m_marking WHERE mk_marking_id='{$_GET['marking_id']}'";
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
	return confirm("マーキングマスタを更新します。よろしいですか？");
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
		<td colspan=2>■マーキングフラグ情報を入力してください。</td>
	</tr>
</table>
<table border="1" cellspacing="0" cellpadding="2" width="100%" class="card">
	<tr>
		<th>ID</th>
		<td><?=$fetch->mk_marking_id?></td>
	</tr>
	<tr>
		<th>マーキング名称</th>
		<td>
			<input type="text" name="name" size=50 <?=value($fetch->mk_name)?>>
		</td>
	</tr>
	<tr>
		<th>表示色</th>
		<td>
			<input type="text" name="color" size=20 <?=value($fetch->mk_color)?>>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　更新　" onclick="document.form1.proc.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.proc.value='delete'">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="marking_id" <?=value($fetch->mk_marking_id)?>>
<input type="hidden" name="proc">
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
