<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:企業/担当者マスタ登録
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/select.php");

//メイン処理

set_global2('project', 'プロジェクト管理｜マスタ登録', '企業担当者情報登録', BACK_TOP);

if ($marketer_id) {
	$sql = "SELECT mr_name1,mr_name2,mr_name1_kana,mr_name2_kana,mr_mail_addr,mr_password,mr_shokushu_cd,mr_kinmu_tel,mr_kinmu_fax,mr_kinmu_unit,mr_kinmu_post FROM t_marketer WHERE mr_marketer_id=$marketer_id";
$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$name1 = $fetch->mr_name1;
		$name2 = $fetch->mr_name2;
		$name1_kana = $fetch->mr_name1_kana;
		$name2_kana = $fetch->mr_name2_kana;
		$mail_addr = $fetch->mr_mail_addr;
		$password = $fetch->mr_password;
		$shokushu_cd = $fetch->mr_shokushu_cd;
		$kinmu_tel = $fetch->mr_kinmu_tel;
		$kinmu_fax = $fetch->mr_kinmu_fax;
		$kinmu_unit = $fetch->mr_kinmu_unit;
		$kinmu_post = $fetch->mr_kinmu_post;
	}
}

$sql = "SELECT cl_name FROM m_client WHERE cl_client_id=$cl_id";
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
<script type="text/javascript">
<!--
function onLoad_body() {
	document.form1.ts_tantousha_id.focus();
}
function onSubmit_form1(f) {
	if (f.ts_tantousha_id.value == "") {
		alert("担当者IDを入力してください。");
		f.ts_tantousha_id.focus();
		return false;
	}
	if (f.ts_name1.value == "") {
		alert("担当者名を入力してください。");
		f.ts_name1.focus();
		return false;
	}
	if (f.ts_name2.value == "") {
		alert("担当者名を入力してください。");
		f.ts_name2.focus();
		return false;
	}
	if (f.ts_name1_kana.value == "") {
		alert("担当者名（カナ）を入力してください。");
		f.ts_name1_kana.focus();
		return false;
	}
	if (f.ts_name2_kana.value == "") {
		alert("担当者名（カナ）を入力してください。");
		f.ts_name2_kana.focus();
		return false;
	}
	return confirm("担当者情報を登録します。よろしいですか？");
}
function sel_marketer() {
  var win;
  win = window.open("sel_marketer.php", "sel_marketer", "scrollbars=yes,resizable=yes,width=600");
  win.focus();
}
function copy_marketer(marketer_id) {
	location.replace("ts_new.php?cl_id=<?=$cl_id?>&marketer_id=" + marketer_id);
}
//-->
</script>
<? menu_script() ?>
</head>
<body onload="onLoad_body()">

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="ts_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=0 cellpadding=0 width=700>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width="100%">
				<tr>
					<td align="center" class="m4"><?=$g_title?></td>
				</tr>
			</table>
			<table border=0 cellspacing=2 cellpadding=3 width="100%">
				<tr>
					<td class="m0" colspan=2>■企業情報</td>
				</tr>
				<tr>
					<td class="m1">企業名</td>
					<td class="n1"><?=htmlspecialchars($fetch->cl_name)?></td>
				</tr>
				<tr>
					<td class="m0" colspan=2>■登録する企業担当者情報を入力して下さい。</td>
				</tr>
				<tr>
					<td class="m1">担当者ID<?=MUST_ITEM?></td>
					<td class="n1">
						<input class="alpha" type="text" name="ts_tantousha_id" size=15 maxlength=10>
						<span class="note">（半角１０文字まで）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">担当者名<?=MUST_ITEM?></td>
					<td class="n1">
						<input class="kanji" type="text" name="ts_name1" size=25 maxlength=25 <?=value($name1)?>>姓
						<span class="note">（全角２５文字まで）</span><br>
						<input class="kanji" type="text" name="ts_name2" size=25 maxlength=25 <?=value($name2)?>>名
						<span class="note">（全角２５文字まで）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">担当者名（カナ）<?=MUST_ITEM?></td>
					<td class="n1">
						<input class="kanji" type="text" name="ts_name1_kana" size=25 maxlength=25 <?=value($name1_kana)?>>セイ
						<span class="note">（全角カタカナ２５文字まで）</span><br>
						<input class="kanji" type="text" name="ts_name2_kana" size=25 maxlength=25 <?=value($name2_kana)?>>メイ
						<span class="note">（全角カタカナ２５文字まで）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">メールアドレス</td>
					<td class="n1">
						<input class="alpha" type="text" name="ts_mail_addr" size=50 maxlength=100 <?=value($mail_addr)?>>
						<span class="note">（半角１００文字まで）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">パスワード</td>
					<td class="n1">
						<input class="number" type="text" name="ts_password" size=20 maxlength=20 <?=value($password)?>>
						<span class="note">（半角２０文字まで）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">電話番号</td>
					<td class="n1">
						<input class="number" type="text" name="ts_tel_no" size=20 maxlength=20 <?=value($kinmu_tel)?>>
						<span class="note">（半角数字）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">FAX番号</td>
					<td class="n1">
						<input class="number" type="text" name="ts_fax_no" size=20 maxlength=20 <?=value($kinmu_fax)?>>
						<span class="note">（半角数字）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">職種</td>
					<td class="n1">
						<select name="ts_shokushu_cd"><? select_shokushu('選択して下さい', $shokushu_cd, 2) ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="m1">部署名</td>
					<td class="n1">
						<input class="kanji" type="text" name="ts_unit" size=25 maxlength=25 <?=value($kinmu_unit)?>>
						<span class="note">（全角２５文字まで）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">役職名</td>
					<td class="n1">
						<input class="kanji" type="text" name="ts_post" size=25 maxlength=25 <?=value($kinmu_post)?>>
						<span class="note">（全角２５文字まで）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">マーケターID</td>
					<td class="n1">
						<input class="number" type="text" name="ts_marketer_id" size=10 maxlength=10 <?=value($marketer_id)?>>
					</td>
				</tr>
				<tr>
					<td class="m1">担当エージェント<?=MUST_ITEM?></td>
					<td class="n1">
						<select name="ts_agent_id"><? select_agent('- 選択してください -', $agent_id, 1) ?>
						</select>
					</td>
				</tr>
			</table><br>
			<div align="center">
			<input type="button" value="マーケター一覧から選択" onclick="sel_marketer()">
			<input type="submit" value="　登録　" onclick="document.form1.next_action.value='new'">
			<input type="reset" value="リセット">
			<input type="button" value="　戻る　" onclick="history.back()">
			<input type="hidden" name="next_action" value="new">
			<input type="hidden" name="cl_id" <?=value($cl_id)?>>
			<input type="hidden" name="back_url" <?=value($HTTP_REFERER)?>>
			</div>
		</td>
	</tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
