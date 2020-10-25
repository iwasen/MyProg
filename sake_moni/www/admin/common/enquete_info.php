<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:�ͣ����󥱡��Ⱦܺ�ɽ��ɽ������
'******************************************************/

$top = '..';
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
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

function decode_question_type($code) {
	switch ($code) {
	case 1:
		return 'ñ������[SA]';
	case 2:
		return 'ʣ������[MA]';
	case 3:
		return '��ͳ����[FA]';
	case 4:
		return '�ޥȥꥯ��[SA]';
	case 5:
		return '�ޥȥꥯ��[MA]';
	}
}

$myenq = new my_enquete_class;
$myenq->read_db($staff_id, $my_enq_no);
$enquete = &$myenq->enquete;
$search = &$myenq->search;

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

set_global('staff', '�����åմ����ãͣ����󥱡��ȴ���', '�ͣ����󥱡���ɽ��', BACK_CLOSE);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>��������������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">���󥱡��ȼ���</td>
		<td class="n1" width="80%"><?=decode_enq_type($enquete->enq_type)?></td>
	</tr>
	<tr>
		<td class="m1">��������</td>
		<td class="n1"><?=$enquete->start_date_y?>ǯ<?=$enquete->start_date_m?>��<?=$enquete->start_date_d?>����<?=$enquete->start_date_h?>��</td>
	</tr>
	<tr>
		<td class="m1">��λ����</td>
		<td class="n1"><?=$enquete->end_date_y?>ǯ<?=$enquete->end_date_m?>��<?=$enquete->end_date_d?>����<?=$enquete->end_date_h?>��</td>
	</tr>
	<tr>
		<td class="m1">�ݥ���ȿ�</td>
		<td class="n1"><?=$enquete->point?>�ݥ����</td>
	</tr>
	<tr>
		<td class="m1">����ȯ����</td>
		<td class="n1"><?=$myenq->send_num?>��</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>�����󥱡�������</td>
	</tr>
	<tr>
		<td class="m1">���󥱡��ȥ����ȥ�</td>
		<td class="n1"><?=htmlspecialchars($enquete->title)?></td>
	</tr>
	<tr>
		<td class="m1">����ʸ</td>
		<td class="n1"><span class="small"><?=nl2br(htmlspecialchars($description))?></span></td>
	</tr>
<?
if (is_array($enquete->question)) {
	foreach ($enquete->question as $qno => $question) {
		if ($question->question_type == 1 || $question->question_type == 2) {
?>
				<tr>
					<td class="m1" rowspan="3">��<?=mb_convert_kana($qno, 'N')?></td>
					<td class="n1"><?=nl2br(htmlspecialchars($question->question_text))?></td>
				</tr>
				<tr>
					<td class="n1"><?=$question->question_type == 1 ? 'ñ������(SA)' : 'ʣ������(MA)'?></td>
				</tr>
				<tr>
					<td class="n1">
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
					<td class="m1" width="20%" rowspan="2">��<?=mb_convert_kana($qno, 'N')?></td>
					<td class="n1"><?=nl2br(htmlspecialchars($question->question_text))?></td>
				</tr>
				<tr>
					<td class="n1">��ͳ����(FA)</td>
				</tr>
<?
		} elseif ($question->question_type == 4 || $question->question_type == 5) {
?>
				<tr>
					<td class="m1" width="20%" rowspan="3">��<?=mb_convert_kana($qno, 'N')?></td>
					<td class="n1"><?=nl2br(htmlspecialchars($question->question_text))?></td>
				</tr>
				<tr>
					<td class="n1"><?=$question->question_type == 4 ? '�ޥȥꥯ��(SA)' : '�ޥȥꥯ��(MA)'?></td>
				</tr>
				<tr>
					<td class="n1">
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
	<tr>
		<td class="m0" colspan=2>�������������</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><?=decode_sex($search->sex, '<br>')?></td>
	</tr>
	<tr>
		<td class="m1">ǯ��</td>
		<td class="n1"><? disp_age2($search->age_cd, $search->age_from, $search->age_to) ?></td>
	</tr>
	<tr>
		<td class="m1">̤����</td>
		<td class="n1"><?=decode_mikikon($search->mikikon, '<br>')?></td>
	</tr>
	<tr>
		<td class="m1">�ｻ�ϰ�</td>
		<td class="n1"><? disp_area($search->jitaku_area) ?></td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><? disp_shokugyou($search->shokugyou) ?></td>
	</tr>
	<tr>
		<td class="m1">��������</td>
		<td class="n1"><? disp_chain($search->chain) ?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���᡼������</td>
	</tr>
	<tr>
		<td class="m1">�᡼�륿���ȥ�</td>
		<td class="n1"><?=htmlspecialchars($mail_title)?></td>
	</tr>
	<tr>
		<td class="m1">�إå�</td>
		<td class="n1"><span class="small"><?=nl2br(htmlspecialchars($mail_header))?></span></td>
	</tr>
	<tr>
		<td class="m1">��ʸ</td>
		<td class="n1"><span class="small"><?=nl2br(htmlspecialchars($mail_contents))?></span></td>
	</tr>
	<tr>
		<td class="m1">�եå�</td>
		<td class="n1"><span class="small"><?=nl2br(htmlspecialchars($mail_footer))?></span></td>
	</tr>
</table>
<br>
<input type="button" value="���Ĥ��롡" onclick="window.close()">
</div>

<? page_footer() ?>
</body>
</html>