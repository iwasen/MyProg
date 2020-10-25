<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:アンケートテンプレート
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

set_global('etc', 'その他管理｜マスタメンテナンス', 'Webアンケート説明文テンプレート', BACK_TOP);
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
				<tr><th colspan=3 align="left" style="color=white;background-color=#4682B4">■Webアンケート説明文テンプレート選択</th></tr>
			  <tr>
			    <td width="33%"><a href="edit.php?template_id=mye">Ｍｙアンケート</a></td>
			    <td width="33%"><a href="edit.php?template_id=myp">Ｍｙパートナー</a></td>
			    <td width="33%"><?/*<a href="edit.php?template_id=pe">PROアンケート（ファインディング無し）</a>*/?></td>
			  </tr>
			  <tr>
			    <td width="33%"></td>
			    <td width="33%"></td>
			    <td width="33%"><?/*<a href="edit.php?template_id=pefd">PROパートナー（ファインディング有り）</a>*/?></td>
			  </tr>
			</table>
		</td>
	</tr>
</table>
<input type="button" value="　戻る　" onclick="location.href='../menu.php'">
</div>

<? page_footer() ?>

</body>
</html>
