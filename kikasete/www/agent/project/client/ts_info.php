<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:企業/担当者情報表示
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

//メイン処理
set_global2('project', 'プロジェクト管理｜マスタ登録', '企業担当者情報表示', BACK_TOP);

$sql = "SELECT ts_name1,ts_name2,ts_name1_kana,ts_name2_kana,ts_mail_addr,ts_tel_no,ts_fax_no,ts_shokushu_cd,ts_unit,ts_post,ts_password,ts_marketer_id,ts_agent_id FROM m_tantousha WHERE ts_tantousha_id='$ts_id'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
}
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
		<td class="m0" colspan=2>■企業担当者情報</td>
	</tr>
	<tr>
		<td class="m1" width="30%">担当者ID</td>
		<td class="n1"><?=$ts_id?></td>
	</tr>
	<tr>
		<td class="m1">担当者名</td>
		<td class="n1"><?=htmlspecialchars("$fetch->ts_name1 $fetch->ts_name2")?></td>
	</tr>
	<tr>
		<td class="m1">担当者名（カナ）</td>
		<td class="n1"><?=htmlspecialchars("$fetch->ts_name1_kana $fetch->ts_name2_kana")?></td>
	</tr>
	<tr>
		<td class="m1">メールアドレス</td>
		<td class="n1"><?=htmlspecialchars($fetch->ts_mail_addr)?></td>
	</tr>
	<tr>
		<td class="m1">電話番号</td>
		<td class="n1"><?=htmlspecialchars($fetch->ts_tel_no)?></td>
	</tr>
	<tr>
		<td class="m1">FAX番号</td>
		<td class="n1"><?=htmlspecialchars($fetch->ts_fax_no)?></td>
	</tr>
	<tr>
		<td class="m1">職種</td>
		<td class="n1"><?decode_shokushu($fetch->ts_shokushu_cd, 2)?></td>
	</tr>
	<tr>
		<td class="m1">部署名</td>
		<td class="n1"><?=htmlspecialchars($fetch->ts_unit)?></td>
	</tr>
	<tr>
		<td class="m1">役職名</td>
		<td class="n1"><?=htmlspecialchars($fetch->ts_post)?></td>
	</tr>
	<tr>
		<td class="m1">マーケターID</td>
		<td class="n1"><?=$fetch->ts_marketer_id?></td>
	</tr>
	<tr>
		<td class="m1">担当エージェント</td>
		<td class="n1"><?=decode_agent($fetch->ts_agent_id)?></td>
	</tr>
</table><br>
<div align="center">
	<input type="button" value="　閉じる　" onclick='window.close()'>
</div>
</form>

</body>
</html>
