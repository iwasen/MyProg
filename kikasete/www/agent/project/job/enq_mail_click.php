<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:�᡼��ʸ��Ͽ
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");

// ����ɽ��
function format_date2($from,$to,$def = '̤����') {
	if ($from && $to)
		return date('Y/m/d H:i', sql_time($from)).' �� '.date('Y/m/d H:i', sql_time($to));
	else
		return $def;
}

// �ᥤ�����

set_global2('project', '�ץ������ȴ���', '����å�����������', BACK_TOP);

if (is_array($cc_ct_id)) {
	foreach ($cc_ct_id as $i) {
		$rec['cc_job_id'] = sql_number($job_id);
		$rec['cc_url_name'] = sql_char($cc_url_name[$i]);
		$rec['cc_jump_url'] = sql_char($cc_jump_url_org[$i]);
		$rec['cc_start_date'] = sql_date2($cc_start_year, $cc_start_month, $cc_start_day, $cc_start_hour);
		$rec['cc_end_date'] = sql_date2($cc_end_year, $cc_end_month, $cc_end_day, $cc_end_hour);
		db_insert('t_click_counter', $rec);
		$ct_id = get_currval('t_click_counter_cc_ct_id_seq');

		$fetch = get_system_info('sy_url_top');
		$body = str_replace($cc_jump_url[$i], "{$fetch->sy_url_top}ct.php?{$ct_id}-%CLICK%", $body);
	}
}

if (isset($body))
	$body = preg_replace('/{###[0-9][0-9][0-9]###}/', 'http', $body);
else
	$body = $mail_format ? $html_body : $jb_body;

$fetch = get_system_info('sy_url_top');
$url_top = $fetch->sy_url_top;

$sql = "SELECT cc_ct_id,cc_url_name,cc_jump_url,cc_start_date,cc_end_date"
		. " FROM t_click_counter"
		. " WHERE cc_job_id=$job_id"
		. " ORDER BY cc_ct_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onload_body() {
	opener.form1.<?=$mail_format ? 'html_body' : 'jb_body'?>.value = document.form1.body.value;
}
//-->
</SCRIPT>
</head>
<body onload="onload_body()">

<form method="post" name="form1" action="enq_edit_click.php">
<center>
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="m0">������å������󥿰���</td>
		<td class="lb">
			<input type="submit" value="���Խ���">
			<input type="button" value="�Ĥ���" onclick="window.close()">
		</td>
	</tr>
</table>
<table <?=LIST_TABLE?> width=100%>
	<tr class="tch">
		<th>CTID</th>
		<th>URL̾��</th>
		<th>������ȴ���</th>
		<th>������URL</th>
		<th>����URL</th>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$click_url = "ct.php?{$fetch->cc_ct_id}-%CLICK%";

	if (strpos($body, $click_url) !== false) {
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->cc_ct_id?></td>
		<td><?=htmlspecialchars($fetch->cc_url_name)?></td>
		<td align="center"><?=format_date2($fetch->cc_start_date,$fetch->cc_end_date)?></td>
		<td><a href="<?=$fetch->cc_jump_url?>"  target='_blank'><?=htmlspecialchars($fetch->cc_jump_url)?></a></td>
		<td><?=$url_top?><?=$click_url?></td>
	</tr>
<?
	} else {
		$sql = "DELETE FROM t_click_counter WHERE cc_ct_id=$fetch->cc_ct_id";
		db_exec($sql);
	}
}
?>
</table>
<br>
<input type="hidden" name="job_id" <?=value($job_id)?>>
<input type="hidden" name="mail_format" <?=value($mail_format)?>>
<textarea name="body" style="display:none"><?=$body?></textarea>
</center>
</form>

</body>
</html>
