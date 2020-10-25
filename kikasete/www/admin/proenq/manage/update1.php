<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｐｒｏアンケート更新処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/pro_enquete.php");
include("$inc/get_form.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/error_msg.php");

set_global('proenquete', 'Ｐｒｏアンケート管理', 'Ｐｒｏアンケート更新', BACK_TOP);

// セッション処理
$pro_enq = &$_SESSION['ss_pro_enq'];
if ($pro_enq->pro_enq_no != $pro_enq_no)
    redirect("show.php?marketer_id=$marketer_id&pro_enq_no=$pro_enq_no");

// フォームから入力データ取得
$enquete = &$pro_enq->enquete;
$enquete2 = &$pro_enq->enquete2;

$pro_enq->finding_flag = $_POST['finding_flag'];
$enquete->enq_kind = $pro_enq->finding_flag == DBTRUE ? 5 : 6;
$enquete->title = trim($_POST['title']);
$enquete2->title = trim($_POST['title']);
$enquete->enq_type = 2;
$enquete->start_date_y = get_number($_POST['start_date_y']);
$enquete->start_date_m = get_number($_POST['start_date_m']);
$enquete->start_date_d = get_number($_POST['start_date_d']);
$enquete->end_date_y = get_number($_POST['end_date_y']);
$enquete->end_date_m = get_number($_POST['end_date_m']);
$enquete->end_date_d = get_number($_POST['end_date_d']);
$enquete->end_date_h = get_number($_POST['end_date_h']);
if (isset($_POST['ans_cond'])) {
	$ans_cond_ary = array();
	foreach ($_POST['ans_cond'] as $ans_cond) {
		if ($ans_cond != '')
			$ans_cond_ary[] = $ans_cond;
	}
	$pro_enq->ans_cond = join("\n", $ans_cond_ary);
}

$enquete->point = $_POST['point'];
if ($pro_enq->finding_flag == DBTRUE)
    $enquete2->point = $_POST['point2'];

$enquete->status = $_POST['status'];
// 更新時、「事務局確認中」のステータスになるよう変更 2006/03/28
if( $enquete->status == 3) {
    $enquete->sub_status = 1;
}

// 入力チェック
if ($enquete->title == '' )
    $msg[] = 'アンケートタイトルを入力してください。';

if ($enquete->enq_type == '')
    $msg[] = 'アンケート種類を選択してください。';

$start_date_ok = true;
$end_date_ok = true;

if ($enquete->start_date_y == '') {
    $msg[] = '開始日時の年を入力してください。';
    $start_date_ok = false;
} elseif (!check_num($enquete->start_date_y, 2000, 2999)) {
    $msg[] = '開始日時の年が正しく入力されていないようです。';
    $start_date_ok = false;
}

if ($enquete->start_date_m == '') {
    $msg[] = '開始日時の月を入力してください。';
    $start_date_ok = false;
} elseif (!check_num($enquete->start_date_m, 1, 12)) {
    $msg[] = '開始日時の月が正しく入力されていないようです。';
    $start_date_ok = false;
}

if ($enquete->start_date_d == '') {
    $msg[] = '開始日時の日を入力してください。';
    $start_date_ok = false;
} elseif (!check_num($enquete->start_date_d, 1, 31)) {
    $msg[] = '開始日時の日が正しく入力されていないようです。';
    $start_date_ok = false;
}

if ($start_date_ok) {
    $start_date = strtotime("$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d");
    if ($start_date == -1) {
        $msg[] = '開始日付が正しく入力されていないようです。';
        $start_date_ok = false;
    } elseif (date('Y', $start_date) != $enquete->start_date_y || date('n', $start_date) != $enquete->start_date_m || date('j', $start_date) != $enquete->start_date_d) {
        $msg[] = '開始日付が正しく入力されていないようです。';
        $start_date_ok = false;
    }
}

if ($enquete->end_date_y == '') {
    $msg[] = '終了日時の年を入力してください。';
    $end_date_ok = false;
} elseif (!check_num($enquete->end_date_y, 2000, 2999)) {
    $msg[] = '終了日時の年が正しく入力されていないようです。';
    $end_date_ok = false;
}

if ($enquete->end_date_m == '') {
    $msg[] = '終了日時の月を入力してください。';
    $end_date_ok = false;
} elseif (!check_num($enquete->end_date_m, 1, 12)) {
    $msg[] = '終了日時の月が正しく入力されていないようです。';
    $end_date_ok = false;
}

if ($enquete->end_date_d == '') {
    $msg[] = '終了日時の日を入力してください。';
    $end_date_ok = false;
} elseif (!check_num($enquete->end_date_d, 1, 31)) {
    $msg[] = '終了日時の日が正しく入力されていないようです。';
    $end_date_ok = false;
}

if ($enquete->end_date_h == '') {
    $msg[] = '終了日時の時を入力してください。';
    $end_date_ok = false;
} elseif (!check_num($enquete->end_date_h, 0, 23)) {
    $msg[] = '終了日時の時が正しく入力されていないようです。';
    $end_date_ok = false;
}

if ($end_date_ok) {
    $end_date = strtotime("$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d");
    if ($end_date == -1) {
        $msg[] = '終了日付が正しく入力されていないようです。';
        $end_date_ok = false;
    } else if ($start_date_ok && $end_date < $start_date) {
        $msg[] = '終了日時は開始日時以降を入力してください。';
        $end_date_ok = false;
    } elseif (date('Y', $end_date) != $enquete->end_date_y || date('n', $end_date) != $enquete->end_date_m || date('j', $end_date) != $enquete->end_date_d) {
        $msg[] = '終了日付が正しく入力されていないようです。';
        $end_date_ok = false;
    }
}

// エラー無しなら次ページへ
if (!$msg) {
    // Proアンケート保存
    $pro_enq->save_enquete = true;
    $pro_enq->save_enquete2 = true;
    $pro_enq->write_db();
    $pro_enq_no = $pro_enq->pro_enq_no;

    // 更新時、アンケート回答データを削除する 2006/03/28 BTI
    if (  !(($enquete->status >= 3 && $enquete->status <= 12) && $enquete->sub_status == 3) ) {
        $sql = "DELETE FROM t_answer WHERE an_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
        db_exec($sql);
        $sql = "DELETE FROM t_ans_select WHERE as_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
        db_exec($sql);
        $sql = "DELETE FROM t_ans_matrix WHERE ax_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
        db_exec($sql);
    }

    // セッション変数削除
    unset($_SESSION['ss_pro_enq']);
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

<br>
<?
if (!$msg) {
?>
<div align="center">
<form>
<p>Ｐｒｏアンケートの実施概要を設定しました。</p>
<input type="button" value="　戻る　" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
</form>
</div>
<?
} else
    error_msg($msg, 'edit1.php?edit=1');
?>
<? page_footer() ?>
</body>
</html>
