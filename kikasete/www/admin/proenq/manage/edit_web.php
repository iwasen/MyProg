<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�У�異�󥱡����ѹ�����
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/pro_enquete.php");
include("$inc/mye_temp.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('proenquete', '�У�異�󥱡��ȴ���', '�У�異�󥱡��Ⱦ����ѹ�', BACK_TOP);

// ���å�������
if ($edit) {
	$pro_enq = &$_SESSION['ss_pro_enq'];
	$pro_enq_no = $pro_enq->pro_enq_no;
} else {
	$_SESSION['ss_pro_enq'] = new pro_enquete_class;
	$pro_enq = &$_SESSION['ss_pro_enq'];
	$pro_enq->read_db($marketer_id, $pro_enq_no);
}

if ($main)
	$enquete = &$pro_enq->enquete2;
else
	$enquete = &$pro_enq->enquete;

if ($enquete->description == '')
	$description = get_enq_description($pro_enq);
else
	$description = $enquete->description;
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
<form method="post" name="form1" action="update_web.php">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0">�����󥱡��ȣףţ²��̤Υ������ޥ���</td>
	</tr>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=100% frame="box">
				<tr>
					<td width="20%" class="m1">����ʸ</td>
					<td class="n1">
						<textarea rows="20" cols="70" name="description"><?=htmlspecialchars($description)?></textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
<?
if (is_array($enquete->question)) {
	foreach ($enquete->question as $qno => $question) {
		if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7) {
?>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=100% frame="box">
				<tr>
					<td width="20%" rowspan="3" class="m1">��<?=mb_convert_kana($qno, 'N')?></td>
					<td class="n1"><?=htmlspecialchars($question->question_text)?></td>
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
			</table>
		</td>
	</tr>
<?
		} elseif ($question->question_type == 3) {
?>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=100% frame="box">
				<tr>
					<td width="20%" rowspan="3" class="m1">��<?=mb_convert_kana($qno, 'N')?></td>
					<td class="n1"><?=htmlspecialchars($question->question_text)?></td>
				</tr>
				<tr>
					<td class="n1"><?=decode_question_type($question->question_type)?></td>
				</tr>
			</table>
		</td>
	</tr>
<?
		} elseif ($question->question_type == 4 || $question->question_type == 5) {
?>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=100% frame="box">
				<tr>
					<td width="20%" rowspan="3" class="m1">��<?=mb_convert_kana($qno, 'N')?></td>
					<td class="n1"><?=htmlspecialchars($question->question_text)?></td>
				</tr>
				<tr>
					<td class="n1"><?=decode_question_type($question->question_type)?></td>
				</tr>
				<tr>
					<td class="n1">
						<table border="0" cellspacing="2" cellpadding="2" width="100%">
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
			</table>
		</td>
		<!-- �ơ��֥����� -->
	</tr>
<?
		} elseif ($question->question_type == 6 || $question->question_type == 8) {
?>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=100% frame="box">
				<tr>
					<td width="20%" rowspan="3" class="m1">��<?=mb_convert_kana($qno, 'N')?></td>
					<td class="n1"><?=htmlspecialchars($question->question_text)?></td>
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
			</table>
		</td>
	</tr>
<?
		}
	}
}
?>
</table>

<br>
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�ꥻ�å�" onclick="document.form1.next_action.value='reset'">
<input type="button" value="����롡" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
<input type="hidden" name="next_action">
<input type="hidden" name="marketer_id" <?=value($marketer_id)?>>
<input type="hidden" name="pro_enq_no" <?=value($pro_enq_no)?>>
<input type="hidden" name="main" <?=value($main)?>>
</form>
</div>

<? page_footer() ?>
</body>
</html>
