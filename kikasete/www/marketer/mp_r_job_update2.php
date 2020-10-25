<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/check.php");
include("$inc/image.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

// 選択肢取得
function get_sel($text) {
	$ary = array();
	$ary2 = explode("\n", $text);
	$i = 1;
	foreach ($ary2 as $sel) {
		$sel = trim($sel);
		if ($sel != '')
			$ary[$i++] = $sel;
	}
	return $ary;
}

// Myパートナークラス取得
$myp = &$_SESSION['s_my_partner'];
$enquete = &$myp->enquete;
$pjt_id = $myp->pjt_id;
if (!$pjt_id)
	redirect('mp_pjt_list.php');

// フォームからクラスへ代入
$enquete->title = $title;

for ($i = 1; $i <= $enquete->max_question_no; $i++) {
	$question = &$enquete->question[$i];

	$question->question_text = $_POST["question_text$i"];
	$question->question_type = $_POST["question_type$i"];
	$question->fa_flag = $_POST["fa_flag$i"];

	switch ($_POST["image_type$i"]) {
	case 1:
		$question->url = '';
		$question->image_id = 0;
		break;
	case 2:
		$question->url = $_POST["url$i"];
		$question->image_id = 0;
		break;
	case 3:
		$question->url = '';
		$question->image_id = save_image($question->image_id, $_FILES["image$i"]);
		break;
	}

	$question->dup_flag = $_POST["dup_flag$i"];

	$question->sel_text = get_sel($_POST["sel_text$i"]);
	$question->hyousoku = get_sel($_POST["hyousoku$i"]);
	$question->hyoutou = get_sel($_POST["hyoutou$i"]);
}

// 質問追加処理
if ($next_action == 'add_question') {
	$enquete->max_question_no++;
	redirect('mp_r_job_edit2.php?edit=1');
}

// プレビュー処理
if ($next_action == 'preview') {
	redirect('mp_preveiw_web.php');
}

// アンケートタイトルチェック
if ($enquete->title == '')
	$msg[] = 'アンケートタイトルが入力されていないようです。';

// 質問チェック
$exist = false;
for ($i = 1; $i <= $enquete->max_question_no; $i++) {
	$question = &$enquete->question[$i];
	$qno = 'Ｑ' . mb_convert_kana($i, 'N');

	if ($question->question_text == '') {
		switch ($question->question_type) {
		case 1:
		case 2:
			if (count($question->sel_text) != 0)
				$msg[] = "{$qno}の質問文が入力されていないようです。";
			break;
		case 4:
		case 5:
			if (count($question->hyousoku) != 0 || count($question->hyoutou) != 0)
				$msg[] = "{$qno}の質問文が入力されていないようです。";
			break;
		}
		continue;
	}

	if ($question->question_text != '') {
		switch ($_POST["image_type$i"]) {
		case 2:
			if ($question->url == '')
				$msg[] = "{$qno}の画像URLが入力されていないようです。";
			elseif (!check_url($question->url))
				$msg[] = "{$qno}の画像URLが正しく入力されていないようです。";
			break;
		case 3:
			if ($question->image_id == '')
				$msg[] = "{$qno}に埋め込む画像が入力されていないようです。";
			break;
		}
	}

	switch ($question->question_type) {
	case 1:
	case 2:
		if (count($question->sel_text) == 0)
			$msg[] = "{$qno}の選択肢が入力されていないようです。";
		elseif (count($question->sel_text) > 21)
			$msg[] = "{$qno}の選択肢は21個以内にしてください。";
		break;
	case 4:
	case 5:
		if (count($question->hyousoku) == 0)
			$msg[] = "{$qno}の表側が入力されていないようです。";
		elseif (count($question->hyousoku) > 10)
			$msg[] = "{$qno}の表側は10個以内にしてください。";

		if (count($question->hyoutou) == 0)
			$msg[] = "{$qno}の表頭が入力されていないようです。";
		elseif (count($question->hyoutou) > 10)
			$msg[] = "{$qno}の表頭は10個以内にしてください。";
		break;
	}

	$exist = true;
}

if (!$exist)
	$msg[] = 'アンケートの質問がひとつも入力されていないようです。';

if (!$msg) {
	// DBに保存
	$myp->save_enquete = true;
	$myp->recruit_flag |= 0x02;
	$myp->write_db();

	// セッション変数削除
	unset($_SESSION['s_my_partner']);
}
?>
<? marketer_header('Myパートナー', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>
<?
if (!$msg) {
?>
<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<!-- myパートナー -->
			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td>リクルートアンケート内容の設定を完了しました。</td>
				</tr>
			</table>
			<br>

<a href="mp_r_job.php?pjt_id=<?=$pjt_id?>"><img src="images/mkk_bt/back.gif" alt="戻る" name="image2" width="108" height="31" border="0"></a>
			</form>
			</div>
		</td>
	</tr>
</table>
<? } else{?>
<table width="788" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><? error_msg($msg);?></td>
  </tr>
</table>
<? }?>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
