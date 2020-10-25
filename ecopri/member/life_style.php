<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�椬�Ȥ�������
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/eng_comment.php");

// ���ڡ������å�
$_SESSION['s_one_advice'] = 'life_style.php#sim_top';

// ����ʬ����ǡ�������
function get_use($seq_no, $date, &$ary) {
	// ��������
	$ary['ele'] = 0;
	$ary['gas'] = 0;
	$ary['oil'] = 0;
	$ary['wtr'] = 0;
	$ary['gso'] = 0;
	$ary['dst'] = 0;

	// ����ʬ����
	$sql = "SELECT bd_el_use,bd_gs_use,bd_ol_use,bd_wt_use,bd_gl_use,bd_gm_use"
			. " FROM t_base_data LEFT JOIN t_co2_rank ON bd_mb_seq_no=cr_seq_no AND bd_month=cr_ym"
			. " WHERE bd_mb_seq_no=$seq_no AND bd_month='$date'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$ary['ele'] = $fetch->bd_el_use;
		$ary['gas'] = $fetch->bd_gs_use;
		$ary['oil'] = $fetch->bd_ol_use;
		$ary['wtr'] = $fetch->bd_wt_use;
		$ary['gso'] = $fetch->bd_gl_use;
		$ary['dst'] = $fetch->bd_gm_use;
	}
}

// �︺�̼���
function get_comp($seq_no, $date, &$use_ary, &$ary) {
	// ��������
	$ary['ele'] = 0;
	$ary['gas'] = 0;
	$ary['oil'] = 0;
	$ary['wtr'] = 0;
	$ary['gso'] = 0;
	$ary['dst'] = 0;

	// ��ǯƱ��ʬ����
	$date2 = add_date($date, -1, 0, 0);
	$sql = "SELECT bd_el_use,bd_gs_use,bd_ol_use,bd_wt_use,bd_gl_use,bd_gm_use"
			. " FROM t_base_data LEFT JOIN t_co2_rank ON bd_mb_seq_no=cr_seq_no AND bd_month=cr_ym"
			. " WHERE bd_mb_seq_no=$seq_no AND bd_month='$date2'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$ary['ele'] = $use_ary['ele'] - $fetch->bd_el_use;
		$ary['gas'] = $use_ary['gas'] - $fetch->bd_gs_use;
		$ary['oil'] = $use_ary['oil'] - $fetch->bd_ol_use;
		$ary['wtr'] = $use_ary['wtr'] - $fetch->bd_wt_use;
		$ary['gso'] = $use_ary['gso'] - $fetch->bd_gl_use;
		$ary['dst'] = $use_ary['dst'] - $fetch->bd_gm_use;
	}
}

// ��󥯼���
function get_rank($seq_no, $date, &$ary) {
	// ��󥯢�gif�ե�����̾
	$gif = array('08', '01', '02', '03', '04', '05', '06', '07');

	// ��������
	$ary['ele'] = $gif[0];
	$ary['gas'] = $gif[0];
	$ary['oil'] = $gif[0];
	$ary['wtr'] = $gif[0];
	$ary['gso'] = $gif[0];
	$ary['dst'] = $gif[0];

	$sql = "SELECT cr_ele_rank,cr_gas_rank,cr_wtr_rank,cr_dst_rank,cr_oil_rank,cr_gso_rank"
			. " FROM t_co2_rank"
			. " WHERE cr_seq_no=$seq_no AND cr_ym='$date'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$ary['ele'] = $gif[$fetch->cr_ele_rank];
		$ary['gas'] = $gif[$fetch->cr_gas_rank];
		$ary['oil'] = $gif[$fetch->cr_oil_rank];
		$ary['wtr'] = $gif[$fetch->cr_wtr_rank];
		$ary['gso'] = $gif[$fetch->cr_gso_rank];
		$ary['dst'] = $gif[$fetch->cr_dst_rank];
	}
}

// ����ֹ����
$seq_no = $_SESSION['ss_seq_no'];

// �ǿ��ξ���ǡ���ǯ������
$sql = "SELECT max(bd_month) FROM t_base_data WHERE bd_mb_seq_no=$seq_no AND bd_commit_flag=1";
$data_ym = db_fetch1($sql);
$this_month = (int)get_datepart('M', $data_ym);

// ����ʬ����ǡ�������
get_use($seq_no, $data_ym, $use_ary);

// �︺�̥ǡ�������
get_comp($seq_no, $data_ym, $use_ary, $comp_ary);

// ��󥯼���
get_rank($seq_no, $data_ym, $rank);

// ��������٥��ȣ�
if (!($month >= 1 && $month <= 12))
	$month = date(m);

if ($month ==12 || $month == 1 || $month == 2) {
	$season = "winter";
	$osusume_url[1] = "kucho_danbou.php?eng_bunrui=2";
	$osusume_url[2] = "kaji_namagomi.php?eng_bunrui=6";
	$osusume_url[3] = "nyuyoku_oidaki.php?eng_bunrui=2";
	$osusume_url[4] = "toilet_senjo.php?f=1&eng_bunrui=1";
	$osusume_url[5] = "kaji_sentaku_furomizu.php?eng_bunrui=3";
}
if ($month == 3 || $month == 4 || $month == 5) {
	$season = "spring";
	$osusume_url[1] = "kotsu_idling.php?eng_bunrui=5";
	$osusume_url[2] = "senmen_shower.php?eng_bunrui=3";
	$osusume_url[3] = "nyuyoku_yokuso.php?eng_bunrui=2";
	$osusume_url[4] = "kaji_kansou.php?eng_bunrui=1";
	$osusume_url[5] = "kaji_yuwakashi.php?eng_bunrui=1";
}
if ($month == 6 || $month == 7 || $month == 8) {
	$season = "summer";
	$osusume_url[1] = "nyuyoku_shower.php?eng_bunrui=2";
	$osusume_url[2] = "nyuyoku_shower_type.php?eng_bunrui=2";
	$osusume_url[3] = "kucho_riebou_ko.php?eng_bunrui=1";
	$osusume_url[4] = "kucho_ryo.php?eng_bunrui=1";
	$osusume_url[5] = "kaji_shigengomi.php?eng_bunrui=6";
}
if ($month == 9 || $month ==10 || $month ==11) {
	$season = "autumn";
	$osusume_url[1] = "kotsu_idling.php?eng_bunrui=5";
	$osusume_url[2] = "syoumei_LD_keikou.php?eng_bunrui=1";
	$osusume_url[3] = "kaji_syokkiarai.php?eng_bunrui=2";
	$osusume_url[4] = "goraku_nagaraTV.php?eng_bunrui=1";
	$osusume_url[5] = "kaji_sentaku_chusui.php?eng_bunrui=3";
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>������xxx��������Club������</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
<script type="text/javascript">
<!--
function open_graph(url) {
	window.open(url + ".php?graph=All", "_blank", "width=600,height=520,scrollbars=no,resizable=no,status=no,menubar=no,toolbar=no");
}
//-->
</script>
<style type="text/css">
<!--
div.advice {
	margin-bottom: 8px;
}
-->
</style>
</head>

<body bgcolor="#FF0000" text="#000000" link="#0033ff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- ��ݳѥơ��֥� -->
<table border="0" cellpadding="0" cellspacing="0" width="714" height="90%" bgcolor="#FFFFFF">
	<tr>
		<td width="35" height="35" align="left" valign="top" bgcolor="#FFFFFF"><img src="img/kado_1.gif" width="35" height="35"></td>
		<td width=650></td>
		<td width="35" height="35" align="right" valign="top" bgcolor="#FFFFFF"><img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td colspan=3 align="center">
			<table border="0" width="650" cellspacing="1" cellpadding="1">
				<tr>
					<td align="center"><img src="img/shindan/sim_lifestyle/title_setuyaku.gif" width="250" height="43" alt="�郎�Ȥ������ļ�"></td>
				</tr>
				<tr>
					<td align="center" class="tc0">
						�ֺ����ɤΤ褦�˲ᤴ��������Ǥ��뤫�ʡ��פȲ�����ä��礦�����Ĥξ�Ǥ���<br>
						��²�ߤ�ʤ�����ˤĤ��ƹͤ���Ȱռ����ޤäư�����Ļ�Ǥ��衣<br><br>
					</td>
				</tr>
			</table>
<?
if (!$_SESSION['ss_yet_flag']) {
?>
			<table border="0" width="644" cellspacing="1" cellpadding="1">
				<tr>
					<td><img src="img/shindan/sim_lifestyle/title_houkoku.gif" width="209" height="25" alt="���ͥ륮����Ĺ�����"></td>
				</tr>
				<tr>
					<td class="tc0">
					���ͥ륮����Ĺ����������ǧ���ơ��ɤΤ��餤�����Ǥ�����������Ρ֥���Хꥹ�ȡפ�����å����ޤ��礦!!
					</td>
				</tr>
			</table>
			<table border="1" width="644" height="300" cellspacing="1" cellpadding="1" bgcolor="#FFFA55" bordercolordark="orange" bordercolorlight="orange">
				<tr>
					<td width="15" height="15"></td>
				</tr>
				<tr>
					<td width="15"></td>
					<td align="center" bgcolor="FFFFFF">
						<table border=0 cellspacing=1 cellpadding=1>
							<tr>
								<td rowspan=2><a href="javascript:open_graph('graph_ele')">
									<img src="img/graph/button_denki.gif" width="63" height="78" border=0 alt="�ŵ�"></a>
								</td>
								<td class="tc0"><? eng_comment('ele', $this_month, $use_ary, $comp_ary, $rank)?></td>
							</tr>
							<tr>
								<td><img src="img/graph/comment/denki/denki_<?=$rank['ele']?>.gif" width="231" height="45" alt="�ŵ�������"></td>
							</tr>
						</table>
					</td>
					<td align="center" bgcolor="FFFFFF">
						<table border=0 cellspacing=1 cellpadding=1>
							<tr>
								<td rowspan=2><a href="javascript:open_graph('graph_gas')">
									<img src="img/graph/button_gas.gif" width="63" height="78" border=0 alt="����"></a></td>
								<td class="tc0"><? eng_comment('gas', $this_month, $use_ary, $comp_ary, $rank)?></td>
							</tr>
							<tr>
								<td><img src="img/graph/comment/gas/gas_<?=$rank['gas']?>.gif" width="231" height="45" alt="����������"></td>
							</tr>
						</table>
					</td>
					<td width="15"></td>
				</tr>
				<tr>
					<td width="15"></td>
					<td align="center" bgcolor="FFFFFF">
						<table border=0 cellspacing=1 cellpadding=1>
							<tr>
								<td rowspan=2><a href="javascript:open_graph('graph_wtr')">
									<img src="img/graph/button_suidou.gif" width="63" height="78" border=0 alt="��ƻ"></td>
								<td class="tc0"><? eng_comment('wtr', $this_month, $use_ary, $comp_ary, $rank)?></td>
							</tr>
							<tr>
								<td><img src="img/graph/comment/suidou/suidou_<?=$rank['wtr']?>.gif" width="231" height="45" alt="��ƻ������"></td>
							</tr>
						</table>
					</td>
					<td align="center" bgcolor="FFFFFF">
						<table border=0 cellspacing=1 cellpadding=1>
							<tr>
								<td rowspan=2><a href="javascript:open_graph('graph_oil')">
									<img src="img/graph/button_touyu.gif" width="63" height="78" border=0 alt="����"></td>
								<td class="tc0"><? eng_comment('oil', $this_month, $use_ary, $comp_ary, $rank)?></td>
							</tr>
							<tr>
								<td><img src="img/graph/comment/touyu/touyu_<?=$rank['oil']?>.gif" width="231" height="45" alt="����������"></td>
							</tr>
						</table>
					</td>
					<td width="15"></td>
				</tr>
				<tr>
					<td width="15"></td>
					<td align="center" bgcolor="FFFFFF">
						<table border=0 cellspacing=1 cellpadding=1>
							<tr>
								<td rowspan=2><a href="javascript:open_graph('graph_gso')">
									<img src="img/graph/button_gasolien.gif" width="63" height="78" border=0 alt="�������"></td>
								<td class="tc0"><? eng_comment('gso', $this_month, $use_ary, $comp_ary, $rank)?></td>
							</tr>
							<tr>
								<td><img src="img/graph/comment/gasoline/gasolien_<?=$rank['gso']?>.gif" width="231" height="45" alt="������󥳥���"></td>
							</tr>
						</table>
					</td>
					<td align="center" bgcolor="FFFFFF">
						<table border=0 cellspacing=1 cellpadding=1>
							<tr>
								<td rowspan=2><a href="javascript:open_graph('graph_dst')">
									<img src="img/graph/button_gomi.gif" width="63" height="78" border=0 alt="����"></td>
								<td class="tc0"><? eng_comment('dst', $this_month, $use_ary, $comp_ary, $rank)?></td>
							</tr>
							<tr>
								<td><img src="img/graph/comment/gomi/gomi_<?=$rank['dst']?>.gif" width="231" height="45" alt="���ߥ�����"></td>
							</tr>
						</table>
					</td>
					<td width="15"></td>
				</tr>
				<tr>
					<td width="15" height="15"></td>
				</tr>
			</table>
			<table border="0" width="644" cellspacing="1" cellpadding="1">
				<tr>
					<td align="right"><a href="decision_scrap.php">
					<img src="img/shindan/sim_lifestyle/button_ganbalist.gif" width="134" height="30" border=0 alt="����Хꥹ�Ȥ򸫤�"></a></td>
				</tr>
			</table>
<?
}
?>
			<br>

			<!-- ���ߥ�졼������ΰ�ơ��֥뤳������ -->
			<a name="sim_top">
			<table border="0" cellspacing=1 cellpadding=1>
				<tr>
					<td align="cener"><img src="img/shindan/sim_lifestyle/title_onepoint.gif" width=455 height=85 alt="���ݥ���ȥ��ɥХ���"></td>
				<tr>
				<tr>
					<td align="center" class="tc0">
					�����Υ�����������󥢥ɥХ������٥��ȣ��פǤ���<br>
					�������ä����ɥХ��������ä�����ǥ����ʡ��˿ʤ�ǡ����ʤ��β�²�ˤ��ä���ɸ��<br>
					�ɥ���Хꥹ�ȡɤ���Ͽ���ƤߤƤ��������͡�<br><br>
					</td>
				</tr>
				<tr>
					<td align="center"><img src="img/shindan/sim_lifestyle/title_best5.gif" width=344 height=48 alt="����Τ�������٥��ȣ�"></td>
				</tr>
			</table>
			<table border="0" cellspacing=1 cellpadding=5>
				<tr>
					<td width=90 align="center"><img src="img/shindan/sim_lifestyle/<?=$season?>/best1.gif" border="0" width="88" height="89"></td>
					<td width=440 align="center"><img src="img/shindan/sim_lifestyle/<?=$season?>/01.gif" border="0" width="432" height="103"></td>
					<td width=90 align="center"><a href="<?=$osusume_url[1]?>">
						<img src="img/shindan/sim_lifestyle/button_shindan.gif" border="0" width="76" height="36"></a></td>
				</tr>
				<tr>
					<td align="center"><img src="img/shindan/sim_lifestyle/<?=$season?>/best2.gif" border="0" width="88" height="89"></td>
					<td align="center"><img src="img/shindan/sim_lifestyle/<?=$season?>/02.gif" border="0" width="432" height="103"></td>
					<td align="center"><a href="<?=$osusume_url[2]?>">
						<img src="img/shindan/sim_lifestyle/button_shindan.gif" border="0" width="76" height="36"></a></td>
				</tr>
				<tr>
					<td align="center"><img src="img/shindan/sim_lifestyle/<?=$season?>/best3.gif" border="0" width="88" height="89"></td>
					<td align="center"><img src="img/shindan/sim_lifestyle/<?=$season?>/03.gif" border="0" width="432" height="103"></td>
					<td align="center"><a href="<?=$osusume_url[3]?>">
						<img src="img/shindan/sim_lifestyle/button_shindan.gif" border="0" width="76" height="36"></a></td>
				</tr>
				<tr>
					<td align="center"><img src="img/shindan/sim_lifestyle/<?=$season?>/best4.gif" border="0" width="88" height="89"></td>
					<td align="center"><img src="img/shindan/sim_lifestyle/<?=$season?>/04.gif" border="0" width="432" height="103"></td>
					<td align="center"><a href="<?=$osusume_url[4]?>">
						<img src="img/shindan/sim_lifestyle/button_shindan.gif" border="0" width="76" height="36"></a></td>
				</tr>
				<tr>
					<td align="center"><img src="img/shindan/sim_lifestyle/<?=$season?>/best5.gif" border="0" width="88" height="89"></td>
					<td align="center"><img src="img/shindan/sim_lifestyle/<?=$season?>/05.gif" border="0" width="432" height="103"></td>
					<td align="center"><a href="<?=$osusume_url[5]?>">
						<img src="img/shindan/sim_lifestyle/button_shindan.gif" border="0" width="76" height="36"></a></td>
				</tr>
			</table>
			<br>
			<table border="0" cellspacing=1 cellpadding=1>
				<tr>
					<td><img src="img/shindan/sim_lifestyle/advice.gif" width=205��height=34 alt="���ݥ���ȥ��ɥХ���" border=0></td>
				</tr>
			</table>
			<table width=600 height=150 border="0" cellspacing=0 cellpadding=0 bgcolor="#ccffcc">
				<tr>
					<td align="center"><a href="one_electron.php"><img src="img/shindan/anime_ele.gif" border=0 alt="�ŵ�"></a><td>
					<td align="center"><a href="one_gas.php"><img src="img/shindan/anime_gas.gif" border=0 alt="����"></a><td>
					<td align="center"><a href="one_water.php"><img src="img/shindan/anime_wtr.gif" border=0 alt="��ƻ"></a><td>
					<td align="center"><a href="one_gasoline.php"><img src="img/shindan/anime_gso.gif" border=0 alt="�������"></a><td>
					<td align="center"><a href="one_garbage.php"><img src="img/shindan/anime_dst.gif" border=0 alt="����"></a><td>
					</td>
				</tr>
			</table>
		<br>
		
<!--
		<a href="decision_scrap.php"><img src="img/button/ganbalist.gif" border="0" width="94" height="30"></a>
-->
		</td>
	</tr>

	<tr>
		<td width="35" height="40" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="top"><a href="main.php"><img src="img/button/main.gif" border="0" width="61" height="30"></a></td>
		<td width="35" height="40" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
<!-- ��ݳѥơ��֥뽪λ -->

</div>
</body>
</html>