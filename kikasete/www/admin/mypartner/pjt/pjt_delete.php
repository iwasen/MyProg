<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・プロジェクト削除処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'Myパートナー削除', BACK_TOP);

// Myパートナー削除
delete_mypartner($pjt_id);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>
<? page_header() ?>
<? menu_start() ?>

<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td align="center" class="m4"><?=$g_title?></td>
	</tr>
	<tr>
		<td align="center"><br>
			<p class="msg">会議室を削除しました。</p>
			<p><input type="button" value="　戻る　" onclick="location.href='pjt_list.php'"></p>
		</td>
	</tr>
</table>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
