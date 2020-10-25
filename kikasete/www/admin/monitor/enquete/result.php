<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�����ͥ��󥱡��ȴ���
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enq_graph.php");

set_global('monitor', '��˥�������', '�����ͥ��󥱡��ȴ���', BACK_TOP);

$sql = "SELECT en_enquete_id,en_title,en_description"
		. " FROM t_free_enquete JOIN t_enquete ON en_enquete_id=fe_enquete_id AND en_enq_kind=1"
		. " WHERE fe_seq_no=$seq_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow) {
	$fetch = pg_fetch_object($result, 0);
	$enquete_id = $fetch->en_enquete_id;
	$title = htmlspecialchars($fetch->en_title);
	$description = nl2br(htmlspecialchars($fetch->en_description));
} else
	redirect('list.php');
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

<table border=0 cellspacing=0 cellpadding=1 width="80%">
	<tr>
		<td class="lt">�������ͥ��󥱡��ȷ��</td>
		<td class="lb">
			<input type="button" value="CSV����" onclick="location.href='csv.php?seq_no=<?=$seq_no?>'">
			<input type="button" value="����롡" onclick="history.back()">
		</td>
	</tr>
</table>

<table border=0 cellspacing=0 cellpadding=0 width="80%">
	<tr>
		<td>
			<table border="1" cellpadding="5" cellspacing="0" width="100%">
				<tr>
					<td bgcolor="#c3c2ff"><?=$title?><br></td>
				</tr>
				<tr>
					<td bgcolor="white"><font size="2"><br><?=$description?><br><br></font></td>
				</tr>
			</table>
		</td>
	</tr>
<?
$sql = "SELECT eq_question_no,eq_question_text FROM t_enq_question WHERE eq_enquete_id=$enquete_id ORDER BY eq_question_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$question_no = $fetch->eq_question_no;
?>
	<tr>
		<td><br></td>
	</tr>
<?
if ($fetch->eq_question_text != '') {
?>
	<tr>
		<td bgcolor="#ccffcc">��<?=$question_no?>��<?=nl2br(htmlspecialchars($fetch->eq_question_text))?></td>
	</tr>
<?
}
?>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=1 width="100%">
				<tr>
					<td colspan=2 bgcolor="#9290c6"><font size=2 color="#ffffff"><b>&nbsp;�ߤ�ʤε�����</b></font></td>
				</tr>
				<tr>
<?
$scale = 400;
$html = '<td width="120"><nobr><font size="2" color="%COLOR%">��</font><font size="2">%TEXT%</font></nobr></td>'
			.	'<td><nobr><font size="2"><img src="' . $top . '/images/graph_%IMAGE%.gif" width="%WIDTH%" height="10"> %COUNT% </font></nobr></td>';
enq_graph_all($enquete_id, $question_no, $scale, $html);
?>
				</tr>
				<tr><td colspan=2><br></td></tr>
				<tr>
					<td colspan=2 bgcolor="#9290c6"><font size=2 color="#ffffff"><b>&nbsp;�����ε�����</b></font></td>
				</tr>
				<tr>
<?
enq_graph_woman($enquete_id, $question_no, $scale, $html);
?>
				</tr>
				<tr><td colspan=2><br></td></tr>
				<tr>
					<td colspan=2 bgcolor="#9290c6"><font size=2 color="#ffffff"><b>&nbsp;�����ε�����</b></font></td>
				</tr>
				<tr>
<?
enq_graph_man($enquete_id, $question_no, $scale, $html);
?>
				</tr>
				<tr><td colspan=2><br></td></tr>
				<tr>
					<td colspan=2 bgcolor="#9290c6"><font size=2 color="#ffffff"><b>&nbsp;�ȿȼԤε�����</b></font></td>
				</tr>
				<tr>
<?
enq_graph_mikon($enquete_id, $question_no, $scale, $html);
?>
				</tr>
				<tr><td colspan=2><br></td></tr>
				<tr>
					<td colspan=2 bgcolor="#9290c6"><font size=2 color="#ffffff"><b>&nbsp;�����Ԥε�����</b></font></td>
				</tr>
				<tr>
<?
enq_graph_kikon($enquete_id, $question_no, $scale, $html);
?>
				</tr>
			</table>
		</td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
