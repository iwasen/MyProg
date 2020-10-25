<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ʥӥڡ������ơ����������ڡ���
 *
 *
 * @package
 * @author
 * @version
 */

$top = '../..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/header.php");
include("$inc/mail.php");
include("$inc/navi_mail.php");

// ���ʥӥڡ������������
function init_navi_page($navi_page_id) {
	db_begin_trans();

	// �磻�磻��ĤΥǥե��������
	$sql = "SELECT nvp_room_id FROM t_navi_page WHERE nvp_navi_page_id=$navi_page_id";
	$room_id = db_fetch1($sql);
	if ($room_id) {
		$rec['rom_header'] = sql_char(db_fetch1("SELECT rmh_header FROM m_room_header WHERE rmh_header_id=1"));
		$rec['rom_footer'] = sql_char(db_fetch1("SELECT rmh_footer FROM m_room_header WHERE rmh_header_id=1"));
		$rec['rom_regist_mail'] = sql_char(db_fetch1("SELECT mtm_body FROM m_mail_template WHERE mtm_template_id='room_regist'"));
		$rec['rom_resign_mail'] = sql_char(db_fetch1("SELECT mtm_body FROM m_mail_template WHERE mtm_template_id='room_resign'"));
		$rec['rom_guest_regist_mail'] = sql_char(db_fetch1("SELECT mtm_body FROM m_mail_template WHERE mtm_template_id='room_guest'"));
		db_update('t_room', $rec, "rom_room_id=$room_id");
	}

	// ���ޥ�������Ⱦ���쥳��������
	$sql = "SELECT nvp_melmaga_id FROM t_navi_page WHERE nvp_navi_page_id=$navi_page_id";
	$melmaga_id = db_fetch1($sql);
	if ($melmaga_id) {
		$sql = "SELECT COUNT(*) FROM c_melmaga_count WHERE mmn_melmaga_id=$melmaga_id";
		if (db_fetch1($sql) == 0) {
			$rec['mmn_melmaga_id'] = sql_number($melmaga_id);
			db_insert('c_melmaga_count', $rec);
		}
	}

	db_commit_trans();
}

// �ѥ�᡼������
$navi_page_id = $_GET['navi_page_id'];
$status = $_GET['status'];

// �ڡ����إå���������
$header = new header_class('�ʥӱ������', BACK_TOP);

// ���祹�ơ���������
$rec['nvp_status'] = sql_number($status);
db_update('t_navi_page', $rec, "nvp_navi_page_id=$navi_page_id");


switch ($status) {
case 0:
	$msg = '�ʥӱ����̤��ǧ���ᤷ�ޤ�����';
	break;
case 1:
	init_navi_page($navi_page_id);
	send_navi_mail($navi_page_id, 'navi_apply_ok');
	$msg = '�ʥӱ����ǧ���ޤ�������ǧ�᡼����ۿ����ޤ�����';
	break;
case 2:
	send_navi_mail($navi_page_id, 'navi_apply_ng');
	$msg = '�ʥӱ�����Ǥꤷ�ޤ��������Ǥ�᡼����ۿ����ޤ�����';
	break;
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body onLoad="document.all.ok.focus()">

<?php $header->page_header(); ?>

<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onClick="location.href='list.php'"></p>

<?php $header->page_footer(); ?>

</body>
</html>
