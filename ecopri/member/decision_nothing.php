<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:ガンバリスト未作成
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");

// 会員番号取得
$seq_no = $_SESSION['ss_seq_no'];

// 今月の年月取得
$ym_now = date('Ym');

// 年月の指定が無ければ今月とする
if (!$ym)
	$ym = $ym_now;

// 年月に分解
$year = substr($ym, 0, 4);
$month = substr($ym, -2);

// 前月取得
$sql = "SELECT count(*) FROM t_ganbalist WHERE gn_seq_no=$seq_no AND gn_ym<'$ym'";
if (db_fetch1($sql))
	$ym_back = date('Ym', mktime(0, 0, 0, $month - 1, 1, $year));

// 次月取得
if ($ym < $ym_now)
	$ym_next = date('Ym', mktime(0, 0, 0, $month + 1, 1, $year));
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- 白丸角テーブル -->
<table border="0" cellpadding="0" cellspacing="0" width="714" height="90%" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_1.gif" width="35" height="35">
		</td>
		<td align="center" valign="top">
			<!-- タイトル部分 -->
			<!-- 先月次月移動ボタン開始 -->
			<div align="center">
			<br>
			<table border="0" align="center">
				<tr align="center" valign="middle">
					<td rowspan="2" align="right" width="33%">
<?
if ($ym_back) {
?>
						<a href="decision_scrap.php?ym=<?=$ym_back?>"><img src="img/button/sengetu.gif" border="0" align="middle" alt="先月" title="先月"></a>
<?
}
?>
					</td>
					<!-- 次先月ボタンで月がかわる -->
					<td align="center width="33%""><img src="img/month/<?=$month?>.gif" border="0" width="63" height="28"></td>
					<td rowspan="2" align="left" width="33%">
<?
if ($ym_next) {
?>
						<a href="decision_scrap.php?ym=<?=$ym_next?>"><img src="img/button/jigetu.gif" border="0" align="middle" alt="次月" title="次月"></a>
<?
}
?>
					</td>
				</tr>

				<tr>
					<td align="center"><img src="img/ganbalist/ganbalist_02.gif" border="0" width="131" height="35"></td>
				</tr>
			</table>
			</div>
			<!-- 先月次月移動ボタン終了 -->
			<hr width="95%" size="1">
			<!-- タイトル部分終了 -->

			<!-- ここに入力 -->
			<table border="0" align="center">
				<tr>
					<td align="center">
						<img src="img/ganbalist/decision_nothing.gif" border="0" width="600" height="300">
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
		<td align="center">
			<hr width="95%" size="1"><br>
		</td>
		<td width="35" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
<!-- 白丸角テーブル終了 -->

</div>
</body>
</html>