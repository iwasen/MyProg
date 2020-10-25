<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�У�異�󥱡��ȹ�������
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/pro_search.php");
include("$inc/pro_enq_target.php");
include("$inc/enquete.php");
include("$inc/pro_enquete.php");
include("$inc/mye_temp.php");
include("$inc/get_form.php");
include("$inc/image.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// �ե����फ��ǡ�������
function get_form_data(&$pro_enq) {
    $enquete = &$pro_enq->enquete;
    $enquete2 = &$pro_enq->enquete2;
    $search = &$pro_enq->search;
    $cell = &$pro_enq->cell;

    $enquete->status = $_POST['status'];
    $enquete2->title = trim($_POST['title']);
    $enquete->start_date_y = get_number($_POST['start_date_y']);
    $enquete->start_date_m = get_number($_POST['start_date_m']);
    $enquete->start_date_d = get_number($_POST['start_date_d']);
    $enquete->start_date_h = get_number($_POST['start_date_h']);
    $enquete->end_date_y = get_number($_POST['end_date_y']);
    $enquete->end_date_m = get_number($_POST['end_date_m']);
    $enquete->end_date_d = get_number($_POST['end_date_d']);
    $enquete->end_date_h = get_number($_POST['end_date_h']);
    if (isset($_POST['ans_cond']))
        $pro_enq->ans_cond = join("\n", $_POST['ans_cond']);

    if ($pro_enq->finding_flag == DBTRUE) {
        $pro_enq->res_and_or = $_POST['res_and_or'];
        $pro_enq->appearance_ratio = get_number($_POST['appearance_ratio']);

        $pro_enq->res_cond = array();
        if (is_array($_POST['and_or'])) {
            foreach ($_POST['and_or'] as $qno => $and_or) {
                $pro_enq->res_cond[$qno]->and_or = $and_or;
                $pro_enq->res_cond[$qno]->not_cond = $_POST['not_cond'][$qno];
                $pro_enq->res_cond[$qno]->select_no = $_POST['select_no'][$qno] ? join(',', $_POST['select_no'][$qno]) : '';
            }
        } else
            $pro_enq->res_cond = null;
    }

    for ($i = 1; $i <= $enquete2->max_question_no; $i++) {
        $question = &$enquete2->question[$i];

        $question->question_text = $_POST["question_text$i"];
        $question->question_type = $_POST["question_type$i"];

		if ($question->question_type == 1 || $question->question_type == 2)
			$question->fa_flag = $_POST["fa_flag$i"];
		else
			$question->fa_flag = '';

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

        $question->must_flag = $_POST["must_flag$i"];
        $question->dup_flag = $_POST["dup_flag$i"];

        $question->sel_text = get_sel($_POST["sel_text$i"]);
        $question->hyousoku = get_sel($_POST["hyousoku$i"]);
        $question->hyoutou = get_sel($_POST["hyoutou$i"]);

		// NA�������ʸ����
		$question->pre_text = $_POST["pre_text$i"];
		$question->post_text = $_POST["post_text$i"];

        // �ե꡼���������
        $last_no = end(array_keys($question->sel_text));
        if ($question->fa_flag == DBTRUE)
            $question->fa_sno = $last_no;

        // ��¾�����
        $question->ex_sno = 0;
        if ($_POST["ex_flag$i"]) {
            $question->ex_sno = $last_no + 1;
            $question->sel_text[$last_no + 1] = $_POST["ex_sel$i"];
        }
    }

    $enquete->point = $_POST['point'];
    if ($pro_enq->finding_flag == DBTRUE)
        $enquete2->point = $_POST['point2'];

    $enquete2->description = $_POST['description'];

    $pro_enq->sample_num = get_number($_POST['sample_num']);
    $search->sex = (count($_POST['sex']) == 1) ? $_POST['sex'][0] : '';
    $search->type = $_POST['type'];
    $pro_enq->age_type = $_POST['age_type'];
    if ($pro_enq->age_type == '1') {
        $search->age_cd = get_multi_data($_POST['age_cd']);
        $search->age_from = '';
        $search->age_to = '';
    } elseif ($pro_enq->age_type == '2') {
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

    $cell->send_num = $_POST['cell'][$cell->age_option];

    $pro_enq->mail_title = $_POST['mail_title'];
    $pro_enq->mail_header = $_POST['mail_header'];
    $pro_enq->mail_contents = $_POST['mail_contents'];
    $pro_enq->mail_footer = $_POST['mail_footer'];

    $pro_enq->save_enquete = true;
    $pro_enq->save_enquete2 = true;
    $pro_enq->save_search = true;
    $pro_enq->save_res_cond = true;
}

// ��������
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
// �ͥåȥ�����̥ơ��֥빹��
function write_pro_enq_target(&$pro_enq, $marketer_id, $pro_enq_no) {
    $search =& $pro_enq->search;
    $search->marketer_id = $marketer_id;
    $search->pro_enq_no = $pro_enq_no;
    $search->read_db($pro_enq->search_id);
//    $search->get_monitor_num($marketer_id, $pro_enq_no); // 2006/06/26
    $target_flg = $search->get_target_flg();
    $pro_enq_target =& new pro_enq_target_class;
    $pro_enq_target->enquete_id = $pro_enq->enquete_id;
    $pro_enq_target->target_flg = $target_flg;
    $pro_enq_target->write_db();
    if ($pro_enq->enquete_id != $pro_enq->enquete2_id) {
        $pro_enq_target->enquete_id = $pro_enq->enquete2_id;
        $pro_enq_target->write_db();
    }
}

// �ᥤ�����
set_global('proenquete', '�У�異�󥱡��ȴ���', '�У�異�󥱡��ȹ���', BACK_TOP);

switch ($next_action) {
case 'app':
    $pro_enq = new pro_enquete_class;
    $pro_enq->read_db($marketer_id, $pro_enq_no);
    $enquete = &$pro_enq->enquete;

    // ��̾��̤����ʤ�ǥե���Ȥ򥻥å�
    if ($pro_enq->mail_title == '')
        $pro_enq->mail_title = $enquete->title;

    // �᡼�뤬̤����ʤ�ǥե���Ȥ򥻥å�
    if ($pro_enq->mail_contents == '')
        get_enq_body($pro_enq, $pro_enq->mail_header, $pro_enq->mail_contents, $pro_enq->mail_footer);

    // ���󥱡�������ʸ��̤����ʤ�ǥե���Ȥ򥻥å�
    if ($enquete->description == '')
        $enquete->description = get_enq_description($pro_enq);

    // ���������򥻥å�
    $enquete->start_date_y = $start_date_y;
    $enquete->start_date_m = $start_date_m;
    $enquete->start_date_d = $start_date_d;
    $enquete->start_date_h = $start_date_h;

    $enquete->status = 4;

    $pro_enq->save_enquete = true;
    $pro_enq->write_db();

    // ��������(t_answer)��ͭ���ե饰��FALSE���ѹ�
    $flag = sql_bool(DBFALSE);
    $sql = "UPDATE t_answer SET an_valid_flag=$flag WHERE an_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
    db_exec($sql);

    $msg = '�У�異�󥱡��Ȥ�ǧ���ޤ�����';
    $back = "location.href='list.php'";
    break;
case 'update':
    $pro_enq = new pro_enquete_class;
    $pro_enq->read_db($marketer_id, $pro_enq_no);
    get_form_data($pro_enq);
    $pro_enq->write_db();

    // �����������󥱡��Ȳ����ǡ����������� 2006/03/07 BTI
    $sql = "DELETE FROM t_answer WHERE an_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
    db_exec($sql);
    $sql = "DELETE FROM t_ans_select WHERE as_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
    db_exec($sql);
    $sql = "DELETE FROM t_ans_matrix WHERE ax_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
    db_exec($sql);
    // �ͥåȥ�����̥ơ��֥빹�� 2006/03/07 BTI
    write_pro_enq_target($pro_enq, $marketer_id, $pro_enq_no);

    $msg = '�У�異�󥱡��Ȥ򹹿����ޤ�����';
    $back = "location.href='list.php'";
    break;
case 'delete':
    $sql = "UPDATE t_enquete SET en_status=9 WHERE en_enquete_id=$enquete_id";
    db_exec($sql);
    $msg = '�У�異�󥱡��Ȥ������ޤ�����';
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
<p><input type="button" id="ok" value="����롡" onclick="<?=$back?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
