<?
/******************************************************
' System :�������ơ�net��̳�ɡ�������������ѥڡ���
' Content:My�ѡ��ȥʡ����ꥯ�롼�ȥ���־ܺپ���
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/my_search.php");
include("$inc/ma_profile.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//�ᥤ�����
set_global2('mypartner', 'My�ѡ��ȥʡ�����', 'My�ѡ��ȥʡ��ꥯ�롼�ȥ���־ܺپ���', BACK_TOP);

// �������Ͽ
set_return_page('select_monitor.php', "pjt_id=$pjt_id");
set_return_page('select_update.php', "pjt_id=$pjt_id");
set_return_page('r_job_edit.php', "pjt_id=$pjt_id");
set_return_page('r_job_update.php', "pjt_id=$pjt_id");

// ��̳�ɥ����å��������
if ($check) {
	$rec['ppj_status'] = sql_number(MPS_REC_CHECK);
	db_update('t_mp_pjt', $rec, "ppj_pjt_id=$pjt_id AND ppj_status=" . MPS_REC_SHINSEI);
}

$myp = new my_partner_class;
$myp->read_db($pjt_id);
$enquete = &$myp->enquete;
$search = &$myp->search;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function display_style(chk, dsp) {
	document.getElementById(dsp).style.display = document.getElementById(chk).checked ? "" : "none";
}
function set_disp() {
	display_style("chk_summary", "dsp_summary");
	display_style("chk_enquete", "dsp_enquete");
	display_style("chk_search", "dsp_search");
	if (document.getElementById("chk_custom"))
		display_style("chk_custom", "dsp_custom");
}
function onclick_cancel() {
	if (confirm("�ꥯ�롼�ȥ���֤ξ�ǧ����ä��ޤ���������Ǥ�����"))
		location.href = "r_job_cancel.php?pjt_id=<?=$pjt_id?>";
}
function apply() {
	if (confirm("�������Ƥǿ������ޤ�����"))
		location.href= "r_job_apply.php?pjt_id=<?=$pjt_id?>";
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lb">
<?
if ($myp->status >= MPS_REC_JISSHI) {
?>
			<input type="button" value="�ꥢ�륿���ླྀ��" onclick="location.href='total.php?pjt_id=<?=$pjt_id?>'">
<?
	if ($myp->real_send > 0) {
?>
			<input type="button" value="�ɲ�ȯ��Ƚ��" onclick="location.href='addition_check.php?pjt_id=<?=$pjt_id?>'">
<?
	}
}
?>
			<input type="button" value="����������" onclick="location.href='select_monitor.php?pjt_id=<?=$pjt_id?>'">
			<input type="button" value="�����Ծ���ѹ�" onclick="location.href='require_edit.php?pjt_id=<?=$pjt_id?>'">
		</td>
	</tr>
</table>

<p>
<form>
<table border=0 cellspacing=2 cellpadding=3 width="<?=width('80%')?>">
<?
if ($myp->real_send != 0) {
	$sql = "SELECT COUNT(*) FROM t_mp_monitor WHERE pmn_pjt_id=$pjt_id";
	$reply_count = db_fetch1($sql);

	if ($myp->select_sql == '') {
		$sel_count = '̤����';
		$sel_percent = '̤����';
	} else {
		$sql = "SELECT COUNT(*) FROM t_mp_monitor WHERE $myp->select_sql";
		$sel_count = db_fetch1($sql);
		$sel_percent = format_percent($sel_count, $myp->real_send, 1);
	}
?>
	<tr>
		<td class="m1">
			<table border=0 cellspacing=0 cellpadding=2 width=100%>
				<tr>
					<td>ȯ�����ֿ�����</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
				<tr>
					<td class="n1" width="20%">ȯ����</td>
					<td	colspan="2" align="center"><?=number_format($myp->real_send)?></td>
					<td>
<?
if ($myp->real_send) {
?>
						<input type="button" value="��CSV��" onclick="location.href='csv_send.php?enquete_id=<?=$myp->enquete_id?>'">
<?
}
?>
					</td>
				</tr>
				<tr>
					<td class="n1">�ֿ���</td>
					<td width="20%" align="center"><?=number_format($reply_count)?></td>
					<td width="20%" align="center"><?=format_percent($reply_count, $myp->real_send, 1)?></td>
					<td width="60%">
<?
if ($reply_count) {
?>
						<input type="button" value="��CSV��" onclick="location.href='csv_recv.php?enquete_id=<?=$myp->enquete_id?>'">
<?
}
?>
					</td>
				</tr>
				<tr>
					<td class="n1">�����Կ�</td>
					<td width="20%" align="center"><?=$sel_count?></td>
					<td width="20%" align="center"><?=$sel_percent?></td>
					<td width="60%">
<?
if ($reply_count) {
?>
						<input type="button" value="��CSV��" onclick="location.href='csv_sel.php?pjt_id=<?=$pjt_id?>'">
<?
}
?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
<?
}
?>
	<tr>
		<td class="m1">
			<table border=0 cellspacing=0 cellpadding=2 width=100%>
				<tr>
					<td><input type="checkbox" id="chk_summary" checked onclick="set_disp()">���󥱡��ȳ���</td>
					<td align="right">
<?
if ($myp->status <= MPS_REC_KANRYOU || $myp->status == MPS_REC_CHECK) {
?>
						<input type="button" value="<?=$myp->recruit_flag & 0x01 ? '��������' : '�����ꡡ'?>" onclick="location.href='r_job_edit.php?pjt_id=<?=$pjt_id?>&df=1'">
<?
}
?>
					</td>
				</tr>
 			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box" id="dsp_summary">
				<tr>
					<td class="n1" width="20%">���󥱡��ȼ���</td>
					<td class="n0" width="80%"><?=decode_enq_type($enquete->enq_type)?></td>
				</tr>
				<tr>
					<td class="n1">��������</td>
					<td class="n0"><?=sprintf('%dǯ%d��%d�� %d��', $myp->recruit_start_y, $myp->recruit_start_m, $myp->recruit_start_d, $myp->recruit_start_h)?></td>
				</tr>
				<tr>
					<td class="n1">��λ����</td>
					<td class="n0"><?=sprintf('%dǯ%d��%d�� %d��', $myp->recruit_end_y, $myp->recruit_end_m, $myp->recruit_end_d, $myp->recruit_end_h)?></td>
				</tr>
				<tr>
					<td class="n1">�ݥ���ȿ�</td>
					<td class="n0"><?=$enquete->point?>�ݥ����</td>
				</tr>
				<tr>
					<td class="n1">����ȯ����</td>
					<td class="n0"><?=number_format($myp->send_num)?>��</td>
				</tr>
				<tr>
					<td class="n1" width="20%">���󥱡��ȥ����ȥ�</td>
					<td width="80%"><?=htmlspecialchars($enquete->title)?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m1">
			<table border=0 cellspacing=0 cellpadding=2 width=100%>
				<tr>
					<td><input type="checkbox" id="chk_enquete" checked onclick="set_disp()">���󥱡�������</td>
					<td align="right">
<?
if ($myp->status <= MPS_REC_KANRYOU || $myp->status == MPS_REC_CHECK) {
?>
						<input type="button" value="<?=$myp->recruit_flag & 0x02 ? '��������' : '�����ꡡ'?>" onclick="location.href='r_job_edit.php?pjt_id=<?=$pjt_id?>&df=2'">
<?
}
?>
					</td>
				</tr>
 			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box" id="dsp_enquete">
<?
if (is_array($enquete->question)) {
	foreach ($enquete->question as $qno => $question) {
		if ($question->question_type == 1 || $question->question_type == 2) {
?>
				<tr>
					<td class="n1" width="20%" rowspan="3">��<?=mb_convert_kana($qno, 'N')?></td>
					<td><?=nl2br(htmlspecialchars($question->question_text))?></td>
				</tr>
				<tr>
					<td><?=$question->question_type == 1 ? 'ñ������(SA)' : 'ʣ������(MA)'?></td>
				</tr>
				<tr>
					<td>
<?
			foreach ($question->sel_text as $sno => $sel_text) {
?>
									<?=$sno?>��<?=htmlspecialchars($sel_text)?><br>
<?
			}
?>
					</td>
				</tr>
<?
		} elseif ($question->question_type == 3) {
?>
				<tr>
					<td class="n1" width="20%" rowspan="2">��<?=mb_convert_kana($qno, 'N')?></td>
					<td><?=nl2br(htmlspecialchars($question->question_text))?></td>
				</tr>
				<tr>
					<td>��ͳ����(FA)</td>
				</tr>
<?
		} elseif ($question->question_type == 4 || $question->question_type == 5) {
?>
				<tr>
					<td class="n1" width="20%" rowspan="3">��<?=mb_convert_kana($qno, 'N')?></td>
					<td><?=nl2br(htmlspecialchars($question->question_text))?></td>
				</tr>
				<tr>
					<td><?=$question->question_type == 4 ? '�ޥȥꥯ��(SA)' : '�ޥȥꥯ��(MA)'?></td>
				</tr>
				<tr>
					<td>
						<table class="small" border="0" cellspacing="2" cellpadding="2" width="100%">
							<tr>
								<td>&nbsp;</td>
<?
			foreach ($question->hyoutou as $hno => $hyoutou) {
?>
								<td bgcolor="#ffffff"><?=mb_convert_kana($hno, 'N')?>��<?=htmlspecialchars($hyoutou)?></td>
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
		}
	}
}
?>
			</table>
		</td>
	</tr>

	<tr>
		<td class="m1">
			<table border=0 cellspacing=0 cellpadding=2 width=100%>
				<tr>
					<td><input type="checkbox" id="chk_search" checked onclick="set_disp()">�оݾ��</td>
					<td align="right">
<?
if ($myp->status <= MPS_REC_KANRYOU || $myp->status == MPS_REC_CHECK) {
?>
						<input type="button" value="<?=$myp->recruit_flag & 0x04 ? '��������' : '�����ꡡ'?>" onclick="location.href='r_job_edit.php?pjt_id=<?=$pjt_id?>&df=3'">
<?
}
?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="small" id="dsp_search">
				<tr>
					<td class="n1" width="20%">�оݥ�˥���</td>
					<td><?=$search->decode_monitor_type($search->type, '<br>')?></td>
				</tr>
				<tr>
					<td class="n1">����</td>
					<td><?=decode_sex($search->sex, '<br>')?></td>
				</tr>
				<tr>
					<td class="n1">ǯ��</td>
					<td><? disp_age2($search->age_cd, $search->age_from, $search->age_to) ?></td>
				</tr>
				<tr>
					<td class="n1">̤����</td>
					<td><?=decode_mikikon($search->mikikon, '<br>')?></td>
				</tr>
				<tr>
					<td class="n1">�ｻ�ϰ�</td>
					<td><? disp_area($search->jitaku_area) ?></td>
				</tr>
				<tr>
					<td class="n1">����</td>
					<td><? disp_shokugyou($search->shokugyou) ?></td>
				</tr>
				<tr>
					<td class="n1">�Ҷ���̵ͭ</td>
					<td><?=multi_decode(decode_have_child, $search->have_child)?></td>
				</tr>
				<tr>
					<td class="n1">�������</td>
					<td><?=multi_decode(decode_housing_form, $search->housing_form)?></td>
				</tr>
				<tr>
					<td class="n1">�֤ν�ͭ</td>
					<td><?=multi_decode(decode_have_car, $search->have_car)?></td>
				</tr>
				<tr>
					<td class="n1">����ӥ�</td>
					<td><? disp_conveni($search->conveni) ?></td>
				</tr>
				<tr>
					<td class="n1">�����ѡ�</td>
					<td><? disp_super($search->super) ?></td>
				</tr>
<?
// �ɲåץ�ե�����
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
				<tr>
					<td class="n1"><?=htmlspecialchars($profile_name)?></td>
					<td><? disp_ma_profile($profile_id, $search->ma_profile) ?></td>
				</tr>
<?
}
?>
				<tr>
					<td class="n1">��̣������</td>
					<td><? disp_genre($search->genre) ?></td>
				</tr>
			</table>
		</td>
	</tr>
<?
if ($myp->status <= MPS_REC_KANRYOU || $myp->status == MPS_REC_CHECK) {
?>
	<tr>
		<td class="m1">
			<table class="small" border=0 cellspacing=0 cellpadding=2 width=100%>
				<tr>
					<td><input checked type="checkbox" id="chk_custom" onclick="set_disp()">���󥱡��ȣףţ²��̤ȥ᡼��ʸ�Υ������ޥ���</td>
					<td align="right"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% id="dsp_custom">
				<tr>
					<td class="n1" width="20%">���󥱡��ȣףţ²��̤Υ������ޥ���</td>
					<td width="80%">
						<input type="button" value="�ףţ²��̤μ�ư����" onclick="location.href='edit_web.php?pjt_id=<?=$pjt_id?>'">
					</td>
				</tr>
				<tr>
					<td class="n1" width="20%">�᡼��ʸ�Υ������ޥ���</td>
					<td>
						<input type="button" value="�᡼��ʸ�̤μ�ư����" onclick="location.href='edit_mail.php?pjt_id=<?=$pjt_id?>'">
					</td>
				</tr>
			</table>
		</td>
	</tr>
<?
}
?>
	<tr>
		<td align="center" colspan=2>
			<br>
			<input type="button" value="�ףţ¥ץ�ӥ塼" onclick="location.href='preveiw_web.php?pjt_id=<?=$pjt_id?>'">
			<input type="button" value="�᡼��ץ�ӥ塼" onclick="location.href='preveiw_mail.php?pjt_id=<?=$pjt_id?>'">
<?
if ($myp->status == MPS_REC_JUNBI && $myp->recruit_flag == 0x07) {
?>
			<input type="button" value="��������λ��" onclick="location.href='r_job_status.php?pjt_id=<?=$pjt_id?>'">
<?
}
if ($myp->status == MPS_REC_KANRYOU) {
?>
			<input type="button" value="��������᤹" onclick="location.href='r_job_status.php?pjt_id=<?=$pjt_id?>'">
			<input type="button" value="��������" onclick="apply()">
<?
}
if ($myp->status == MPS_REC_SHOUNIN) {
?>
			<input type="button" value="��ǧ��ä�" onclick="onclick_cancel()">
<?
}
?>
			<input type="button" value="����롡" onclick="location.href='<?=get_return_page("pjt_design.php?pjt_id=$pjt_id")?>'">
		</td>
	</tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
