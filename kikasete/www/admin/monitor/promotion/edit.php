<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:プロモーション管理
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
include("$inc/encrypt.php");

//=== 媒体選択肢 ===
function select_media($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT md_media_id,md_name FROM m_media WHERE md_media_id<>'03' ORDER BY md_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->md_media_id, $selected), '>', htmlspecialchars($fetch->md_name), '</option>', "\n";
	}
}

//=== 広告タイプ選択肢 ===
function select_adv_type($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT at_adv_id,at_name FROM m_adv_type ORDER BY at_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->at_adv_id, $selected), '>', htmlspecialchars($fetch->at_name), '</option>', "\n";
	}
}

//=== 前データのID取得 ===
function get_prev_pr_id() {
	$sql = "SELECT MAX(pr_pr_id) FROM t_promotion";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_row($result, 0);
		return $fetch[0];
	}
}

//=== 次のPRID取得 ===
function get_next_pr_id() {
	$sql = "SELECT MAX(pr_pr_id) FROM t_promotion";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_row($result, 0);
		return $fetch[0] + 1;
	}
}

//=== チェックサム生成 ===
function CreateCheckSum($id) {
	$sum = 0;
	for ($i = 0; $i < strlen($id); $i++)
		$sum = ($sum + substr($id, i, 1)) % 10;

	return chr((10 - $sum) % 10 + ord('0'));
}

//=== ＤＢよりデータ読み込み ===
function read_data($id) {
	global $media_id, $pr_name, $adv_id, $contents_type, $contents_text, $url, $haishin_suu, $click_suu, $haishin_start_y;
	global $haishin_start_m, $haishin_start_d, $haishin_end_y, $haishin_end_m, $haishin_end_d, $cost, $affiliate, $bikou;
	global $contents_pict, $jump_url;

	if ($id) {
		$sql = "SELECT * FROM t_promotion WHERE pr_pr_id=$id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$media_id = $fetch->pr_media_id;
			$pr_name = $fetch->pr_pr_name;
			$adv_id = $fetch->pr_adv_id;
			$contents_type = $fetch->pr_contents_type;
			$contents_text = $fetch->pr_contents_text;
			$url = $fetch->pr_url;
			$haishin_suu = $fetch->pr_haishin_suu;
			$click_suu = $fetch->pr_click_suu;
			$haishin_start_y = get_datepart('Y', $fetch->pr_haishin_start);
			$haishin_start_m = get_datepart('M', $fetch->pr_haishin_start);
			$haishin_start_d = get_datepart('D', $fetch->pr_haishin_start);
			$haishin_end_y = get_datepart('Y', $fetch->pr_haishin_end);
			$haishin_end_m = get_datepart('M', $fetch->pr_haishin_end);
			$haishin_end_d = get_datepart('D', $fetch->pr_haishin_end);
			$cost = $fetch->pr_cost;
			$affiliate = $fetch->pr_affiliate;
			$bikou = $fetch->pr_bikou;
			$contents_pict = $fetch->pr_contents_pict;
			$jump_url = $fetch->pr_jump_url;
		}
	}
}

// メイン処理
set_global('monitor', 'モニター管理', 'プロモーション管理', BACK_TOP);

if (!$pr_id) {
	$pr_id = get_next_pr_id();
	If ($cp == '') {
		$haishin_start_m = 1;
		$haishin_start_d = 1;
		$haishin_end_m = 1;
		$haishin_end_d = 1;
	} else
		read_data(get_prev_pr_id());

	$contents_text = "";
	$fetch = get_system_info('sy_url_top');
	$url = "{$fetch->sy_url_top}pr.php?id=" . encrypt_number($pr_id, 8);
	$new_pr = true;
} else
	read_data($pr_id);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function InputCheck() {
	with (document.form1) {
		if (media_id.value == "") {
			alert("媒体名を選択してください。");
			media_id.focus();
			return false;
		}
		if (pr_name.value == "") {
			alert("プロモーション名を入力してください。");
			pr_name.focus();
			return false;
		}
		if (adv_id.value == "") {
			alert("広告タイプを選択してください。");
			adv_id.focus();
			return false;
		}
	}
	return true;
}
function onClick_regist() {
	if (InputCheck()) {
		if (confirm("プロモーションを登録します。よろしいですか？")) {
			document.form1.next_action.value = "new";
			document.form1.submit();
		}
	}
}
function onClick_update() {
	if (InputCheck()) {
		if (confirm("プロモーションを更新します。よろしいですか？")) {
			document.form1.next_action.value = "update";
			document.form1.submit();
		}
	}
}
function onClick_delete() {
	if (InputCheck()) {
		if (confirm("プロモーションを削除します。よろしいですか？")) {
			document.form1.next_action.value = "delete";
			document.form1.submit();
		}
	}
}
function onClick_showpict() {
	if (document.form1.contents_pict.value != "") {
		document.all.pict.style.width = "";
		document.all.pict.style.height = "";
		document.all.pict.src = document.form1.contents_pict.value;
	}
}
function onClick_copy() {
	location.href = "edit.php?cp=1";
}
function disp_jump_url() {
	var f = document.form1;
	f.jump_url.style.display = f.jump_url_flag[1].checked ? '' : 'none';
}
//-->
</script>
</head>

<body onload="disp_jump_url()">
<? page_header() ?>

<center>
<form method="post" action="update.php" name="form1" ENCtype="multipart/form-data">
	<table border=0 cellspacing=0 cellpadding=0 width='75%'>
		<tr>
			<td class="m0">■プロモーション情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m1" width="22%">PRID</td>
						<td class="n1"><?=$pr_id?><input type="hidden" name="pr_id" <?=value($pr_id)?>></td>
					</tr>
					<tr>
						<td class="m1">媒体名</td>
						<td class="n1"><select name="media_id"><? select_media('- 選択してください -', $media_id) ?></select></td>
					</tr>
					<tr>
						<td class="m1">プロモーション名</td>
						<td class="n1">
							<input class="kanji" type="text" name="pr_name" size=50 maxlength=25 <?=value($pr_name)?>>
							<font class="note">（全角２５文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m1">広告タイプ</td>
						<td class="n1"><select name="adv_id"><? select_adv_type('- 選択してください -', $adv_id) ?></select></td>
					</tr>
					<tr>
						<td class="m1">コンテンツ（テキスト）</td>
						<td class="n1"><textarea name="contents_text" cols=78 rows=5 class="np"><?=$contents_text?></textarea></td>
					</tr>
					<tr>
						<td class="m1">コンテンツ（画像）</td>
						<td class="n1"><img src="pict.php?id=<?=$contents_pict?>" id="pict" alt="画像"<? if (!$contents_pict) echo ' style="width:0;height:0"' ?>><br>
							<input type="file" name="contents_pict" size=50>
							<input type="button" value="表示" onclick="onClick_showpict()"></td>
					</tr>
					<tr>
						<td class="m1">クリックカウンタURL</td>
						<td class="n1">
							<?=$url?>
							<input class="alpha" type="hidden" name="url" <?=value($url)?>>
							<input type="button" value="ｸﾘｯﾌﾟﾎﾞｰﾄﾞへｺﾋﾟｰ" onclick="clipboardData.setData('Text','<?=$url?>')">
						</td>
					</tr>
					<tr>
						<td class="m1">飛び先URL</td>
						<td class="n1">
							<input type="radio" name="jump_url_flag" <?=value_checked('1', $jump_url == '' ? '1' : '2')?> onclick="disp_jump_url()">モニタートップページ
							<input type="radio" name="jump_url_flag" <?=value_checked('2', $jump_url == '' ? '1' : '2')?> onclick="disp_jump_url()">その他のページ
							<input class="alpha" type="text" name="jump_url" size=50 <?=value($jump_url)?>>
						</td>
					</tr>
					<tr>
						<td class="m1">配信数</td>
						<td class="n1">
							<input class="number" type="text" name="haishin_suu" maxlength=8 <?=value($haishin_suu)?>>
							<font class="note">（数字８桁まで）</font>
							</td>
					</tr>
					<tr>
						<td class="m1">保証クリック数</td>
						<td class="n1">
							<input class="number" type="text" name="click_suu" maxlength=8 <?=value($click_suu)?>>
							<font class="note">（数字８桁まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m1">配信開始日</td>
						<td class="n1">
							<input class="number" type="text" name="haishin_start_y" size=4 maxlength=4 <?=value($haishin_start_y)?>>年&nbsp;
							<select name="haishin_start_m"><? select_month('', $haishin_start_m) ?></select>月&nbsp;
							<select name="haishin_start_d"><? select_day('', $haishin_start_d) ?></select>日
						</td>
					</tr>
					<tr>
						<td class="m1">配信終了日</td>
						<td class="n1">
							<input class="number" type="text" name="haishin_end_y" size=4 maxlength=4 <?=value($haishin_end_y)?>>年&nbsp;
							<select name="haishin_end_m"><? select_month('', $haishin_end_m) ?></select>月&nbsp;
							<select name="haishin_end_d"><? select_day('', $haishin_end_d) ?></select>日
						</td>
					</tr>
					<tr>
						<td class="m1">コスト</td>
						<td class="n1">
							<input class="number" type="text" name="cost" maxlength=8 <?=value($cost)?>>
							<font class="note">（数字８桁まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m1">アフィリエイト単価</td>
						<td class="n1">
							<input class="number" type="text" name="affiliate" maxlength=8 <?=value($affiliate)?>>
							<font class="note">（数字８桁まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m1">備考</td>
						<td class="n1">
							<input class="kanji" type="text" name="bikou" size=80 <?=value($bikou)?>>
							<font class="note">（全角２５０文字まで）</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<br>
<?
if ($new_pr) {
?>
	<input type="button" name="copy" value="前ﾃﾞｰﾀｺﾋﾟｰ" Onclick="onClick_copy()">
	<input type="button" name="regist" value=" 登録 " Onclick="onClick_regist()">
<?
} else {
?>
	<input type="button" name="update" value=" 更新 " Onclick="onClick_update()">
	<input type="button" name="delete" value=" 削除 " Onclick="onClick_delete()">
<?
}
?>
	<input type="reset" value="リセット">
	<input type="button" value="　戻る　" onclick="history.back()">
	<input type="hidden" name="next_action">

</form>
</center>

<? page_footer() ?>
</body>
</html>