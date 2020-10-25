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
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

	<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff"><tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
			<td width="778" align="center" valign="top"> <table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<!-- myパートナー -->
			<form method="post" name="form1">
			<input type="hidden" name="sort_col" value="1">
			<input type="hidden" name="sort_dir" value="1">
			<input type="hidden" name="page" value=0>
			<input type="hidden" name="pset" value=1>
			</form>
			<form method="post" name="form1" action="mp_pjt_update.php" onsubmit="return onsubmit_form1(this)">
			<div align="center">
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
			<tr>
				<td bgcolor="#eff7e8" colspan=2>■会議室情報</td>
			</tr>
	<tr>
		<td  bgcolor="#eeeeee">会議室名</td>
		<td bgcolor="#ffeecc"><input class="kanji" type="text" name="" size=50 maxlength=100 value="●●の会議室"></td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">目的</td>
		<td bgcolor="#ffeecc"><textarea name="" cols="50" rows="5" class="kanji">目的目的</textarea></td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">参加条件</td>
		<td bgcolor="#ffeecc">
			<table>
				<tr>
					<td><input class="kanji" type="text" name="" size=50 maxlength=100 value="あああああああああああああああああ"></td>
				</tr>
				<tr>
					<td><input class="kanji" type="text" name="" size=50 maxlength=100 value="あああああああああああああああああ"></td>
				</tr>
				<tr>
					<td><input class="kanji" type="text" name="" size=50 maxlength=100 value="あああああああああああああああああ"></td>
				</tr>
				<tr>
					<td><input type="button" value="参加条件の追加"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">リクルート条件</td>
		<td bgcolor="#ffeecc">
			<table>
				<tr>
					<td><input class="kanji" type="text" name="" size=50 maxlength=100 value="あああああああああああああああああ"></td>
				</tr>
				<tr>
					<td><input class="kanji" type="text" name="" size=50 maxlength=100 value="あああああああああああああああああ"></td>
				</tr>
				<tr>
					<td><input class="kanji" type="text" name="" size=50 maxlength=100 value="あああああああああああああああああ"></td>
				</tr>
				<tr>
					<td><input type="button" value="リクルート条件の追加"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">会議室運営ポリシー</td>
		<td bgcolor="#ffeecc"><input type="radio"  name="policy" value="1" checked>本名を使用する　<input type="radio"  name="policy" value="2">ニックネームを使用する</td>
	</tr>
	<tr>
		<td bgcolor="#eeeeee">メンバー数</td>
		<td bgcolor="#ffeecc"><input class="kanji" type="text" name="" size=5 maxlength=100 value="10">人</td>
	</tr>
	<tr>
		<td bgcolor="#eeeeee">リクルート期間</td>
		<td bgcolor="#ffeecc"><input type="text" name="" value="2004" size="5" maxlength="4">年
						<input type="text" name="" value="12" size="3" maxlength="2">月
						<input type="text" name="" value="1" size="3" maxlength="2">日
						<input type="text" name="" value="18" size="3" maxlength="2">時
						〜
						<input type="text" name="" value="2004" size="5" maxlength="4">年
						<input type="text" name="" value="12" size="3" maxlength="2">月
						<input type="text" name="" value="2" size="3" maxlength="2">日
						<input type="text" name="" value="18" size="3" maxlength="2">時</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">決定日</td>
		<td bgcolor="#ffeecc"><input type="text" name="" value="2004" size="5" maxlength="4">年
						<input type="text" name="" value="12" size="3" maxlength="2">月
						<input type="text" name="" value="3" size="3" maxlength="2">日</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">会議室実施期間</td>
		<td bgcolor="#ffeecc"><input type="text" name="" value="2004" size="5" maxlength="4">年
						<input type="text" name="" value="12" size="3" maxlength="2">月
						<input type="text" name="" value="4" size="3" maxlength="2">日
						〜
						<input type="text" name="" value="2004" size="5" maxlength="4">年
						<input type="text" name="" value="12" size="3" maxlength="2">月
						<input type="text" name="" value="5" size="3" maxlength="2">日</td>
	</tr>
			</table>
			</div>
			<table>
				<tr align="right">
					<td> <input type="button" value="更新"><input type="button" value="戻る"> </td>
				</tr>
			</table>
			</form>
			<!-- myパートナー -->
		</td></tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
