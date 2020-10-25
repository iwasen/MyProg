<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/search.php");
include("$inc/my_search.php");
include("$inc/ma_profile.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

// ������說�饹����
$search = new search_class;

// �ե����फ�鸡��������
$search->type = $type;
$search->sex = (count($sex) == 1) ? $sex[0] : '';
$search->age_cd = get_multi_data($age_cd);
$search->mikikon = (count($mikikon) == 1) ? $mikikon[0] : '';
$search->jitaku_area = get_multi_data($jitaku_area);
$search->shokugyou = get_multi_data($shokugyou);
$search->gyoushu = get_multi_data($gyoushu);
$search->shokushu = get_multi_data($shokushu);
$search->have_child = get_multi_data($_POST['have_child']);
$search->housing_form = get_multi_data($_POST['housing_form']);
$search->have_car = get_multi_data($_POST['have_car']);
$search->genre = get_multi_data($genre);
$search->conveni = get_multi_data($conveni);
$search->super = get_multi_data($super);
$search->ma_profile = get_multi_data($ma_profile);

if ($title == '')
	$msg[] = '�����ȥ�����Ϥ��Ƥ���������';
else {
	// ��˥���������
	$monitor_num = $search->get_monitor_num();

	// ����ID��My����������¸
	$owner_id = $_SESSION['ss_owner_id'];
	$sql = "SELECT ms_search_id FROM t_my_search WHERE ms_marketer_id=$owner_id AND ms_search_no=$search_no";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$search_id = $search->write_db();
		$sql = "INSERT INTO t_my_search (ms_marketer_id,ms_search_no,ms_title,ms_search_id,ms_monitor_num) VALUES (%s,%s,%s,%s,%s)";
	} else {
		$fetch = pg_fetch_row($result, 0);
		$search_id = $fetch[0];
		$search->write_db($search_id);
		$sql = "UPDATE t_my_search SET ms_marketer_id=%s,ms_search_no=%s,ms_title=%s,ms_search_id=%s,ms_monitor_num=%s WHERE ms_marketer_id=$owner_id AND ms_search_no=$search_no";
	}
	$sql = sprintf($sql,
			sql_number($owner_id),
			sql_number($search_no),
			sql_char($title),
			sql_number($search_id),
			sql_number($monitor_num));
	db_exec($sql);
}
?>
<? marketer_header('�ͣ����󥱡���', PG_NULL) ?>

<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="538" valign="top"><img src="images/mkk_mysearch/title_05.gif"><br><br></td>
	</tr>
</table>
<?
if (!$msg) {
?>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="8" border="0"></td>
		<td width="700"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td align="center">
		<form method="" action="mkk_mysearch.php">
			<table width="500" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
				<tr>
					<td width="30%" bgcolor="#eeeeee">No</td>
					<td width="70%"><?=$search_no?></td>
				</tr>
				<tr>
					<td width="30%" bgcolor="#eeeeee">�����ȥ�</td>
					<td width="70%"><?=htmlspecialchars($title)?></td>
				</tr>
				<tr>
					<td width="30%" bgcolor="#eeeeee">��˥�����</td>
					<td width="70%"><?=number_format($monitor_num)?></td>
				</tr>
				<tr>
					<td width="30%" bgcolor="#eeeeee">����</td>
					<td width="70%"><?=decode_sex($search->sex, '<br>')?></td>
				</tr>
				<tr>
					<td width="30%" bgcolor="#eeeeee">ǯ��</td>
					<td width="70%"><? disp_age($search->age_cd) ?></td>
				</tr>
				<tr>
					<td width="30%" bgcolor="#eeeeee">̤����</td>
					<td width="70%"><?=decode_mikikon($search->mikikon, '<br>')?></td>
				</tr>
				<tr>
					<td width="30%" bgcolor="#eeeeee">�ｻ�ϰ�</td>
					<td width="70%"><? disp_area($search->jitaku_area) ?></td>
				</tr>
				<tr>
					<td width="30%" bgcolor="#eeeeee">����</td>
					<td width="70%"><? disp_shokugyou($search->shokugyou) ?></td>
				</tr>
				<tr>
					<td width="30%" bgcolor="#eeeeee">�ȼ�</td>
					<td width="70%"><? disp_gyoushu($search->gyoushu) ?></td>
				</tr>
				<tr>
					<td width="30%" bgcolor="#eeeeee">����</td>
					<td width="70%"><? disp_shokushu($search->shokushu) ?></td>
				</tr>
				<tr>
					<td width="30%" bgcolor="#eeeeee">����ӥ�</td>
					<td width="70%"><? disp_conveni($search->conveni) ?></td>
				</tr>
				<tr>
					<td width="30%" bgcolor="#eeeeee">�����ѡ�</td>
					<td width="70%"><? disp_super($search->super) ?></td>
				</tr>
<?
// �ɲåץ�ե�����
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
				<tr>
					<td width="30%" bgcolor="#eeeeee"><?=htmlspecialchars($profile_name)?></td>
					<td width="70%"><? disp_ma_profile($profile_id, $search->ma_profile) ?></td>
				</tr>
<?
}
?>
				<tr>
					<td width="30%" bgcolor="#eeeeee">��̣������</td>
					<td width="70%"><? disp_genre($search->genre) ?></td>
				</tr>
			</table>
			<br>
			<table width="450" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>�� ���ܺ٤ʸ����򤴴�˾�����ϡ�����������ȤޤǤ�Ϣ����������</td>
				</tr>
			</table>
			<table width="500" valign="top" bgcolor="#ffffff" align="center">
				<tr>
					<td width="100%" align="right">
						
						<a href="mkk_mysearch.php"><img src="images/mkk_achange/bt01.gif" alt="���" name="image2" width="108" height="31" border="0"></a>
						
					</td>
				</tr>
			</table>
  </form>
		</td>
	</tr>
</table>
<?
} else{?>
<table width="788" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><? error_msg($msg);?></td>
  </tr>
</table>
<? } ?></td>
    <td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
