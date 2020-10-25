<?
/******************************************************
' System :ICPオペレータページ
' Content:マスタメンテナンスメニュー
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// 権限チェック
if (!$_SESSION[SCD]['admin_auth'])
	redirect('../top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title>icp.net オペレーターページ</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
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

				<form method="get" name="form1" action="top.php">
				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td width="50%">■マスタメンテナンス</td>
				</tr>
				</table>
				</form>
				<br>
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="card">
				<tr>
					<td width="25%"><a href="system/list.php">システムマスタ</a></td>
					<td width="25%"><a href="mail_template/list.php">送信メールテンプレート</a></td>
					<td width="25%"><a href="marking/list.php">マーキングマスタ</a></td>
					<td width="25%"></td>
				</tr>
				</table>

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
