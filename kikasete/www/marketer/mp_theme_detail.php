<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/icp_db.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

$sql = "SELECT rm_subject,rm_content,rm_date FROM l_remark WHERE rm_remark_id=$remark_id";
$result = icp_db_exec($sql);
if (pg_numrows($result))
	$fetch = pg_fetch_object($result, 0);
?>
<? marketer_header('Myパートナー', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
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
			<div align="center">
			<form method="post" name="form1">
			<table border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td>
<!--
						<table border=0 cellspacing=2 cellpadding=3 width=768>
							<tr>
								<td align="left" bgcolor="#eff7e8">■テーマ</td>
							</tr>
							<tr>
								<td colspan=2 bgcolor="#ffeecc"><font size="2">昼ご飯の実態（行動モード）</font></td>
							</tr>
						</table><br>
-->
						<table border=0 cellspacing=2 cellpadding=3 width=768>
							<tr>
								<td colspan=2 align="left" bgcolor="#eff7e8">■メール内容</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">配信開始日時</td>
								<td bgcolor="#ffeecc"><?=mb_convert_kana(get_datepart('M', $fetch->rm_date), 'N')?>月<?=mb_convert_kana(get_datepart('D', $fetch->rm_date), 'N')?>日<?=mb_convert_kana(get_datepart('h', $fetch->rm_date), 'N')?>時
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">なげかけタイトル</td>
								<td bgcolor="#ffeecc"><?=htmlspecialchars($fetch->rm_subject)?></td>
							</tr>
							<tr>
								<td width=140 bgcolor="#eeeeee">本文</td>
								<td bgcolor="#ffeecc"><pre><?=htmlspecialchars($fetch->rm_content)?></pre></td>
							</tr>
						</table><br>
						<div align="center">
						<a href="javascript:window.close();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image19','','images/chart/bt01_ov.gif',1)"><img src="images/chart/bt01.gif" alt="閉じる" name="Image19" width="108" height="31" border="0"></a>
						</div>
					</td>
				</tr>
			</table>
			</form>
			<!-- myパートナー -->

		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
