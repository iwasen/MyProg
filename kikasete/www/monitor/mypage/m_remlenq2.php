<?
$top = './..';
$inc = "$top/../inc";
include("$inc/format.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");

// ���󥱡���ID��̵����Хȥåפ��᤹
if ($enquete_id == ''){
	redirect('m_index_main.php');
}
// --- SEQ AD3 START -----------------------------------
// WEB���󥱡��Ȥ��᡼�륢�󥱡��Ȥ����̤���
if($en_type==''){
	$en_enq_type_vari=1;
}else{
	$en_enq_type_vari=2;
}
// --- SEQ AD3 END -----------------------------------
//��WEB���󥱡��Ȥ��᡼�륢�󥱡��Ȥ����̤���SQL�����
// �����Υ��󥱡��Ȥ�̵����Хȥåפ��᤹
$monitor_id = $_SESSION['ss_monitor_id'];
$sql = "SELECT en_title,en_description,en_end_date,en_point"
		. " FROM t_enquete JOIN t_enquete_list ON el_enquete_id=en_enquete_id AND el_monitor_id=$monitor_id"
		. " WHERE en_enquete_id=$enquete_id AND en_status=5 AND en_enq_type=".$en_enq_type_vari; 
/* --- SEQ AD3 START -----------------------------------		
 * Original SQL
 * $sql = "SELECT en_title,en_description,en_end_date,en_point"
 * . " FROM t_enquete JOIN t_enquete_list ON el_enquete_id=en_enquete_id AND el_monitor_id=$monitor_id"
 * . " WHERE en_enquete_id=$enquete_id AND en_status=5 AND en_enq_type=1";
 *
 * --- SEQ AD3 END -----------------------------------
 */
$result = db_exec($sql);
if (pg_numrows($result) == 0){
	redirect('m_index_main.php');
}

$fetch = pg_fetch_object($result, 0);
$title = htmlspecialchars($fetch->en_title);
$end_date = format_date($fetch->en_end_date);
$point = number_format($fetch->en_point);
?>
<? monitor_menu() ?>
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
	<TBODY>
		<TR bgColor=#c0e2fa>
			<TD><IMG height=29 alt=�̾異�󥱡��� src="<?=$img?>/my_enq.gif" width=45 align=absMiddle>
				<SPAN class=mysttl> �̾異�󥱡��� </SPAN>
			</TD>
		</TR>
		<TR>
			<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD></TR>
		<TR>
			<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
		</TR>
	</TBODY>
</TABLE>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15">��</td>
		<td colspan="2">
			<table border="0" cellpadding="20" cellspacing="0" width="90%" bgcolor="white">
				<tr>
					<td>
						<table border="0" cellpadding="3" cellspacing="2" width="100%">
							<tr>
								<td bgcolor="#525294"><font color="#ffcc00">��</font><font color="white">�������¤ޤǤˤ��������������͡�</font></td>
							</tr>
							<tr>
								<td>
										<font size="2"><br>���Ĥ⥢�󥱡��Ȥˤ����Ϥ������������꤬�Ȥ��������ޤ����⤷�褫�ä��顢�ʲ��Υ��󥱡��Ȥˤ⤴���Ϥ��������͡�</font><br>
										<table border="0" cellpadding="3" cellspacing="2" width="100%">
											<tr bgcolor="#c3c2ff">
												<td width="203"><font size="2">�ơ���</font></td>
												<td width="90"><font size="2">���󥱡��Ȥμ���</font></td>
												<td width="83"><font size="2">��������</font></td>
												<td width="42"><font size="2">�ݥ����</font></td>
											</tr>
											<tr bgcolor="#dedede">
												<td width="203"><font size="2"><?=$title?></font></td>
												<td width="90"><font size="2">
												
												<? // --- SEQ AD3 START ----------------------
													//���̤�WEB���󥱡��Ȥ��᡼�륢�󥱡��Ȥ����̤���ɽ������
												if($en_type==''){ ?>
													�᡼�륢�󥱡���
												<? }else{ ?>
													WEB ���󥱡���
												<? }
													// --- SEQ AD3 END -----------------------
												?>
												</font></td>
												<td width="83"><font size="2"><?=$end_date?>�ޤ�</font></td>
												<td width="42"><font size="2"><?=$point?>P</font></td>
											</tr>
										</table>
										<font size="2"><br>���������ơ�net ��̳�ɤ����Ϥ������󥱡��ȥ᡼���ʤ����Ƥ��ޤä��Ȥ������ϡ������Υܥ���򲡤��ơ��᡼��κ������¥���Ƥ���������<br>�������������ϣ���ΤߤȤʤäƤ���ޤ��Τǡ�����դ���������<br><br><br>
									<a href="m_remlenq2a.php?enquete_id=<?=$enquete_id?>&en_type=<?=$en_type?>">�᡼����������</a><br><br>
									</font>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>