<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・ＷＥＢアンケート手動修正
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/enq_temp.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'ＷＥＢアンケート手動修正', BACK_TOP);

if ($edit) {
	$myp = &$_SESSION['s_my_partner'];
	$pjt_id = $myp->pjt_id;
} else {
	$_SESSION['s_my_partner'] = new my_partner_class;
	$myp = &$_SESSION['s_my_partner'];
	$myp->read_db($pjt_id);
}
$enquete = &$myp->enquete;

if ($enquete->description == '')
	$description = get_enq_description($myp);
else
	$description = $enquete->description;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	if (document.form1.next_action.value == 'update')
		return confirm("このＷＥＢアンケートを更新します。よろしいですか？");
	else
		return true;
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<p>
<form method="post" name="form1" action="update_web.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="<?=width('80%')?>">
	<tr>
		<td>
			<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
				<tr>
					<td class="n1" width="20%" rowspan="3">説明文</td>
					<td>
						<textarea class="kanji" rows="20" cols="70" name="description"><?=htmlspecialchars($description)?></textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>

<?
if (is_array($enquete->question)) {
	foreach ($enquete->question as $qno => $question) {
		if ($question->question_type == 1 || $question->question_type == 2) {
?>
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="small">
				<tr>
					<td width="20%" rowspan="3" bgcolor="#eeeeee">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
					<td><?=htmlspecialchars($question->question_text)?></td>
				</tr>
				<tr>
					<td><?=$question->question_type == 1 ? '単一選択(SA)' : '複数選択(MA)'?></td>
				</tr>
				<tr>
					<td>
<?
			foreach ($question->sel_text as $sno => $sel_text) {
?>
									<?=$sno?>　<?=htmlspecialchars($sel_text)?><br>
<?
			}
			if ($question->fa_flag == 't') {
?>
									（最後の選択肢でフリー回答を取得する）
<?
			}
?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
<?
		} elseif ($question->question_type == 3) {
?>
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="small">
				<tr>
					<td width="20%" rowspan="3" bgcolor="#eeeeee">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
					<td><?=htmlspecialchars($question->question_text)?></td>
				</tr>
				<tr>
					<td>自由回答</td>
				</tr>
			</table>
		</td>
	</tr>
<?
		} elseif ($question->question_type == 4 || $question->question_type == 5) {
?>
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="small">
				<tr>
					<td width="20%" rowspan="3" bgcolor="#eeeeee">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
					<td><?=htmlspecialchars($question->question_text)?></td>
				</tr>
				<tr>
					<td><?=$question->question_type == 4 ? 'マトリックス(SA)' : 'マトリックス(MA)'?></td>
				</tr>
				<tr>
					<td>
						<table border="0" cellspacing="2" cellpadding="2" width="100%" class="small">
							<tr class="tc0">
								<td>&nbsp;</td>
<?
			foreach ($question->hyoutou as $hno => $hyoutou) {
?>
								<td><?=mb_convert_kana($hno, 'N')?>．<?=htmlspecialchars($hyoutou)?></td>
<?
			}
?>
							</tr>
<?
			foreach ($question->hyousoku as $hno => $hyousoku) {
?>
							<tr class="tc<?=$hno % 2 * 2?>">
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
			</table>
		</td>
		<!-- テーブル大枠 -->
	</tr>
<?
		}
	}
}
?>
</table>

<br>
<div align="center">
	<input type="submit" value="ＷＥＢプレビュー" onclick="document.form1.next_action.value='preview'">
	<input type="submit" value="　登録　" onclick="document.form1.next_action.value='update'">
	<input type="submit" value="リセット" onclick="document.form1.next_action.value='reset'">
	<input type="button" value="　戻る　" onclick="history.back()">
	<input type="hidden" name="next_action">
</div>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
