<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ͣ����󥱡���Web�ץ�ӥ塼
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/mye_temp.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('myenquete', '�ͣ����󥱡��ȴ���', '�ף��ץ�ӥ塼', BACK_TOP);

$myenq = new my_enquete_class;
$myenq->read_db($marketer_id, $my_enq_no);
$enquete = &$myenq->enquete;

// ����ʸ�����ꤵ��Ƥ��ʤ����ϥƥ�ץ졼�Ȥ�ɽ��
$description = ($enquete->description != '') ? $enquete->description : get_enq_description($myenq);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<div align="center">
<table width="470" border="0" cellpadding="2" cellspacing="0" bgcolor="#c8c8c8">
	<tr>
		<td><font size="2"><table border="0" cellpadding="5" cellspacing="0" width="100%">
				<tr>
					<td bgcolor="#c3c2ff" colspan="2"><font color="#2b2b63" size="2"><b><?=htmlspecialchars($enquete->title)?></b></font>�ˤĤ��ƤΥ��󥱡���</td>
				</tr>
				<tr>
					<td colspan="2" bgcolor="white"><font size="2"><br><?=nl2br(htmlspecialchars($description))?><br>
						</font></td>
				</tr>
<?
if (is_array($enquete->question)) {
	foreach (array_keys($enquete->question) as $i) {
		$question = &$enquete->question[$i];
		if ($question->question_text != '') {
				if (isset($enquete->branch_cond[$i])) {
?>
				<tr><td colspan=2 bgcolor="#ffff99" align="center"><font size="2">ʬ������ʲ��ڡ�����<span style="color:#ff0000;">��<?=htmlspecialchars($enquete->branch_cond_text($i, '�����򤷤����Τ�', false, false))?>��</span></font></td></tr>
<?
				} elseif ($question->page_break == DBTRUE) {
?>
				<tr><td colspan=2 bgcolor="#ffcc99" align="center"><font size="2">���ڡ���</font></td></tr>
<?
				}
?>
				<tr>
					<td bgcolor="#c3c2ff" colspan="2">
						<font color="#2b2b63" size="2"><b>Q<?=$i?>. <?=nl2br(htmlspecialchars($question->question_text))?></b></font>
<?
			if ($question->url != '') {
?>
						<br><a href="<?=$question->url?>" target="_blank"><?=htmlspecialchars($question->url)?></a>
<?
			}
			if ($question->image_id) {
?>
						<br><img src="../../../monitor/img.php?id=<?=$question->image_id?>" alt="">
<?
			}
?>
					</td>
				</tr>
				<tr>
					<td bgcolor="white" width="7%" valign="top"><font size="2">A<?=$i?>.</font></td>
					<td bgcolor="white" width="93%"><font size="2">
<?
			switch ($question->question_type) {
			case 1:		// ñ������
				if (is_array($question->sel_text)) {
					$br = '';
					foreach ($question->sel_text as $sno => $sel_text) {
						if ($sel_text != '') {
							echo $br;
							$br = '<br>';
?>
						<nobr><input type="radio" name="answer<?=$i?>" value=""> <?=htmlspecialchars($sel_text)?></nobr>
<?
							if ($question->fa_sno == $sno) {
?>
						<input type="text" name="free_answer<?=$i?>" size=25>
<?
							}
						}
					}
				}
				break;
			case 2:		// ʣ������
				if (is_array($question->sel_text)) {
					$br = '';
					foreach ($question->sel_text as $sno => $sel_text) {
						if ($sel_text != '') {
							echo $br;
							$br = '<br>';
?>
						<nobr><input type="checkbox" name="answer<?=$i?>" value=""> <?=htmlspecialchars($sel_text)?></nobr>
<?
							if ($question->fa_sno == $sno) {
?>
						<input type="text" name="free_answer<?=$i?>" size=25>
<?
							}
						}
					}
				}
				break;
			case 3:		// ��ͳ����
?>
						<textarea name="answer<?=$i?>" cols="50" rows="15"></textarea>
						<br><br>����ͳ�ˤ���������������
<?
				break;
			case 4:		// �ޥȥꥯ��(SA)
?>
						<table width="100%">
							<tr>
								<td>&nbsp;</td>
<?
				foreach ($question->hyoutou as $tno => $hyoutou) {
?>
								<td align="center" bgcolor="#ffddee"><font size="2"><?=htmlspecialchars($hyoutou)?></font></td>
<?
				}
?>
							</tr>
<?
				foreach ($question->hyousoku as $sno => $hyousoku) {
?>
							<tr bgcolor="<?=$sno % 2 ? '#ddeeff' : '#ddffee'?>">
								<td><font size="2"><?=htmlspecialchars($hyousoku)?></font></td>
<?
					foreach ($question->hyoutou as $tno => $hyoutou) {
?>
								<td align="center"><input type="radio" name="answer<?=$sno?>"></td>
<?
					}
?>
							</tr>
<?
				}
?>
					</table>
<?
				if ($question->dup_flag == 't') {
?>
								��Ʊ�������ǽ�ʣ�������򤹤뤳�ȤϤǤ��ޤ����
<?
				}
				break;
			case 5:		// �ޥȥꥯ��(MA)
?>
						<table width="100%">
							<tr>
								<td>&nbsp;</td>
<?
				foreach ($question->hyoutou as $tno => $hyoutou) {
?>
								<td align="center" bgcolor="#ffddee"><font size="2"><?=htmlspecialchars($hyoutou)?></font></td>
<?
				}
?>
							</tr>
<?
				foreach ($question->hyousoku as $sno => $hyousoku) {
?>
							<tr bgcolor="<?=$sno % 2 ? '#ddeeff' : '#ddffee'?>">
								<td><font size="2"><?=htmlspecialchars($hyousoku)?></font></td>
<?
					foreach ($question->hyoutou as $tno => $hyoutou) {
?>
								<td align="center"><input type="checkbox" name="answer<?=$i?>"></td>
<?
					}
?>
							</tr>
<?
				}
?>
					</table>
<?
				if ($question->dup_flag == 't') {
?>
								��Ʊ�������ǽ�ʣ�������򤹤뤳�ȤϤǤ��ޤ����
<?
				}
				break;
			case 6:		// ���̲���
?>
					<?=htmlspecialchars($question->pre_text)?> <input type="text" name="answer<?=$i?>" size="10"> <?=htmlspecialchars($question->post_text)?>
<?
				break;
			case 7:		// �ץ������
?>
					<select name="answer<?=$i?>">
						<option value="">���򤷤Ƥ�������</option>
<?
				if (is_array($question->sel_text)) {
					foreach ($question->sel_text as $sno => $sel_text) {
?>
						<option <?=value($sno)?>><?=htmlspecialchars($sel_text)?></option>
<?
					}
				}
?>
					</select>
<? 
				break;
			case 8:		// �ե꡼�����ʣ��ԡ�
?>
					<?=htmlspecialchars($question->pre_text)?> <input type="text" name="answer<?=$i?>"> <?=htmlspecialchars($question->post_text)?>
<?
				break;
			}
?>
						<br><br>
						</font></td>
				</tr>
<?
		}
	}
}
?>
			</table>
		</td>
	</tr>
</table>
<br>
<input type="button" value="����롡" onclick="history.back()">
</div>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
