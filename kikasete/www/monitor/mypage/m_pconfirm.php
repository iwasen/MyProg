<?
$top = './..';
$inc = "$top/../inc";
include("$inc/check.php");
include("$inc/format.php");
include("$inc/point.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");
include("$inc/mn_chara.php");
include("$inc/mn_error.php");

// �򴹾��ʥǥ�����
function decode_shouhin($code) {
	switch ($code) {
	case 1:
		return 'VISA���եȷ�';
	case 2:
		return '�޽��';
	case 3:
		return 'WEBMONEY';
	case 4:
	//jeon_start seq=ad1
	//	return 'WEBMONEY';
		return '��ŷ���';
	//jeon_start seq=ad1	
	case 5:
		return '�����';
	}
}

// ���Ϲ��ܥ����å�
$sql = "SELECT mp_curr_point FROM t_monitor_point WHERE mp_monitor_id={$_SESSION['ss_monitor_id']}";
$curr_point = db_fetch1($sql);
$min_exchange_point = get_min_exchange_point($shouhin);
if ($ex_point == 0)
	$msg[] = '��������ݥ���ȿ������Ϥ��Ƥ���������';
elseif (!check_num($ex_point))
	$msg[] = '�����ݥ���ȿ������������Ϥ���Ƥ��ޤ���';
elseif ($shouhin == 1 && $ex_point % 1000 != 0)
	$msg[] = "�����ݥ���ȿ���1000ñ�̤����Ϥ��Ƥ���������";
elseif ($shouhin == 4 && $ex_point != 200 && $ex_point != 500 && $ex_point % 1000 != 0)
	$msg[] = "�����ݥ���ȿ���200�ޤ���500������ʾ��1000ñ�̤����Ϥ��Ƥ���������";
elseif ($shouhin == 5 && $ex_point != $min_exchange_point)
	$msg[] = "������{$min_exchange_point}�ݥ���Ȥ����Ϥ��Ƥ���������";
else {
	$charge_point = get_charge_point($shouhin);
	if ($ex_point + $charge_point > $curr_point)
		$msg[] = '�ݥ���ȿ���­��ޤ���';
	elseif ($ex_point < $min_exchange_point)
		$msg[] = "�����Ǥ���ݥ���ȿ���{$min_exchange_point}�ݥ���Ȱʾ�Ǥ���";
}

if ($shouhin == '')
	$msg[] = '�򴹤��������ʤ����򤷤Ƥ���������';
// jeon_start seq=ad1
if (!$msg) {
	if ($shouhin == 4) {
		if($siten_name=='')
			$msg[] = '��Ź̾�����Ϥ��Ƥ���������';
		if($siten_no=='')
			$msg[] = '��Ź�ֹ�����Ϥ��Ƥ���������';
		else{
			$str = $siten_no;
			for($i =0;$i<strlen($str);$i++){
				echo $str{$i};
				if($str{$i}>=0 && $str{$i}<=9){	
					
				}else{
					$msg[] = 'Ⱦ�ѿ����Τߡ����Ϥ��Ƥ���������';
					break;
				}
			
			}
	
		}
		if($kouza_no=='')
			$msg[] = '�����ֹ�����Ϥ��Ƥ���������';
		if($kouza_name=='')
			$msg[] = '����̾�������Ϥ��Ƥ���������';
	// jeon_end seq=ad1					
	}elseif ($shouhin == 1){
		if ($name1 == '')
			$msg[] = '��̾���������Ϥ��Ƥ���������';

		if ($name2 == '')
			$msg[] = '��̾��̾�����Ϥ��Ƥ���������';

		if ($zip1 == '' || $zip2 == '')
			$msg[] = '͹���ֹ�����Ϥ��Ƥ���������';
		elseif (!check_zip($zip1, $zip2))
			$msg[] = '͹���ֹ椬���������Ϥ���Ƥ��ޤ���';

		if ($addr1 == '')
			$msg[] = '���꣱����ƻ�ܸ��ˤ����Ϥ��Ƥ���������';

		if ($addr2 == '')
			$msg[] = '���ꣲ�ʻԶ�Į¼�ˤ����Ϥ��Ƥ���������';

		if ($addr3 == '')
			$msg[] = '���ꣳ�����ϡˤ����Ϥ��Ƥ���������';

		if ($tel1 == '' || $tel2 == '' || $tel3 == '')
			$msg[] = '�����ֹ�����Ϥ��Ƥ���������';
		elseif (!check_tel($tel1, $tel2, $tel3))
			$msg[] = '�����ֹ椬���������Ϥ���Ƥ��ޤ���';
	}
}
$sql = "select mn_name1,mn_name2 from t_monitor where mn_monitor_id={$_SESSION['ss_monitor_id']}";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
if (!$msg) {
	if ($shouhin == 4 || $shouhin == 5) {
		// WEBMONEY�ξ��
		$name1 = $fetch->mn_name1;
		$name2 = $fetch->mn_name2;
		$name = '-';
		$addr = '-';
		$zip = '-';
		$tel = '-';
	} else {
		// WEBMONEY�ʳ��ξ��
		$name = "$name1 $name2";
		$addr = trim("$addr1$addr2$addr3 $addr4");
		$zip = "$zip1$zip2";
		$tel = format_tel($tel1, $tel2, $tel3);
	}
}
?>
<? monitor_menu() ?>
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#85A9DE>
				<TD><IMG height=29 src="<?=$img?>/spacer.gif" width=1 align=absMiddle>
					<SPAN class=mysttl_menu>�����ݥ����</SPAN>
				</TD>
			</TR>
			<TR>
				<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD></TR>
			<TR>
				<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
		</TBODY>
	</TABLE>
<br>
<table width="496" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15">��</td>
		<td colspan="3">
<?
if (!$msg) {
?>
			<table width="450" border="0" cellspacing="0" cellpadding="20">
				<tr>
					<td><font color="black" size="2">�������Ƥ򤴳�ǧ����������<br><br></font> 
						<table width="400" border="0" cellspacing="0" cellpadding="1" bgcolor="#9999ff">
							<tr>
								<td>
									<table width="100%" border="0" cellspacing="1" cellpadding="2">
										<tr>
											<td width="93" bgcolor="#ccccff"><font size="2">�����ݥ����</font></td>
											<td width="307" bgcolor="white"><font size="2"><?=number_format($ex_point)?>�ݥ����</font></td>
										</tr>
										<tr>
											<td width="93" bgcolor="#ccccff"><font size="2">�򴹾���</font></td>
											<td width="307" bgcolor="white"><font size="2"><?=decode_shouhin($shouhin)?></font></td>
										</tr>
								<!-- jeon_start seq=ad1 -->		
								<?if ($shouhin == 4) {?>
										<tr>
											<td width="93" bgcolor="#ccccff"><font size="2">��Ź̾</font></td>
											<td width="307" bgcolor="white"><font size="2"><?=htmlspecialchars($siten_name)?></font></td>
										</tr>
										<tr>
											<td width="93" bgcolor="#ccccff"><font size="2">��Ź�ֹ�</font></td>
											<td width="307" bgcolor="white"><font size="2"><?=$siten_no?></font></td>
										</tr>
										<tr>
											<td width="93" bgcolor="#ccccff"><font size="2">�����ֹ�</font></td>
											<td width="307" bgcolor="white"><font size="2"><?=$kouza_no?></font></td>
										</tr>
										<tr>
											<td width="93" bgcolor="#ccccff"><font size="2">����̾��</font></td>
											<td width="307" bgcolor="white"><font size="2"><?=htmlspecialchars($kouza_name)?></font></td>
										</tr>
								<?} elseif ($shouhin == 1) {?>		
								<!-- jeon_end seq=ad1 -->	
										<tr>
											<td width="93" bgcolor="#ccccff"><font size="2">��̾��</font></td>
											<td width="307" bgcolor="white"><font size="2"><?=htmlspecialchars($name)?></font></td>
										</tr>
										<tr>
											<td width="93" bgcolor="#ccccff"><font size="2">͹���ֹ�</font></td>
											<td width="307" bgcolor="white"><font size="2"><?=format_zip($zip)?></font></td>
										</tr>
										<tr>
											<td width="93" bgcolor="#ccccff"><font size="2">������</font></td>
											<td width="307" bgcolor="white"><font size="2"><?=htmlspecialchars($addr)?></font></td>
										</tr>
										<tr>
											<td width="93" bgcolor="#ccccff"><font size="2">�����ֹ�</font></td>
											<td width="307" bgcolor="white"><font size="2"><?=$tel?></font></td>
										</tr>
								<?}?>		
										<br>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<form name="form1" method="post" action="m_pcomp.php">
				<div align="center">
					<input type="button" value="�ݥ���������򤹤�" onclick="disabled=true;document.form1.submit()">
					<input type="button" value="����롡" onclick="history.back()">
					<input type="hidden" name="param" <?=value(serialize($_POST))?>>
				</div>
			</form>
<?
} else
	error_msg($msg);
?>
		</td>
	</tr>
</table>
