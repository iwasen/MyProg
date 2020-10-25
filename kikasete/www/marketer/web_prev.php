<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:ＰｒｏアンケートWebプレビュー
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
include("$inc/mye_temp.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('proenquete', 'Ｐｒｏアンケート管理', 'Ｗｅｂプレビュー', BACK_TOP);

$pro_enq = new pro_enquete_class;
$pro_enq->read_db($marketer_id, $pro_enq_no);
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
<?
if (!isset($main) && $pro_enq->finding_flag == DBTRUE) {
	disp_preview($pro_enq, 0, 'ファインディングアンケート', $pro_enq->enquete->get_finding_point());
	disp_preview($pro_enq, 1, '本アンケート', $pro_enq->enquete2->get_enquete_point());
//////jeon_start seq=99//////////////////////
//ファインディングアンケートだけ　プレビュー画面場合
}else if($pro_enq->finding_flag == DBTRUE){
	disp_preview($pro_enq, $main, '', $pro_enq->enquete->get_finding_point());
//////jeon_end seq=99////////////////////////
} else
	disp_preview($pro_enq, $main, '', $pro_enq->enquete->get_enquete_point());

function disp_preview(&$pro_enq, $main, $enquete_kind, $point) {
	if ($main){
		$enquete = &$pro_enq->enquete2;
		//jeon_start seq=81
		$enquete2 = &$pro_enq->enquete;
		//jeon_end seq=81
	}else
		$enquete = &$pro_enq->enquete;

	if ($enquete->description == '')
		$description = get_enq_description($pro_enq);
	else
		$description = $enquete->description;

	$description = str_replace('%POINT%', $point, $description);
?>
<table width="470" border="0" cellpadding="2" cellspacing="0" bgcolor="#c8c8c8">
	<tr>
		<td><font size="2"><table border="0" cellpadding="5" cellspacing="0" width="100%">
<?
	if ($enquete_kind != '') {
?>
				<tr>
					<td bgcolor="#ccffff" colspan="2" align="center"><b><?=htmlspecialchars($enquete_kind)?></b><br>
						</td>
				</tr>
<?
	}
?>
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
				if (isset($enquete->branch_cond[$i])) {
?>
<!--jeon_start seq=81-->
										<tr><td colspan=2 bgcolor="#ffff99" align="center"><font size="2">分岐あり（改ページ）<span style="color:#ff0000;">＜<?=htmlspecialchars($enquete->branch_cond_text($i, 'を選択した方のみ',$enquete2, false, false))?>＞</span></font></td></tr>
<!--jeon_end seq=81-->
<?
				} elseif ($question->page_break == DBTRUE) {
?>
										<tr><td colspan=2 bgcolor="#ffcc99" align="center"><font size="2">改ページ</font></td></tr>
<?
				}
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
						foreach ($question->sel_text as $sno => $sel_text) {
							if ($sel_text != '') {
								echo $br;
								$br = '<br>';
?>
						<nobr><input type="radio" name="answer<?=$i?>" value=""> <?=htmlspecialchars($sel_text)?></nobr>
<?
								if ($question->fa_sno == $sno) {
?>
						<input type="text" name="free_answer<?=$i?>" size=25>
<?
								}
							}
						}
					}
					break;
				case 2:		// 複数選択
					if (is_array($question->sel_text)) {
						$br = '';
						foreach ($question->sel_text as $sno => $sel_text) {
							if ($sel_text != '') {
								echo $br;
								$br = '<br>';
?>
						<nobr><input type="checkbox" name="answer<?=$i?>" value=""> <?=htmlspecialchars($sel_text)?></nobr>
<?
								if ($question->fa_sno == $sno) {
?>
						<input type="text" name="free_answer<?=$i?>" size=25>
<?
								}
							}
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
<?
}
?>
<input type="button" value="　戻る　" onClick="history.back()">
</div>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
