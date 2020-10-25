<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:Ｍｙアンケート詳細表示表示処理
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/my_enquete.php");
include("$inc/my_search.php");
include("$inc/mye_temp.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

function decode_question_type($code) {
	switch ($code) {
	case 1:
		return '単一選択[SA]';
	case 2:
		return '複数選択[MA]';
	case 3:
		return '自由回答[FA]';
	case 4:
		return 'マトリクス[SA]';
	case 5:
		return 'マトリクス[MA]';
	}
}

$myenq = new my_enquete_class;
$myenq->read_db($staff_id, $my_enq_no);
$enquete = &$myenq->enquete;
$search = &$myenq->search;

// 説明文が設定されていない場合はテンプレートを表示
$description = ($enquete->description != '') ? $enquete->description : get_enq_description($myenq);

// メールタイトルが設定されていない場合はアンケートタイトルを表示
$mail_title = ($myenq->mail_title != '') ? $myenq->mail_title : $enquete->title;

// メール分が設定されていない場合はテンプレートを表示
if ($myenq->mail_contents == '')
	get_enq_body($myenq, $mail_header, $mail_contents, $mail_footer);
else {
	$mail_header = $myenq->mail_header;
	$mail_contents = $myenq->mail_contents;
	$mail_footer = $myenq->mail_footer;
}

set_global('staff', 'スタッフ管理｜Ｍｙアンケート管理', 'Ｍｙアンケート表示', BACK_CLOSE);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■概要設定内容</td>
	</tr>
	<tr>
		<td class="m1" width="20%">アンケート種類</td>
		<td class="n1" width="80%"><?=decode_enq_type($enquete->enq_type)?></td>
	</tr>
	<tr>
		<td class="m1">開始日時</td>
		<td class="n1"><?=$enquete->start_date_y?>年<?=$enquete->start_date_m?>月<?=$enquete->start_date_d?>日　<?=$enquete->start_date_h?>時</td>
	</tr>
	<tr>
		<td class="m1">終了日時</td>
		<td class="n1"><?=$enquete->end_date_y?>年<?=$enquete->end_date_m?>月<?=$enquete->end_date_d?>日　<?=$enquete->end_date_h?>時</td>
	</tr>
	<tr>
		<td class="m1">ポイント数</td>
		<td class="n1"><?=$enquete->point?>ポイント</td>
	</tr>
	<tr>
		<td class="m1">最大発信数</td>
		<td class="n1"><?=$myenq->send_num?>人</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■アンケート内容</td>
	</tr>
	<tr>
		<td class="m1">アンケートタイトル</td>
		<td class="n1"><?=htmlspecialchars($enquete->title)?></td>
	</tr>
	<tr>
		<td class="m1">説明文</td>
		<td class="n1"><span class="small"><?=nl2br(htmlspecialchars($description))?></span></td>
	</tr>
<?
if (is_array($enquete->question)) {
	foreach ($enquete->question as $qno => $question) {
		if ($question->question_type == 1 || $question->question_type == 2) {
?>
				<tr>
					<td class="m1" rowspan="3">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
					<td class="n1"><?=nl2br(htmlspecialchars($question->question_text))?></td>
				</tr>
				<tr>
					<td class="n1"><?=$question->question_type == 1 ? '単一選択(SA)' : '複数選択(MA)'?></td>
				</tr>
				<tr>
					<td class="n1">
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
					<td class="m1" width="20%" rowspan="2">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
					<td class="n1"><?=nl2br(htmlspecialchars($question->question_text))?></td>
				</tr>
				<tr>
					<td class="n1">自由回答(FA)</td>
				</tr>
<?
		} elseif ($question->question_type == 4 || $question->question_type == 5) {
?>
				<tr>
					<td class="m1" width="20%" rowspan="3">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
					<td class="n1"><?=nl2br(htmlspecialchars($question->question_text))?></td>
				</tr>
				<tr>
					<td class="n1"><?=$question->question_type == 4 ? 'マトリクス(SA)' : 'マトリクス(MA)'?></td>
				</tr>
				<tr>
					<td class="n1">
						<table class="small" border="0" cellspacing="2" cellpadding="2" width="100%">
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
	<tr>
		<td class="m0" colspan=2>■条件設定内容</td>
	</tr>
	<tr>
		<td class="m1">性別</td>
		<td class="n1"><?=decode_sex($search->sex, '<br>')?></td>
	</tr>
	<tr>
		<td class="m1">年代</td>
		<td class="n1"><? disp_age2($search->age_cd, $search->age_from, $search->age_to) ?></td>
	</tr>
	<tr>
		<td class="m1">未既婚</td>
		<td class="n1"><?=decode_mikikon($search->mikikon, '<br>')?></td>
	</tr>
	<tr>
		<td class="m1">居住地域</td>
		<td class="n1"><? disp_area($search->jitaku_area) ?></td>
	</tr>
	<tr>
		<td class="m1">職業</td>
		<td class="n1"><? disp_shokugyou($search->shokugyou) ?></td>
	</tr>
	<tr>
		<td class="m1">チェーン</td>
		<td class="n1"><? disp_chain($search->chain) ?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■メール内容</td>
	</tr>
	<tr>
		<td class="m1">メールタイトル</td>
		<td class="n1"><?=htmlspecialchars($mail_title)?></td>
	</tr>
	<tr>
		<td class="m1">ヘッダ</td>
		<td class="n1"><span class="small"><?=nl2br(htmlspecialchars($mail_header))?></span></td>
	</tr>
	<tr>
		<td class="m1">本文</td>
		<td class="n1"><span class="small"><?=nl2br(htmlspecialchars($mail_contents))?></span></td>
	</tr>
	<tr>
		<td class="m1">フッタ</td>
		<td class="n1"><span class="small"><?=nl2br(htmlspecialchars($mail_footer))?></span></td>
	</tr>
</table>
<br>
<input type="button" value="　閉じる　" onclick="window.close()">
</div>

<? page_footer() ?>
</body>
</html>
