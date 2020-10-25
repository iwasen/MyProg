<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:我が家のEcoFootPrint
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/foot_print.php");

// 会員番号取得
$seq_no = $_SESSION['ss_seq_no'];

// 年月の指定がない場合は最新のデータ
if (!$ym) {
	$sql = "SELECT to_char(max(cr_ym), 'YYYYMM') FROM t_co2_rank WHERE cr_seq_no=$seq_no";
	$ym = db_fetch1($sql);
	if (!$ym) {
		$sql = "SELECT to_char(max(bd_month), 'YYYYMM') FROM t_base_data WHERE bd_mb_seq_no=$seq_no AND bd_commit_flag=1";
		$ym = db_fetch1($sql);
	}
}

// 年月に分解
$year = substr($ym, 0, 4);
$month = substr($ym, -2);

// 日付形式
$date = "$year-$month-01";

// 前月取得
$sql = "SELECT to_char(max(cr_ym), 'YYYYMM') FROM t_co2_rank WHERE cr_seq_no=$seq_no AND cr_ym<'$date'";
$ym_back = db_fetch1($sql);

// 次月取得
$sql = "SELECT to_char(min(cr_ym), 'YYYYMM') FROM t_co2_rank WHERE cr_seq_no=$seq_no AND cr_ym>'$date'";
$ym_next = db_fetch1($sql);

// 消費量とランクを取得
get_foot_print($seq_no, $date, $use, $rank);

// Flashパラメータ編集
/* １〜7段階ランク */
$fv[] = "elc_spendRank={$rank['ele']}";
$fv[] = "gas_spendRank={$rank['gas']}";
$fv[] = "wtr_spendRank={$rank['wtr']}";
$fv[] = "oil_spendRank={$rank['oil_gso']}";
$fv[] = "dst_spendRank={$rank['dst']}";
$fv[] = "co2_spendRank={$rank['co2']}";
/* 消費量総数 */
$fv[] = "elc_spendValue={$use['ele']}";
$fv[] = "gas_spendValue={$use['gas']}";
$fv[] = "wtr_spendValue={$use['wtr']}";
$fv[] = "oil_spendValue={$use['oil_gso']}";
$fv[] = "dst_spendValue={$use['dst']}";
$fv[] = "co2_spendValue={$use['co2']}";
$fv = join('&', $fv);
?>
<html>
<head>
<title>☆★☆Eco象さん一家のEco生活☆★☆</title>
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="css/main.css">
<script type="text/javascript">
<!--
function open_window(url) {
 window.open(url, "_blank","width=750,scrollbars=yes,resizable=yes,status=no,menubar=no,toolbar=no");
}
//-->
</script>
</head>

<body bgcolor="FF0000">
<div align="center">
<table border="0" cellspacing="0" cellpadding="0" width="720">
	<tr>
		<td><img src="img/kado_1.gif" width="35" height="35"></td>
		<td width=650 bgcolor="#ffffff"><br></td>
		<td><img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td align="center" bgcolor="#FFFFFF" colspan=3>
			<!-- 外枠テーブル -->
			<table border="0" cellspacing=0 cellpadding=0 width=720>
				<tr>
					<td align="center">
						<!-- 先月次月移動ボタン開始 -->
						<table border="0" width="500" cellspacing="0" cellpadding="0">
							<tr>
								<td align="center">
<?
if ($ym_back) {
?>
									<a href="foot_check.php?ym=<?=$ym_back?>"><img src="img/button/sengetu.gif" border="0" align="middle" alt="先月" title="先月"></a>
<?
}
?>
								</td>
								<td><img src="img/month/<?=$month?>.gif" width="63" height="28"></td>
								<td>
<?
if ($ym_next) {
?>
									<a href="foot_check.php?ym=<?=$ym_next?>"><img src="img/button/jigetu.gif" border="0" align="middle" alt="次月" title="次月"></a>
<?
}
?>
								</td>
								<td>
									<img src="img/foot/ecopri.gif" width="300" height="42">
								</td>
							</tr>
						</table>
						<!-- 先月次月移動ボタン終了 -->
					</td>
				</tr>
				<tr>
					<td align="center" class="tc0">
					数値入力で集計したあなたの家のエネルギー使用量を７色で表現しています。<br>
					さて、今月の各エネルギーの足色はどうですか？
					</td>
				</tr>
				<tr>
					<td align="center">
						<!-- フットチェックテーブル開始 -->
						<table border="0" width="700" height="485">
							<tr>
								<td align="center"><!-- Flashの挿入 -->
									<OBJECT ID=SWFlash classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
									 codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
									 WIDTH="700" HEIGHT="485">
										<PARAM NAME=movie VALUE="flash/eco_foot.swf">
										<PARAM NAME=quality VALUE=high>
										<PARAM NAME=bgcolor VALUE=#FFFFFF>
										<PARAM NAME=play VALUE=TRUE>
										<PARAM NAME=loop VALUE=FALSE>
										<PARAM NAME=FlashVars VALUE="<?=$fv?>">
										<EMBED src="flash/eco_foot.swf" play=TRUE loop=FALSE quality=high bgcolor=#FFFFFF  WIDTH="700" HEIGHT="485" NAME="SWFlash"
										 TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer" FlashVars="<?=$fv?>"></EMBED>
									</OBJECT>
								</td>
							</tr>
						</table>
						<!-- フットチェックテーブル終了 -->
					</td>
				</tr>
			</table>
			<!-- 外枠テーブル終了 -->
		</td>
	</tr>

	<tr>
		<td><img src="img/kado_3.gif" width="35" height="35"></td>
		<td width=650 bgcolor="#FFFFFF"><br></td>
		<td><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
<!-- 白丸角練習テーブル終了 -->

<!-- Flash画像リンク -->
<!-- 我が家の実績へ -->
<!-- Flah内のリンクで３つの混合グラフへ飛ぶ -->
<!-- 別ウィンドウで、閉じるボタンのみ -->
</div>
</body>
</html>