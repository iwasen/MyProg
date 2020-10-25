<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・質問表示
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

$myp = new my_partner_class;
$myp->read_db($pjt_id);
$enquete = &$myp->enquete;
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>きかせて・net−Myパートナー</title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div align="center">
<br>
<table border="0" cellspacing="0" cellpadding="0" width="710" class="small">
	<tr>
		<td width="10"></td>
		<td width="700" valign="top"><b><?=htmlspecialchars($myp->room_name)?></b></td>
	</tr>
</table>
<br>

<form>

<table border=0 cellspacing=2 cellpadding=3 width="95%" class="small">
	<tr bgcolor="#eff7e8">
		<td>
			<table border=0 cellspacing=0 cellpadding=0 width="100%">
				<tr>
					<td bgcolor="#eff7e8" colspan="2">■質問表示</td>
					<td align="right"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="small">
				<tr>
					<td width="20%" bgcolor="#eeeeee">アンケートタイトル</td>
					<td width="80%" bgcolor="#ffeecc"><?=htmlspecialchars($enquete->title)?></td>
				</tr>
<?
if (is_array($enquete->question)) {
	foreach ($enquete->question as $qno => $question) {
		if ($question->question_type == 1 || $question->question_type == 2) {
?>
				<tr>
					<td bgcolor="#eeeeee" width="20%" rowspan="3">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
					<td bgcolor="#ffeecc"><?=htmlspecialchars($question->question_text)?></td>
				</tr>
				<tr>
					<td bgcolor="#ffeecc"><?=$question->question_type == 1 ? '単一選択(SA)' : '複数選択(MA)'?></td>
				</tr>
				<tr>
					<td bgcolor="#ffeecc">
<?
			foreach ($question->sel_text as $sno => $sel_text) {
?>
									<?=$sno?>　<?=htmlspecialchars($sel_text)?><br>
<?
			}
?>
					</td>
				</tr>
<?
		} elseif ($question->question_type == 3) {
?>
				<tr>
					<td bgcolor="#eeeeee" width="20%" rowspan="2">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
					<td bgcolor="#ffeecc"><?=htmlspecialchars($question->question_text)?></td>
				</tr>
				<tr>
					<td bgcolor="#ffeecc">自由回答(FA)</td>
				</tr>
<?
		} elseif ($question->question_type == 4 || $question->question_type == 5) {
?>
				<tr>
					<td bgcolor="#eeeeee" width="20%" rowspan="3">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
					<td bgcolor="#ffeecc"><?=htmlspecialchars($question->question_text)?></td>
				</tr>
				<tr>
					<td bgcolor="#ffeecc"><?=$question->question_type == 4 ? 'マトリックス(SA)' : 'マトリックス(MA)'?></td>
				</tr>
				<tr>
					<td bgcolor="#ffeecc">
						<table border="0" cellspacing="2" cellpadding="2" width="100%" class="small">
							<tr>
								<td>&nbsp;</td>
<?
			foreach ($question->hyoutou as $hno => $hyoutou) {
?>
								<td bgcolor="#ffffff"><?=mb_convert_kana($hno, 'N')?>．<?=htmlspecialchars($hyoutou)?></td>
<?
			}
?>
							</tr>
<?
			foreach ($question->hyousoku as $hno => $hyousoku) {
?>
							<tr bgcolor="<?=$hno % 2 ? '#e8fbfd' : '#ffffff'?>">
								<td><?=mb_convert_kana($hno, 'N')?>．<?=htmlspecialchars($hyousoku)?></td>
<?
				foreach ($question->hyoutou as $hno => $hyoutou) {
?>
								<td></td>
<?
				}
?>
							</tr>
<?
			}
?>
						</table>
					</td>
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

<input type="button" value="閉じる" onclick="window.close()">
</form>
</div>

</body>
</html>
