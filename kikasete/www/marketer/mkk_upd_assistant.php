<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/mr_permission.php");
$inc = "$top/inc";
include("$inc/mk_regist.php");
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// アシスタント番号チェック
if ($assistant < 1 || $assistant > 2)
	redirect('mkk_assistant.php');

// セッション処理
if (!isset($_SESSION['ss_regist']) || !$cont) {
	$_SESSION['ss_regist'] = new marketer_regist;
	$reg = &$_SESSION['ss_regist'];

	// アシスタントのマーケターID取得
	$marketer_id = $_SESSION['ss_marketer_id'];
	$sql = "SELECT mr_marketer_id FROM t_marketer WHERE mr_parent_id=$marketer_id AND mr_status<>9 AND mr_type=4 ORDER BY mr_marketer_id LIMIT 1 OFFSET " . ($assistant - 1);
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_row($result, 0);
		$reg->read_db($fetch[0]);
	} else {
		$reg->type = 4;
		$reg->parent_id = $marketer_id;
		$reg->permission = PMR_DEF_ASSISTANT;
	}
} else
	$reg = &$_SESSION['ss_regist'];
?>
<? marketer_header('きかせて・net ネットリサーチにプロのエージェントを', PG_NULL) ?>

<!--contents start-->
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr> 
<td width="788"> 
<table width="788" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
<td bgcolor="#dbdbdb"> 
<td width="12" rowspan="2"> <img src="images/common/spacer.gif" width="18" height="10"> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td width="170" valign="top" bgcolor="#e5eaf0"> 

<? marketer_menu(M_ASSISTANT); ?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10" /> 
<td valign="top"> 
<form method="post" name="form1" action="mkk_confirm_assistant.php">
<input type="hidden" name="assistant" value="<?=$assistant?>">
<input type="hidden" name="mode">
<table width="553" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td width="553"><img src="images/common_my/title_07.gif" alt="MY SETTING" width="553" height="25" /></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td><img src="images/mkk_assistant/title01.gif" alt="アシスタントの設定" width="200" height="28"> 
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td bgcolor="#dadada"> 
<table border="0" cellspacing="1" cellpadding="5" width="100%" class="nor_text">
<tr bgcolor="#FFFFFF"> 
<td width="30%" bgcolor="#e5eaf0">お名前（漢字）</td>
<td width="65%" colspan="2">姓 
<input type="text" name="name1" size="14" maxlength="50" <?=value($reg->name1)?>>
名 
<input type="text" name="name2" size="14" maxlength="50" <?=value($reg->name2)?>>
</td>
</tr>
<tr bgcolor="#FFFFFF"> 
<td bgcolor="#e5eaf0">お名前（フリガナ）</td>
<td colspan="2">セイ 
<input type="text" name="name1_kana" size="14" maxlength="50" <?=value($reg->name1_kana)?>>
メイ 
<input type="text" name="name2_kana" size="14" maxlength="50" <?=value($reg->name2_kana)?>>
</td>
</tr>
<tr bgcolor="#FFFFFF"> 
<td bgcolor="#e5eaf0">生年月日</td>
<td> 
<input type="text" name="birthday_y" size="4" maxlength="4" <?=value($reg->birthday_y)?>>
年 
<input type="text" name="birthday_m" size="2" maxlength="2" <?=value($reg->birthday_m)?>>
月 
<input type="text" name="birthday_d" size="2" maxlength="2" <?=value($reg->birthday_d)?>>
日 （半角数字） </td>
</tr>
<tr bgcolor="#FFFFFF"> 
<td bgcolor="#e5eaf0">性別</td>
<td colspan="2"> 
<input type="radio" name="sex" <?=value_checked('1', $reg->sex)?>>
男性　　 
<input type="radio" name="sex" <?=value_checked('2', $reg->sex)?>>
女性 </td>
</tr>
<tr bgcolor="#FFFFFF"> 
<td bgcolor="#e5eaf0">メールアドレス</td>
<td colspan="2" nowrap> 
<input type="text" name="mail_addr" size="40" maxlength="100" <?=value($reg->mail_addr)?>>
</td>
</tr>
<tr bgcolor="#FFFFFF"> 
<td bgcolor="#e5eaf0">メールアドレス確認</td>
<td colspan="2" nowrap> 
<input type="text" name="mail_addr2" size="40" maxlength="100" <?=value($reg->mail_addr2)?>>
</td>
</tr>
<tr bgcolor="#FFFFFF"> 
<td bgcolor="#e5eaf0">パスワード</td>
<td colspan="2"> 
<input type="password" name="password" size="20" <?=value($reg->password)?>>
（半角英数字6〜20文字まで）</td>
</tr>
<tr bgcolor="#FFFFFF"> 
<td bgcolor="#e5eaf0">パスワード確認</td>
<td colspan="2"> 
<input type="password" name="password2" size="20" <?=value($reg->password2)?>>
（半角英数字6〜20文字まで）</td>
</tr>
<tr bgcolor="#FFFFFF"> 
<td bgcolor="#e5eaf0">職業</td>
<td colspan="2"> 
<select name="shokugyou_cd">
<? select_shokugyou('選択してください', $reg->shokugyou_cd) ?>
</select>
</td>
</tr>
<tr bgcolor="#FFFFFF"> 
<td bgcolor="#e5eaf0">業種</td>
<td colspan="2"> 
<select name="gyoushu_cd">
<? select_gyoushu('選択してください', $reg->gyoushu_cd, 2) ?>
</select>
</td>
</tr>
<tr bgcolor="#FFFFFF"> 
<td bgcolor="#e5eaf0">職種</td>
<td colspan="2"> 
<select name="shokushu_cd">
<? select_shokushu('選択してください', $reg->shokushu_cd, 2) ?>
</select>
</td>
</tr>
<tr bgcolor="#FFFFFF"> 
<td bgcolor="#e5eaf0">アシスタントの権限</td>
<td colspan="2"> 
<table width="100%" border="0" cellspacing="0" cellpadding="3" class="nor_text">
<tr> 
<td> 
<? input_permission($reg->permission) ?>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td align="center"> 
<table width="300" border="0" cellspacing="0" cellpadding="0">
<tr> 

<?
if ($reg->marketer_id != '') {
?>
<td> 
<input name="image" type="image" src="images/mkk_assistant/bt03.gif" alt="削　除" width="108" height="31"  onClick="document.form1.mode.value=1">
</td>
<?
}
?>

<td><img src="images/common/spacer.gif" width="20" height="10"></td>
<td> 
<input name="image2" type="image" src="images/mkk_assistant/bt04.gif" alt="登　録" width="108" height="31" onClick="document.form1.mode.value=0">
</td>
<td><img src="images/common/spacer.gif" width="20" height="10"></td>
<td> 
<a href="mkk_upd_assistant.php?assistant=<?=$assistant?>"><img src="images/mkk_assistant/bt05.gif" alt="クリア" name="image3" width="108" height="31" border="0"></a>
</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="30"></td>
</tr>
</table>
</form>
</td>
</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>
<!--footer start-->

<? marketer_footer() ?>
