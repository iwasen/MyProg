<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:メール文登録
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/select.php");

function ClickCountURL($ct_id) {
	$fetch = get_system_info('sy_url_top');
	return $fetch->sy_url_top . "ct.php?%CTID".$ct_id."%-%CLICK%";
}

//メイン処理

set_global2('project', 'プロジェクト管理', 'クリックカウンタ設定', BACK_TOP);

for ($i = 1; ; $i++) {
	$replacement = sprintf('{###%03d###}', $i);
	$body = preg_replace ('/http/', $replacement, $body, 1, $count);
	if ($count == 0)
		break;
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>

<form method="post" name="form1" action="enq_mail_click.php">
<center>
<table border=0 cellpadding=2 cellspacing=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■クリックカウンタ自動設定</td>
		</td>
	</tr>
	<tr>
		<td class="m1" width=15%>カウンタ期間(開始)</td>
		<td class="n1">
			<select name="cc_start_year"><? select_year(date(Y),' ','')?></select>年
			<select name="cc_start_month"><? select_month(' ','')?></select>月
			<select name="cc_start_day"><? select_day(' ','')?></select>日
			<select name="cc_start_hour"><? select_hour(' ','')?></select>時
			<span class="note">（開始を指定しない場合は年をブランクにしてください）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">カウンタ期間(終了)</td>
		<td class="n1">
			<select name="cc_end_year"><? select_year(date(Y),' ','')?></select>年
			<select name="cc_end_month"><? select_month(' ','')?></select>月
			<select name="cc_end_day"><? select_day(' ','')?></select>日
			<select name="cc_end_hour"><? select_hour(' ','')?></select>時
			<span class="note">（終了を指定しない場合は年をブランクにしてください）</span>
		</td>
	</tr>
	<tr>
		<td><br></td>
	</tr>
</table>
<table <?=LIST_TABLE?> width=100%>
	<tr bgcolor="#E0FFE0">
		<th>CTID</th>
		<th>URL名称</th>
		<th>飛び先URL</th>
		<th>生成URL</th>
		<th>生成</th>
	</tr>
<?
$sql = "SELECT MAX(cc_ct_id) FROM t_click_counter";
$ct_id = db_fetch1($sql) + 1;

$n = preg_match_all('/(?:(?:(<[a-zA-Z]+)[^<>]+)|(?:))({###[0-9][0-9][0-9]###}s?:\/\/[-_.!~*\'\(\)a-zA-Z0-9;\/?:\@&=+\$,%#]+)/', $body, $matches);
$i2 = 0;
for ($i = 0; $i < $n; $i++) {
	if ($matches[1][$i]{0} != '<' || strcasecmp($matches[1][$i], '<a') == 0) {
		$cc_jump_url = $matches[2][$i];
		$cc_jump_url_org = preg_replace('/{###[0-9][0-9][0-9]###}/', 'http', $cc_jump_url);
		if (!strpos($cc_jump_url, "%CLICK%") && !strpos($cc_jump_url, "%ENQUETE_PARAM%")) {
?>
	<tr class="tc<?=$i2 % 2?>">
		<td align="center"><?=$ct_id?></td>
		<td><input type="text" name="cc_url_name[<?=$i2?>]" size=50 maxlength=50 value="URL-<?=$ct_id?>"></td>
		<td>
			<input type="hidden" name="cc_jump_url[<?=$i2?>]" <?=value($cc_jump_url)?>>
			<input type="hidden" name="cc_jump_url_org[<?=$i2?>]" <?=value($cc_jump_url_org)?>>
			<a href="<?=$cc_jump_url_org?>"  target='_blank'><?=htmlspecialchars($cc_jump_url_org)?></a>
		</td>
		<td><input type="hidden" name="click_url[<?=$i2?>]" <?=value(ClickCountURL($ct_id))?>><?=ClickCountURL($ct_id)?></td>
		<td align="center"><input type="checkbox" name="cc_ct_id[]" <?=value($i2)?> checked></td>
	</tr>
<?
			$ct_id++;
			$i2++;
		}
	}
}
?>
</table>
<br>
<input type="submit" value="　OK　">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="job_id" <?=value($job_id)?>>
<input type="hidden" name="mail_format" <?=value($mail_format)?>>
<textarea name="body" style="display:none"><?=$body?></textarea>
</center>
</form>

</body>
</html>
