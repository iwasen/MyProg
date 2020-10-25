<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・リクルートジョブ更新
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/check.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/get_form.php");
include("$inc/image.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/error_msg.php");

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

// メイン処理
set_global2('mypartner', 'Myパートナー管理', 'Myパートナー　リクルートジョブ更新', BACK_TOP);

// Myパートナークラス取得
$myp = &$_SESSION['s_my_partner'];
$pjt_id = $myp->pjt_id;
$enquete = &$myp->enquete;
$search = &$myp->search;

// フォーム（実施概要）からクラスへ代入
if ($add) {
	$myp->add_send_date_y = get_number($add_send_date_y);
	$myp->add_send_date_m = get_number($add_send_date_m);
	$myp->add_send_date_d = get_number($add_send_date_d);
	$myp->add_send_date_h = get_number($add_send_date_h);
} else {
	$myp->recruit_start_y = get_number($recruit_start_y);
	$myp->recruit_start_m = get_number($recruit_start_m);
	$myp->recruit_start_d = get_number($recruit_start_d);
	$myp->recruit_start_h = get_number($recruit_start_h);
	$enquete->point = get_number($point);
}
$myp->recruit_end_y = get_number($recruit_end_y);
$myp->recruit_end_m = get_number($recruit_end_m);
$myp->recruit_end_d = get_number($recruit_end_d);
$myp->recruit_end_h = get_number($recruit_end_h);
$myp->send_num = get_number($send_num);

// フォーム（質問の設定）からクラスへ代入
$enquete->title = $title;

if (!$add) {
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
}

// フォーム（条件の設定）からクラスへ代入
$search->type = $_POST['type'];
$search->sex = (count($_POST['sex']) == 1) ? $_POST['sex'][0] : '';
$myp->age_type = $_POST['age_type'];
if ($myp->age_type == '1') {
	$search->age_cd = get_multi_data($_POST['age_cd']);
	$search->age_from = '';
	$search->age_to = '';
} elseif ($myp->age_type == '2') {
	$search->age_cd = '';
	$search->age_from = $_POST['age_from'];
	$search->age_to = $_POST['age_to'];
} else {
	$search->age_cd = '';
	$search->age_from = '';
	$search->age_to = '';
}
$search->mikikon = (count($_POST['mikikon']) == 1) ? $_POST['mikikon'][0] : '';
$search->jitaku_area = get_multi_data($_POST['jitaku_area']);
$search->shokugyou = get_multi_data($_POST['shokugyou']);
$search->have_child = get_multi_data($_POST['have_child']);
$search->housing_form = get_multi_data($_POST['housing_form']);
$search->have_car = get_multi_data($_POST['have_car']);
$search->genre = get_multi_data($_POST['genre']);
$search->conveni = get_multi_data($_POST['conveni']);
$search->super = get_multi_data($_POST['super']);
$search->ma_profile = get_multi_data($_POST['ma_profile']);

// 質問追加処理
if ($next_action == 'add_question') {
	$enquete->max_question_no++;
	redirect('r_job_edit.php?edit=1');
}

// Webプレビュー処理
if ($next_action == 'web_preview')
	redirect('preveiw_web.php');

// メールプレビュー処理
if ($next_action == 'mail_preview')
	redirect('preveiw_mail.php');

// ＷＥＢ画面の手動修正
if ($next_action == 'web_edit')
	redirect('edit_web.php?edit=1');

// メール文面の手動修正
if ($next_action == 'mail_edit')
	redirect('edit_mail.php?edit=1');

// リクルート期間の開始年チェック
$recruit_start_ok = true;
if ($myp->recruit_start_y == '') {
	$msg[] = 'リクルート期間の開始年が入力されていないようです。';
	$recruit_start_ok = false;
} elseif (!check_num($myp->recruit_start_y, 2004, 2100)) {
	$msg[] = 'リクルート期間の開始年が正しく入力されていないようです。';
	$recruit_start_ok = false;
}

// リクルート期間の開始月チェック
if ($myp->recruit_start_m == '') {
	$msg[] = 'リクルート期間の開始月が入力されていないようです。';
	$recruit_start_ok = false;
} elseif (!check_num($myp->recruit_start_m, 1, 12)) {
	$msg[] = 'リクルート期間の開始月が正しく入力されていないようです。';
	$recruit_start_ok = false;
}

// リクルート期間の開始日チェック
if ($myp->recruit_start_d == '') {
	$msg[] = 'リクルート期間の開始日が入力されていないようです。';
	$recruit_start_ok = false;
} elseif (!check_num($myp->recruit_start_d, 1, 31)) {
	$msg[] = 'リクルート期間の開始日が正しく入力されていないようです。';
	$recruit_start_ok = false;
}

// リクルート期間の開始時間チェック
if ($myp->recruit_start_h == '') {
	$msg[] = 'リクルート期間の開始時間が入力されていないようです。';
	$recruit_start_ok = false;
} elseif (!check_num($myp->recruit_start_h, 0, 23)) {
	$msg[] = 'リクルート期間の開始時間が正しく入力されていないようです。';
	$recruit_start_ok = false;
}

// リクルート期間の開始年月日正当性チェック
if ($recruit_start_ok) {
	if (!checkdate($myp->recruit_start_m, $myp->recruit_start_d, $myp->recruit_start_y)) {
		$msg[] = 'リクルート期間の開始年月日が正しく入力されていないようです。';
		$recruit_start_ok = false;
	} else
		$recruit_start = mktime($myp->recruit_start_h, 0, 0, $myp->recruit_start_m, $myp->recruit_start_d, $myp->recruit_start_y);
}

if ($add) {
	// 追加発信開始日時の年チェック
	$add_send_date_ok = true;
	if ($myp->add_send_date_y == '') {
		$msg[] = '追加発信開始日時の年が入力されていないようです。';
		$add_send_date_ok = false;
	} elseif (!check_num($myp->add_send_date_y, 2004, 2100)) {
		$msg[] = '追加発信開始日時の年が正しく入力されていないようです。';
		$add_send_date_ok = false;
	}

	// 追加発信開始日時の月チェック
	if ($myp->add_send_date_m == '') {
		$msg[] = '追加発信開始日時の月が入力されていないようです。';
		$add_send_date_ok = false;
	} elseif (!check_num($myp->add_send_date_m, 1, 12)) {
		$msg[] = '追加発信開始日時の月が正しく入力されていないようです。';
		$add_send_date_ok = false;
	}

	// 追加発信開始日時の日チェック
	if ($myp->add_send_date_d == '') {
		$msg[] = '追加発信開始日時の日が入力されていないようです。';
		$add_send_date_ok = false;
	} elseif (!check_num($myp->add_send_date_d, 1, 31)) {
		$msg[] = '追加発信開始日時の日が正しく入力されていないようです。';
		$add_send_date_ok = false;
	}

	// 追加発信開始日時の時間チェック
	if ($myp->add_send_date_h == '') {
		$msg[] = '追加発信開始日時の時間が入力されていないようです。';
		$add_send_date_ok = false;
	} elseif (!check_num($myp->add_send_date_h, 0, 23)) {
		$msg[] = '追加発信開始日時の時間が正しく入力されていないようです。';
		$add_send_date_ok = false;
	}

	// 追加発信開始日時の年月日正当性チェック
	if ($add_send_date_ok) {
		if (!checkdate($myp->add_send_date_m, $myp->add_send_date_d, $myp->add_send_date_y)) {
			$msg[] = '追加発信開始日時の年月日が正しく入力されていないようです。';
			$add_send_date_ok = false;
		} else
			$add_send_date = mktime($myp->add_send_date_h, 0, 0, $myp->add_send_date_m, $myp->add_send_date_d, $myp->add_send_date_y);
	}
}

// リクルート期間の終了年チェック
$recruit_end_ok = true;
if ($myp->recruit_end_y == '') {
	$msg[] = 'リクルート期間の終了年が入力されていないようです。';
	$recruit_end_ok = false;
} elseif (!check_num($myp->recruit_end_y, 2004, 2100)) {
	$msg[] = 'リクルート期間の終了年が正しく入力されていないようです。';
	$recruit_end_ok = false;
}

// リクルート期間の終了月チェック
if ($myp->recruit_end_m == '') {
	$msg[] = 'リクルート期間の終了月が入力されていないようです。';
	$recruit_end_ok = false;
} elseif (!check_num($myp->recruit_end_m, 1, 12)) {
	$msg[] = 'リクルート期間の終了月が正しく入力されていないようです。';
	$recruit_end_ok = false;
}

// リクルート期間の終了日チェック
if ($myp->recruit_end_d == '') {
	$msg[] = 'リクルート期間の終了日が入力されていないようです。';
	$recruit_end_ok = false;
} elseif (!check_num($myp->recruit_end_d, 1, 31)) {
	$msg[] = 'リクルート期間の終了日が正しく入力されていないようです。';
	$recruit_end_ok = false;
}

// リクルート期間の終了時間チェック
if ($myp->recruit_end_h == '') {
	$msg[] = 'リクルート期間の終了時間が入力されていないようです。';
	$recruit_end_ok = false;
} elseif (!check_num($myp->recruit_end_h, 0, 23)) {
	$msg[] = 'リクルート期間の終了時間が正しく入力されていないようです。';
	$recruit_end_ok = false;
}

// リクルート期間の終了年月日正当性チェック
if ($recruit_end_ok) {
	if (!checkdate($myp->recruit_end_m, $myp->recruit_end_d, $myp->recruit_end_y)) {
		$msg[] = 'リクルート期間の終了年月日が正しく入力されていないようです。';
		$recruit_end_ok = false;
	} else
		$recruit_end = mktime($myp->recruit_end_h, 0, 0, $myp->recruit_end_m, $myp->recruit_end_d, $myp->recruit_end_y);
}

// リクルート期間の開始と終了年月日の関係チェック
if ($recruit_start && $recruit_end) {
	if ($recruit_start >= $recruit_end)
		$msg[] = 'リクルート期間の終了日は開始日より後の日付を入力してください。';
}

// 追加発信開始日時とリクルート期間の終了年月日の関係チェック
if ($add) {
	if ($add_send_date && $recruit_end) {
		if ($add_send_date >= $recruit_end)
			$msg[] = '追加発信開始日時はリクルート期間の終了日より前の日付を入力してください。';
	}
}

// リクルート条件入力チェック
if (count($myp->recruit_jouken) == 0)
	$msg[] = 'リクルート条件を１つ以上入力してください。';

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

// アンケートタイトルチェック
if ($search->type == '')
	$msg[] = '対象モニターが選択されていないようです。';

// 対象者数チェック
if (!$msg && $search->get_monitor_num() < $myp->send_num)
	$msg[] = '対象モニター数が最大発信数に満たないようです。';

if (!$exist)
	$msg[] = 'アンケートの質問がひとつも入力されていないようです。';

if (!$msg) {
	// DBに保存
	$myp->save_enquete = true;
	$myp->save_search = true;
	$myp->recruit_flag |= 0x07;
	$myp->write_db();

	// セッション変数削除
	unset($_SESSION['s_my_partner']);
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>
<? page_header() ?>
<? menu_start() ?>

<?
if (!$msg) {
?>
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td align="center" class="m4"><?=$g_title?></td>
	</tr>
	<tr>
		<td align="center"><br>
			<p class="msg">リクルートジョブを更新しました。</p>
			<p><input type="button" value="　戻る　" onclick="location.href='<?=get_return_page("r_job.php?pjt_id=$pjt_id")?>'"></p>
		</td>
	</tr>
</table>
<?
} else
	error_msg($msg);
?>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
