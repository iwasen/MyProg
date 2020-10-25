<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/decode.php");
include("$inc/pro_enquete.php");
include("$inc/pro_enq_target.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// ���ɽ��
function link_tag($kind, $str) {
	global $pro_enq_no;

	if ($_GET['disp'] == $kind)
		return "<strong>$str</strong>";
	else
		return "<a href='pe_enq_profile.php?pro_enq_no=$pro_enq_no&disp=$kind'>$str</a>";
}

// �ץ�ե�����ɽ��
function disp_profile($str, $sql, $item_ary) {
	$cnt_ary = array();
	$sum = 0;

	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);

		$cnt_ary[$fetch[0]] = $fetch[1];
		$sum += $fetch[1];
	}
?>
			<br>
			��<?=$str?>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr align="center" bgcolor="#eeeeee">
					<td width="30%"><?=$str?></td>
					<td width="35%">�Ϳ�</td>
					<td width="35%">���ʡ��</td>
				</tr>
<?
	foreach ($item_ary as $code => $text) {
?>
				<tr align="center">
					<td><?=$text?></td>
					<td><?=number_format($cnt_ary[$code])?></td>
					<td><?=$sum ? round($cnt_ary[$code] / $sum * 100) : 0?></td>
				</tr>
<?
	}
?>
				<tr align="center">
					<td>���</td>
					<td><?=number_format($sum)?></td>
					<td>100</td>
				</tr>
			</table>
<?
}

if ($pro_enq_no == '')
	redirect('pe_index.php');

$owner_id = $_SESSION['ss_owner_id'];
$pro_enq = new pro_enquete_class;
$pro_enq->read_db($owner_id, $pro_enq_no);
$enquete = &$pro_enq->enquete;
$enquete_id = $pro_enq->enquete_id;

$job_name = htmlspecialchars($enquete->title);
$enq_type = decode_enq_type($enquete->enq_type);

// ���ѥͥåȥ���μ���
$pro_enq_target = new pro_enq_target_class;
$pro_enq_target->read_db($enquete_id);
$target_flg = $pro_enq_target->target_flg;
?>
<? marketer_header('���׷��ɽ��', PG_NULL) ?>

<script type="text/javascript">
<!--
function open_fa(fn, sort) {
	var f = document.forms[fn];
	f.sort.value = sort;
	f.submit();
}
function check_fa(qno, flag) {
	document.getElementById("fa1_" + qno).style.display = flag ? "none" : "";
	document.getElementById("fa2_" + qno).style.display = flag ? "" : "none";
}
//-->
</script>
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
		<td width="400" valign="top"><strong>��ե�����ǥ��󥰲����ԥץ�ե������</strong></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="580" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="right">
						
						<?=link_tag('all', '����')?>���á�<?=link_tag('sex', '����')?>���á�<?=link_tag('age', 'ǯ��')?>���á�<?=link_tag('mikikon', '̤����')?>���á�<?=link_tag('shokugyo', '����')?>���á�<?=link_tag('kyoju', '�ｻ��')?>���á�<a href="pe_enqdet1.php?pro_enq_no=<?=$pro_enq_no?>">���󥱡��Ⱦܺ�ɽ�������</a>
					</td>
				</tr>
			</table>
			<br><br>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="25%" align="right" bgcolor="#eeeeee">���󥱡���ID����</td>
					<td width="75%"><?=$enquete_id?></td>
				</tr>
				<tr>
					<td width="25%" align="right" bgcolor="#eeeeee">���󥱡���̾����</td>
					<td width="75%"><?=$job_name?>�ˤĤ��ƤΥ��󥱡���</td>
				</tr>
				<tr>
					<td width="25%" align="right" bgcolor="#eeeeee">���ࡡ��</td>
					<td width="75%"><?=$enq_type?></td>
				</tr>
			</table>
<?
// ����
if ($disp == 'all' || $disp == 'sex') {
	$item_ary = array();
	$item_ary[1] = decode_sex(1);
	$item_ary[2] = decode_sex(2);

	$sql = "SELECT mn_sex,COUNT(*)"
			. " FROM t_answer"
			. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
			. " WHERE an_enquete_id=$enquete_id AND an_valid_flag"
			. " GROUP BY mn_sex";
	disp_profile('����', $sql, $item_ary);
}

// ǯ��
if ($disp == 'all' || $disp == 'age') {
	$item_ary = array();
	$sql = "SELECT ge_age_cd,ge_age_text FROM m_age10 ORDER BY ge_age_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$item_ary[$fetch->ge_age_cd] = $fetch->ge_age_text;
	}

	$sql = "SELECT ge_age_cd,COUNT(*)"
			. " FROM t_answer"
			. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
			. " JOIN m_age10 ON mn_age BETWEEN ge_age_from AND ge_age_to"
			. " WHERE an_enquete_id=$enquete_id AND an_valid_flag"
			. " GROUP BY ge_age_cd";
	disp_profile('ǯ��', $sql, $item_ary);
}

// ̤����
if ($disp == 'all' || $disp == 'mikikon') {
	$item_ary = array();
	$item_ary[1] = decode_mikikon(1);
	$item_ary[2] = decode_mikikon(2);
	$item_ary[3] = decode_mikikon(3);

	$sql = "SELECT mn_mikikon,COUNT(*)"
			. " FROM t_answer"
			. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
			. " WHERE an_enquete_id=$enquete_id AND an_valid_flag"
			. " GROUP BY mn_mikikon";
	disp_profile('̤����', $sql, $item_ary);
}

// ����
if ($disp == 'all' || $disp == 'shokugyo') {
	$item_ary = array();
	$sql = "SELECT sg_shokugyou_cd,sg_shokugyou_name FROM m_shokugyou ORDER BY sg_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$item_ary[$fetch->sg_shokugyou_cd] = $fetch->sg_shokugyou_name;
	}

	$sql = "SELECT mn_shokugyou_cd,COUNT(*)"
			. " FROM t_answer"
			. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
			. " WHERE an_enquete_id=$enquete_id AND an_valid_flag"
			. " GROUP BY mn_shokugyou_cd";
	disp_profile('����', $sql, $item_ary);
}

// �ｻ��
if ($disp == 'all' || $disp == 'kyoju') {
	$item_ary = array();
	$sql = "SELECT ar_area_cd,ar_area_name FROM m_area ORDER BY ar_area_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$item_ary[$fetch->ar_area_cd] = $fetch->ar_area_name;
	}

	$sql = "SELECT mn_jitaku_area,COUNT(*)"
			. " FROM t_answer"
			. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
			. " WHERE an_enquete_id=$enquete_id AND an_valid_flag"
			. " GROUP BY mn_jitaku_area";
	disp_profile('�ｻ��', $sql, $item_ary);
}
?>
			<br>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
