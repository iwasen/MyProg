<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP" />
<title>�������ơ�net �ͥåȥꥵ�����˥ץ�Υ���������Ȥ�</title>
<body bgcolor="#FFFFFF">
<?
	$url = 'mk/agent/mk_agintro1.php?agent_id=';
	
	$top = '.';
	$inc = "$top/../inc";
	include("$inc/common.php");
	include("$inc/database.php");
	
	// �����������ɽ��
	$sql = "SELECT ag_agent_id,ag_name1 FROM t_agent WHERE ag_status=0 AND ag_disp_flag ORDER BY ag_agent_id DESC";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

/*	
	echo("<table><tr><td nowrap>");
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		
		echo "<a href='javascript:void(0)' onclick='open(\"$url{$fetch->ag_agent_id}\", \"_blank\", \"width=800,height=730,scrollbars=yes,resizable=yes,status=yes\")'>";
		echo "<img src='images/agent{$fetch->ag_agent_id}.jpg' width=50 height=50 border=0>";
		echo "</a>&nbsp;";
	}
	echo("</td></tr></table>");
*/

	$fatch_arr = array();

	// ��̤�����˳�Ǽ	
	for ($i=0; $i<$nrow; $i++)
		$fetch_arr[] = pg_fetch_object($result, $i);


	// ��������¤��ؤ�
	shuffle($fetch_arr); 
	
	
	// ����
	echo("<table><tr><td nowrap>");
	for ($i = 0; $i < $nrow; $i++) {
		$fetch =$fetch_arr[$i];
		
		echo "<a href='javascript:void(0)' onclick='open(\"$url{$fetch->ag_agent_id}\", \"_blank\", \"width=800,height=730,scrollbars=yes,resizable=yes,status=yes\")'>";
		echo "<img src='images/agent{$fetch->ag_agent_id}.jpg' width=50 height=50 border=0>";
		echo "</a>&nbsp;";
	}
	echo("</td></tr></table>");
?>


</body>
</html>
