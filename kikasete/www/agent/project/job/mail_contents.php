<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:送信メールコンテンツ表示
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/enq_mail.php");
include("$inc/str_replace.php");

set_global2('project', 'プロジェクト管理', '送信メールコンテンツ表示', BACK_TOP);

if ($en_id) {
	$enquete = new enquete_class;
	$enquete->read_db($en_id);
	$jb_body = str_replace('%DESCRIPTION%', $enquete->description, $jb_body);
	if ($enquete->get_question_num()) {
		$jb_body = str_replace('%QUESTION%', $enquete->get_question_num(), $jb_body);
		$jb_body = make_enq_mail($enquete, $jb_header, $jb_body, $jb_footer);
	} else
		$jb_body = "$jb_header\n$jb_body\n$jb_footer";
} else
	$jb_body = "$jb_header\n$jb_body\n$jb_footer";

if (SERVICE == 'admin') {
	$sql = "SELECT ad_mail_addr,ad_name1 FROM m_admin WHERE ad_admin_id={$_SESSION['admin_login_id']}";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$to_addr = $fetch->ad_mail_addr;
		$to_name = $fetch->ad_name1;
	}
} elseif (SERVICE == 'agent') {
	$sql = "SELECT ag_mail_addr,ag_name1 FROM t_agent WHERE ag_agent_id={$_SESSION['agent_login_id']}";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$to_addr = $fetch->ag_mail_addr;
		$to_name = $fetch->ag_name1;
	}
}

$jb_body = str_replace('%MAIL_ADDR%', $to_addr, $jb_body);
$jb_body = str_replace('%MONITOR_NAME%', $to_name, $jb_body);

$sql = "SELECT jb_search_id FROM t_job WHERE jb_job_id=$job_id";
replace_kyoumi_genre(0, db_fetch1($sql), $jb_body);

$pat  = '(https?:\/\/[-_.!~*\'()a-zA-Z0-9;/?:@&=+$,%#]+)';
$rep  = '<a href="\1" target="_blank">\1</a>';
$jb_body = ereg_replace($pat, $rep, htmlspecialchars($jb_body));
$jb_body = str_replace("\r", '', $jb_body);
$jb_body = str_replace("\n", "\n<br>", $jb_body);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<center>
<table border=0 cellspacing=1 cellpadding=0>
	<tr>
		<td class="m5">■<?=$jb_subject?></td>
	</tr>
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=3 bordercolor="navy" width="100%">
				<tr>
					<td><tt><?=$jb_body?></tt></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</center>

<center>
<input type="button" value=" 閉じる " onclick="window.close()">
</center>
</body>
</html>
