<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mkk_myp_check.php");

$sql = "SELECT ppj_room_name,ppj_icp_job_id FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
$icp_job_id = $fetch->ppj_icp_job_id;
?>
<? marketer_header('Myパートナー', PG_NULL) ?>

<script type="text/javascript">
<!--
function add_member() {
	var f = document.form1;
	f.action = 'mp_regist_rom.php';
	f.submit();
}
function onsubmit_form1(f) {
	var ok = false;
	for (var i = 0; i < f["name[]"].length; i++) {
		if (f["name[]"][i].value != "") {
			if (f["mail_addr[]"][i].value == "") {
				alert("メールアドレスを入力してください。");
				f["mail_addr[]"][i].focus();
				return false;
			}
			if (!f["mail_addr[]"][i].value.match(/^[a-zA-Z0-9._\/-]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9._-]+[^.]$/)) {
				alert("メールアドレスが正しくありません。");
				f["mail_addr[]"][i].focus();
				return false;
			}
			if (f["password[]"][i].value == "") {
				alert("パスワードを入力してください。");
				f["password[]"][i].focus();
				return false;
			}
			ok = true;
		}
	}
	if (!ok) {
		alert("閲覧者を入力してください。");
		f["name[]"][0].focus();
		return false;
	}
	return confirm("閲覧者を登録します。よろしいですか？");
}
//-->
</script>
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
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
			</tr>
			</table>
			
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td width="95"><img src="images/fl_l_1_0.gif" alt="" width="95" height="18" border="0"></td>
				<td colspan="3" width="285"><img src="images/fl_l_2_0.gif" alt="" width="285" height="18" border="0"></td>
				<td colspan="2" width="190"><img src="images/fl_l_3_0.gif" alt="" width="190" height="18" border="0"></td>
				<td width="95"><img src="images/fl_l_4_1.gif" alt="" width="95" height="18" border="0"></td>
			</tr>
			<tr>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
			</tr>
			<tr>
				<td><img src="images/fl_s_1_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_2_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_3_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_4_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_5_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_6_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_7_2.gif" alt="" width="95" height="50" border="0"></td>
			</tr>
			</table>
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
			</tr>
			</table>
			</div>

			<br>
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><strong>■<?=htmlspecialchars($fetch->ppj_room_name)?></strong></td>
				</tr>
			</table>

			
			<form method="post" name="form1" action="mp_regist_rom_update.php" onsubmit="return onsubmit_form1(this)">
			<div align="center">
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td bgcolor="#eff7e8" colspan=3>■閲覧者登録</td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee" align="center">名前</td>
					<td bgcolor="#eeeeee" align="center">メールアドレス</td>
					<td bgcolor="#eeeeee" align="center">パスワード</td>
				</tr>
<?
if (!isset($num))
	$num = 5;
for ($i = 0; $i < $num; $i++) {
?>
				<tr>
					<td bgcolor="#ffeecc"><input type="text" name="name[]" size="20" <?=value($name[$i])?>></td>
					<td bgcolor="#ffeecc"><input type="text" name="mail_addr[]" size="30" <?=value($mail_addr[$i])?>></td>
					<td bgcolor="#ffeecc"><input type="password" name="password[]" size="20" <?=value($password[$i])?>></td>
				</tr>
<?
}
?>
				<tr>
					<td colspan=5 align="right"><input type="button" value="閲覧者追加" onclick="add_member()"></td>
				</tr>
			</table>
			</div>
			<br>
			<div align="center"><input type="submit" value="　登録　">
			<input type="button" value="　戻る　" onclick="location.href='mp_pjt_job.php?pjt_id=<?=$pjt_id?>'"></div>
			<input type="hidden" name="pjt_id" <?=value($pjt_id)?>>
			<input type="hidden" name="num" <?=value($num + 1)?>>
			<input type="hidden" name="icp_job_id" <?=value($icp_job_id)?>>
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
