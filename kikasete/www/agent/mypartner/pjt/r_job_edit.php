<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・リクルートジョブ新規登録
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/ma_profile.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// チェックボックス表示処理
function checkbox_common($sql, $name, $code, $cols) {
	$ary = explode(',', $code);

	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	echo '<table border=0 cellspacing=0 width="100%">', "\n";
	$col = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		if ($col % $cols == 0)
			echo "<tr>\n";

		echo "<td width='10%'><input type='checkbox' name='${name}[]' value='$fetch[0]'", in_array($fetch[0], $ary) ? ' checked' : '', "><font size=2>", htmlspecialchars($fetch[1]), "</font></td>\n";

		if (++$col % $cols == 0)
			echo "</tr>\n";
	}
	if ($col % $cols != 0)
		echo "</tr>\n";

	echo "</table>\n";
}

// 選択肢表示
function disp_sel(&$sel) {
	if (is_array($sel))
		echo join("\n", $sel);
}

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'Myパートナーリクルートジョブ登録', BACK_TOP);

// 戻り先登録
set_return_page('update_web.php', 'edit=1');
set_return_page('update_mail.php', 'edit=1');

// セッション処理
if ($edit) {
	$myp = &$_SESSION['s_my_partner'];
	$pjt_id = $myp->pjt_id;
	$df = $_SESSION['s_df'];
} else {
	$_SESSION['s_my_partner'] = new my_partner_class;
	$myp = &$_SESSION['s_my_partner'];
	$myp->read_db($pjt_id);

	if ($add) {
		$myp->add_send_date_y = date('Y');
		$myp->add_send_date_m = date('n');
		$myp->add_send_date_d = date('j');
		$myp->add_send_date_h = '';
	}
}
$enquete = &$myp->enquete;
$search = &$myp->search;

if ($df) {
	$_SESSION['s_df'] = $df;
	$disp_flag[$df] = true;

	if (!($myp->recruit_flag & 0x01))
		$disp_flag[1] = true;
	if (!($myp->recruit_flag & 0x02))
		$disp_flag[2] = true;
	if (!($myp->recruit_flag & 0x04))
		$disp_flag[3] = true;
} else {
	$disp_flag[1] = true;
	$disp_flag[2] = true;
	$disp_flag[3] = true;
}

if ($enquete->title == '')
	$title = "『{$myp->room_name}』参加者募集";
else
	$title = $enquete->title;

if ($enquete->max_question_no == 0)
	$enquete->max_question_no = 1;

// 追加発信
if ($add) {
	$myp->send_num = $add;
	$myp->status = MPS_REC_JUNBI;
	$enquete->status = 0;
	$disabled = ' disabled';
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
function onclick_pre_matrix(qno) {
	window.open("", "pre_matrix", "width=400,height=200,scrollbars=yes,resizable=yes");
	document.form2.hyousoku.value = document.form1["hyousoku" + qno].value;
	document.form2.hyoutou.value = document.form1["hyoutou" + qno].value;
	document.form2.submit();
}
function display_style(chk, dsp) {
	document.getElementById(dsp).style.display = document.getElementById(chk).checked ? "" : "none";
}
function set_disp() {
	display_style("chk_summary", "dsp_summary");
	display_style("chk_enquete", "dsp_enquete");
	display_style("chk_search", "dsp_search");
}
function sel_question_type(n) {
	var f = document.form1;
	switch (f["question_type" + n].value) {
	case "1":
	case "2":
		document.getElementById("sel" + n).style.display = "";
		document.getElementById("matrix" + n).style.display = "none";
		break;
	case "3":
		document.getElementById("sel" + n).style.display = "none";
		document.getElementById("matrix" + n).style.display = "none";
		break;
	case "4":
	case "5":
		document.getElementById("sel" + n).style.display = "none";
		document.getElementById("matrix" + n).style.display = "";
		break;
	}
}
function change_age_type() {
	var f = document.form1;
	if (f.age_type.selectedIndex == 1) {
		document.getElementById("age_type1").disabled = false;
	} else {
		document.getElementById("age_type1").disabled = true;
		for (var i = 0; i < f["age_cd[]"].length; i++) {
			f["age_cd[]"][i].checked = false;
		}
	}
	if (f.age_type.selectedIndex == 2) {
		document.getElementById("age_type2").disabled = false;
		f.age_from.disabled = false;
		f.age_to.disabled = false;
	} else {
		document.getElementById("age_type2").disabled = true;
		f.age_from.disabled = true;
		f.age_to.disabled = true;
		f.age_from.value = "";
		f.age_to.value = "";
	}
}
function all_check(radio, flag) {
	var f = document.form1;
	for (var i = 0; i < f[radio].length; i++)
		f[radio][i].checked = flag;
}
function onload_body() {
	set_disp();
	change_age_type();
	for (var i = 1; i <= <?=$enquete->max_question_no?>; i++)
		sel_question_type(i);
}
//-->
</script>
<? menu_script() ?>
</head>
<body onload="onload_body()">

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" enctype="multipart/form-data" action="r_job_update.php">
<table border=0 cellspacing=2 cellpadding=3 width="<?=width('80%')?>">
	<tr>
		<td class="m0" colspan=2>■アンケートの設定</td>
	</tr>
	<tr>
		<td class="m1" colspan=2>
			<input type="checkbox" id="chk_summary" onclick="set_disp()" <?=checked($disp_flag[1])?>>実施概要
		</td>
	</tr>
 	<tr id="dsp_summary">
		<td>
			<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
				<tr>
					<td class="n1" width="15%">開始日時</td>
					<td>
						<input class="number" type="text" name="recruit_start_y" size="4" maxlength="4" <?=value($myp->recruit_start_y)?><?=$disabled?>>年
						<input class="number" type="text" name="recruit_start_m" size="2" maxlength="2" <?=value($myp->recruit_start_m)?><?=$disabled?>>月
						<input class="number" type="text" name="recruit_start_d" size="2" maxlength="2" <?=value($myp->recruit_start_d)?><?=$disabled?>>日
						<input class="number" type="text" name="recruit_start_h" size="2" maxlength="2" <?=value($myp->recruit_start_h)?><?=$disabled?>>時
					</td>
				</tr>
<?
if ($add) {
?>
				<tr>
					<td class="n1" width="15%">追加発信開始日時</td>
					<td>
						<input class="number" type="text" name="add_send_date_y" size="4" maxlength="4" <?=value($myp->add_send_date_y)?>>年
						<input class="number" type="text" name="add_send_date_m" size="2" maxlength="2" <?=value($myp->add_send_date_m)?>>月
						<input class="number" type="text" name="add_send_date_d" size="2" maxlength="2" <?=value($myp->add_send_date_d)?>>日
						<input class="number" type="text" name="add_send_date_h" size="2" maxlength="2" <?=value($myp->add_send_date_h)?>>時
					</td>
				</tr>
<?
}
?>
				<tr>
					<td class="n1">終了日時</td>
					<td>
						<input class="number" type="text" name="recruit_end_y" size="4" maxlength="4" <?=value($myp->recruit_end_y)?>>年
						<input class="number" type="text" name="recruit_end_m" size="2" maxlength="2" <?=value($myp->recruit_end_m)?>>月
						<input class="number" type="text" name="recruit_end_d" size="2" maxlength="2" <?=value($myp->recruit_end_d)?>>日
						<input class="number" type="text" name="recruit_end_h" size="2" maxlength="2" <?=value($myp->recruit_end_h)?>>時
					</td>
				</tr>
				<tr>
					<td class="n1">ポイント数</td>
					<td>
						<input class="number" type="text" name="point" size="3" maxlength="3" <?=value($enquete->point)?><?=$disabled?>>ポイント
					</td>
				</tr>
				<tr>
					<td class="n1">最大発信数</td>
					<td>
						<input class="number" type="text" name="send_num" size="4" maxlength="4" <?=value($myp->send_num)?>>人
					</td>
				</tr>
				<tr>
					<td class="n1">アンケートタイトル</td>
					<td>
						<input class="kanji" type="text" name="title" size="50" maxlength="60" <?=value($title)?>>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m1">
			<input type="checkbox" name="chk_enquete" onclick="set_disp()" <?=checked($disp_flag[2])?>>質問の設定
		</td>
	</tr>
	<tr id="dsp_enquete">
		<td>
			<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box" id="question">
<?
for ($i = 1; $i <= $enquete->max_question_no; $i++) {
	$question = &$enquete->question[$i];

	if ($question->url)
		$image_type = 2;
	elseif ($question->image_id)
		$image_type = 3;
	else
		$image_type = 1;
?>
				<tr>
					<td class="n1" width="15%" rowspan="5">Ｑ<?=mb_convert_kana($i, 'N')?></td>
					<td>
						<textarea rows="3" cols="70" name="question_text<?=$i?>"<?=$disabled?>><?=htmlspecialchars($question->question_text)?></textarea>
						<div class="small">■画像の指定<br>
							<input type="radio" name="image_type<?=$i?>" <?=value_checked('1', $image_type)?><?=$disabled?>>なし<br>
							<input type="radio" name="image_type<?=$i?>" <?=value_checked('2', $image_type)?><?=$disabled?>>URLを指定する 　<input type="text" name="url<?=$i?>" size="30" <?=value($question->url)?><?=$disabled?>><br>
							<input type="radio" name="image_type<?=$i?>" <?=value_checked('3', $image_type)?><?=$disabled?>>画像を埋め込む　<input type="file" name="image<?=$i?>" size="30"<?=$disabled?>>
<?
if ($image_type == 3) {
?>
									<br>　　（画像は設定済みです。画像を上書きしたい場合は、再度パスを指定してください）
<?
}
?>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<select name="question_type<?=$i?>" onchange="sel_question_type(<?=$i?>)"<?=$disabled?>>
							<option <?=value_selected('1', $question->question_type)?>>単一選択(SA)
							<option <?=value_selected('2', $question->question_type)?>>複数選択(MA)
							<option <?=value_selected('4', $question->question_type)?>>ﾏﾄﾘｸｽ(SA)
							<option <?=value_selected('5', $question->question_type)?>>ﾏﾄﾘｸｽ(MA)
							<option <?=value_selected('3', $question->question_type)?>>自由回答(FA)
						</select>
					</td>
				</tr>
				<tr id="sel<?=$i?>">
					<td>
						<textarea name="sel_text<?=$i?>" cols="50" rows="10"<?=$disabled?>><?=disp_sel($question->sel_text)?></textarea>
						<div class="small"><input type="checkbox" name="fa_flag<?=$i?>" <?=value_checked('t', $question->fa_flag)?><?=$disabled?>>最後の選択肢でフリー回答を取得</div>
					</td>
				</tr>
				<tr id="matrix<?=$i?>">
					<td>
					<table border="0" cellspacing="0" cellpadding="0" width="100%">
					<tr>
						<td width="50%"><strong>表側（10個まで）</strong><br>
						<textarea name="hyousoku<?=$i?>" cols="32" rows="10"<?=$disabled?>><? disp_sel($question->hyousoku) ?></textarea></td>
						<td width="50%"><strong>表頭（10個まで）</strong><br>
						<textarea name="hyoutou<?=$i?>" cols="32" rows="10"<?=$disabled?>><? disp_sel($question->hyoutou) ?></textarea></td>
						<td width="25%">
							<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
								<tr>
									<td width="25%"></td>
									<td width="75%" align="center">表頭</td>
								</tr>
								<tr>
									<td>表側</td>
									<td>
									</td>
								</tr>
							</table>
							<br><br>
							<input type="button" name="" value="プレビュー" onclick="onclick_pre_matrix(<?=$i?>)">
						</td>
					</tr>
					</table>
						<div class="small"><input type="checkbox" name="dup_flag<?=$i?>" <?=value_checked('t', $question->dup_flag)?><?=$disabled?>>回答の重複を許さない</div>
					</td>
				</tr>
				<tr></tr>
<?
}
?>
				<tr>
					<td colspan="2">
						<input type="submit" value="　質問を追加　" onclick="document.form1.next_action.value='add_question'"<?=$disabled?>>
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td class="m1">
			<input type="checkbox" name="chk_search" onclick="set_disp()" <?=checked($disp_flag[3])?>>条件の設定
		</td>
	</tr>
	<tr id="dsp_search">
		<td>
			<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">


				<tr>
					<td class="n1" width="15%">対象モニター</td>
					<td width="85%">
						<input type="radio" name="type" <?=value_checked('2', $search->type)?>>リサーチパートナーのみ<br>
						<input type="radio" name="type" <?=value_checked('4', $search->type)?>>リサーチパートナー優先<br>
						<input type="radio" name="type" <?=value_checked('3', $search->type)?>>通常のモニター優先<br>
						<input type="radio" name="type" <?=value_checked('1', $search->type)?>>通常のモニターのみ<br>
						<input type="radio" name="type" <?=value_checked('0', $search->type)?>>全て
					</td>
				</tr>


<!--
<input type="hidden" name="type" value="2">
-->

				<tr>
					<td class="n1" width="15%">性別</td>
					<td width="85%">
						<input type="checkbox" name="sex[]" <?=value_checked('1', $search->sex)?>>男性&nbsp;
						<input type="checkbox" name="sex[]" <?=value_checked('2', $search->sex)?>>女性
					</td>
				</tr>
				<tr>
					<td class="n1">年代</td>
<?
// 年齢指定方法
if ($search->age_cd != '')
	$age_type = '1';
elseif ($search->age_from != '' || $search->age_to != '')
	$age_type = '2';
else
	$age_type = '';
?>
					<td>
						<select name="age_type" onchange="change_age_type()">
							<option <?=value_selected('', $age_type)?>>形式を選択してください</option>
							<option <?=value_selected('1', $age_type)?>>5歳刻みの年齢</option>
							<option <?=value_selected('2', $age_type)?>>任意の年齢</option>
						</select>
						<span id=age_type1>
<?
$sql = "SELECT ae_age_cd,ae_age_text FROM m_age ORDER BY ae_age_cd";
checkbox_common($sql, 'age_cd', $search->age_cd, 4);
?>
						</span>
						<span id="age_type2">
						<table border=0 width="100%">
							<tr>
								<td><input type="text" name="age_from" <?=value($search->age_from)?> size="4" maxlength="2">
								才〜
								<input type="text" name="age_to" <?=value($search->age_to)?> size="4" maxlength="2">
								才<font class="note">（半角数字）</font></td>
							</tr>
						</table>
						</span>
					</td>
				</tr>
				<tr>
					<td class="n1">未既婚</td>
					<td>
						<input type="checkbox" name="mikikon[]" <?=value_checked('1', $search->mikikon)?>>独身&nbsp;
						<input type="checkbox" name="mikikon[]" <?=value_checked('2', $search->mikikon)?>>既婚&nbsp;
						<input type="checkbox" name="mikikon[]" <?=value_checked('3', $search->mikikon)?>>その他
					</td>
				</tr>
				<tr>
					<td class="n1">居住地域</td>
					<td>
<?
$sql = "SELECT ar_area_cd,ar_area_name FROM m_area ORDER BY ar_area_cd";
checkbox_common($sql, 'jitaku_area', $search->jitaku_area, 5);
?>
					</td>
				</tr>
				<tr>
					<td class="n1">職業</td>
					<td>
<?
$sql = "SELECT sg_shokugyou_cd,sg_shokugyou_name FROM m_shokugyou ORDER BY sg_order";
checkbox_common($sql, 'shokugyou', $search->shokugyou, 2);
?>
					</td>
				</tr>
				<tr>
					<td class="n1">子供の有無</td>
					<td>
						<input type="checkbox" name="have_child[]" <?=value_checked_multi('1', $search->have_child)?>>有り&nbsp;
						<input type="checkbox" name="have_child[]" <?=value_checked_multi('2', $search->have_child)?>>無し
					</td>
				</tr>
				<tr>
					<td class="n1">住居形態</td>
					<td>
						<input type="checkbox" name="housing_form[]" <?=value_checked_multi('1', $search->housing_form)?>>一戸建て&nbsp;
						<input type="checkbox" name="housing_form[]" <?=value_checked_multi('2', $search->housing_form)?>>集合住宅
					</td>
				</tr>
				<tr>
					<td class="n1">車の所有</td>
					<td>
						<input type="checkbox" name="have_car[]" <?=value_checked_multi('1', $search->have_car)?>>免許と車を所有している&nbsp;
						<input type="checkbox" name="have_car[]" <?=value_checked_multi('2', $search->have_car)?>>免許のみ所有している&nbsp;
						<input type="checkbox" name="have_car[]" <?=value_checked_multi('3', $search->have_car)?>>免許は所有していない
					</td>
				</tr>
				<tr>
					<td class="n1">コンビニ</td>
					<td>
<?
$sql = "SELECT cv_conveni_cd,cv_name FROM m_conveni WHERE cv_status=0 ORDER BY cv_order";
checkbox_common($sql, 'conveni', $search->conveni, 2);
?>
					</td>
				</tr>
				<tr>
					<td class="n1">スーパー</td>
					<td>
<?
$sql = "SELECT sp_super_cd,sp_name FROM m_super WHERE sp_status=0 ORDER BY sp_order";
checkbox_common($sql, 'super', $search->super, 2);
?>
					</td>
				</tr>
<?
// 追加プロファイル
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
				<tr>
					<td class="n1"><?=htmlspecialchars($profile_name)?></td>
					<td>
<?
$sql = "SELECT pfs_profile_cd,pfs_select_text FROM t_profile_sel WHERE pfs_profile_id=$profile_id ORDER BY pfs_order";
checkbox_common($sql, 'ma_profile', $search->ma_profile, 2);
?>
					</td>
				</tr>
<?
}
?>
				<tr>
					<td class="n1">興味ジャンル</td>
					<td>
<?
$sql = "SELECT kg_genre_cd,kg_name FROM m_genre WHERE kg_status=0 ORDER BY kg_order";
checkbox_common($sql, 'genre', $search->genre, 2);
?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center">
			<br>
<?
if ($add) {
?>
			<input type="hidden" name="add" <?=value($add)?>>
<?
} else {
?>
			<input type="submit" value="ＷＥＢ画面の手動修正" onclick="document.form1.next_action.value='web_edit'">
			<input type="submit" value="メール文面の手動修正" onclick="document.form1.next_action.value='mail_edit'">
<?
}
?>
			<input type="submit" value="ＷＥＢプレビュー" onclick="document.form1.next_action.value='web_preview'">
			<input type="submit" value="メールプレビュー" onclick="document.form1.next_action.value='mail_preview'">
			<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
			<input type="button" value="　戻る　" onclick="location.href='<?=get_return_page()?>'">

			<input type="hidden" name="next_action">
			<input type="hidden" name="pjt_id" <?=value($pjt_id)?>>
		</td>
	</tr>
</table>

</form>

<form method="post" name="form2" action="pre_matrix.php" target="pre_matrix">
<input type="hidden" name="hyousoku">
<input type="hidden" name="hyoutou">
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
