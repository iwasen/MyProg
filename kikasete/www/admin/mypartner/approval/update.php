<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:My�ѡ��ȥʡ����ꥯ�롼�Ⱦ�ǧ����
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enq_temp.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// �ᥤ�����
set_global('mypartner', 'My�ѡ��ȥʡ�����', 'My�ѡ��ȥʡ���ǧ', BACK_TOP);

// My�ѡ��ȥʡ��������
$myp = new my_partner_class;
$myp->read_db($pjt_id);

db_begin_trans();

if ($flag == '1') {
	// �ץ������ȤΥ��ơ��������å�
	$myp->status = MPS_REC_SHOUNIN;

	// ��̾��̤����ʤ�ǥե���Ȥ򥻥å�
	if ($myp->subject == '')
		$myp->subject = $myp->enquete->title;

	// �᡼�뤬̤����ʤ�ǥե���Ȥ򥻥å�
	if ($myp->body == '')
		get_enq_body($myp, $myp->header, $myp->body, $myp->footer);

	// �ꥯ�롼�ȳ������������
	$myp->recruit_start_y = $start_date_y;
	$myp->recruit_start_m = $start_date_m;
	$myp->recruit_start_d = $start_date_d;
	$myp->recruit_start_h = $start_date_h;
	$myp->recruit_start = sprintf("%d/%d/%d %d:0:0", $start_date_y, $start_date_m, $start_date_d, $start_date_h);

	// My�ѡ��ȥʡ����󹹿�
	$myp->write_db();

	// ���󥱡��ȤΥ��ơ������򥻥å�
	$rec['en_status'] = sql_number(4);

	// ���󥱡��ȳ��Ͻ�λ�����򥻥å�
	$rec['en_start_date'] = sql_date($myp->add_send_date == '' ? $myp->recruit_start : $myp->add_send_date);
	$rec['en_end_date'] = sql_date($myp->recruit_end);

	// ���󥱡�������ʸ��̤����ʤ�ǥե���Ȥ򥻥å�
	if ($myp->enquete->description == '')
		$rec['en_description'] = sql_char(get_enq_description($myp));

	// ���󥱡��ȹ���
	db_update('t_enquete', $rec, "en_enquete_id=$myp->enquete_id");

	$msg = '�ͣ��ѡ��ȥʡ���ǧ���ޤ�����';
} elseif ($flag == '2') {
	// �ץ������ȤΥ��ơ��������å�
	$myp->status = MPS_REC_JUNBI;

	// My�ѡ��ȥʡ����󹹿�
	$myp->write_db();

	$msg = '�ͣ��ѡ��ȥʡ��򺹤��ᤷ�ޤ�����';
}

db_commit_trans();
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
<p><input type="button" id="ok" value="����롡" onclick="location.href='list.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
