<?
$top = './..';
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");

db_begin_trans();

// ���ե��ꥨ���ȿ���
$rec['af_monitor_id'] = sql_number($_SESSION['ss_monitor_id']);
$rec['af_affiliate_mgr_id'] = sql_number($affiliate_mgr_id);
$rec['af_intro_num'] = sql_number($intro_num);
db_insert('t_affiliate', $rec);
$affiliate_id = get_currval('t_affiliate_af_affiliate_id_seq');

// ���ե��ꥨ���ȥ������
$rec['afc_affiliate_id'] = sql_number($affiliate_id);
db_insert('t_affiliate_cnt', $rec);

// ����Ϳ�������ȥ��å�
$sql = "UPDATE t_affiliate_mgr SET afm_appl_count=afm_appl_count+1 WHERE afm_affiliate_mgr_id=$affiliate_mgr_id";
db_exec($sql);

db_commit_trans();

db_vacuum('t_affiliate_cnt');
db_vacuum('t_affiliate_mgr');
?>
<? monitor_menu() ?>
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#c0e2fa>
				<TD>
					<IMG height=29 alt=���ե��ꥨ���� src="<?=$img?>/my_affiliate.gif" width=45 align=absMiddle>
					<SPAN class=mysttl>���ե��ꥨ���� </SPAN>
				</TD>
			</TR>
			<TR>
				<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
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
			<table width="481" border="0" cellspacing="0" cellpadding="20">
				<tr>
					<td>
						<table border="0" cellpadding="3" cellspacing="2" width="100%">
							<tr>
								<td bgcolor="#525294"><font color="#ffcc00">����</font><font color="white">�������ơ�net�Ҳ𡡱���ե�����</font></td>
							</tr>
							<tr>
								<td>
										<tr>
											<td><br><font size="2">�����礢�꤬�Ȥ��������ޤ�����</font></td>
										</tr>
										<tr>
										</tr>
										<tr>
											<td colspan="3"><hr></td>
										</tr>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
