<?
$top = './..';
$inc = "$top/../inc";
include("$inc/decode.php");
include("$inc/ma_profile.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_regist.php");
include("$inc/mn_header.php");

// コンビニ名表示
function decode_conveni($code) {
	if ($code != '') {
		$sql = "SELECT cv_name FROM m_conveni WHERE cv_conveni_cd IN ($code)";
		$name = db_decode_multi($sql);
	}
	return $name;
}

// スーパー名表示
function decode_super($code) {
	if ($code != '') {
		$sql = "SELECT sp_name FROM m_super WHERE sp_super_cd IN ($code)";
		$name = db_decode_multi($sql);
	}
	return $name;
}

// 興味ジャンル名表示
function decode_genre($code) {
	if ($code != '') {
		$sql = "SELECT kg_name FROM m_genre WHERE kg_genre_cd IN ($code)";
		$name = db_decode_multi($sql);
	}
	return $name;
}

// 追加プロファイル名表示
function decode_ma_profile($profile_id, $code) {
	if ($code != '') {
		$sql = "SELECT pfs_select_text FROM t_profile_sel WHERE pfs_profile_id=$profile_id AND pfs_profile_cd IN ($code) ORDER BY pfs_order";
		$name = db_decode_multi($sql);
	}
	return $name;
}

// マスタから複数名称表示
function db_decode_multi($sql) {
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		$name .= "<nobr>$fetch[0]　</nobr>";
	}
	return $name;
}

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist']))
	redirect('regist2.php');
$reg = &$_SESSION['ss_regist'];
?>
<?monitor_menu()?>
<!-- コンテンツ本体のソースはこの間に表記 -->
<form name="form1" method="post" action="regicode.php">
<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
	<TBODY>
	<TR>
		<TD align="left" bgcolor="#EEEEEE" background="<?=$img?>/tit_bk.gif"><IMG src="<?=$img?>/tit_reg.gif" width=590 height=30 border=0 alt="モニター登録"></TD>
		<TD align="right" bgcolor="#EEEEEE" background="<?=$img?>/tit_bk.gif"><IMG src="<?=$img?>/tit_bk.gif" width=10 height=30 border=0></TD>
	</TR>
</TABLE>
<TABLE cellSpacing=0 cellPadding=0 border=0 width="600">
<TR>
<TD align="left" colspan="2"><IMG src="<?=$img?>/spacer.gif" width=1 height=15 border=0></TD>
</TR>
<TR>
<TD align="left"><IMG src="<?=$img?>/reg4_m.gif" width=600 height=36 border=0 alt="基本情報＆生活情報の確認"></TD>
</TR>
<TR>
<TD align="left" colspan="2"><IMG src="<?=$img?>/spacer.gif" width=1 height=15 border=0></TD>
</TR>
<TR>
<TD colspan="2">

	<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
	<TBODY>
	<TR>
	<TD align="left">
<SPAN class=fs12_lh120>
<font color="#666666">
<B>以下の内容で登録します。よろしいですか？</B><BR>
（修正はご登録後、Myページからおこなうことができます。） <br>
<BR>
</font></SPAN>
	</TD>
	</TR>
	<TR>
	<TD align="left">

		<table border="0" cellpadding="0" cellspacing="0" width="600">
			<tr><td bgcolor="#999999">

		<table border="0" cellpadding="4" cellspacing="1" width="100%">
			<tr>
				<td bgcolor="#dedede" width="30%"><SPAN class=fs12_lh120><font color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> お名前（漢字）</SPAN></td>
				<td bgcolor="#FFFFFF"><SPAN class=fs12_lh120>姓：<?=htmlspecialchars($reg->name1)?></SPAN></td>
				<td bgcolor="#FFFFFF"><SPAN class=fs12_lh120>名：<?=htmlspecialchars($reg->name2)?></td>
			</tr>
			<tr>
				<td bgcolor="#dedede"><SPAN><SPAN class=fs12_lh120><font  color="#ff0009">※</font></SPAN></SPAN><SPAN class=fs12_lh120> お名前（フリガナ）</SPAN></td>
				<td bgcolor="#FFFFFF"><SPAN class=fs12_lh120>セイ：<?=htmlspecialchars($reg->name1_kana)?></SPAN></td>
				<td bgcolor="#FFFFFF"><SPAN class=fs12_lh120>メイ：<?=htmlspecialchars($reg->name2_kana)?></SPAN></td>
			</tr>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120><font  color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> 生年月日</SPAN></td>
				<td colspan="2" bgcolor="#FFFFFF">
					<SPAN class=fs12_lh120><?=$reg->birthday_y?>年 <?=$reg->birthday_m?>月 <?=$reg->birthday_d?>日</SPAN>
				</td>
			</tr>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120><font  color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> 未既婚</SPAN></td>
				<td bgcolor="#FFFFFF" colspan="2">
					<SPAN class=fs12_lh120><?=decode_mikikon($reg->mikikon)?></SPAN>
				</td>
			</tr>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120><font  color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> 性別</SPAN></td>
				<td bgcolor="#FFFFFF" colspan="2">
					<SPAN class=fs12_lh120><?=decode_sex($reg->sex)?></SPAN>
				</td>
			</tr>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120><font  color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> メールアドレス</SPAN></td>
				<td bgcolor="#FFFFFF" colspan="2"><SPAN class=fs12_lh120><?=htmlspecialchars($reg->mail_addr)?></SPAN></td>
			</tr>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120><font  color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> パスワード</SPAN></td>
				<td colspan="2" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=htmlspecialchars($reg->password)?></SPAN></td>
			</tr>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120><font  color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> HTMLメール受信</SPAN></td>
				<td colspan="2" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_html_mail($reg->html_mail)?></SPAN></td>
			</tr>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120><font  color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> はいめーる受信</SPAN></td>
				<td colspan="2" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_haimail_flag($reg->haimail_flag)?></SPAN></td>
			</tr>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120><font  color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> 郵便番号（自宅）</SPAN></td>
				<td colspan="2" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=$reg->jitaku_zip1?>-<?=$reg->jitaku_zip2?></SPAN></td>
			</tr>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120><font  color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> 居住地域（自宅）</SPAN></td>
				<td colspan="2" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_area($reg->jitaku_area)?></SPAN>
				</td>
			</tr>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120><font  color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> 職業</SPAN></td>
				<td colspan="2" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_shokugyou($reg->shokugyou_cd)?></SPAN></td>
			</tr>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120><font  color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> 業種</SPAN></td>
				<td colspan="2" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_gyoushu($reg->gyoushu_cd)?></SPAN></td>
			</tr>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120><font  color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> 職種</SPAN></td>
				<td colspan="2" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_shokushu($reg->shokushu_cd)?></SPAN></td>
			</tr>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120><font id="kinmu_zip"  color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> 郵便番号</SPAN></td>
				<td colspan="2" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=$reg->kinmu_zip1?>-<?=$reg->kinmu_zip2?></SPAN></td>
			</tr>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120><font id="kinmu_area"  color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> 通勤・通学先地域</SPAN></td>
				<td colspan="2" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_area($reg->kinmu_area)?></SPAN></td>
			</tr>
<?
if ($reg->spouse_flg != '') {
?>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120>配偶者の有無</SPAN></td>
				<td colspan="2" valign="top" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_spouse_flg($reg->spouse_flg) . ($reg->spouse_flg == '3' && $reg->spouse_other != '' ? "：$reg->spouse_other" : '')?></SPAN></td>
			</tr>
<?
	if ($reg->spouse_flg == '1') {
		if ($reg->spouse_birthday_y != '' && $reg->spouse_birthday_m != '' && $reg->spouse_birthday_d != '') {
?>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120>配偶者の生年月日</SPAN></td>
				<td colspan="2" valign="top" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=$reg->spouse_birthday_y?>年 <?=$reg->spouse_birthday_m?>月 <?=$reg->spouse_birthday_d?>日</SPAN></td>
			</tr>
<?
		}

		if ($reg->spouse_shokugyo_cd != '') {
?>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120>配偶者の職業</SPAN></td>
				<td colspan="2" valign="top" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_shokugyou($reg->spouse_shokugyo_cd)?></SPAN></td>
			</tr>
<?
		}
	}
}

if ($reg->have_child != '') {
?>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120>お子さまの有無</SPAN></td>
				<td colspan="2" valign="top" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_have_child($reg->have_child)?></SPAN></td>
			</tr>
<?
	if ($reg->have_child == '1') {
		foreach ($reg->child as $i => &$child) {
?>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120><?=$i + 1?>人目のお子さま</SPAN></td>
				<td colspan="2" valign="top" bgcolor="#FFFFFF"><SPAN class=fs12_lh120>
					性別：<?=decode_child_sex($child->sex)?><br>
					生年月日：<?=$child->birthday_y?>年 <?=$child->birthday_m?>月 <?=$child->birthday_d?>日
				</SPAN></td>
			</tr>
<?
		}
	}
}

if ($reg->senior_flg != '') {
?>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120>現在同居されているシニア層の方の有無</SPAN></td>
				<td colspan="2" valign="top" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_senior_flg($reg->senior_flg)?></SPAN></td>
			</tr>
<?
}

if ($reg->housing_form != '') {
?>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120>住居形態</SPAN></td>
				<td colspan="2" valign="top" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_housing_form($reg->housing_form)?></SPAN></td>
			</tr>
<?
}

if ($reg->have_car != '') {
?>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120>車の所有</SPAN></td>
				<td colspan="2" valign="top" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_have_car($reg->have_car)?></SPAN></td>
			</tr>
<?
}
?>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120>よく行くコンビニ</SPAN></td>
				<td colspan="2" valign="top" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_conveni($reg->conveni)?></SPAN></td>
			</tr>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120>よく行くスーパー</SPAN></td>
				<td colspan="2" valign="top" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_super($reg->super)?></SPAN></td>
			</tr>
<?
// 追加プロファイル
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120><?=htmlspecialchars($profile_name)?></SPAN></td>
				<td colspan="2" valign="top" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_ma_profile($profile_id, $reg->ma_profile)?></SPAN></td>
			</tr>
<?
}
?>
			<tr>
				<td bgcolor="#dedede"><SPAN class=fs12_lh120>興味ジャンル</SPAN></td>
				<td colspan="2" valign="top" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_genre($reg->genre)?></SPAN></td>
			</tr>
			<tr>
				<td colspan="3" align="center" bgcolor="#FFFFFF">
			<input type="button" value="　次　へ　" onclick="disabled=true;document.form1.submit()">
			<input type="button" value="修正する" onclick="location.href='regist2.php'">
				</td>
			</tr>
		</table>

			</td>
			</tr>
		</table>

	</TD>
	</TR>
	</TBODY>
	</TABLE>
<DIV align="left"><img height="75" width="600" src="<?=$img?>/reg_flow04.gif" border="0" alt="基本情報＆生活情報の確認" vspace="15"></DIV>
</TD>
</TR>
</TBODY>
</TABLE>
</form>
<!-- コンテンツ本体のソースはこの間に表記 -->
			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>