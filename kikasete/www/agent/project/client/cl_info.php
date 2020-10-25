<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:企業情報表示
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/decode.php");
include("$inc/format.php");

//メイン処理
set_global2('project', 'プロジェクト管理｜マスタ登録', '企業情報表示', BACK_TOP);

$sql = "SELECT * FROM m_client WHERE cl_client_id=$cl_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('企業IDが不正', __FILE__);
$fetch = pg_fetch_object($result, 0);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>

<form>
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■企業情報</td>
	</tr>
	<tr>
		<td class="m1" width="30%">企業ID</td>
		<td class="n1"><?=$fetch->cl_client_id?></td>
	</tr>
	<tr>
		<td class="m1">企業名</td>
		<td class="n1"><?=htmlspecialchars($fetch->cl_name)?></td>
	</tr>
	<tr>
		<td class="m1">企業名(カナ)</td>
		<td class="n1"><?=htmlspecialchars($fetch->cl_name_kana)?></td>
	</tr>
	<tr>
		<td class="m1">郵便番号</td>
		<td class="n1"><?=format_zip($fetch->cl_zip_code)?></td>
	</tr>
	<tr>
		<td class="m1">住所</td>
		<td class="n1">
			<?=decode_area($fetch->cl_address1)?><br>
			<?=htmlspecialchars($fetch->cl_address2)?><br>
			<?=htmlspecialchars($fetch->cl_address3)?><br>
			<?=htmlspecialchars($fetch->cl_address4)?>
		</td>
	</tr>
	<tr>
		<td class="m1">業種</td>
		<td class="n1"><?=decode_gyoushu($fetch->cl_gyoushu_cd)?></td>
	</tr>
</table><br>
<div align="center">
	<input type="button" value="　閉じる　" onclick='window.close()'>
</div>
</form>

</body>
</html>
