#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteͽ�����ϥڡ���
' Content:ͽ�����������å�����
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/reserve.php");
include("$inc/decode.php");

// ���å���󤫤�ͽ��������
session_start();
if ($reserve == null)
	redirect('timeout.php');

// ���顼�ꥻ�å�
$reserve->error = null;

// ɽ��ǯ�����
if ($ym) {
	$ym_ary = explode('/', $ym);
	$reserve->year = $ym_ary[0];
	$reserve->month = $ym_ary[1];
}

// ͽ���˾����
$reserve->choice_num = 0;
for ($i = 0; $i < MAX_CHOICE; $i++) {
	if ($choice[$i] != '') {
		$ary = explode(',', $choice[$i]);
		$reserve->choice[$i]->room_id = $ary[0];
		$reserve->choice[$i]->room = decode_room($ary[0]);
		$reserve->choice[$i]->date = $ary[1];
		$reserve->choice[$i]->start_time = $ary[2];
		$reserve->choice[$i]->end_time = $ary[3];
		$reserve->choice_num++;
		$reserve_select = true;
	} else
		$reserve->choice[$i]->room_id = 0;
}

// ͽ���˾���ϥ����å�
if (!$reserve_select && $next == 'con_form.php') {
	$reserve->error['reserve_select'] = '��ͽ���˾���������Ӥ���������';
	redirect('sche_form.php');
}

// ���ڡ�����
redirect($next);
?>