<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:前年実績入力案内
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/cnv_eng_mny.php");
include("$inc/cnv_unit.php");
$inc = "$top/inc";
include("$inc/regist3.php");

// セッション処理
session_start();
if (!isset($_SESSION['ss_seq_no']))
	redirect('login.php');

$_SESSION['ss_regist3'] = new mm_regist3;
$reg = &$_SESSION['ss_regist3'];

$reg->set_start_ym();
$reg->set_energy_cd($_SESSION['ss_seq_no']);
$reg->set_family_cd($_SESSION['ss_seq_no']);
$reg->set_price_unit();
$reg->set_all_average();

?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
		<img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="bottom"><br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>

					<td align="center"><img src="img/nyuukai/title/f_exp01.gif" width="250" height="38"></td>
				</tr>
			</table>
		</td>
		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td><br></td>
		<td align="center" valign="top">
		<hr width="95%" size="1">
		<br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width ="500" valign="top"><font size=2> ★本サービスで前年の使用量との比較をする際、領収書（検針票）があると正確な比較ができます<br>
					・領収書（検針票）は「電気」「ガス」は一年分、「水道」はお手持ちの一番あたらしいものをご用意ください <br>
					<br>
					★前年度の領収書（検針票）（電気・ガス・水道）と、もしある方は灯油・ガソリンの給油明細、
					ゴミの重さを記入したノートをご用意ください<br><br>
					★領収書（検針票）がお手元にない方でも手続き可能です<br>
					・電気・ガス・水道の領収書（検針票）がない方で、自分のデータを入れたいという方は各請求先に問い合わせれば請求することができます </font></td>
				</tr>
			</table><br>
			<font color="#FF0000"><img src="img/nyuukai/cyuui.gif" alt="注意"></font>
			<br>

			<table width="500" border="1" cellspacing="3" cellpadding="0" bordercolor="#00CCFF">
				<tr>
					<td bgcolor="#CCFFFF"><font size=2>
					<br>
					<ul>
					<li>前年の領収書（検針票）内容を入力するとあなたの前年実績を元に本サービスを利用できます</li><br>
					<li>前年の領収書（検針票）を持ってない方、領収書（検針票）が全て揃っていない方の場合、自動的に会員の平均値が入力されます</li><br>
					<li>いつでも【会員情報の変更】からデータ内容の変更が可能です</li>
					</ul>
					</font></td>
				</tr>
			</table>

			<br>
			<table border="1" cellspacing="3" cellpadding="0" width="500" bordercolor="#FF99CC">
				<tr>
					<td  width="150">
					<div align="center"><img src="img/nyuukai/zou_hoshi.gif" width="57" height="53"></div>
					</td>
					<td>
					<div align="center"><img src="img/nyuukai/title/exp01.gif" alt="個人情報"></div>
					</td>
				</tr>
				<tr>
					<td>
					<div align="center"><img src="img/nyuukai/zou_hoshi.gif" width="57" height="53"></div>
					</td>
					<td>
					<div align="center"><img src="img/nyuukai/title/exp02.gif" alt="シュミレーション条件"></div>
					</td>
				</tr>
				<tr>
					<td height ="53" valign="middle">
					<div align="center"><img src="img/nyuukai/hata/hata_3.gif"></div>
					</td>
					<td>
					<div align="center"><img src="img/nyuukai/title/exp03.gif" alt="前年実績入力"></div>
					</td>
				</tr>
			</table>
		</td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom">
		<img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
		<br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
				<form name="form1" method="post" action="fiducial_input.php">
					<td width="100" valign="bottom"><input type="image" src="img/nyuukai/button/motteru.gif" width="171" height="114" alt="持っている" border="0"></a></td>
					<td width><img src="img/nyuukai/zou_img/f_exp01.gif" alt="あなたの前年の領収書（検針票）"></td>
				</form>
				<form name="form2" method="post" action="fiducial_average.php">
					<td width="100" valign="bottom">
					<input type="image" src="img/nyuukai/button/mottenai.gif" width="99" height="40" alt="持っていない" border="0"></a></td>
				</form>
				</tr>
				<tr align="center">
					<td height="7" colspan="3"><img src="img/spacer.gif" width="208" height="7"></td>
				</tr>
			</table>
		</td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>

</div>
</body>
</html>