<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:My�ѡ��ȥʡ�����������
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//�ᥤ�����
set_global2('mypartner', 'My�ѡ��ȥʡ�����', '��������', BACK_TOP);

$enquete = new enquete_class;
$enquete->read_db($enquete_id);
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

<form method="post" name="form1" action="crs_result.php">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">���������</td>
	</tr>
</table>

<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
		<th>�����ֹ�</th>
		<th>����</th>
		<th>���׼���</th>
	</tr>
<?
$i = 0;
foreach ($enquete->question as $qno => $question) {
	if ($question->question_type == 1 || $question->question_type == 2) {
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center" rowspan="2">Q<?=$qno?></td>
		<td><?=nl2br(htmlspecialchars($question->question_text))?></td>
		<td align="center" rowspan="2">
			<input type="radio" name="hyousoku" value="<?=$qno?>">ɽ¦
			<input type="radio" name="hyoutou" value="<?=$qno?>">ɽƬ
		</td>
	</tr>
	<tr class="tc<?=$i % 2?>">
		<td>
<?
		foreach ($question->sel_text as $sel_text) {
?>
			<?=htmlspecialchars($sel_text)?><br>
<?
		}
?>
		</td>
	</tr>
<?
	}
	$i++;
}
?>
</table>
<br>
			<div align="center">
				<input type="submit" value="��������">
				<input type="button" value="����롡" onclick="history.back()">
				<input type="hidden" name="enquete_id" <?=value($enquete_id)?>>
			</div>
		</td>
	</tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
