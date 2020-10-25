<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:マスタメンテナンスメニュー画面
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

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
			    <td width="33%"><a href="client/list.php">企業マスタ</a></td>
			  </tr>
			  <tr>
			    <td><a href="shokugyou/list.php">職業マスタ</a></td>
			    <td><a href="gyoushu/list.php">業種マスタ</a></td>
			    <td><a href="shokushu/list.php">職種マスタ</a></td>
			  </tr>
			  <tr>
			    <td><a href="conveni/list.php">コンビニマスタ</a></td>
			    <td><a href="super/list.php">スーパーマスタ</a></td>
			    <td><a href="genre/list.php">興味ジャンルマスタ</a></td>
			  </tr>
			  <tr>
			    <td><a href="area/list.php">地域マスタ</a></td>
			    <td><a href="city/list.php">都市名マスタ</a></td>
			    <td><a href="family/list.php">家族続柄マスタ</a></td>
			  </tr>
			  <tr>
			    <td><a href="mail_template/list.php">送信メールテンプレート</a></td>
			    <td><a href="blacklist/list.php">ブラックリスト種別</a></td>
			    <td><a href="reply_rate/list.php">アンケート返信率マスタ</a></td>
			  </tr>
			  <tr>
			    <td><a href="media/list.php">媒体マスタ</a></td>
			    <td><a href="advtype/list.php">広告タイプマスタ</a></td>
			    <td><a href="kinmu_gyoushu/list.php">勤務先業種マスタ</a></td>
			  </tr>
			  <tr>
			    <td><a href="enq_template/menu.php">アンケートメールテンプレート</a></td>
			    <td><a href="web_template/menu.php">アンケート説明文テンプレート</a></td>
				<td><a href="gakunen/list.php">学年マスタ</a></td>
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
