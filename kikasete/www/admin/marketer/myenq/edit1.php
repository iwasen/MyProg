<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ͣ����󥱡����ѹ�����
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/my_enquete.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// ���󥱡��ȼ»ܾ��������
function select_enq_status($sel) {
	if ($sel <= 4) {
		echo "<option ", value_selected('0', $sel), ">������</option>";
		echo "<option ", value_selected('1', $sel), ">������λ</option>";
		echo "<option ", value_selected('2', $sel), ">������</option>";
		echo "<option ", value_selected('3', $sel), ">��̳�ɥ����å���</option>";
		echo "<option ", value_selected('4', $sel), ">��ǧ�Ѥ�</option>";
		echo "<option ", value_selected('8', $sel), ">�ƿ���</option>";
	} else {
		echo "<option ", value_selected('5', $sel), ">�»���</option>";
		echo "<option ", value_selected('6', $sel), ">������</option>";
		echo "<option ", value_selected('7', $sel), ">��λ</option>";
	}
	echo "<option ", value_selected('9', $sel), ">����Ѥ�</option>";
}

set_global('myenquete', '�ͣ����󥱡��ȴ���', '�ͣ����󥱡��Ⱦ����ѹ�', BACK_TOP);

// ���å�������
if ($edit) {
	$myenq = &$_SESSION['ss_my_enq'];
	$my_enq_no = $myenq->my_enq_no;
	$marketer_id = $myenq->marketer_id;
} else {
	$_SESSION['ss_my_enq'] = new my_enquete_class;
	$myenq = &$_SESSION['ss_my_enq'];
	$myenq->read_db($marketer_id, $my_enq_no);
}

$enquete = &$myenq->enquete;
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

<div align="center">
<form method="post" name="form1" action="update1.php">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0">���»ܳ���</td>
	</tr>
	<tr>
		<td class="m1" width="20%">���󥱡��ȥ����ȥ�</td>
		<td class="n1" width="80%"><input type="text" name="title" size="30" <?=value($enquete->title)?>>�ˤĤ��ƤΥ��󥱡���</td>
	</tr>
	<tr>
		<td class="m1">��������</td>
		<td class="n1">
			<input class="number" type="text" name="start_date_y" size="4" maxlength="4" <?=value($enquete->start_date_y)?>>ǯ
			<input class="number" type="text" name="start_date_m" size="2" maxlength="2" <?=value($enquete->start_date_m)?>>��
			<input class="number" type="text" name="start_date_d" size="2" maxlength="2" <?=value($enquete->start_date_d)?>>��
			<input class="number" type="text" name="start_date_h" size="2" maxlength="2" <?=value($enquete->start_date_h)?>>��
		</td>
	</tr>
	<tr>
		<td class="m1">��λ����</td>
		<td class="n1">
			<input class="number" type="text" name="end_date_y" size="4" maxlength="4" <?=value($enquete->end_date_y)?>>ǯ
			<input class="number" type="text" name="end_date_m" size="2" maxlength="2" <?=value($enquete->end_date_m)?>>��
			<input class="number" type="text" name="end_date_d" size="2" maxlength="2" <?=value($enquete->end_date_d)?>>��
			<input class="number" type="text" name="end_date_h" size="2" maxlength="2" <?=value($enquete->end_date_h)?>>��
		</td>
	</tr>
	<tr>
		<td class="m1">�������</td>
		<td class="n1">
<?
$ans_cond = explode("\n", $myenq->ans_cond);
for ($i = 0; $i < 5; $i++) {
?>
				<input type="text" name="ans_cond[]" size=50 maxlength=100 <?=value($ans_cond[$i])?>><br>
<?
}
?>
		</td>
	</tr>
	<tr>
		<td class="m1">�ݥ���ȿ�</td>
		<td class="n1">
			<input class="number" type="text" name="point" size="3" maxlength="3" <?=value($enquete->point)?>>�ݥ����
		</td>
	</tr>
	<tr>
		<td class="m1">�»ܾ���</td>
		<td class="n1"><select name="status"><? select_enq_status($enquete->status) ?></select></td>
	</tr>
</table>
<br>
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="button" value="����롡" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>'">
<input type="hidden" name="next_action">
<input type="hidden" name="marketer_id" <?=value($marketer_id)?>>
<input type="hidden" name="my_enq_no" <?=value($my_enq_no)?>>
</form>

<? page_footer() ?>
</body>
</html>
