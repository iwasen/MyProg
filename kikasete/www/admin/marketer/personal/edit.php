<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:マーケター個人検索
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/mr_permission.php");

// 登録方法選択肢
function select_regist_type($sel) {
	echo "<option ", value_selected('0', $sel), ">通常登録</option>";
	echo "<option ", value_selected('1', $sel), ">代理登録</option>";
}

// メイン処理
set_global('marketer', 'マーケター管理', 'マーケター個人検索', BACK_TOP);

$sql = "SELECT * FROM t_marketer WHERE mr_marketer_id=$marketer_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('マーケターＩＤが不正', __FILE__);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	return confirm("マーケター登録情報を更新します。よろしいですか？");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■基本情報</td>
	</tr>
	<tr>
		<td class="m1">名前（漢字）<?=MUST_ITEM?></td>
		<td class="n1">
			姓<input class="kanji" type="text" name="name1" size=25 maxlength=25 <?=value($fetch->mr_name1)?>>
			<span class="note">（全角２５文字まで）</span>
			<br>
			名<input class="kanji" type="text" name="name2" size=25 maxlength=25 <?=value($fetch->mr_name2)?>>
			<span class="note">（全角２５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">名前（カナ）<?=MUST_ITEM?></td>
		<td class="n1">
			姓<input class="kanji" type="text" name="name1_kana" size=25 maxlength=25 <?=value($fetch->mr_name1_kana)?>>
			<span class="note">（全角カタカナ２５文字まで）</span>
			<br>
			名<input class="kanji" type="text" name="name2_kana" size=25 maxlength=25 <?=value($fetch->mr_name2_kana)?>>
			<span class="note">（全角カタカナ２５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">生年月日<?=MUST_ITEM?></td>
		<td class="n1">
			西暦<input class="number" type="text" name="birthday_y" size=4 <?=value(get_datepart('Y', $fetch->mr_birthday))?>>年
			<select name="birthday_m"><?select_month('', get_datepart('M', $fetch->mr_birthday))?></select>月
			<select name="birthday_d"><?select_day('', get_datepart('D', $fetch->mr_birthday))?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">性別<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="sex" <?=value_checked(1, $fetch->mr_sex)?>>男性
			<input type="radio" name="sex" <?=value_checked(2, $fetch->mr_sex)?>>女性
		</td>
	</tr>
	<tr>
		<td class="m1">メールアドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100 <?=value($fetch->mr_mail_addr)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">パスワード<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="password" maxlength=20 <?=value($fetch->mr_password)?>>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">職業</td>
		<td class="n1"><select name="shokugyou_cd"><? select_shokugyou('- 未設定 -', $fetch->mr_shokugyou_cd)?></select></td>
	</tr>
	<tr>
		<td class="m1">業種</td>
		<td class="n1"><select name="gyoushu_cd"><? select_gyoushu('- 未設定 -', $fetch->mr_gyoushu_cd, 2)?></select></td>
	</tr>
	<tr>
		<td class="m1">職種</td>
		<td class="n1"><select name="shokushu_cd"><? select_shokushu('- 未設定 -', $fetch->mr_shokushu_cd)?></select></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■勤務先情報</td>
	</tr>
	<tr>
		<td class="m1">勤務先郵便番号</td>
		<td class="n1">
			<input class="number" type="text" name="kinmu_zip1" size=3 maxlength=3 <?=value(substr($fetch->mr_kinmu_zip, 0, 3))?>>
			-
			<input class="number" type="text" name="kinmu_zip2" size=4 maxlength=4 <?=value(substr($fetch->mr_kinmu_zip, 3, 4))?>>
		</td>
	</tr>
	<tr>
		<td class="m1">勤務先地域</td>
		<td class="n1"><select name="kinmu_area"><? select_area('- 未設定 -', $fetch->mr_kinmu_area)?></select></td>
	</tr>
	<tr>
		<td class="m1">勤務先住所１</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr1" size=80 maxlength=50 <?=value($fetch->mr_kinmu_addr1)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">勤務先住所２</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr2" size=80 maxlength=50 <?=value($fetch->mr_kinmu_addr2)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">勤務先住所３</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr3" size=80 maxlength=50 <?=value($fetch->mr_kinmu_addr3)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">勤務先電話番号</td>
		<td class="n1">
			<input class="alpha" type="text" name="kinmu_tel" size=20 maxlength=20 <?=value($fetch->mr_kinmu_tel)?>>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">勤務先FAX番号</td>
		<td class="n1">
			<input class="alpha" type="text" name="kinmu_fax" size=20 maxlength=20 <?=value($fetch->mr_kinmu_fax)?>>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">企業名</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_name" size=50 maxlength=50 <?=value($fetch->mr_kinmu_name)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">部署名</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_unit" size=50 maxlength=50 <?=value($fetch->mr_kinmu_unit)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">役職</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_post" size=50 maxlength=50 <?=value($fetch->mr_kinmu_post)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■その他情報</td>
	</tr>
	<tr>
		<td class="m1">種別</td>
		<td class="n1"><select name="type"><? select_marketer_type('', $fetch->mr_type)?></select></td>
	</tr>
	<tr>
		<td class="m1">エージェント</td>
		<td class="n1"><select name="agent_id"><? select_agent('- 未設定 -', $fetch->mr_agent_id, AG_MAIN)?></select></td>
	</tr>
	<tr>
		<td class="m1">アシスタント</td>
		<td class="n1">
<?
$sql = "SELECT mr_marketer_id,mr_name1,mr_name2,mr_mail_addr FROM t_marketer WHERE mr_parent_id=$marketer_id AND mr_status<>9 AND mr_type=4";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow) {
	for ($i = 0; $i < $nrow; $i++) {
		$fetch2 = pg_fetch_object($result, $i);
?>
			<a href="<?=$top?>/common/marketer_info?marketer_id=<?=$fetch2->mr_marketer_id?>" target="_blank"><?=$fetch2->mr_marketer_id?></a> <?=htmlspecialchars("$fetch2->mr_name1 $fetch2->mr_name2 <$fetch2->mr_mail_addr>")?>
<?
	}
} else
	echo '無し';
?>
		</td>
	</tr>
	<tr>
		<td class="m1">状態</td>
		<td class="n1"><select name="status"><? select_marketer_status($fetch->mr_status)?></select></td>
	</tr>
	<tr>
		<td class="m1">登録方法</td>
		<td class="n1"><select name="regist_type"><? select_regist_type($fetch->mr_regist_type)?></select></td>
	</tr>
	<tr>
		<td class="m1">登録日時</td>
		<td class="n1"><?=format_datetime($fetch->mr_regist_date)?></td>
	</tr>
	<tr>
		<td class="m1">最新更新日時</td>
		<td class="n1"><?=format_datetime($fetch->mr_update_date)?></td>
	</tr>
<?
if ($fetch->mr_type == MK_ASSISTANT) {
?>
	<tr>
		<td class="m0" colspan=2>■権限</td>
	</tr>
	<tr>
		<td class="m1">アシスタントの権限</td>
		<td class="n1"><? input_permission($fetch->mr_permission) ?></td>
	</tr>
<?
} else {
?>
	<input type="hidden" name="permission" <?=value($fetch->mr_permission)?>>
<?
}
?>
</table>
<br>
<input type="hidden" name="marketer_id" <?=value($marketer_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
