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
include("$inc/ma_profile.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

$owner_id = $_SESSION['ss_owner_id'];
$myenq = new my_enquete_class;
$myenq->read_db($owner_id, $my_enq_no);
$enquete = &$myenq->enquete;
$search = &$myenq->search;
?>
<? marketer_header('�ͣ����󥱡���', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_myenq2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<br>
			<table width="650" border="0" cellspacing="0" cellpadding="" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td>
						�����˺������ޤ�����
					</td>
				</tr>
			</table>
			<br>
			<table width="680" border="1" cellspacing="0" cellpadding="3" bordercolor="#ffffff" bordercolorlight="#ffffff" bordercolordark="#ffffff">
				<tr>
					<td width="100%" valign="top" colspan="2" bgcolor="#eff7e8">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td align="center">������������</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>���󥱡��ȥ����ȥ�</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=htmlspecialchars($enquete->title)?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>��������</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=$enquete->start_date_y?>ǯ<?=$enquete->start_date_m?>��<?=$enquete->start_date_d?>��</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>��λ����</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=$enquete->end_date_y?>ǯ<?=$enquete->end_date_m?>��<?=$enquete->end_date_d?>����<?=$enquete->end_date_h?>��</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>����ȯ����</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=$myenq->send_num?>��</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%" valign="top" colspan="2" bgcolor="#eff7e8">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td align="center">�����������</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>����</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=decode_sex($search->sex, '<br>')?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>ǯ��</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><? disp_age2($search->age_cd, $search->age_from, $search->age_to) ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>̤����</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=decode_mikikon($search->mikikon, '<br>')?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>�ｻ�ϰ�</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><? disp_area($search->jitaku_area) ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>����</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><? disp_shokugyou($search->shokugyou) ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>�ȼ�</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><? disp_gyoushu($search->gyoushu) ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>����</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><? disp_shokushu($search->shokushu) ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>�Ҷ���̵ͭ</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=multi_decode(decode_have_child, $search->have_child)?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>�������</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=multi_decode(decode_housing_form, $search->housing_form)?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>�֤ν�ͭ</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=multi_decode(decode_have_car, $search->have_car)?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>����ӥ�</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><? disp_conveni($search->conveni) ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>�����ѡ�</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><? disp_super($search->super) ?></td>
							</tr>
						</table>
					</td>
				</tr>
<?
// �ɲåץ�ե�����
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=htmlspecialchars($profile_name)?></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><? disp_ma_profile($profile_id, $search->ma_profile) ?></td>
							</tr>
						</table>
					</td>
				</tr>
<?
}
?>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>��̣������</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><? disp_genre($search->genre) ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<form method="post" action="mkk_myenq_erased.php">
			<input type="hidden" name="my_enq_no" value="<?=$my_enq_no?>">
			<table width="768" border="0" cellspacing="0" cellpadding="3">
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
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
