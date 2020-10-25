<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:エージェント情報表示
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/decode.php");
include("$inc/format.php");

// エージェント識別
function decode_agent_disc($code) {
	switch ($code) {
	case 1:
		return '○○○○○○社員';
	case 2:
		return 'バイター';
	case 3:
		return '協力会社社員';
	}
}

// エージェント状態
function decode_agent_status($code) {
	switch ($code) {
	case 0:
		return '有効';
	case 9:
		return '無効';
	}
}

// エージェント種別
function decode_agent_type($code) {
	switch ($code) {
	case 1:
		return 'エージェント';
	case 2:
		return 'サブエージェント';
	}
}

// 表示状態
function decode_disp_flag($code) {
	switch ($code) {
	case 't':
		return '表示';
	case 'f':
		return '非表示';
	}
}

// メイン処理
set_global('agent', 'エージェント管理', 'エージェント情報表示', BACK_CLOSE);

if ($agent_id != '')
	$where = "ag_agent_id=$agent_id";
elseif ($mail_addr != '')
	$where = 'ag_mail_addr_low=' . sql_char(strtolower($mail_addr)) . ' AND ag_status<>9';

if ($where) {
	$sql = "SELECT * FROM t_agent WHERE $where";
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
		<td class="m1" width="20%">名前（漢字）</td>
		<td class="n1"><?=htmlspecialchars("$fetch->ag_name1 $fetch->ag_name2")?></td>
	</tr>
	<tr>
		<td class="m1">名前（カナ）</td>
		<td class="n1"><?=htmlspecialchars("$fetch->ag_name1_kana $fetch->ag_name2_kana")?></td>
	</tr>
	<tr>
		<td class="m1">生年月日</td>
		<td class="n1"><?=format_date($fetch->ag_birthday)?></td>
	</tr>
	<tr>
		<td class="m1">性別</td>
		<td class="n1"><?=decode_sex($fetch->ag_sex)?></td>
	</tr>
	<tr>
		<td class="m1">メールアドレス</td>
		<td class="n1"><?=htmlspecialchars($fetch->ag_mail_addr)?></td>
	</tr>
	<tr>
		<td class="m1">エージェント識別</td>
		<td class="n1"><?=decode_agent_disc($fetch->ag_agent_disc)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■勤務先情報</td>
	</tr>
	<tr>
		<td class="m1">企業名</td>
		<td class="n1"><?=htmlspecialchars($fetch->ag_kinmu_name)?></td>
	</tr>
	<tr>
		<td class="m1">部署名</td>
		<td class="n1"><?=htmlspecialchars($fetch->ag_kinmu_unit)?></td>
	</tr>
	<tr>
		<td class="m1">役職</td>
		<td class="n1"><?=htmlspecialchars($fetch->ag_kinmu_post)?></td>
	</tr>
	<tr>
		<td class="m1">エージェント所属部署</td>
		<td class="n1"><?=htmlspecialchars($fetch->ag_agent_unit)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■設定情報</td>
	</tr>
	<tr>
		<td class="m1">自己紹介文</td>
		<td class="n1"><textarea cols=60 rows=5 readonly><?=htmlspecialchars($fetch->ag_self_introduction)?></textarea></td>
	</tr>
	<tr>
		<td class="m1">表示状態</td>
		<td class="n1"><?=decode_disp_flag($fetch->ag_disp_flag)?></td>
	</tr>
	<tr>
		<td class="m1">ＢＣＣ設定</td>
		<td class="n1"><textarea cols=60 rows=3 readonly><?=htmlspecialchars($fetch->ag_bcc_addr)?></textarea></td>
	</tr>
	<tr>
		<td class="m1">署名</td>
		<td class="n1"><textarea cols=60 rows=5 readonly><?=htmlspecialchars($fetch->ag_signature)?></textarea></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■その他情報</td>
	</tr>
	<tr>
		<td class="m1">種別</td>
		<td class="n1"><?=decode_agent_type($fetch->ag_type)?></td>
	</tr>
	<tr>
		<td class="m1">状態</td>
		<td class="n1"><?=decode_agent_status($fetch->ag_status)?></td>
	</tr>
	<tr>
		<td class="m1">登録日時</td>
		<td class="n1"><?=format_datetime($fetch->ag_regist_date)?></td>
	</tr>
	<tr>
		<td class="m1">最新更新日時</td>
		<td class="n1"><?=format_datetime($fetch->ag_update_date)?></td>
	</tr>
</table>
<?
} else {
?>
<p class="msg">該当するエージェントは登録されていません。</p>
<?
}
?>
<br>
<input type="button" value="　閉じる　" onclick="window.close()">
</div>

<? page_footer() ?>
</body>
</html>
