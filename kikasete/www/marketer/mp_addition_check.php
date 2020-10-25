<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

?>
<? marketer_header('Myパートナー', PG_NULL) ?>

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
					<td width="768" valign="top"><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<!-- myパートナー -->

			<p>
			<table border=0 cellspacing=0 cellpadding=1 width="100%">
				<tr>
					<td>■ホゲホゲに関する会議室</td>
					<td align="right">
						<input type="button" value="　戻る　" onclick="location.href='../menu.php'">
					</td>
				</tr>
			</table>
			</p>

			<div align="center">
			<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="80%" class="small">
				<tr bgcolor="#292864">
					<td width="20%" align="center">&nbsp;</td>
					<td width="10%" align="center"><span style="color:#ffffff;">発信数</span></td>
					<td width="10%" align="center"><span style="color:#ffffff;">返信数（率）</span></td>
					<td width="10%" align="center"><span style="color:#ffffff;">該当数（率）</span></td>
					<td width="10%" align="center"><span style="color:#ffffff;">目標数</span></td>
					<td width="10%" align="center"><span style="color:#ffffff;">不足数</span></td>
				</tr>
				<tr class="tc0" align="center">
					<td class="tch" align="center">現在の状況</td>
					<td>100</td>
					<td>40(40%)</td>
					<td>20(20%)</td>
					<td>30</td>
					<td>10</td>
				</tr>
				<tr class="tc1" align="center">
					<td class="tch" align="center">最終予測</td>
					<td>100</td>
					<td>50(50%)</td>
					<td><strong>23(23%)</strong></td>
					<td>30</td>
					<td bgcolor="#ffff00"><strong>7</strong></td>
				</tr>
				<tr class="tc2" align="center">
					<td class="tch" align="center">追加発信予測</td>
					<td><strong>120</strong></td>
					<td>80(65%)</td>
					<td><strong>35(35%)</strong></td>
					<td>30</td>
					<td>-5</td>
				</tr>
			</table>
			<br>
			<div align="center">目標数　<strong>30</strong>　人までにはあと　<strong>20</strong>　人に追加発信する必要があります。</div>
			<div align="right"><small>※この数字はあくまで参考です。</small></div>
			<form>
			<input type="button" value="追加発信をする" onclick="location.href('r_job_new.php')">
			<input type="button" value="追加発信はしない" onclick="">
			</form>
			</div>
			<!-- myパートナー -->

		</td>
	</tr>
</table>

<? marketer_footer () ?>
