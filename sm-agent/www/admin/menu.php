<?
/******************************************************
' System :メール配信サービス
' Content:トップメニュー画面
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/header.php");
include("$inc/common.php");

set_global('メール配信サービス　管理者メニュー', BACK_NONE);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>

<? page_header() ?>
<table align="center" border=0 cellpadding=8 cellspacing=0 width="90%">
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="admin" colspan=3 align="left">■マスタメンテナンス</th></tr>
				<tr>
					<td width="33%"><a href="system/list.php">システムマスタ</a></td>
					<td width="33%"><a href="user/list.php">ユーザマスタ</a></td>
					<td width="33%"></td>
				</tr>
			</table>
		</td>
	</tr>
	</tr>
</table>

<? page_footer() ?>

</body>
</html>
