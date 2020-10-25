<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:���󥱡��ȥ����å�
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/enq_check.php");

//���䥿����DECODE
function decode_ans_type($code) {
	switch ($code) {
	case 1:
		return 'ñ������(SA)';
	case 2:
		return 'ʣ������(MA)';
	case 3:
		return '��ͳ����(FA)';
	}
	return $code;
}

set_global2('project', '�ץ������ȴ���', '���󥱡��ȥ����å�', BACK_TOP);
$question = enq_check($jb_body);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<center>
<?
if ($enq_err) {
?>
<table border=0 cellspacing=1 cellpadding=0>
	<tr>
		<td class="m0">�����󥱡������Ƥ˥��顼������ޤ�</td>
	</tr>
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=3 bordercolor="navy" width="100%">
				<tr>
					<td>
						<tt>
<?
$ary = explode("\n", $jb_body);
foreach ($ary as $lno => $line) {
	echo htmlspecialchars($line), "<br>\n";
	if (isset($enq_err[$lno])) {
		foreach ($enq_err[$lno] as $msg)
			echo "<font color='red'>>Error: $msg</font><br>\n";
	}
}
?>
						</tt>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?
} else {
?>
<table border=0 cellspacing=1 cellpadding=0 width=100%>
	<tr>
		<td class="m0">���ʲ��Υ��󥱡��Ȥ򸡽Ф��ޤ���</td>
	</tr>
</table>
<table border=1 cellspacing=0 cellpadding=3 width=100%>
<?
	$i = 0;
	if (is_array($question)) {
		foreach ($question as $qno => $q) {
?>
	<tr id="edit2">
		<td valign="top" class="q<?=$i % 3?>" width="10%" rowspan=3>��<?=$qno?></td>
		<td valign="top" class="q<?=$i % 3?>" width="10%">����ʸ</td>
		<td><tt><?=nl2br(htmlspecialchars($q->question_text))?></tt></td>
	</tr>
	<tr>
		<td valign="top" class="q<?=$i % 3?>">�������</td>
		<td><font size="-1"><?=decode_ans_type($q->question_type)?></font></td>
	</tr>
	<tr>
		<td valign="top" class="q<?=$i % 3?>">
<?
			echo $q->question_type <> 3 ? '�����' : '������';
?>
		</td>
		<td>
			<table border=0>
<?
			if (is_array($q->sel_text)) {
				$last_sno = end(array_keys($q->sel_text));
				foreach ($q->sel_text as $sno => $sel_text) {
					if ($q->question_type == 3 || $q->fa_flag == 't' && $sno == $last_sno) {
?>
				<tr>
					<td></td>
					<td><font size=-1><?=$sel_text?>���С�������������</font></td>
				</tr>
<?
					} else {
?>
				<tr>
					<td><?=$sno?>��</td>
					<td><font size=-1><?=$sel_text?></font></td>
				</tr>
<?
					}
				}
			} else {
				if ($q->question_type == 3) {
?>
				<tr>
					<td>
						<font size="-1">
						--------�ʲ������������--------<br><br>
						--------�ʲ��������ޤǡ�--------
					</td>
				</tr>
<?
				}
			}
?>
			</table>
		</td>
	</tr>
<?
			$i++;
		}
	}
}
?>
</table>
</center>

<center>
<br>
<input type="button" value=" �Ĥ��� " onclick="window.close()">
</center>
</body>
</html>
