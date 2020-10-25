<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:１万人アンケート管理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/enquete.php");

set_global('monitor', 'モニター管理', '１万人アンケート管理', BACK_TOP);

$enquete = new enquete_class;
$enquete->title = trim($_POST['title']);
$enquete->description = trim($_POST['description']);
for ($i = 1; $i <= $_POST['question_cnt']; $i++) {
	$enquete->question[$i] = new question_class;
	$question = &$enquete->question[$i];
	$question->question_text = $_POST["question_text$i"];
	$question->question_type = $_POST["question_type$i"];
	for ($j = 1; $j <= $_POST["sel_cnt$i"]; $j++) {
		$question->sel_text[$j] = $_POST["sel_text$i"][$j - 1];
		$question->sel_text_s[$j] = $_POST["sel_text_s$i"][$j - 1];
	}
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

<form>
<center>
<table width="520" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
	<tr>
		<td width="100%" valign="top" bgcolor="#ffffff">
			<table width="450" border="0" cellpadding="2" cellspacing="0" bgcolor="#c8c8c8">
				<tr>
					<td><font size="2"><table border="0" cellpadding="5" cellspacing="0" width="100%">
							<tr>
								<td bgcolor="#c3c2ff" colspan="2"><font color="#2b2b63" size="2"><b><?=htmlspecialchars($enquete->title)?></b></font><font size="2"><br>
									</font></td>
							</tr>
							<tr>
								<td colspan="2" bgcolor="white"><font size="2"><br><?=nl2br(htmlspecialchars($enquete->description))?><br>
									<br>
									</font></td>
							</tr>
<?
if (is_array($enquete->question)) {
	foreach (array_keys($enquete->question) as $i) {
		$question = &$enquete->question[$i];
		if ($question->question_text != '') {
?>
							<tr>
<? /* 07/04/23 wakasa HTML差込機能追加 */ ?>
								<td bgcolor="#c3c2ff" colspan="2"><font color="#2b2b63" size="2"><b>Q<?=$i?>. <?=nl2br($question->question_text)?></b></font></td>
							</tr>
							<tr>
								<td bgcolor="white" width="9%" valign="top"><font size="2">A<?=$i?>.</font></td>
								<td bgcolor="white" width="91%"><font size="2">
<?
			switch ($question->question_type) {
			case 1:		// 単一選択
				if (is_array($question->sel_text)) {
					foreach (array_keys($question->sel_text) as $j) {
						if ($question->sel_text[$j] != '') {
?>
									<nobr><input type="radio" name="answer<?=$no?>" value="<?=$j?>"> <?=$question->sel_text[$j]?> </nobr>
<?
						}
					}
				}
				break;
			case 2:		// 複数選択
				if (is_array($question->sel_text)) {
					foreach (array_keys($question->sel_text) as $j) {
						if ($question->sel_text[$j] != '') {
?>
									<nobr><input type="checkbox" name="answer<?=$no?>" value="<?=$j?>"> <?=$question->sel_text[$j]?> </nobr>
<?
						}
					}
				}
				break;
			case 3:		// 自由回答
?>
									<textarea name="answer<?=$no?>" cols="50" rows="5"></textarea>
									<br><br>ご自由にご記入ください。
<?
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
						</table></font></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="500" valign="top" bgcolor="#ffffff" align="center">
				<tr>
					<td width="50%" align="right">
						<font size="2">
						<input type="button" value="　回答を送る　">
						</font>
					</td>
					<td width="50%" align="left">
						<font size="2">
						<input type="reset" value="　クリア　">
						</font>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<input type="button" value="　閉じる　" onclick="window.close()">
</center>
</form>

</body>
</html>
