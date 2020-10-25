<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:照明エコ診断
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

// 照明プルダウン表示（LD：天井灯）
function select_lighting1($name, $default, $eco = '') {
	$ary = array(
		1 => '蛍光灯（標準）',
		2 => '蛍光灯（節電）'
	);
	sim_select_ary($name, $ary, $default, $eco);
}

// 照明プルダウン表示（LD：ペンダントライト）
function select_lighting2($name, $default, $eco = '') {
	$ary = array(
		3 => '白熱灯',
		4 => '蛍光灯'
	);
	sim_select_ary($name, $ary, $default, $eco);
}

// 照明プルダウン表示（LD：ダウンライト）
function select_lighting3($name, $default, $eco = '') {
	$ary = array(
		5 => '白熱灯×4',
		6 => '白熱灯×2',
		7 => '蛍光灯×4',
		8 => '蛍光灯×2'
	);
	sim_select_ary($name, $ary, $default, $eco);
}

// 照明プルダウン表示（台所：天井灯）
function select_lighting4($name, $default, $eco = '') {
	$ary = array(
		9 => '白熱灯',
		10 => '蛍光灯'
	);
	sim_select_ary($name, $ary, $default, $eco);
}

// 照明プルダウン表示（和室：天井灯）
function select_lighting5($name, $default, $eco = '') {
	$ary = array(
		11 => '白熱灯',
		12 => '蛍光灯'
	);
	sim_select_ary($name, $ary, $default, $eco);
}

// 照明プルダウン表示（廊下：天井灯）
function select_lighting6($name, $default, $eco = '') {
	$ary = array(
		13 => '白熱灯×4',
		14 => '白熱灯×2',
		15 => '蛍光灯×4',
		16 => '蛍光灯×2'
	);
	sim_select_ary($name, $ary, $default, $eco);
}

// 照明プルダウン表示（玄関：天井灯）
function select_lighting7($name, $default, $eco = '') {
	$ary = array(
		17 => '白熱灯',
		18 => '蛍光灯'
	);
	sim_select_ary($name, $ary, $default, $eco);
}

// 点灯時間プルダウン表示
function select_hour($name, $default, $eco = '') {
	sim_select_no($name, 0, 24, $default, $eco, '', '', 1);
}

// 点灯時間プルダウン表示（通過時のみ付き）
function select_hour2($name, $default, $eco = '') {
	$ary['0.5'] = '通過時のみ';
	for ($i = 0; $i <= 24; $i++)
		$ary[$i] = $i;
	sim_select_ary($name, $ary, $default, $eco);
}

// エコ診断クラス生成
$sim = new sim_shoumei;

// DBから診断データを読み込み
$sim->read_eco_data();
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>照明診断</title>
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

<!--台所-->
function change_img_kitchen(){
	document.img1.src=Images_kitchen[0].src;
}
<!--和室-->
function change_img_LD_down(){
	document.img1.src=Images_LD_down[0].src;
}
<!--廊下-->
function change_img_rouka(){
	document.img1.src=Images_rouka[0].src;
}

<!--居間-->
function change_img_room(){
	document.img1.src=Images_room[0].src;
}

<!--玄関-->
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
<noscript>このページはJavaScriptを使用しています。</noscript>
</head>

<body bgcolor="#ff0000" leftmargin="8" topmargin="8">

<div align="center">

<!-- 白丸角テーブル -->
<table border="0" cellpadding="0" cellspacing="0" width="714" height="90%" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_1.gif" width="35" height="35">
		</td>
		<td align="center" valign="top">

			<!-- ここに入力 -->
			<!-- タイトルテーブル -->
			<table border="0" cellpadding="0" cellspacing="2" width="100%">
				<tr>
					<td align="center"><img src="img/shindan/title/shindan_syomei.gif" border="0" width="278" height="68"></td>
				</tr>
			</table>
			あなたの家の照明使用方法を変えることでどれくらいの「Eco効果」があるか計算します！！
			<hr>
			<!-- タイトルテーブル終了 -->


			<!-- フォーム処理テーブル囲み開始 -->
			<form name="form1" action="res_syoumei.php" method="post">
			<b><font size="4">モデルプラン</font></b><br>
			プランにマウスをのせると、照明のサンプルを見ることができます！
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
						<td height="30"  bgcolor="#00cfff">場所</td>
						<td height="30" colspan="2" bgcolor="#ffffce">照明の種類</td>
						<td height="30" bgcolor="#ffff9c">一日あたりの<br>点灯時間</td>
					</tr>

					<tr height="40">
						<td bgcolor="#00cfff"  rowspan="3">1.居間・食堂（LD)</td>
						<td bgcolor="#ffffce" height="40" width="120">天井灯</td>
						<td width="10" height="40" bgcolor="#ffffce"><? select_lighting1('lighting_ary[1]', $sim->lighting_ary[1], 'lighting_ary_e[1]') ?></td>
						<td height="40" bgcolor="#ffff9c"><? select_hour('hour_ary[1]', $sim->hour_ary[1], 'hour_ary_e[1]') ?>時間
						</td>
					</tr>

					<tr height="41">
						<td bgcolor="#ffffce" height="41">ペンダントライト</td>
						<td width="10" height="41" bgcolor="#ffffce"><? select_lighting2('lighting_ary[2]', $sim->lighting_ary[2], 'lighting_ary_e[2]') ?></td>
						<td height="41" bgcolor="#ffff9c"><? select_hour('hour_ary[2]', $sim->hour_ary[2], 'hour_ary_e[2]') ?>時間
						</td>
					</tr>

					<tr height="40">
						<td bgcolor="#ffffce" height="40">ダウンライト</td>
						<td width="10" height="40" bgcolor="#ffffce"><? select_lighting3('lighting_ary[3]', $sim->lighting_ary[3], 'lighting_ary_e[3]') ?></td>
						<td height="40" bgcolor="#ffff9c"><? select_hour('hour_ary[3]', $sim->hour_ary[3], 'hour_ary_e[3]') ?>時間
						</td>
					</tr>

					<tr height="22">
						<td bgcolor="#00cfff" height="22" >2.台所（K)</td>
						<td width="120" height="22" bgcolor="#ffffce">天井灯</td>
						<td height="22" bgcolor="#ffffce"><? select_lighting4('lighting_ary[4]', $sim->lighting_ary[4], 'lighting_ary_e[4]') ?></td>
						<td bgcolor="#ffff9c" height="22"><? select_hour('hour_ary[4]', $sim->hour_ary[4], 'hour_ary_e[4]') ?>時間
						</td>
					</tr>

					<tr height="22">
						<td bgcolor="#00cfff" height="22" >3.和室</td>
						<td height="22" bgcolor="#ffffce">天井灯</td>
						<td height="22" bgcolor="#ffffce"><? select_lighting5('lighting_ary[5]', $sim->lighting_ary[5], 'lighting_ary_e[5]') ?></td>
						<td height="22" bgcolor="#ffff9c"><? select_hour('hour_ary[5]', $sim->hour_ary[5], 'hour_ary_e[5]') ?>時間
						</td>
					</tr>

					<tr height="22">
						<td bgcolor="#00cfff" height="22">4.1階廊下</td>
						<td bgcolor="#ffffce" height="22">天井灯</td>
						<td width="10" height="22" bgcolor="#ffffce"><? select_lighting6('lighting_ary[6]', $sim->lighting_ary[6], 'lighting_ary_e[6]') ?></td>
						<td height="22" bgcolor="#ffff9c"><? select_hour2('hour_ary[6]', $sim->hour_ary[6], 'hour_ary_e[6]') ?>時間
						</td>
					</tr>

					<tr height="22">
						<td bgcolor="#00cfff" height="22">5.玄関ホール</td>
						<td height="22" bgcolor="#ffffce">天井灯</td>
						<td height="22" bgcolor="#ffffce"><? select_lighting7('lighting_ary[7]', $sim->lighting_ary[7], 'lighting_ary_e[7]') ?></td>
						<td bgcolor="#ffff9c" height="22"><? select_hour('hour_ary[7]', $sim->hour_ary[7], 'hour_ary_e[7]') ?>時間
						</td>
					</tr>
				</table>
				<br><br>

				<!-- テーブル2診断条件 -->
				<table border="0" cellpadding="0" cellspacing="2" width="100%">
					<tr height="30">
						<td colspan="8" align="center" bgcolor="#67fac6">
							<img src="img/shindan/button/new_05.gif" border="0" width="114" height="27">
						</td>
					</tr>

					<tr height="30">
						<td height="30" bgcolor="#FF9999">場所</td>
						<td height="30" colspan="2" bgcolor="#ffffce">照明の種類</td>
						<td height="30" bgcolor="#ffff9c">一日あたりの<br>点灯時間</td>
					</tr>

					<tr height="40">
						<td rowspan="3" bgcolor="#FF9999">1.居間・食堂（LD)</td>
						<td height="40" width="121" bgcolor="#ffffce">天井灯</td>
						<td width="10" height="40" bgcolor="#ffffce"><? select_lighting1('lighting_ary_e[1]', $sim->lighting_ary_e[1]) ?></td>
						<td height="40" bgcolor="#ffff9c"><? select_hour('hour_ary_e[1]', $sim->hour_ary_e[1]) ?>時間
						</td>
					</tr>

					<tr height="41">
						<td height="41" bgcolor="#ffffce">ペンダントライト</td>
						<td width="10" height="41" bgcolor="#ffffce"><? select_lighting2('lighting_ary_e[2]', $sim->lighting_ary_e[2]) ?></td>
						<td height="41" bgcolor="#ffff9c"><? select_hour('hour_ary_e[2]', $sim->hour_ary_e[2]) ?>時間
						</td>
					</tr>

					<tr height="40">
						<td bgcolor="#ffffce" height="40">ダウンライト</td>
						<td width="10" height="40" bgcolor="#ffffce"><? select_lighting3('lighting_ary_e[3]', $sim->lighting_ary_e[3]) ?></td>
						<td height="40" bgcolor="#ffff9c"><? select_hour('hour_ary_e[3]', $sim->hour_ary_e[3]) ?>時間
						</td>
					</tr>

					<tr height="22">
						<td height="22" bgcolor="#FF9999">2.台所（K)</td>
						<td height="22" bgcolor="#ffffce">天井灯</td>
						<td height="22" bgcolor="#ffffce"><? select_lighting4('lighting_ary_e[4]', $sim->lighting_ary_e[4]) ?></td>
						<td bgcolor="#ffff9c" height="22"><? select_hour('hour_ary_e[4]', $sim->hour_ary_e[4]) ?>時間
						</td>
					</tr>

					<tr height="22">
						<td height="22" bgcolor="#FF9999">3.和室</td>
						<td height="22" bgcolor="#ffffce">天井灯</td>
						<td height="22" bgcolor="#ffffce"><? select_lighting5('lighting_ary_e[5]', $sim->lighting_ary_e[5]) ?></td>
						<td height="22" bgcolor="#ffff9c"><? select_hour('hour_ary_e[5]', $sim->hour_ary_e[5]) ?>時間
						</td>
					</tr>

					<tr height="22">
						<td height="22" bgcolor="#FF9999">4.1階廊下</td>
						<td width="121" height="22" bgcolor="#ffffce">天井灯</td>
						<td width="10" height="22" bgcolor="#ffffce"><? select_lighting6('lighting_ary_e[6]', $sim->lighting_ary_e[6]) ?></td>
						<td height="22" bgcolor="#ffff9c"><? select_hour2('hour_ary_e[6]', $sim->hour_ary_e[6]) ?>時間
						</td>
					</tr>

					<tr height="22">
						<td height="22" bgcolor="#FF9999">5.玄関ホール</td>
						<td height="22" bgcolor="#ffffce">天井灯</td>
						<td height="22" bgcolor="#ffffce"><? select_lighting7('lighting_ary_e[7]', $sim->lighting_ary_e[7]) ?></td>
						<td bgcolor="#ffff9c" height="22"><? select_hour('hour_ary_e[7]', $sim->hour_ary_e[7]) ?>時間
						</td>
					</tr>
				</table>
				<!-- ここまで -->

				<!-- 3段目テーブル開始 -->
				<table border="0" cellspacing="0" cellpadding="0" width="100%" height="200">
					<tr>
						<!-- 空白行＆画像挿入スペース -->
						<td height="10">　</td>
						<td rowspan="2" width="300"><img src="img/shindan/conte/035.gif" border="0" width="300" height="200"></td>
					</tr>
					<tr>
						<td height="190">

						<!-- リンク専用テーブル開始 -->
						<table border="0" width="100%" height="180">
							<tr height="60" bgcolor="#FFFFFF">
								<td colspan="3"><br></td>
							</tr>
							<tr height="60">
								<!-- リンク開始 -->
								<td align="center">
									<a href="<?=$_SESSION['s_one_advice']?>" title="ワンポイントアドバイス電気編"><img src="img/shindan/button/back3.gif" border="0" width="117" height="36"></a>
								</td>
								<!-- ↓リセット機能をつける -->
								<td align="center">
									<a href="sim_syoumei.php" title="リセット"><img src="img/shindan/button/reset.gif" border="0" width="117" height="36"></a>
								</td>
								<td align="center">
									<a href="javascript:document.form1.submit()" title="診断結果"><img src="img/shindan/button/shindan.gif" border="0" width="117" height="36"></a>
								</td>
								<!-- リンク終了 -->
							</tr>
							<tr height="60">
								<td colspan="3"><br></td>
							</tr>
						</table>
						<!-- リンク専用テーブル終了 -->
						</td>
					</tr>
				</table>
			<!-- ここまで -->
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
<!-- 白丸角テーブル終了 -->

</div>

<map name="LD1ccacb4"> 
<!--　玄関　-->
<area shape="circle" coords="58,64,8" onmouseover="change_img_EN_tenjo()" onmouseout="change_img()">
<!--　和室　--> 
<area shape="circle" coords="74,110,7" onmouseover="change_img_LD_down()" onmouseout="change_img()">
<!--　廊下　--> 
<area shape="circle" coords="123,31,6" onmouseover="change_img_rouka()" onmouseout="change_img()">
<!--　居間　--> 	
<area shape="circle" coords="177,119,8" onmouseover="change_img_room()" onmouseout="change_img()">
<!--　台所　--> 	
<area shape="circle" coords="180,37,6" onmouseover="change_img_kitchen()" onmouseout="change_img()">
</map>
</body>
</html>