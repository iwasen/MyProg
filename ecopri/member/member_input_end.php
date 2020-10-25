<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:詳細情報登録完了
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/regist2.php");

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist2']))
	redirect('login.php');
$reg = &$_SESSION['ss_regist2'];

$reg->save_db($_SESSION['ss_seq_no'],2);

// セッション変数削除
unset($_SESSION['ss_regist2']);
session_unregister('ss_regist2');

?>
<html>
<head>
<title>☆★☆xxxゾウさんClub☆★☆</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
	<script language="JavaScript">
	<!--
	history.go( +1 );
	//-->
	</script>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
		<img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><img src="img/nyuukai/title/otukare.gif" width="196" height="38" alt="お疲れ様でした。"></td>
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
		<br><br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top"><br>
					<br><br><br><br><br><br>
					<font color="#FF0000">
					これで会員情報登録が全て完了しました。<br>
					ここまで入力して頂いた結果を反映した<br>
					あなたの家の推定使用量が出来ました。<br>
					次ページでご確認ください。<br>
					</font>
					</td>
					<td><img src="img/spacer.gif" width="10" height="247"></td>
					<td><img src="img/nyuukai/zou_img/m_input_end01.gif" width="180" height="247" alt="「次ページにあなたの家の平均値が表示されるぞ〜」（会員情報の設定が全て完了しました）"></td>
				</tr>
			</table>
			<br>
		</td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom">
		<img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
		<hr width="95%" size="1">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><a href="fiducial_check.php"><img src="img/nyuukai/button/next.gif"  border ="0" width="99" height="40"></a></td>
				</tr>
				<tr>
					<td height="7" valign="bottom"><img src="img/spacer.gif" height="7" width="99"></td>
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