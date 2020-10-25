<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・マーケター情報表示
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/list.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// メイン処理
set_global2('mypartner', 'Myパートナー管理', 'マーケター情報表示', BACK_CLOSE);

$sql = "SELECT * FROM t_marketer WHERE mr_marketer_id=$marketer_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
//-->
</script>
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
	<tr>
		<td class="m0" colspan=2>■Myパートナーご利用履歴</td>
	</tr>
</table>
<form method="post" name="form1">
<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
<?
sort_header(1, 'ID');
sort_header(2, '会議室名');
sort_header(3, '開始日');
sort_header(4, '終了日');
?>
	</tr>
<?
// ソート条件
$order_by = order_by(1, 0, 'ppj_pjt_id', 'ppj_room_name', 'ppj_recruit_start', 'ppj_room_end');

$sql = "SELECT ppj_pjt_id,ppj_room_name,ppj_recruit_start,ppj_room_end"
		. " FROM t_mp_pjt"
		. " WHERE ppj_marketer_id=$marketer_id"
		. " $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>" align="center">
		<td><?=$fetch->ppj_pjt_id?></td>
		<td><?=htmlspecialchars($fetch->ppj_room_name)?></td>
		<td align="center"><?=format_date($fetch->ppj_recruit_start)?></td>
		<td align="center"><?=format_date($fetch->ppj_room_end)?></td>
	</tr>
<?
}
?>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>
</form>
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
