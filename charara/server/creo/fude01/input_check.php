<?
/******************************************************
' System :�֤�����Factory�ץ��쥪�����ڡ���
' Content:���ϲ���
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/get_form.php");
include("$inc/check.php");
$inc = "../inc";
include("$inc/regist.php");

// ���å�������
session_start();
if (!isset($_SESSION['ss_regist']))
	redirect('index.php');
$reg = &$_SESSION['ss_regist'];

// ��Ͽ�Ѥߥ����å�
function check_regist($addr, $week_no) {
	$sql = "SELECT df_mail_addr FROM d_fude01 WHERE df_mail_addr=". sql_char($addr) . " AND df_week_no=" . $week_no;
	$result = db_exec($sql);
	return pg_numrows($result);
}

// �ɣ�ͭ���������å�
function check_id($id) {
	if (substr($id, 0, 2) == 'CE' && ereg("^[0-9]+$", substr($id, 2, 10)))
		return true;
}

// �ե����फ��ǡ�������
$reg->df_creo_id = $df_creo_id;
$reg->df_name1 = $df_name1;
$reg->df_name2 = $df_name2;
$reg->df_name1_kana = get_name_kana($df_name1_kana);
$reg->df_name2_kana = get_name_kana($df_name2_kana);
$reg->df_mail_addr = get_mail_addr(strtolower($df_mail_addr));

$reg->get_week_no(date("Ymd"));
if (!$reg->week_no)
	redirect('index.php');
	
if ($reg->df_creo_id == '')
	$msg[] = '����ֹ椬���Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (strlen($reg->df_creo_id) != 12)
	$msg[] = '����ֹ椬�������ʤ��褦�Ǥ���';
elseif (!check_id($reg->df_creo_id))
	$msg[] = '����ֹ椬�������ʤ��褦�Ǥ���';

if ($reg->df_name1 == '')
	$msg[] = '��̾�������ˤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';

if ($reg->df_name2 == '')
	$msg[] = '��̾����̾�ˤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';

if ($reg->df_name1_kana == '')
	$msg[] = '�եꥬ�ʡ����ˤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';

if ($reg->df_name2_kana == '')
	$msg[] = '�եꥬ�ʡ�̾�ˤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';

if ($reg->df_mail_addr == '')
	$msg[] = '�᡼�륢�ɥ쥹�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (!check_mail_addr($reg->df_mail_addr))
	$msg[] = '�᡼�륢�ɥ쥹�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (check_regist($reg->df_mail_addr, $reg->week_no))
	$msg[] = '���Ϥ��줿�᡼�륢�ɥ쥹�Ϥ��Ǥ���Ͽ����Ƥ���褦�Ǥ���';

if (!$msg)
	redirect('confirm.php');

?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>�֤ˤ������ץ����ڡ����</title>

<script language="JavaScript">
<!--
mac=(navigator.appVersion.indexOf('Mac') != -1)?true:false;
ie=(navigator.appName.charAt(0) == "M")?true:false;
ns=(navigator.appName.charAt(0) == "N")?true:false;

if(mac)	{
	fName = "css/mac.css";
}
else	{
	if(ie)	{
	fName = "css/win_ie.css";
	}
	else if(ns)	{
	fName = "css/win_ns.css";
	}
}

if(fName != null)	{
	document.write("<link rel=stylesheet href='"+fName+"' type='text/css'>");
}
//-->
</script>



</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align="center">
<br>
<table>
	<tr>
		<td>
			<table border="0" width="400" cellspacing="10">
				<td colspan="2" height="2" bgcolor="#CCFF33"><img src="img/spacer.gif" width="400" height="2"></td>
					<tr>
						<td align="center">
							<font size="2" color="red"><br>�� ����ǧ���ꤤ���ޤ�<br></font>
						</td>
					</tr>
					<tr>
						<td>
							<font size="2"><br><?=disp_msg($msg, '����', '<br><br>')?><br></font>
						</td>
					</tr>
					<tr>
						<td colspan="2" height="2" bgcolor="#CCFF33"><img src="img/spacer.gif" width="400" height="2"></td>
					</tr>
				</td>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center">
			<input type="image" src="img/back.gif" onclick="history.back()" width=106 height=46 alt="���">
		</td>
	</tr>
<table>
</div>
</body>
</html>