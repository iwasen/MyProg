<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");

$staff_id = $_SESSION['ss_staff_id'];

if ($my_enq_no) {
	$sql = "SELECT me_enquete_id,me_send_num,me_real_send FROM t_my_enquete WHERE me_staff_id=$staff_id AND me_my_enq_no=$my_enq_no";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$send_num = $fetch->me_send_num;
		$real_send = $fetch->me_real_send;
		$enquete_id = $fetch->me_enquete_id;
	} else
		redirect('enq.php');
}

$sql = "SELECT en_enq_type,en_title,en_start_date,en_end_date,en_point FROM t_enquete WHERE en_enquete_id=$enquete_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);
} else
	redirect('enq.php');
?>
<? staff_header('�ץ������Ⱦܺ�ɽ��') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="400" valign="top"><img src="images/t_mkk_enqdet1.gif" width="400" height="20" border="0"><br><br></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td rowspan="2" width="20"><img src="images/shim.gif" width="20" height="1" border="0"></td>
		<td width="700"><img src="images/shim.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="580" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<font size="3" class="honbun1">
						<b>�ۤˤ��</b> ���󥱡���<!--�ץ������Ȥξ��ϡ��֡������󥱡��ȥ���֤ξܺ٤Ǥ����פ�ɽ��-->�ξܺ٤Ǥ���
						</font>
					</td>
				</tr>
			</table>
			<br><br>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>�ץ������ȣɣ�</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">417</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>�ץ�������̾</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">�ۤˤ��</font>
					</td>
				</tr>
				<!--�ʣϣ£ɣġ��ʣϣ�̾�ˤĤ��Ƥϡ��ץ������Ȥξ��Τ�ɽ��-->
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>����֣ɣ�</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">10125</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>�����̾</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">�ۤˤ��</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>����</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">�ף�⥢�󥱡���</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>�ݥ����</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">10</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>������</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">2001ǯ08��07��</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>��λ��</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">2001ǯ08��10��10��</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>ȯ����</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">300</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>������</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">
						300
						<a href="enq_smail.php">�������ԥץ�ե������</a>
						</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>������</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">
						100
						<a href="enq_rmail.php">�ʼ����ԥץ�ե������</a>
						</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>�᡼�륳��ƥ��</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">
						<a href="enq_mail.php">�ʥ᡼�륳��ƥ�ġ�</a>
						</font>
					</td>
				</tr>				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>�оݼԾ��</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">
						<a href="enq_pollee.php">���оݼԾ���</a>
						</font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>����</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2">��λ</font>
					</td>
				</tr>
			</table>
			<br><br>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="30%" bgcolor="#eeeeee"><font size="2" class="honbun2">���׷��ɽ��</font></td>
					<td width="70%" colspan="2">
						<font size="2" class="honbun2">
							<a href="resdet_pv.php">���׷��ɽ��</a>
						</font>
					</td>
				</tr>
				<tr>
					<td width="30%" bgcolor="#eeeeee" rowspan="3"><font size="2" class="honbun2">CSV���������</font></td>
					<td width="40%"><font size="2" class="honbun2">�����ԥꥹ��</font></td>
					<td width="30%">
						<form method="post" action="enq_dl1.php">
						<input type="hidden" name="enq_cd" value="">
						<input type="hidden" name="type" value="s">
						<font size="3" class="honbun1"><input type="submit" name="sl" value="���������"></font>
						</form>
					</td>
				</tr>
				<tr>
					<td width="40%"><font size="2" class="honbun2">�����ԥꥹ��</font></td>
					<td width="30%">
						<form method="post" action="enq_dl1.php">
						<input type="hidden" name="enq_cd" value="">
						<input type="hidden" name="type" value="recv">
						<font size="3" class="honbun1"><input type="submit" name="rl" value="���������"></font>
						</form>
					</td>
				</tr>
				<tr>
					<td width="40%"><font size="2" class="honbun2">�����ܲ�����°��</font></td>
					<td width="30%">
						<form method="post" action="enq1a.php">
						<input type="hidden" name="MYECD" value="">
						<input type="hidden" name="MKCD" value="">
						<font size="3" class="honbun1"><input type="submit" name="al" value="���������"></font>
						</form>
					</td>
				</tr>
			</table>
			<br>
			<!--���󥱡��Ȥξ��Τ�ɽ��-->
			<table width="700" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="3"><font size="3" class="honbun1"><b>�� ���󥱡��Ȥη�̤�Ǥ�դΥ��ɥ쥹������</b></font></td>
				</tr>
				<tr>
					<td colspan="3"><img src="images/shim.gif" width="10" height="10"></td>
				</tr>
				<tr>
					<td width="500"><font size="3" class="honbun1">���󥱡��Ȥη�̤�Ǥ�դΥ��ɥ쥹���������뤳�Ȥ��Ǥ��ޤ���<br>
					���Υ��󥱡��Ȥη�̡ʽ��׷�̵ڤӣãӣ֥�������ɡˤ򶵤������Υ᡼�륢�ɥ쥹�򤴵�������������<br>
					�� ʣ��������������ϡ��᡼�륢�ɥ쥹�򥫥�޶��ڤ������Ƥ���������</font></td>
					<td width="15" align="center"><img src="images/line_t.gif" width="10" height="80" border="0"></td>
					<td width="185"><textarea rows="5" cols="30" name="mailadress"></textarea></td>
				</tr>
				<tr>
					<td colspan="3"><img src="images/shim.gif" width="10" height="10"></td>
				</tr>
				<tr>
					<form method="post" action="notice_myenq.php"><td colspan="3" align="right"><font size="3" class="honbun1"><input type="submit" value="��Ͽ"></font></td></form>
				</tr>
			</table>
		</td>
	</tr>
</table>

<? staff_footer() ?>
