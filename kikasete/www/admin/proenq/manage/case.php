<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�У�異�󥱡���ɽ������
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/pro_enquete.php");
include("$inc/my_search.php");

$pro_enq = new pro_enquete_class;
$pro_enq->read_db($marketer_id, $pro_enq_no);
$enquete = &$pro_enq->enquete;
$search = &$pro_enq->search;

// ��������̳�ɥ����å�����ѹ�
if ($check == '1' && $enquete->status == 2) {
	$sql = "UPDATE t_enquete SET en_status=3 WHERE en_enquete_id=$pro_enq->enquete_id";
	db_exec($sql);
}

set_global('proenquete', '�У�異�󥱡��ȴ���', '�У�異�󥱡���ɽ��', BACK_TOP);

if ($marketer_id != ''){
	$where = "mr_marketer_id=$marketer_id";
}

if ($where) {
	$sql = "SELECT * FROM t_marketer WHERE $where";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
}

if ($nrow) {
	$fetch = pg_fetch_object($result, 0);
}

if ($pro_enq_no) {
	$sql2 = "SELECT mep_enquete_id,mep_real_send FROM t_pro_enquete WHERE mep_marketer_id=$marketer_id AND mep_pro_enq_no=$pro_enq_no";
	$result2 = db_exec($sql2);
	if (pg_numrows($result2)) {
		$fetch2 = pg_fetch_object($result2, 0);
		$send_num2 = number_format($fetch2->mep_real_send);
		$enquete_id2 = $fetch2->mep_enquete_id;
	} else {
	$send_num2 = 0;
	}
}

if ($enquete_id2) {
	$sql3 = "SELECT COUNT(*) FROM t_answer WHERE an_enquete_id=$enquete_id2 AND an_valid_flag";
	$result3 = db_exec($sql3);
	if (pg_numrows($result3)) {
		$fetch3 = pg_fetch_row($result3, 0);
		$recv_count = number_format($fetch3[0]);
	} else{
		$recv_count = 0;
	}
	if($send_num2){
		$receive_rate =round($recv_count/$send_num2*100,1);
	}else{
		$receive_rate =0;
	}
}
?>

<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	return confirm("���ΣУ�異�󥱡��Ȥ�ǧ���ޤ���������Ǥ�����");
}
function onclick_csv() {
	location.href = "csv.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>";
}
function onclick_mail_prev() {
	location.href = "mail_prev.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>";
}
function onclick_web_prev() {
	location.href = "web_prev.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>";
}
function onclick_test_mail() {
	location.href = "test_mail.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>";
}
function onclick_search() {
	window.open("search.php?search_id=<?=$pro_enq->search_id?>", "search", "width=300,height=130");
}
function onclick_edit() {
	location.href = "edit.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>";
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" >�����󥱡��ȳ���<br>
		----------------------------------------------------------------------
		</td>
	</tr>
	<tr>
		<td class="n1" >�����饤����ȴ��̾����<?=htmlspecialchars($fetch->mr_kinmu_name)?></td>
	</tr>
	<tr>
		<td class="n1" >��ô����̾��������������<?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></td>
	</tr>
	<tr>
		<td class="n1" >�����󥱡��ȼ��ࡡ������<?=decode_enq_type($enquete->enq_type)?></td>
	</tr>
	<tr>
		<td class="n1" >������������������������<?=$enquete->start_date_y?>ǯ<?=$enquete->start_date_m?>��<?=$enquete->start_date_d?>��</td>
	</tr>
	<tr>
		<td class="n1" >����λ������������������<?=$enquete->end_date_y?>ǯ<?=$enquete->end_date_m?>��<?=$enquete->end_date_d?>����<?=$enquete->end_date_h?>��</td>
	</tr>
	<tr>
		<td class="n1" >���ݥ���ȿ�������������<?=$enquete->point?>�ݥ����</td>
	</tr>
	<tr>
		<td class="n1" >��ȯ��������������������<?=$send_num2?>��</td>
	</tr>
	<tr>
		<td class="n1" >���ֿ�������������������<?=$recv_count?>��</td>
	</tr>
	<tr>
		<td class="n1" >������Ψ����������������<?=$receive_rate?>��</td>
	</tr>
	<tr>
		<td>----------------------------------------------------------------------<br></td>
	</tr>
	<tr>
		<td class="m0"><br>�����󥱡�������</td>
	</tr>
	<tr>
		<td>----------------------------------------------------------------------</td>
	</tr>
	<tr>
		<td class="n1">�����󥱡��ȥ����ȥ롡����<?=htmlspecialchars($enquete->title)?><br></td>
	</tr>
<!--
	<tr>
		<td class="n1" colspan=2>����ʸ������������������<?=nl2br(htmlspecialchars($enquete->description))?></span></td>
	</tr>
-->
	<tr>
		<td class="n1">��������ܡ������</td>
	</tr>
<?
if (is_array($enquete->question)) {
	foreach ($enquete->question as $qno => $question) {
?>
	<tr>
		<td class="n1" colspan=2>
		-------------------------------------------<br>
		����<?=mb_convert_kana($qno, 'N')?><br>
			<?=nl2br(htmlspecialchars($question->question_text))?><br>
			��<?=decode_question_type($question->question_type)?>��<br>

<?
		if (is_array($question->sel_text)) {
			foreach ($question->sel_text as $sno => $sel_text) {
?>
			<br><?=$sno?>��<?=htmlspecialchars($sel_text)?>
<?
			}
		}
?>

		<br>-------------------------------------------<br>
		</span><br>
		</td>
	</tr>
<?
	}
}
?>
	<tr>
		<td><br></td>
	</tr>

	<tr>
		<td class="m0">�������������</td>
	</tr>
	<tr>
		<td>----------------------------------------------------------------------</td>
	</tr>
	<tr>
		<td class="n1">�����̡���������<?=decode_sex($search->sex, '<br>')?></td>
	</tr>
	<tr>
		<td class="n1">��ǯ�塡��������<? disp_age2($search->age_cd, $search->age_from, $search->age_to) ?></td>
	</tr>
	<tr>
		<td class="n1">��̤������������<?=decode_mikikon($search->mikikon, '<br>')?></td>
	</tr>
	<tr>
		<td class="n1">���ｻ�ϰ衡����<? disp_area($search->jitaku_area) ?></td>
	</tr>
	<tr>
		<td class="n1">�����ȡ���������<? disp_shokugyou($search->shokugyou) ?></td>
	</tr>
	<tr>
		<td class="n1">������ӥˡ�����<? disp_conveni($search->conveni) ?></td>
	</tr>
	<tr>
		<td class="n1">�������ѡ�������<? disp_super($search->super) ?></td>
	</tr>
	<tr>
		<td class="n1">����̣�����롧<? disp_genre($search->genre) ?></td>
	</tr>
	<tr>
		<td>----------------------------------------------------------------------</td>
	</tr>
</table>
<br>


<input type="hidden" name="next_action">
<input type="hidden" name="marketer_id" value="<?=$marketer_id?>">
<input type="hidden" name="pro_enq_no" value="<?=$pro_enq_no?>">
<input type="hidden" name="enquete_id" value="<?=$pro_enq->enquete_id?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
