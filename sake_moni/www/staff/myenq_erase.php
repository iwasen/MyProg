<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/my_search.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");

$staff_id = $_SESSION['ss_staff_id'];
$myenq = new my_enquete_class;
$myenq->read_db($staff_id, $my_enq_no);
$enquete = &$myenq->enquete;
$search = &$myenq->search;
?>
<? staff_header('���󥱡�����Ͽ') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

			<table width="650" border="0" cellspacing="0" cellpadding="" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td>
						<font size="3" class="honbun1">�������˺������ޤ�����</font>
					</td>
				</tr>
			</table>
			<br>
			<table width="680" border="1" cellspacing="0" cellpadding="3" bordercolor="#ffffff" bordercolorlight="#ffffff" bordercolordark="#ffffff">
				<tr>
					<td width="100%" valign="top" colspan="2" bgcolor="#eff7e8">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td align="center"><font size="2" class="honbun2">������������</font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">���󥱡��ȼ���</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><?=decode_enq_type($enquete->enq_type)?></font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">��������</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><?=$enquete->start_date_y?>ǯ<?=$enquete->start_date_m?>��<?=$enquete->start_date_d?>��</font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">��λ����</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><?=$enquete->end_date_y?>ǯ<?=$enquete->end_date_m?>��<?=$enquete->end_date_d?>����<?=$enquete->end_date_h?>��</font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">�ݥ���ȿ�</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><?=$enquete->point?>�ݥ����</font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">����ȯ����</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><?=$myenq->send_num?>��</font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%" valign="top" colspan="2" bgcolor="#eff7e8">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td align="center"><font size="2" class="honbun2">�����������</font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">����</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><?=decode_sex($search->sex, '<br>')?></font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">ǯ��</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><? disp_age2($search->age_cd, $search->age_from, $search->age_to) ?></font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">̤����</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><?=decode_mikikon($search->mikikon, '<br>')?></font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">�Ҷ���̵ͭ</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><?=decode_child($search->child, '<br>')?></font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">�ｻ�ϰ�</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><? disp_area($search->jitaku_area) ?></font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">����</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><? disp_shokugyou($search->shokugyou) ?></font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">��������</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><? disp_chain($search->chain) ?></font></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<form method="post" action="myenq_erased.php">
			<input type="hidden" name="my_enq_no" value="<?=$my_enq_no?>">
			<table width="700" border="0" cellspacing="0" cellpadding="3">
				<tr>
					<td width="50%" align="right">
						<input type="submit" value="�������">
					</td>
					<td width="50%" align="left">
						<input type="button" value="����롡" onclick="history.back()">
					</td>
				</tr>
			</table>
			</form>

<? staff_footer() ?>
