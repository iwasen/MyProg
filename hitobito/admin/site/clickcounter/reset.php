<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ����å������󥿡��ꥻ�å�
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
include("$inc/data.php");
include("data.php");

// �ѥ�᡼������
$recruit_id = $_GET['recruit_id'];

// �ǡ����������饹����
$data = new recruit_data_class();

$data->reset_click_counter($recruit_id);

redirect("edit.php?recruit_id=$recruit_id");
?>