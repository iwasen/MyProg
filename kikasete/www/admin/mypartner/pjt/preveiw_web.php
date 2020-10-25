<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・Ｗｅｂプレビュー
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/enq_temp.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'Ｗｅｂプレビュー', BACK_TOP);

// Myパートナークラス取得
if ($pjt_id) {
	$myp = new my_partner_class;
	$myp->read_db($pjt_id);
} else
	$myp = &$_SESSION['s_my_partner'];
$enquete = &$myp->enquete;

if ($enquete->description == '')
	$description = get_enq_description($myp);
else
	$description = $enquete->description;

$description = str_replace('%POINT%', $enquete->point, $description)
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<div align="center">
<table width="470" border="0" cellpadding="2" cellspacing="0" bgcolor="#c8c8c8">
	<tr>
		<td><font size="2"><table border="0" cellpadding="5" cellspacing="0" width="100%">
				<tr>
					<td bgcolor="#c3c2ff" colspan="2"><font color="#2b2b63" size="2"><b><?=htmlspecialchars($enquete->title)?></b></font><font size="2"><br>
						</font></td>
				</tr>
				<tr>
					<td colspan="2" bgcolor="white"><font size="2"><br><?=nl2br(htmlspecialchars($description))?><br>
						</font></td>
				</tr>
<?
if (is_array($enquete->question)) {
	foreach (array_keys($enquete->question) as $i) {
		$question = &$enquete->question[$i];
		if ($question->question_text != '') {
?>
				<tr>
					<td bgcolor="#c3c2ff" colspan="2">
						<font color="#2b2b63" size="2"><b>Q<?=$i?>. <?=nl2br(htmlspecialchars($question->question_text))?></b></font>
<?
			if ($question->url != '') {
?>
						<br><a href="<?=$question->url?>" target="_blank"><?=htmlspecialchars($question->url)?></a>
<?
			}
			if ($question->image_id) {
				if (!$monitor_url) {
					$fetch = get_system_info('sy_url_monitor');
					$monitor_url = $fetch->sy_url_monitor;
				}
?>
						<br><img src="<?=$monitor_url?>img.php?id=<?=$question->image_id?>" alt="">
<?
			}
?>
					</td>
				</tr>
				<tr>
					<td bgcolor="white" width="7%" valign="top"><font size="2">A<?=$i?>.</font></td>
					<td bgcolor="white" width="93%"><font size="2">
<?
			switch ($question->question_type) {
			case 1:		// 単一選択
				if (is_array($question->sel_text)) {
					$br = '';
					foreach ($question->sel_text as $sel_text) {
						if ($sel_text != '') {
							echo $br;
							$br = '<br>';
?>
						<nobr><input type="radio" name="answer<?=$i?>" value=""> <?=htmlspecialchars($sel_text)?></nobr>
<?
						}
					}
					if ($question->fa_flag == 't') {
?>
						<input type="text" name="free_answer<?=$i?>" size=25>
<?
					}
				}
				break;
			case 2:		// 複数選択
				if (is_array($question->sel_text)) {
					$br = '';
					foreach ($question->sel_text as $sel_text) {
						if ($sel_text != '') {
							echo $br;
							$br = '<br>';
?>
						<nobr><input type="checkbox" name="answer<?=$i?>" value=""> <?=htmlspecialchars($sel_text)?></nobr>
<?
						}
					}
					if ($question->fa_flag == 't') {
?>
						<input type="text" name="free_answer<?=$i?>" size=25>
<?
					}
				}
				break;
			case 3:		// 自由回答
?>
						<textarea name="answer<?=$i?>" cols="50" rows="15"></textarea>
						<br><br>ご自由にご記入ください。
<?
				break;
			case 4:		// マトリクス(SA)
?>
						<table width="100%">
							<tr>
								<td>&nbsp;</td>
<?
				foreach ($question->hyoutou as $tno => $hyoutou) {
?>
								<td align="center" bgcolor="#ffddee"><font size="2"><?=htmlspecialchars($hyoutou)?></font></td>
<?
				}
?>
							</tr>
<?
				foreach ($question->hyousoku as $sno => $hyousoku) {
?>
							<tr bgcolor="<?=$sno % 2 ? '#ddeeff' : '#ddffee'?>">
								<td><font size="2"><?=htmlspecialchars($hyousoku)?></font></td>
<?
					foreach ($question->hyoutou as $tno => $hyoutou) {
?>
								<td align="center"><input type="radio" name="answer<?=$sno?>"></td>
<?
					}
?>
							</tr>
<?
				}
?>
					</table>
<?
				if ($question->dup_flag == 't') {
?>
								（同じ列の中で重複して選択することはできません）
<?
				}
				break;
			case 5:		// マトリクス(MA)
?>
						<table width="100%">
							<tr>
								<td>&nbsp;</td>
<?
				foreach ($question->hyoutou as $tno => $hyoutou) {
?>
								<td align="center" bgcolor="#ffddee"><font size="2"><?=htmlspecialchars($hyoutou)?></font></td>
<?
				}
?>
							</tr>
<?
				foreach ($question->hyousoku as $sno => $hyousoku) {
?>
							<tr bgcolor="<?=$sno % 2 ? '#ddeeff' : '#ddffee'?>">
								<td><font size="2"><?=htmlspecialchars($hyousoku)?></font></td>
<?
					foreach ($question->hyoutou as $tno => $hyoutou) {
?>
								<td align="center"><input type="checkbox" name="answer<?=$i?>"></td>
<?
					}
?>
							</tr>
<?
				}
?>
					</table>
<?
				if ($question->dup_flag == 't') {
?>
								（同じ列の中で重複して選択することはできません）
<?
				}
				break;
			}
?>
						<br><br>
						</font></td>
				</tr>
<?
		}
	}
}
?>
			</table>
		</td>
	</tr>
</table>
<br>
<input type="button" value="　戻る　" onclick="history.back()">
</div>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
