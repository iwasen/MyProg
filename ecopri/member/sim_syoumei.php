<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:������������
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_define.php");
include("$inc/sim_sub.php");
include("$inc/sim_shoumei.php");
include("$inc/sim_db.php");

// �����ץ������ɽ����LD��ŷ������
function select_lighting1($name, $default, $eco = '') {
	$ary = array(
		1 => '�ָ�����ɸ���',
		2 => '�ָ��������š�'
	);
	sim_select_ary($name, $ary, $default, $eco);
}

// �����ץ������ɽ����LD���ڥ����ȥ饤�ȡ�
function select_lighting2($name, $default, $eco = '') {
	$ary = array(
		3 => '��Ǯ��',
		4 => '�ָ���'
	);
	sim_select_ary($name, $ary, $default, $eco);
}

// �����ץ������ɽ����LD��������饤�ȡ�
function select_lighting3($name, $default, $eco = '') {
	$ary = array(
		5 => '��Ǯ����4',
		6 => '��Ǯ����2',
		7 => '�ָ�����4',
		8 => '�ָ�����2'
	);
	sim_select_ary($name, $ary, $default, $eco);
}

// �����ץ������ɽ������ꡧŷ������
function select_lighting4($name, $default, $eco = '') {
	$ary = array(
		9 => '��Ǯ��',
		10 => '�ָ���'
	);
	sim_select_ary($name, $ary, $default, $eco);
}

// �����ץ������ɽ�����¼���ŷ������
function select_lighting5($name, $default, $eco = '') {
	$ary = array(
		11 => '��Ǯ��',
		12 => '�ָ���'
	);
	sim_select_ary($name, $ary, $default, $eco);
}

// �����ץ������ɽ����ϭ����ŷ������
function select_lighting6($name, $default, $eco = '') {
	$ary = array(
		13 => '��Ǯ����4',
		14 => '��Ǯ����2',
		15 => '�ָ�����4',
		16 => '�ָ�����2'
	);
	sim_select_ary($name, $ary, $default, $eco);
}

// �����ץ������ɽ���ʸ��ء�ŷ������
function select_lighting7($name, $default, $eco = '') {
	$ary = array(
		17 => '��Ǯ��',
		18 => '�ָ���'
	);
	sim_select_ary($name, $ary, $default, $eco);
}

// �������֥ץ������ɽ��
function select_hour($name, $default, $eco = '') {
	sim_select_no($name, 0, 24, $default, $eco, '', '', 1);
}

// �������֥ץ������ɽ�����̲���Τ��դ���
function select_hour2($name, $default, $eco = '') {
	$ary['0.5'] = '�̲���Τ�';
	for ($i = 0; $i <= 24; $i++)
		$ary[$i] = $i;
	sim_select_ary($name, $ary, $default, $eco);
}

// �������ǥ��饹����
$sim = new sim_shoumei;

// DB������ǥǡ������ɤ߹���
$sim->read_eco_data();
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>��������</title>
<script language="javascript">
<!--
Images_kitchen=new Array();
Images_kitchen[0]=new Image();
Images_kitchen[0].src="img/light/kitchen.jpg";


Images_LD_down=new Array();
Images_LD_down[0]=new Image();
Images_LD_down[0].src="img/light/wasitu.jpg";

Images_rouka=new Array();
Images_rouka[0]=new Image();
Images_rouka[0].src="img/light/rouka.jpg";

Images_room=new Array();
Images_room[0]=new Image();
Images_room[0].src="img/light/room.jpg";


Images_EN_tenjo=new Array();
Images_EN_tenjo[0]=new Image();
Images_EN_tenjo[0].src="img/light/genkan.jpg";


function change_img(){
	document.img1.src="img/light/light.gif";
}

<!--���-->
function change_img_kitchen(){
	document.img1.src=Images_kitchen[0].src;
}
<!--�¼�-->
function change_img_LD_down(){
	document.img1.src=Images_LD_down[0].src;
}
<!--ϭ��-->
function change_img_rouka(){
	document.img1.src=Images_rouka[0].src;
}

<!--���-->
function change_img_room(){
	document.img1.src=Images_room[0].src;
}

<!--����-->
function change_img_EN_tenjo(){
	document.images.img1.src=Images_EN_tenjo[0].src;
}

function select_list(name, val) {
	var f = document.form1;
	var n = f[name].options.length;
	for (i = 0; i < n; i++) {
		if (f[name].options[i].value == val) {
			f[name].selectedIndex = i;
			break;
		}
	}
}
// -->
</script>
<noscript>���Υڡ�����JavaScript����Ѥ��Ƥ��ޤ���</noscript>
</head>

<body bgcolor="#ff0000" leftmargin="8" topmargin="8">

<div align="center">

<!-- ��ݳѥơ��֥� -->
<table border="0" cellpadding="0" cellspacing="0" width="714" height="90%" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_1.gif" width="35" height="35">
		</td>
		<td align="center" valign="top">

			<!-- ���������� -->
			<!-- �����ȥ�ơ��֥� -->
			<table border="0" cellpadding="0" cellspacing="2" width="100%">
				<tr>
					<td align="center"><img src="img/shindan/title/shindan_syomei.gif" border="0" width="278" height="68"></td>
				</tr>
			</table>
			���ʤ��βȤξ���������ˡ���Ѥ��뤳�ȤǤɤ줯�餤�Ρ�Eco���̡פ����뤫�׻����ޤ�����
			<hr>
			<!-- �����ȥ�ơ��֥뽪λ -->


			<!-- �ե���������ơ��֥�Ϥ߳��� -->
			<form name="form1" action="res_syoumei.php" method="post">
			<b><font size="4">��ǥ�ץ��</font></b><br>
			�ץ��˥ޥ�����Τ���ȡ������Υ���ץ�򸫤뤳�Ȥ��Ǥ��ޤ���
			<p>
				<img src="img/light/sketch.gif" width="229" height=179 border="0" usemap="#LD1ccacb4"><br>
				<img name="img1" src="img/light/light.gif" width="282" border="0" height="113">
			</p>

			<table border="0" cellpadding="0" cellspacing="2" width="100%">

					<tr>
						<td colspan="8" bgcolor="#FF0000" align="center">
							<img src="img/shindan/button/new_04.gif" border="0" width="125" height="27">
						</td>
					</tr>

					<tr height="30">
						<td height="30"  bgcolor="#00cfff">���</td>
						<td height="30" colspan="2" bgcolor="#ffffce">�����μ���</td>
						<td height="30" bgcolor="#ffff9c">�����������<br>��������</td>
					</tr>

					<tr height="40">
						<td bgcolor="#00cfff"  rowspan="3">1.��֡���Ʋ��LD)</td>
						<td bgcolor="#ffffce" height="40" width="120">ŷ����</td>
						<td width="10" height="40" bgcolor="#ffffce"><? select_lighting1('lighting_ary[1]', $sim->lighting_ary[1], 'lighting_ary_e[1]') ?></td>
						<td height="40" bgcolor="#ffff9c"><? select_hour('hour_ary[1]', $sim->hour_ary[1], 'hour_ary_e[1]') ?>����
						</td>
					</tr>

					<tr height="41">
						<td bgcolor="#ffffce" height="41">�ڥ����ȥ饤��</td>
						<td width="10" height="41" bgcolor="#ffffce"><? select_lighting2('lighting_ary[2]', $sim->lighting_ary[2], 'lighting_ary_e[2]') ?></td>
						<td height="41" bgcolor="#ffff9c"><? select_hour('hour_ary[2]', $sim->hour_ary[2], 'hour_ary_e[2]') ?>����
						</td>
					</tr>

					<tr height="40">
						<td bgcolor="#ffffce" height="40">������饤��</td>
						<td width="10" height="40" bgcolor="#ffffce"><? select_lighting3('lighting_ary[3]', $sim->lighting_ary[3], 'lighting_ary_e[3]') ?></td>
						<td height="40" bgcolor="#ffff9c"><? select_hour('hour_ary[3]', $sim->hour_ary[3], 'hour_ary_e[3]') ?>����
						</td>
					</tr>

					<tr height="22">
						<td bgcolor="#00cfff" height="22" >2.����K)</td>
						<td width="120" height="22" bgcolor="#ffffce">ŷ����</td>
						<td height="22" bgcolor="#ffffce"><? select_lighting4('lighting_ary[4]', $sim->lighting_ary[4], 'lighting_ary_e[4]') ?></td>
						<td bgcolor="#ffff9c" height="22"><? select_hour('hour_ary[4]', $sim->hour_ary[4], 'hour_ary_e[4]') ?>����
						</td>
					</tr>

					<tr height="22">
						<td bgcolor="#00cfff" height="22" >3.�¼�</td>
						<td height="22" bgcolor="#ffffce">ŷ����</td>
						<td height="22" bgcolor="#ffffce"><? select_lighting5('lighting_ary[5]', $sim->lighting_ary[5], 'lighting_ary_e[5]') ?></td>
						<td height="22" bgcolor="#ffff9c"><? select_hour('hour_ary[5]', $sim->hour_ary[5], 'hour_ary_e[5]') ?>����
						</td>
					</tr>

					<tr height="22">
						<td bgcolor="#00cfff" height="22">4.1��ϭ��</td>
						<td bgcolor="#ffffce" height="22">ŷ����</td>
						<td width="10" height="22" bgcolor="#ffffce"><? select_lighting6('lighting_ary[6]', $sim->lighting_ary[6], 'lighting_ary_e[6]') ?></td>
						<td height="22" bgcolor="#ffff9c"><? select_hour2('hour_ary[6]', $sim->hour_ary[6], 'hour_ary_e[6]') ?>����
						</td>
					</tr>

					<tr height="22">
						<td bgcolor="#00cfff" height="22">5.���إۡ���</td>
						<td height="22" bgcolor="#ffffce">ŷ����</td>
						<td height="22" bgcolor="#ffffce"><? select_lighting7('lighting_ary[7]', $sim->lighting_ary[7], 'lighting_ary_e[7]') ?></td>
						<td bgcolor="#ffff9c" height="22"><? select_hour('hour_ary[7]', $sim->hour_ary[7], 'hour_ary_e[7]') ?>����
						</td>
					</tr>
				</table>
				<br><br>

				<!-- �ơ��֥�2���Ǿ�� -->
				<table border="0" cellpadding="0" cellspacing="2" width="100%">
					<tr height="30">
						<td colspan="8" align="center" bgcolor="#67fac6">
							<img src="img/shindan/button/new_05.gif" border="0" width="114" height="27">
						</td>
					</tr>

					<tr height="30">
						<td height="30" bgcolor="#FF9999">���</td>
						<td height="30" colspan="2" bgcolor="#ffffce">�����μ���</td>
						<td height="30" bgcolor="#ffff9c">�����������<br>��������</td>
					</tr>

					<tr height="40">
						<td rowspan="3" bgcolor="#FF9999">1.��֡���Ʋ��LD)</td>
						<td height="40" width="121" bgcolor="#ffffce">ŷ����</td>
						<td width="10" height="40" bgcolor="#ffffce"><? select_lighting1('lighting_ary_e[1]', $sim->lighting_ary_e[1]) ?></td>
						<td height="40" bgcolor="#ffff9c"><? select_hour('hour_ary_e[1]', $sim->hour_ary_e[1]) ?>����
						</td>
					</tr>

					<tr height="41">
						<td height="41" bgcolor="#ffffce">�ڥ����ȥ饤��</td>
						<td width="10" height="41" bgcolor="#ffffce"><? select_lighting2('lighting_ary_e[2]', $sim->lighting_ary_e[2]) ?></td>
						<td height="41" bgcolor="#ffff9c"><? select_hour('hour_ary_e[2]', $sim->hour_ary_e[2]) ?>����
						</td>
					</tr>

					<tr height="40">
						<td bgcolor="#ffffce" height="40">������饤��</td>
						<td width="10" height="40" bgcolor="#ffffce"><? select_lighting3('lighting_ary_e[3]', $sim->lighting_ary_e[3]) ?></td>
						<td height="40" bgcolor="#ffff9c"><? select_hour('hour_ary_e[3]', $sim->hour_ary_e[3]) ?>����
						</td>
					</tr>

					<tr height="22">
						<td height="22" bgcolor="#FF9999">2.����K)</td>
						<td height="22" bgcolor="#ffffce">ŷ����</td>
						<td height="22" bgcolor="#ffffce"><? select_lighting4('lighting_ary_e[4]', $sim->lighting_ary_e[4]) ?></td>
						<td bgcolor="#ffff9c" height="22"><? select_hour('hour_ary_e[4]', $sim->hour_ary_e[4]) ?>����
						</td>
					</tr>

					<tr height="22">
						<td height="22" bgcolor="#FF9999">3.�¼�</td>
						<td height="22" bgcolor="#ffffce">ŷ����</td>
						<td height="22" bgcolor="#ffffce"><? select_lighting5('lighting_ary_e[5]', $sim->lighting_ary_e[5]) ?></td>
						<td height="22" bgcolor="#ffff9c"><? select_hour('hour_ary_e[5]', $sim->hour_ary_e[5]) ?>����
						</td>
					</tr>

					<tr height="22">
						<td height="22" bgcolor="#FF9999">4.1��ϭ��</td>
						<td width="121" height="22" bgcolor="#ffffce">ŷ����</td>
						<td width="10" height="22" bgcolor="#ffffce"><? select_lighting6('lighting_ary_e[6]', $sim->lighting_ary_e[6]) ?></td>
						<td height="22" bgcolor="#ffff9c"><? select_hour2('hour_ary_e[6]', $sim->hour_ary_e[6]) ?>����
						</td>
					</tr>

					<tr height="22">
						<td height="22" bgcolor="#FF9999">5.���إۡ���</td>
						<td height="22" bgcolor="#ffffce">ŷ����</td>
						<td height="22" bgcolor="#ffffce"><? select_lighting7('lighting_ary_e[7]', $sim->lighting_ary_e[7]) ?></td>
						<td bgcolor="#ffff9c" height="22"><? select_hour('hour_ary_e[7]', $sim->hour_ary_e[7]) ?>����
						</td>
					</tr>
				</table>
				<!-- �����ޤ� -->

				<!-- 3���ܥơ��֥볫�� -->
				<table border="0" cellspacing="0" cellpadding="0" width="100%" height="200">
					<tr>
						<!-- ����ԡ������������ڡ��� -->
						<td height="10">��</td>
						<td rowspan="2" width="300"><img src="img/shindan/conte/035.gif" border="0" width="300" height="200"></td>
					</tr>
					<tr>
						<td height="190">

						<!-- ������ѥơ��֥볫�� -->
						<table border="0" width="100%" height="180">
							<tr height="60" bgcolor="#FFFFFF">
								<td colspan="3"><br></td>
							</tr>
							<tr height="60">
								<!-- ��󥯳��� -->
								<td align="center">
									<a href="<?=$_SESSION['s_one_advice']?>" title="���ݥ���ȥ��ɥХ����ŵ���"><img src="img/shindan/button/back3.gif" border="0" width="117" height="36"></a>
								</td>
								<!-- ���ꥻ�åȵ�ǽ��Ĥ��� -->
								<td align="center">
									<a href="sim_syoumei.php" title="�ꥻ�å�"><img src="img/shindan/button/reset.gif" border="0" width="117" height="36"></a>
								</td>
								<td align="center">
									<a href="javascript:document.form1.submit()" title="���Ƿ��"><img src="img/shindan/button/shindan.gif" border="0" width="117" height="36"></a>
								</td>
								<!-- ��󥯽�λ -->
							</tr>
							<tr height="60">
								<td colspan="3"><br></td>
							</tr>
						</table>
						<!-- ������ѥơ��֥뽪λ -->
						</td>
					</tr>
				</table>
			<!-- �����ޤ� -->
		</td>
		<td width="35" align="right" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_2.gif" width="35" height="35">
		</td>
	</tr>

	<tr>
		<td width="35" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td><hr width="95%" size="1"></td>
		<td width="35" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
<!-- ��ݳѥơ��֥뽪λ -->

</div>

<map name="LD1ccacb4"> 
<!--�����ء�-->
<area shape="circle" coords="58,64,8" onmouseover="change_img_EN_tenjo()" onmouseout="change_img()">
<!--���¼���--> 
<area shape="circle" coords="74,110,7" onmouseover="change_img_LD_down()" onmouseout="change_img()">
<!--��ϭ����--> 
<area shape="circle" coords="123,31,6" onmouseover="change_img_rouka()" onmouseout="change_img()">
<!--����֡�--> 	
<area shape="circle" coords="177,119,8" onmouseover="change_img_room()" onmouseout="change_img()">
<!--����ꡡ--> 	
<area shape="circle" coords="180,37,6" onmouseover="change_img_kitchen()" onmouseout="change_img()">
</map>
</body>
</html>