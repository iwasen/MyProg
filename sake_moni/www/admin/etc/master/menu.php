<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:マスタメンテナンスメニュー画面
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('etc', 'その他管理', 'マスタメンテナンス', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>

<? page_header() ?>

<div align="center">
<table border=0 cellpadding=8 cellspacing=0 width="100%">
	<tr>
		<td>
			<table width="90%" align="center" <?=LIST_TABLE?>>
				<tr><th colspan=3 align="left" style="color=white;background-color=#4682B4">■マスタメンテナンスメニュー</th></tr>
			  <tr>
			    <td width="33%"><a href="system/list.php">システムマスタ</a></td>
			    <td width="33%"><a href="admin/list.php">管理者マスタ</a></td>
			    <td width="33%"><a href="client/list.php">クライアントマスタ</a></td>
			  </tr>
			  <tr>
			    <td><a href="shokugyou/list.php">職業マスタ</a></td>
			    <td><a href="area/list.php">地域マスタ</a></td>
			    <td><a href="chain/list.php">チェーンマスタ</a></td>
			  </tr>
			  <tr>
			    <td><a href="mail_template/list.php">送信メールテンプレート</a></td>
			    <td><a href="enq_template/edit.php?template_id=mye_web">アンケートメールテンプレート</a></td>
			    <td><a href="web_template/edit.php?template_id=mye">アンケート説明文テンプレート</a></td>
			  </tr>
			  <tr>
			    <td><a href="gyoutai/list.php">業態マスタ</a></td>
			    <td><a href="rem_template/edit.php?template_id=mye_rem">督促メールテンプレート</a></td>
			    <td><br></td>
			  </tr>
			</table>
		</td>
	</tr>
</table>
<input type="button" value="　戻る　" onclick="location.href='<?=$top?>/menu.php'">
</div>

<? page_footer() ?>

</body>
</html>
