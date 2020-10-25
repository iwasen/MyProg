<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/icp_db.php");
include("$inc/icp_func.php");
include("$inc/icp_regist.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mkk_myp_check.php");

icp_db_begin_trans();

if (!$msg) {
	// ICPプロジェクトID取得
	$icp_pjt_id = get_icp_pjt_id();

	for ($i = 0; $i < count($name); $i++) {
		if ($name[$i] != "") {
			$data = array();
			$data['mail_addr'] = strtolower($mail_addr[$i]);
			$data['password'] = $password[$i];
			$data['nickname'] = $name[$i];
			regist_icp_member($icp_pjt_id, $icp_job_id, $data, '0000000000', '0001100000', 0);
		}
	}
}

icp_db_commit_trans();
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
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td>閲覧者を登録しました。</td>
				</tr>
			</table>
			<br>
			<a href="mp_pjt_job.php?pjt_id=<?=$pjt_id?>"><img src="images/mkk_bt/back.gif" alt="戻る" name="image2" width="108" height="31" border="0"></a>
			</form>
			</div>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
