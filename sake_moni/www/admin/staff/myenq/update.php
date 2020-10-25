<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:Ｍｙアンケート更新処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/my_enquete.php");
include("$inc/mye_temp.php");
include("$inc/get_form.php");
include("$inc/image.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");


// フォームからデータ取得
function get_form_data(&$myenq) {
	$enquete = &$myenq->enquete;
	$search = &$myenq->search;

	$enquete->status = $_POST['status'];
	$enquete->title = trim($_POST['title']);
	$enquete->enq_type = $_POST['enq_type'];
	$enquete->start_date_y = get_number($_POST['start_date_y']);
	$enquete->start_date_m = get_number($_POST['start_date_m']);
	$enquete->start_date_d = get_number($_POST['start_date_d']);
	$enquete->start_date_h = get_number($_POST['start_date_h']);
	$enquete->end_date_y = get_number($_POST['end_date_y']);
	$enquete->end_date_m = get_number($_POST['end_date_m']);
	$enquete->end_date_d = get_number($_POST['end_date_d']);
	$enquete->end_date_h = get_number($_POST['end_date_h']);
	if (isset($_POST['ans_cond']))
		$myenq->ans_cond = join("\n", $_POST['ans_cond']);

	for ($i = 1; $i <= $enquete->max_question_no; $i++) {
		$question = &$enquete->question[$i];

		$question->question_text = $_POST["question_text$i"];
		$question->question_type = $_POST["question_type$i"];
		$question->fa_flag = ($question->question_type == 1 || $question->question_type == 2) ? $_POST["fa_flag$i"] : '';

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

	$enquete->point = $_POST['point'];
	$enquete->description = $_POST['description'];

	$myenq->send_num = get_number($_POST['send_num']);
	$search->sex = (count($_POST['sex']) == 1) ? $_POST['sex'][0] : '';
	$myenq->age_type = $_POST['age_type'];
	if ($myenq->age_type == '1') {
		$search->age_cd = get_multi_data($_POST['age_cd']);
		$search->age_from = '';
		$search->age_to = '';
	} elseif ($myenq->age_type == '2') {
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
	$search->chain = get_multi_data($_POST['chain']);

	$myenq->mail_title = $_POST['mail_title'];
	$myenq->mail_header = $_POST['mail_header'];
	$myenq->mail_contents = $_POST['mail_contents'];
	$myenq->mail_footer = $_POST['mail_footer'];

	$myenq->save_enquete = true;
	$myenq->save_search = true;
}

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
set_global('staff', 'スタッフ管理｜Ｍｙアンケート管理', 'Ｍｙアンケート更新', BACK_TOP);

switch ($next_action) {
case 'app':
	$myenq = new my_enquete_class;
	$myenq->read_db($staff_id, $my_enq_no);
	$enquete = &$myenq->enquete;

	// 件名が未設定ならデフォルトをセット
	if ($myenq->mail_title == '')
		$myenq->mail_title = $enquete->title;

	// メールが未設定ならデフォルトをセット
	if ($myenq->mail_contents == '')
		get_enq_body($myenq, $myenq->mail_header, $myenq->mail_contents, $myenq->mail_footer);

	// アンケート説明文が未設定ならデフォルトをセット
	if ($enquete->description == '')
		$enquete->description = get_enq_description($myenq);

	$enquete->status = 4;

	$myenq->save_enquete = true;
	$myenq->write_db();

	$msg = 'Ｍｙアンケートを承認しました。';
	$back = "location.href='list.php'";
	break;
case 'update':
	$myenq = new my_enquete_class;
	$myenq->read_db($staff_id, $my_enq_no);
	get_form_data($myenq);
	$myenq->write_db();
	$msg = 'Ｍｙアンケートを更新しました。';
	$back = "location.href='list.php'";
	break;
case 'delete':
	$sql = "UPDATE t_enquete SET en_status=9 WHERE en_enquete_id=$enquete_id";
	db_exec($sql);
	$msg = 'Ｍｙアンケートを削除しました。';
	$back = "location.href='list.php'";
	break;
default:
	redirect('list.php');
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$back?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
