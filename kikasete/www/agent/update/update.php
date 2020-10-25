<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:登録情報更新
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/check.php");

//メイン処理
set_global('登録情報更新', BACK_TOP);

$bcc_addr = str_replace("\r\n",',',trim($bcc_addr));
if ($type == 1) $main_agent = "";

$sql = "UPDATE t_agent SET ag_update_date='now',ag_name1=%s,ag_name2=%s,ag_name1_kana=%s,ag_name2_kana=%s,ag_birthday=%s,ag_sex=%s,ag_mail_addr=%s,ag_mail_addr_low=%s,ag_password=%s,ag_kinmu_name=%s,ag_kinmu_unit=%s,ag_kinmu_post=%s,ag_agent_unit=%s,ag_self_introduction=%s,ag_disp_flag=%s,ag_bcc_addr=%s,ag_signature=%s,ag_agent_disc=%s WHERE ag_agent_id=$agent_login_id";
$sql = sprintf($sql,
	sql_char($name1),
	sql_char($name2),
	sql_char($name1_kana),
	sql_char($name2_kana),
	sql_date2($birthday_y,$birthday_m,$birthday_d),
	sql_number($sex),
	sql_char($mail_addr),
	sql_char(strtolower($mail_addr)),
	sql_char($password),
	sql_char($kinmu_name),
	sql_char($kinmu_unit),
	sql_char($kinmu_post),
	sql_char($agent_unit),
	sql_char($self_introduction),
	sql_bool($disp_flag),
	sql_char($bcc_addr),
	sql_char($signature),
	sql_number($agent_disc));
if (db_exec($sql)) {
	$msg = '登録情報を更新しました。';
	$back = "location.href='./edit.php'";
} else {
	$msg = 'エラーが発生したため、更新できませんでした。';
	$back = 'history.back()';

}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body onLoad="document.all.ok.focus()">

<? page_header() ?>
<? menu_start() ?>

<table border=0 cellspacing=0 cellpadding=1 width="95%">
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m4" align="center"><?=$g_title?></td>
				</tr>
				<tr>
					<td align="center"><br><br>
						<p class="msg"><?=$msg?></p>
						<p><input type="button" id="ok" value="　戻る　" onclick="<?=$back?>"></p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
