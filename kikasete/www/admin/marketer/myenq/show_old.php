<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ͣ����󥱡���ɽ������
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/my_enquete.php");
include("$inc/my_search.php");
include("$inc/mye_temp.php");
include("$inc/ma_profile.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// �ϰ�̾
function decode_area3($code) {
	if ($code != '') {
		$sql = "SELECT ar3_area3_name FROM m_area3 WHERE ar3_area3_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

$myenq = new my_enquete_class;
$myenq->read_db($marketer_id, $my_enq_no);
$enquete = &$myenq->enquete;
$search = &$myenq->search;
$cell = &$myenq->cell;

// ����ʸ�����ꤵ��Ƥ��ʤ����ϥƥ�ץ졼�Ȥ�ɽ��
$description = ($enquete->description != '') ? $enquete->description : get_enq_description($myenq);

// �᡼�륿���ȥ뤬���ꤵ��Ƥ��ʤ����ϥ��󥱡��ȥ����ȥ��ɽ��
$mail_title = ($myenq->mail_title != '') ? $myenq->mail_title : $enquete->title;

// �᡼��ʬ�����ꤵ��Ƥ��ʤ����ϥƥ�ץ졼�Ȥ�ɽ��
if ($myenq->mail_contents == '')
	get_enq_body($myenq, $mail_header, $mail_contents, $mail_footer);
else {
	$mail_header = $myenq->mail_header;
	$mail_contents = $myenq->mail_contents;
	$mail_footer = $myenq->mail_footer;
}

// ��������̳�ɥ����å�����ѹ�
if ($check == '1' && $enquete->status == 2) {
	$sql = "UPDATE t_enquete SET en_status=3 WHERE en_enquete_id=$myenq->enquete_id";
	db_exec($sql);
}

set_global('myenquete', '�ͣ����󥱡��ȴ���', '�ͣ����󥱡���ɽ��', BACK_TOP);
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
	return confirm("���Σͣ����󥱡��Ȥ�ǧ���ޤ���������Ǥ�����");
}
<?
// kim_start seq no 49
//������QR�Τߡ�����޶褭���case1���Ѥ���csv.php�ǰ�ư����
//������QR�Τ�,0,1������case2���Ѥ���csv��.php�ǰ�ư���롣
//���䡡case3:���Ѥ���csv3.php�ǰ�ư����
//������QR�Τߡ�����޶褭���case4:���Ѥ���csv4.php�ǰ�ư���롣
//������QR�Τ�,0,1������case5:���Ѥ���csv��.php�ǰ�ư���롣
//������DR�Τߡ�����޶褭���case6:���Ѥ���csv6.php�ǰ�ư���롣
//������DR�Τߡ�0,1������case7:���Ѥ���csv7.php�ǰ�ư���롣
?>
	function func_csv(){
		f=document.form1;
		switch(f.ss.value){
			case "1":location.href ="csvt.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>&mode=1";
			break;
			case "2":location.href ="csvt.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>&mode=2";
			break;
			case "3":location.href ="csvt.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>&mode=3";
			break;
			default://redirect("show.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>");
		}
	}
<?// kim_end49 ?>
<?
//seq no=50 kim_start �ؿ����ɲ�
?>
function onclick_dr_replyview(){
	<!--window.open('pe_recv_count_detail.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>','_new','menubar=no toolbar=no location=no scrollbars=no status=no resizable=no width=900 height=400 left=200  top=300');//-->
	location.href ="pe_recv_count_detail.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>&age_cd=<?=$search->age_cd?>";
}
<?
//kim_end50
?>
<?//kim_start seq no=49 ��ɬ�פʴؿ� ?>
function onclick_csv(type) {
	location.href = "csv.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>";
}
function onclick_csv2(type) {
	location.href = "csv2.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>";
}
function onclick_csv3(type) {
	location.href = "csv3.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>";
}
<?//kim_end seq no=49 ?>
function onclick_mail_prev() {
	location.href = "mail_prev.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>";
}
function onclick_web_prev() {
	location.href = "web_prev.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>";
}
function onclick_test_mail() {
	location.href = "test_mail.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>";
}
function onclick_search() {
	window.open("search.php?search_id=<?=$myenq->search_id?>", "search", "width=300,height=130");
}
function onclick_edit() {
	location.href = "edit.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>";
}
function onclick_mailTemplate() {
	location.href = "case.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>";
}
function onclick_shounin() {
	var f = document.form1;
	if (showModalDialog("shounin.php",f,"status:off;help:off;dialogWidth:350px;dialogHeight:150px"))
		f.submit();
}

//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%" frame="box">
	<tr>
		<td class="m0" colspan=3>��������������</td>
	</tr>
	<tr>
		<td class="m1" colspan="2" width="20%">���󥱡��ȼ���</td>
		<td class="n1" width="80%"><?=decode_enq_type($enquete->enq_type)?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2">��������</td>
		<td class="n1"><?=$enquete->start_date_y?>ǯ<?=$enquete->start_date_m?>��<?=$enquete->start_date_d?>����<?=$enquete->start_date_h?>��</td>
	</tr>
	<tr>
		<td class="m1" colspan="2">��λ����</td>
		<td class="n1"><?=$enquete->end_date_y?>ǯ<?=$enquete->end_date_m?>��<?=$enquete->end_date_d?>����<?=$enquete->end_date_h?>��</td>
	</tr>
	<tr>
		<td class="m1" colspan="2">�ݥ���ȿ�</td>
		<td class="n1"><?=$enquete->point?>�ݥ����</td>
	</tr>
	<tr>
		<td class="m1" colspan="2">����ȯ����</td>
		<td class="n1"><?=$myenq->send_num?>��</td>
	</tr>
<?
if ($cell->cell_option == 2) {
	$send_cell = $cell->get_send_cell();
	$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e);
?>
	<tr>
		<td class="m1" colspan="2">������ȯ����</td>
		<td class="n1">
			<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box" class="small">
<?
	if ($cell->age_option == 2) {
?>
				<tr align="center">
					<td nowrap width="5%" rowspan=2>&nbsp;</td>
					<td nowrap width="5%" rowspan=2>10��<br>̤��</td>
					<td nowrap width="10%" colspan=2>10��</td>
					<td nowrap width="10%" colspan=2>20��</td>
					<td nowrap width="10%" colspan=2>30��</td>
					<td nowrap width="10%" colspan=2>40��</td>
					<td nowrap width="10%" colspan=2>50��</td>
					<td nowrap width="10%" colspan=2>60��</td>
					<td nowrap width="5%" rowspan=2>70��<br>�ʾ�</td>
					<td nowrap width="5%" rowspan=2>���</td>
				</tr>
				<tr align="center">
					<td>��</td>
					<td>��</td>
					<td>��</td>
					<td>��</td>
					<td>��</td>
					<td>��</td>
					<td>��</td>
					<td>��</td>
					<td>��</td>
					<td>��</td>
					<td>��</td>
					<td>��</td>
				</tr>
<?
	} elseif ($cell->age_option == 3) {
?>
				<tr align="center">
					<td nowrap width="10%">&nbsp;</td>
					<td nowrap width="10%">10��̤��</td>
					<td nowrap width="10%">10��</td>
					<td nowrap width="10%">20��</td>
					<td nowrap width="10%">30��</td>
					<td nowrap width="10%">40��</td>
					<td nowrap width="10%">50��</td>
					<td nowrap width="10%">60��</td>
					<td nowrap width="10%">70��ʾ�</td>
					<td nowrap width="10%">���</td>
				</tr>
<?
	} else {
?>
				<tr align="center">
					<td nowrap width="10%">&nbsp;</td>
					<td nowrap width="10%">ǯ�����ʤ�</td>
					<td nowrap width="10%">���</td>
				</tr>
<?
	}

	$sum_row = array();
	for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
?>
				<tr>
					<td nowrap align="center"><?=decode_sex($sex, '���̻���ʤ�')?></td>
<?
		$sum_col = 0;
		for ($age = $age_s; $age <= $age_e; $age++) {
			$num = $send_cell[$sex][$age][0];
			$sum_col += $num;
			$sum_row[$age] += $num;
?>
					<td nowrap align="right"><?=number_format($num)?></td>
<?
		}
?>
					<td nowrap align="right"><?=number_format($sum_col)?></td>
				</tr>
<?
	}
?>
				<tr>
					<td nowrap align="center">���</td>
<?
	$sum_col = 0;
	for ($age = $age_s; $age <= $age_e; $age++) {
		$num = $sum_row[$age];
		$sum_col += $num;
?>
					<td nowrap align="right"><?=number_format($num)?></td>
<?
	}
?>
					<td nowrap align="right"><?=number_format($sum_col)?></td>
				</tr>
			</table>
		</td>
	</tr>
<?
}
?>
	<tr>
		<td class="m0" colspan=3>�����󥱡�������</td>
	</tr>
	<tr>
		<td class="m1" colspan="2">���󥱡��ȥ����ȥ�</td>
		<td class="n1"><?=htmlspecialchars($enquete->title)?>���ˤĤ��ƤΥ��󥱡���</td>
	</tr>
	<tr>
		<td class="m1" colspan="2">����ʸ</td>
		<td class="n1"><span class="small"><?=nl2br(htmlspecialchars($description))?></span></td>
	</tr>
<?
if (is_array($enquete->question)) {
	foreach ($enquete->question as $qno => $question) {
		// �����λ���
		$image_msg = '�����λ��ꡧ';
		if ($question->url)
			$image_msg .= "URL����ꤹ�� [$question->url]";
		elseif ($question->image_id) {
			$sql = "SELECT im_name FROM t_image WHERE im_image_id=$question->image_id";
			$image_msg .= '������������ [' . htmlspecialchars(db_fetch1($sql)) . ']';
		} else
			$image_msg .= '�ʤ�';

		if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7) {
?>
	<tr>
		<td class="m1" colspan="2" rowspan="4">��<?=mb_convert_kana($qno, 'N')?>
			<div class="small"><?=$question->must_flag == 't' ? '��ɬ�ܲ�����' : '��Ǥ�ղ�����'?></div></td>
		<td class="n1"><?=nl2br(htmlspecialchars($question->question_text))?></td>
	</tr>
	<tr>
		<td class="n1"><?=$image_msg?></td>
	</tr>
	<tr>
		<td class="n1"><?=decode_question_type($question->question_type)?></td>
	</tr>
	<tr>
		<td class="n1">
<?
			foreach ($question->sel_text as $sno => $sel_text) {
?>
			<?=$sno?>��<?=htmlspecialchars($sel_text)?><?=$question->fa_sno == $sno ? ' �ʥե꡼������' : ''?><?=$question->ex_sno == $sno ? ' ����¾��' : ''?><br>
<?
			}
?>
		</td>
	</tr>
<?
		} elseif ($question->question_type == 3) {
?>
	<tr>
		<td class="m1" colspan="2" width="20%" rowspan="3">��<?=mb_convert_kana($qno, 'N')?>
			<div class="small"><?=$question->must_flag == 't' ? '��ɬ�ܲ�����' : '��Ǥ�ղ�����'?></div></td>
		<td class="n1"><?=nl2br(htmlspecialchars($question->question_text))?></td>
	</tr>
	<tr>
		<td class="n1"><?=$image_msg?></td>
	</tr>
	<tr>
		<td class="n1"><?=decode_question_type($question->question_type)?></td>
	</tr>
<?
		} elseif ($question->question_type == 4 || $question->question_type == 5) {
?>
	<tr>
		<td class="m1" colspan="2" width="20%" rowspan="4">��<?=mb_convert_kana($qno, 'N')?>
			<div class="small"><?=$question->must_flag == 't' ? '��ɬ�ܲ�����' : '��Ǥ�ղ�����'?></div></td>
		<td class="n1"><?=nl2br(htmlspecialchars($question->question_text))?></td>
	</tr>
	<tr>
		<td class="n1"><?=$image_msg?></td>
	</tr>
	<tr>
		<td class="n1"><?=decode_question_type($question->question_type)?></td>
	</tr>
	<tr>
		<td class="n1">
			<table class="small" border="0" cellspacing="2" cellpadding="2" width="100%">
				<tr>
					<td>&nbsp;</td>
<?
			foreach ($question->hyoutou as $hno => $hyoutou) {
?>
					<td><?=mb_convert_kana($hno, 'N')?>��<?=htmlspecialchars($hyoutou)?></td>
<?
			}
?>
				</tr>
<?
			foreach ($question->hyousoku as $hno => $hyousoku) {
?>
				<tr bgcolor="<?=$hno % 2 ? '#e8fbfd' : '#ffffff'?>">
					<td><?=mb_convert_kana($hno, 'N')?>��<?=htmlspecialchars($hyousoku)?></td>
<?
				foreach ($question->hyoutou as $hno => $hyoutou) {
?>
					<td></td>
<?
				}
?>
				</tr>
<?
			}
?>
			</table>
		</td>
	</tr>
<?
		} elseif ($question->question_type == 6 || $question->question_type == 8) {
?>
	<tr>
		<td class="m1" colspan="2" width="20%" rowspan="4">��<?=mb_convert_kana($qno, 'N')?>
			<div class="small"><?=$question->must_flag == 't' ? '��ɬ�ܲ�����' : '��Ǥ�ղ�����'?></div></td>
		<td class="n1"><?=nl2br(htmlspecialchars($question->question_text))?></td>
	</tr>
	<tr>
		<td class="n1"><?=$image_msg?></td>
	</tr>
	<tr>
		<td class="n1"><?=decode_question_type($question->question_type)?></td>
	</tr>
	<tr>
		<td class="n1">
			<div>�����������ɽ������ʸ����<?=htmlspecialchars($question->pre_text)?></div>
			<div>������θ��ɽ������ʸ����<?=htmlspecialchars($question->post_text)?></div>
		</td>
	</tr>
<?
		}
	}
}
?>
	<tr>
		<td class="m0" colspan=3>�������������</td>
	</tr>
	<tr>
		<td class="m1" colspan="2">�оݥ�˥���</td>
		<td class="n1"><?=$search->decode_monitor_type($search->type, '')?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2">����</td>
		<td class="n1"><?=decode_sex($search->sex, '')?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2">ǯ��</td>
		<td class="n1"><? disp_age2($search->age_cd, $search->age_from, $search->age_to) ?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2">̤����</td>
		<td class="n1"><?=decode_mikikon($search->mikikon, '')?></td>
	</tr>
<?
if ($cell->rate_option == 2) {
?>
	<tr>
		<td class="m1" colspan="2">ȯ���������</td>
		<td class="n1">̤��<?=number_format($cell->send_rate[1])?>% �� ����<?=number_format($cell->send_rate[2])?>% �� ����¾<?=number_format($cell->send_rate[3])?>%</td>
	</tr>
<?
}
?>
	<tr>
		<td class="m1" colspan="2">�ｻ�ϰ�</td>
		<td class="n1"><? disp_area($search->jitaku_area) ?></td>
	</tr>
<?
if ($cell->rate_option == 3) {
?>
	<tr>
		<td class="m1" colspan="2">ȯ���������</td>
		<td class="n1">
<?
	if (is_array($cell->send_rate)) {
		foreach ($cell->send_rate as $area_cd => $rate)
			$rate_area_ary[] = decode_area3($area_cd) . $rate . '%';

		echo join(' �� ', $rate_area_ary);
	}
?>
		</td>
	</tr>
<?
}
?>
<!-- jeon_start seq=mp3-->
<!--͹���ֹ����ɽ���ɲ�-->
	<tr>
		<td class="m1" rowspan='3' <?if($search->zip=='' && $search->zip_kinmuchi=='')echo "colspan='2'"?>>͹���ֹ�</td>
		<?if($search->zip!='' && $search->zip_kinmuchi!=''){?>
		<td class="m1" width='15%'>���</td>
		<td class="n1"><? if($search->zip_kinmuchi_andor==1)echo "�ｻ�ϰ� AND ��̳��";
							else echo"�ｻ�ϰ�  OR ��̳��";?></td>
		<?}else if($search->zip=='' && $search->zip_kinmuchi==''){?><td rowspan='3' class="n1">&nbsp;</td><?}?>
	</tr>
	<tr>
		<?if($search->zip!=''){?>
		<td class="m1" width='15%'>�ｻ�ϰ�</td>
		<td class="n1"><?=$search->zip;?></td>
		<?}?>
	</tr>
	<tr>
		<?if($search->zip_kinmuchi!=''){?>
		<td class="m1" width='15%'>��̳��</td>
		<td class="n1"><?=$search->zip_kinmuchi;?></td>
		<?}?>
	</tr>
<!-- jeon_end seq=mp3-->
	<tr>
		<td class="m1" colspan="2" >����</td>
		<td class="n1"><? disp_shokugyou($search->shokugyou) ?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >�ȼ�</td>
		<td class="n1"><? disp_gyoushu($search->gyoushu) ?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >����</td>
		<td class="n1"><? disp_shokushu($search->shokushu) ?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >�Ҷ���̵ͭ</td>
		<td class="n1"><?=multi_decode(decode_have_child, $search->have_child)?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >�������</td>
		<td class="n1"><?=multi_decode(decode_housing_form, $search->housing_form)?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >�֤ν�ͭ</td>
		<td class="n1"><?=multi_decode(decode_have_car, $search->have_car)?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >����ӥ�</td>
		<td class="n1"><? disp_conveni($search->conveni) ?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >�����ѡ�</td>
		<td class="n1"><? disp_super($search->super) ?></td>
	</tr>
<?
// �ɲåץ�ե�����
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
	<tr>
		<td class="m1" colspan="2" ><?=htmlspecialchars($profile_name)?></td>
		<td class="n1"><? disp_ma_profile($profile_id, $search->ma_profile) ?></td>
	</tr>
<?
}
?>
	<tr>
		<td class="m1" colspan="2" >��̣������</td>
		<td class="n1"><? disp_genre($search->genre) ?></td>
	</tr>
	<tr>
		<td class="m0" colspan=3>���᡼������</td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >�᡼�륿���ȥ�</td>
		<td class="n1"><?=htmlspecialchars($mail_title)?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >�إå�</td>
		<td class="n1"><span class="small"><?=nl2br(htmlspecialchars($mail_header))?></span></td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >��ʸ</td>
		<td class="n1"><span class="small"><?=nl2br(htmlspecialchars($mail_contents))?></span></td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >�եå�</td>
		<td class="n1"><span class="small"><?=nl2br(htmlspecialchars($mail_footer))?></span></td>
	</tr>
</table>
<br>
<!--kim_start seq no=50 ��ǽ��� �ڤ� button�ɲ� <input type="button" value="����CSV����" onclick="onclick_csv3()">
<?// if ($enquete->status >= 5) { ?>
<input type="button" value="CSV����" onclick="onclick_csv()">
<input type="button" value="CSV����(0,1)" onclick="onclick_csv2()"> kim_end seq no=50//-->
<? //} ?>
<?
 /**	 kim_start seq no=49
	����CSV����������QR,DRCSV��������ޡ�(0,1)����selectʸ�ǽ�������ӵ�ǽ�ɲ�
*/
?>
<select name="ss">
	<option unselected>CSV�ե��������</option>
	<option value="1">����</option>

	<?if($enquete->status >= 5){?>
	<option>----------------------</option>
	<option value="2">������DR�Τߡ�����޶褭���</option>
	<option value="3">������DR�Τߡ�0,1������</option>
	<?}?>
</select>
	<input type="button" value="CSV����" onclick="func_csv()">
<br>
<?
//kim_end seq no=49
?>
<? if ($enquete->enq_type == ENQ_TYPE_MAIL) { ?>
<input type="button" value="�᡼��ץ�ӥ塼" onclick="onclick_mail_prev()">
<? } elseif ($enquete->enq_type == ENQ_TYPE_WEB) { ?>
<input type="button" value="WEB�ץ�ӥ塼" onclick="onclick_web_prev()">
<? } ?>
<input type="button" value="�᡼��ƥ�������" onclick="onclick_test_mail()">
<input type="button" value="�оݿͿ�����" onclick="onclick_search()">
<input type="button" value="���ѹ���" onclick="onclick_edit()">
<? if ($enquete->status == 2 || $enquete->status == 3) { ?>
<input type="button" value="����ǧ��" onclick="onclick_shounin()">
<? } ?>
<input type="button" value="����᡼��ƥ�ץ졼��" onclick="onclick_mailTemplate()">
<?
// kim_start seq no=50 ������ɽ��Button�ɲ�
?>
<input type="button" value="������ɽ��" onclick="onclick_dr_replyview();">
<?
//kim_end seq no=50
?>
<input type="button" value="����롡" onclick="location.href='list.php'">
<input type="hidden" name="next_action" value="app">
<input type="hidden" name="marketer_id" value="<?=$marketer_id?>">
<input type="hidden" name="my_enq_no" value="<?=$my_enq_no?>">
<input type="hidden" name="enquete_id" value="<?=$myenq->enquete_id?>">
<input type="hidden" name="start_date_y" <?=value($enquete->start_date_y)?>>
<input type="hidden" name="start_date_m" <?=value($enquete->start_date_m)?>>
<input type="hidden" name="start_date_d" <?=value($enquete->start_date_d)?>>
<input type="hidden" name="start_date_h" <?=value($enquete->start_date_h)?>>
</form>
</div>

<? page_footer() ?>
</body>
</html>
