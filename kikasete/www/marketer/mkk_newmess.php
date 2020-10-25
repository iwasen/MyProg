<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
?>
<? marketer_header('Ｍｙモニター', PG_NULL, '') ?>

<script type="text/javascript">
<!--
function onload_body() {
	var f = document.form1;
	set_display("list", f.member[1].checked);
}
function set_display(id, visible) {
	if (visible) {
		all_check('to_ary[]', false);
		if (document.all)
			document.all(id).style.display = "";
		else if (document.getElementById)
			document.getElementById(id).style.display = "";
	} else {
		all_check('to_ary[]', true);
		if (document.all)
			document.all(id).style.display = "none";
		else if (document.getElementById)
			document.getElementById(id).style.display = "none";
	}
}
function all_check(name, check) {
	var f = document.form1;
	if (f[name]) {
		if (f[name].length) {
			for (var i = 0; i < f[name].length; i++)
				f[name][i].checked = check;
		} else
			f[name].checked = check;
	}
}
function onsubmit_form1(f) {
	switch (f.next_action.value) {
	case "test":
		msg = "テスト送信を行います。よろしいですか？";
		break;
	case "send":
		msg = "メンバーへメールを送信します。よろしいですか？";
		break;
	default:
		return false;
	}
	return confirm(msg);
}

window.onload = function() {
  onload_body();
}
//-->
</script>

	<TABLE border=0 cellPadding=0 cellSpacing=0 width=788>
	<TBODY>
		<TR>
			<TD><IMG border=0 height=8 src="images/common/spacer.gif" width=1></TD>
		</TR>
	</TBODY>
	</TABLE>
<FORM METHOD="POST" name="form1" ACTION="mkk_mymoni_messcompl.php" onsubmit="return onsubmit_form1(this)">
	<TABLE border=0 cellPadding=0 cellSpacing=0 width=788>
	<TBODY>
	<tr>
		<td width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768" ><b>新規投げかけ</b></td>
	</tr>
	<tr>
		<td width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768">
			<table width="768" border="1" cellspacing="0" cellpadding="3" bordercolor="#ffffff" bordercolorlight="#ffffff" bordercolordark="#ffffff">
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						〆切
					</td>
					<td width="550" bgcolor="#ffeecc">
						<input name="limit_y" type="TEXT" size="8" maxlength=4>年
						<input name="limit_m" type="TEXT" size="4" maxlength=2>月
						<input name="limit_d" type="TEXT" size="4" maxlength=2>日
						<input name="limit_h" type="TEXT" size="4" maxlength=2>時
						<span class="footer_text">（２４時間表記でお書きください。）</span>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						タイトル
					</td>
					<td width="550" bgcolor="#ffeecc">
						<input name="title" type="TEXT" size="75">
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						本文
					</td>
					<td width="550" bgcolor="#ffeecc">
						<TEXTAREA name="content" rows="30" cols="75"></TEXTAREA>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						送信先
					</td>
					<td width="550" bgcolor="#ffeecc">
						<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
							<tr>
								<td width=400>
									<input name="member" type="radio" value="1" checked onclick="set_display('list', false)">メンバー全員
									<input name="member" type="radio" value="2" onclick="set_display('list', true)">選択する
								</td>
							</tr>
						</table>
						<div id="list">
						<table border=0 cellspacing=0 cellpadding=0 width="100%">
							<tr>
								<td>
									※送信するメンバーを選択して下さい。　　　
								<td>
								<td align="right">
									<input type="button" value="全選択" onclick="all_check('to_ary[]', true)">
									<input type="button" value="全解除" onclick="all_check('to_ary[]', false)">
								</td>
							</tr>
						</table>
						<table border=1 cellspacing=1 cellpadding=0 width="100%">
							<tr>
								<td align="center">メンバー</td>
								<td align="center">性別</td>
								<td align="center">年齢</td>
								<td align="center">未既婚</td>
								<td align="center">居住地</td>
								<td align="center">職業</td>
							</tr>
<?
$sql = "SELECT mn_monitor_id,mn_name1,mn_name2,mn_sex,DATE_PART('Y',AGE(mn_birthday)) AS mn_age,mn_mikikon,ar_area_name,sg_shokugyou_name"
		. " FROM t_my_monitor_list"
		. " JOIN t_monitor ON mn_monitor_id=rl_monitor_id"
		. " LEFT JOIN m_area ON ar_area_cd=mn_jitaku_area"
		. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
		. " WHERE rl_room_id=$room_id"
		. " ORDER BY mn_monitor_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
							<tr>
								<td><font size=2><input type="checkbox" name="to_ary[]" value="<?=$fetch->mn_monitor_id?>"><?=$i + 1?>:<?="$fetch->mn_name1 $fetch->mn_name2"?></td>
								<td align="center"><?=decode_sex($fetch->mn_sex)?></td>
								<td align="right"><?=$fetch->mn_age?></td>
								<td align="center"><?=decode_mikikon($fetch->mn_mikikon)?></td>
								<td><?=htmlspecialchars($fetch->ar_area_name)?></td>
								<td><?=htmlspecialchars($fetch->sg_shokugyou_name)?></td>
							</tr>
<?
}
?>
						</table>
						</div>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						ＢＣＣ先
					</td>
					<td width="550" bgcolor="#ffeecc">
						<TEXTAREA name="bcc_addr" rows="5" cols="35">
<?
$marketer_id = $_SESSION['ss_marketer_id'];
$sql = "SELECT mr_mail_addr FROM t_marketer WHERE mr_parent_id=$marketer_id AND mr_status<>9 AND mr_type=4 ORDER BY mr_marketer_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	echo "$fetch->mr_mail_addr\n";
}
?>
						</TEXTAREA>
						<br><span class="footer_text">複数登録される場合は、改行もしくはカンマ区切りで入力してください。</span>
						<!-- 自分とエージェントと事務局には毎回同報必須。アシスタントはデフォルトで表示しておき、自分で消せるようにして頂きたいです。-->
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768">
			<table width=768>
				<div align="center">
				<input type="hidden" name="room_id" value="<?=$room_id?>">
				<input type="hidden" name="next_action">
				<input type="submit" value="テスト送信" onclick="document.form1.next_action.value='test'">
				<input type="submit" value="　送信　" onclick="document.form1.next_action.value='send'">
				<input type="button" value="　戻る　" onclick="history.back()">
				</div>
			</table>
		</td>
	</tr>
	</TABLE>
</FORM>

<? marketer_footer() ?>
