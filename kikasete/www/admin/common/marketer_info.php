<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:マーケター情報表示
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");

// メイン処理
set_global('marketer', 'マーケター管理', 'マーケター情報表示', BACK_CLOSE);

if ($marketer_id != '')
	$where = "mr_marketer_id=$marketer_id";
elseif ($mail_addr != '')
	$where = 'mr_mail_addr_low=' . sql_char(strtolower($mail_addr)) . ' AND mr_status<>9';

if ($where) {
	$sql = "SELECT * FROM t_marketer WHERE $where";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
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
<? page_header() ?>

<div align="center">
<?
if ($nrow) {
	$fetch = pg_fetch_object($result, 0);
?>
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0">■基本情報</td>
		<td align="right"><input type="button" value="　閉じる　" onclick="window.close()"></td>
	</tr>
	<tr>
		<td class="m2" width="20%">名前（漢字）</td>
		<td class="n2"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></td>
	</tr>
	<tr>
		<td class="m2">名前（カナ）</td>
		<td class="n2"><?=htmlspecialchars("$fetch->mr_name1_kana $fetch->mr_name2_kana")?></td>
	</tr>
	<tr>
		<td class="m2">生年月日</td>
		<td class="n2"><?=format_date($fetch->mr_birthday)?></td>
	</tr>
	<tr>
		<td class="m2">性別</td>
		<td class="n2"><?=decode_sex($fetch->mr_sex)?></td>
	</tr>
	<tr>
		<td class="m2">メールアドレス</td>
		<td class="n2"><?=htmlspecialchars($fetch->mr_mail_addr)?></td>
	</tr>
	<tr>
		<td class="m2">職業</td>
		<td class="n2"><?=decode_shokugyou($fetch->mr_shokugyou_cd)?></td>
	</tr>
	<tr>
		<td class="m2">業種</td>
		<td class="n2"><?=decode_gyoushu($fetch->mr_gyoushu_cd)?></td>
	</tr>
	<tr>
		<td class="m2">職種</td>
		<td class="n2"><?=decode_shokushu($fetch->mr_shokushu_cd)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■勤務先情報</td>
	</tr>
	<tr>
		<td class="m2">勤務先郵便番号</td>
		<td class="n2"><?=format_zip($fetch->mr_kinmu_zip)?></td>
	</tr>
	<tr>
		<td class="m2">勤務先地域</td>
		<td class="n2"><?=decode_area($fetch->mr_kinmu_area)?></td>
	</tr>
	<tr>
		<td class="m2">勤務先住所１</td>
		<td class="n2"><?=htmlspecialchars($fetch->mr_kinmu_addr1)?></td>
	</tr>
	<tr>
		<td class="m2">勤務先住所２</td>
		<td class="n2"><?=htmlspecialchars($fetch->mr_kinmu_addr2)?></td>
	</tr>
	<tr>
		<td class="m2">勤務先住所３</td>
		<td class="n2"><?=htmlspecialchars($fetch->mr_kinmu_addr3)?></td>
	</tr>
	<tr>
		<td class="m2">勤務先電話番号</td>
		<td class="n2"><?=htmlspecialchars($fetch->mr_kinmu_tel)?></td>
	</tr>
	<tr>
		<td class="m2">勤務先FAX番号</td>
		<td class="n2"><?=htmlspecialchars($fetch->mr_kinmu_fax)?></td>
	</tr>
	<tr>
		<td class="m2">企業名</td>
		<td class="n2"><?=htmlspecialchars($fetch->mr_kinmu_name)?></td>
	</tr>
	<tr>
		<td class="m2">部署名</td>
		<td class="n2"><?=htmlspecialchars($fetch->mr_kinmu_unit)?></td>
	</tr>
	<tr>
		<td class="m2">役職</td>
		<td class="n2"><?=htmlspecialchars($fetch->mr_kinmu_post)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■その他情報</td>
	</tr>
	<tr>
		<td class="m2">エージェント</td>
		<td class="n2"><?=decode_agent($fetch->mr_agent_id)?></td>
	</tr>
	<tr>
		<td class="m2">種別</td>
		<td class="n2"><?=decode_marketer_type($fetch->mr_type)?></td>
	</tr>
	<tr>
		<td class="m2">状態</td>
		<td class="n2"><?=decode_marketer_status($fetch->mr_status)?></td>
	</tr>
	<tr>
		<td class="m2">登録日時</td>
		<td class="n2"><?=format_datetime($fetch->mr_regist_date)?></td>
	</tr>
	<tr>
		<td class="m2">最新更新日時</td>
		<td class="n2"><?=format_datetime($fetch->mr_update_date)?></td>
	</tr>
</table>
<?
} else {
?>
<p class="msg">該当するマーケターは登録されていません。</p>
<?
}
?>
<br>
<input type="button" value="　閉じる　" onclick="window.close()">
</div>

<? page_footer() ?>
</body>
</html>
