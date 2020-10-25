<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｍｙアンケート表示処理
'******************************************************/

$top = '../..';
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
include("$inc/ma_profile.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// 地域名
function decode_area3($code) {
	if ($code != '') {
		$sql = "SELECT ar3_area3_name FROM m_area3 WHERE ar3_area3_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

$myenq = new my_enquete_class;
$myenq->read_db($marketer_id, $my_enq_no);
$enquete = &$myenq->enquete;
$search = &$myenq->search;
$cell = &$myenq->cell;

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

// 申請中を事務局チェック中に変更
if ($check == '1' && $enquete->status == 2) {
	$sql = "UPDATE t_enquete SET en_status=3 WHERE en_enquete_id=$myenq->enquete_id";
	db_exec($sql);
}

set_global('myenquete', 'Ｍｙアンケート管理', 'Ｍｙアンケート表示', BACK_TOP);
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
	return confirm("このＭｙアンケートを承認します。よろしいですか？");
}
<?
// kim_start seq no 49
//回答（QRのみ、カンマ区きり）case1利用してcsv.phpで移動する
//回答（QRのみ,0,1形式）case2利用してcsv２.phpで移動する。
//設問　case3:利用してcsv3.phpで移動する
//回答（QRのみ、カンマ区きり）case4:利用してcsv4.phpで移動する。
//回答（QRのみ,0,1形式）case5:利用してcsv５.phpで移動する。
//回答（DRのみ、カンマ区きり）case6:利用してcsv6.phpで移動する。
//回答（DRのみ、0,1形式）case7:利用してcsv7.phpで移動する。
?>
	function func_csv(){
		f=document.form1;
		switch(f.ss.value){
			case "1":location.href ="csvt.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>&mode=1";
			break;
			case "2":location.href ="csvt.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>&mode=2";
			break;
			case "3":location.href ="csvt.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>&mode=3";
			break;
			default://redirect("show.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>");
		}
	}
<?// kim_end49 ?>
<?
//seq no=50 kim_start 関数　追加
?>
function onclick_dr_replyview(){
	<!--window.open('pe_recv_count_detail.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>','_new','menubar=no toolbar=no location=no scrollbars=no status=no resizable=no width=900 height=400 left=200  top=300');//-->
	location.href ="pe_recv_count_detail.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>&age_cd=<?=$search->age_cd?>";
}
<?
//kim_end50
?>
<?//kim_start seq no=49 不必要な関数 ?>
function onclick_csv(type) {
	location.href = "csv.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>";
}
function onclick_csv2(type) {
	location.href = "csv2.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>";
}
function onclick_csv3(type) {
	location.href = "csv3.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>";
}
<?//kim_end seq no=49 ?>
function onclick_mail_prev() {
	location.href = "mail_prev.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>";
}
function onclick_web_prev() {
	location.href = "web_prev.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>";
}
function onclick_test_mail() {
	location.href = "test_mail.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>";
}
function onclick_search() {
	window.open("search.php?search_id=<?=$myenq->search_id?>", "search", "width=300,height=130");
}
function onclick_edit() {
	location.href = "edit.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>";
}
function onclick_mailTemplate() {
	location.href = "case.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>";
}
function onclick_shounin() {
	var f = document.form1;
	if (showModalDialog("shounin.php",f,"status:off;help:off;dialogWidth:350px;dialogHeight:150px"))
		f.submit();
}

//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%" frame="box">
	<tr>
		<td class="m0" colspan=3>■概要設定内容</td>
	</tr>
	<tr>
		<td class="m1" colspan="2" width="20%">アンケート種類</td>
		<td class="n1" width="80%"><?=decode_enq_type($enquete->enq_type)?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2">開始日時</td>
		<td class="n1"><?=$enquete->start_date_y?>年<?=$enquete->start_date_m?>月<?=$enquete->start_date_d?>日　<?=$enquete->start_date_h?>時</td>
	</tr>
	<tr>
		<td class="m1" colspan="2">終了日時</td>
		<td class="n1"><?=$enquete->end_date_y?>年<?=$enquete->end_date_m?>月<?=$enquete->end_date_d?>日　<?=$enquete->end_date_h?>時</td>
	</tr>
	<tr>
		<td class="m1" colspan="2">ポイント数</td>
		<td class="n1"><?=$enquete->point?>ポイント</td>
	</tr>
	<tr>
		<td class="m1" colspan="2">最大発信数</td>
		<td class="n1"><?=$myenq->send_num?>人</td>
	</tr>
<?
if ($cell->cell_option == 2) {
	$send_cell = $cell->get_send_cell();
	$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e);
?>
	<tr>
		<td class="m1" colspan="2">セル別発信数</td>
		<td class="n1">
			<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box" class="small">
<?
	if ($cell->age_option == 2) {
?>
				<tr align="center">
					<td nowrap width="5%" rowspan=2>&nbsp;</td>
					<td nowrap width="5%" rowspan=2>10代<br>未満</td>
					<td nowrap width="10%" colspan=2>10代</td>
					<td nowrap width="10%" colspan=2>20代</td>
					<td nowrap width="10%" colspan=2>30代</td>
					<td nowrap width="10%" colspan=2>40代</td>
					<td nowrap width="10%" colspan=2>50代</td>
					<td nowrap width="10%" colspan=2>60代</td>
					<td nowrap width="5%" rowspan=2>70代<br>以上</td>
					<td nowrap width="5%" rowspan=2>合計</td>
				</tr>
				<tr align="center">
					<td>前</td>
					<td>後</td>
					<td>前</td>
					<td>後</td>
					<td>前</td>
					<td>後</td>
					<td>前</td>
					<td>後</td>
					<td>前</td>
					<td>後</td>
					<td>前</td>
					<td>後</td>
				</tr>
<?
	} elseif ($cell->age_option == 3) {
?>
				<tr align="center">
					<td nowrap width="10%">&nbsp;</td>
					<td nowrap width="10%">10代未満</td>
					<td nowrap width="10%">10代</td>
					<td nowrap width="10%">20代</td>
					<td nowrap width="10%">30代</td>
					<td nowrap width="10%">40代</td>
					<td nowrap width="10%">50代</td>
					<td nowrap width="10%">60代</td>
					<td nowrap width="10%">70代以上</td>
					<td nowrap width="10%">合計</td>
				</tr>
<?
	} else {
?>
				<tr align="center">
					<td nowrap width="10%">&nbsp;</td>
					<td nowrap width="10%">年代指定なし</td>
					<td nowrap width="10%">合計</td>
				</tr>
<?
	}

	$sum_row = array();
	for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
?>
				<tr>
					<td nowrap align="center"><?=decode_sex($sex, '性別指定なし')?></td>
<?
		$sum_col = 0;
		for ($age = $age_s; $age <= $age_e; $age++) {
			$num = $send_cell[$sex][$age][0];
			$sum_col += $num;
			$sum_row[$age] += $num;
?>
					<td nowrap align="right"><?=number_format($num)?></td>
<?
		}
?>
					<td nowrap align="right"><?=number_format($sum_col)?></td>
				</tr>
<?
	}
?>
				<tr>
					<td nowrap align="center">合計</td>
<?
	$sum_col = 0;
	for ($age = $age_s; $age <= $age_e; $age++) {
		$num = $sum_row[$age];
		$sum_col += $num;
?>
					<td nowrap align="right"><?=number_format($num)?></td>
<?
	}
?>
					<td nowrap align="right"><?=number_format($sum_col)?></td>
				</tr>
			</table>
		</td>
	</tr>
<?
}
?>
	<tr>
		<td class="m0" colspan=3>■アンケート内容</td>
	</tr>
	<tr>
		<td class="m1" colspan="2">アンケートタイトル</td>
		<td class="n1"><?=htmlspecialchars($enquete->title)?>　についてのアンケート</td>
	</tr>
	<tr>
		<td class="m1" colspan="2">説明文</td>
		<td class="n1"><span class="small"><?=nl2br(htmlspecialchars($description))?></span></td>
	</tr>
<?
if (is_array($enquete->question)) {
	foreach ($enquete->question as $qno => $question) {
		// 画像の指定
		$image_msg = '画像の指定：';
		if ($question->url)
			$image_msg .= "URLを指定する [$question->url]";
		elseif ($question->image_id) {
			$sql = "SELECT im_name FROM t_image WHERE im_image_id=$question->image_id";
			$image_msg .= '画像を埋め込む [' . htmlspecialchars(db_fetch1($sql)) . ']';
		} else
			$image_msg .= 'なし';

		if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7) {
?>
	<tr>
		<td class="m1" colspan="2" rowspan="4">Ｑ<?=mb_convert_kana($qno, 'N')?>
			<div class="small"><?=$question->must_flag == 't' ? '（必須回答）' : '（任意回答）'?></div></td>
		<td class="n1"><?=nl2br(htmlspecialchars($question->question_text))?></td>
	</tr>
	<tr>
		<td class="n1"><?=$image_msg?></td>
	</tr>
	<tr>
		<td class="n1"><?=decode_question_type($question->question_type)?></td>
	</tr>
	<tr>
		<td class="n1">
<?
			foreach ($question->sel_text as $sno => $sel_text) {
?>
			<?=$sno?>　<?=htmlspecialchars($sel_text)?><?=$question->fa_sno == $sno ? ' （フリー回答）' : ''?><?=$question->ex_sno == $sno ? ' （排他）' : ''?><br>
<?
			}
?>
		</td>
	</tr>
<?
		} elseif ($question->question_type == 3) {
?>
	<tr>
		<td class="m1" colspan="2" width="20%" rowspan="3">Ｑ<?=mb_convert_kana($qno, 'N')?>
			<div class="small"><?=$question->must_flag == 't' ? '（必須回答）' : '（任意回答）'?></div></td>
		<td class="n1"><?=nl2br(htmlspecialchars($question->question_text))?></td>
	</tr>
	<tr>
		<td class="n1"><?=$image_msg?></td>
	</tr>
	<tr>
		<td class="n1"><?=decode_question_type($question->question_type)?></td>
	</tr>
<?
		} elseif ($question->question_type == 4 || $question->question_type == 5) {
?>
	<tr>
		<td class="m1" colspan="2" width="20%" rowspan="4">Ｑ<?=mb_convert_kana($qno, 'N')?>
			<div class="small"><?=$question->must_flag == 't' ? '（必須回答）' : '（任意回答）'?></div></td>
		<td class="n1"><?=nl2br(htmlspecialchars($question->question_text))?></td>
	</tr>
	<tr>
		<td class="n1"><?=$image_msg?></td>
	</tr>
	<tr>
		<td class="n1"><?=decode_question_type($question->question_type)?></td>
	</tr>
	<tr>
		<td class="n1">
			<table class="small" border="0" cellspacing="2" cellpadding="2" width="100%">
				<tr>
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
		} elseif ($question->question_type == 6 || $question->question_type == 8) {
?>
	<tr>
		<td class="m1" colspan="2" width="20%" rowspan="4">Ｑ<?=mb_convert_kana($qno, 'N')?>
			<div class="small"><?=$question->must_flag == 't' ? '（必須回答）' : '（任意回答）'?></div></td>
		<td class="n1"><?=nl2br(htmlspecialchars($question->question_text))?></td>
	</tr>
	<tr>
		<td class="n1"><?=$image_msg?></td>
	</tr>
	<tr>
		<td class="n1"><?=decode_question_type($question->question_type)?></td>
	</tr>
	<tr>
		<td class="n1">
			<div>回答欄の前に表示する文字列：<?=htmlspecialchars($question->pre_text)?></div>
			<div>回答欄の後に表示する文字列：<?=htmlspecialchars($question->post_text)?></div>
		</td>
	</tr>
<?
		}
	}
}
?>
	<tr>
		<td class="m0" colspan=3>■条件設定内容</td>
	</tr>
	<tr>
		<td class="m1" colspan="2">対象モニター</td>
		<td class="n1"><?=$search->decode_monitor_type($search->type, '')?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2">性別</td>
		<td class="n1"><?=decode_sex($search->sex, '')?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2">年代</td>
		<td class="n1"><? disp_age2($search->age_cd, $search->age_from, $search->age_to) ?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2">未既婚</td>
		<td class="n1"><?=decode_mikikon($search->mikikon, '')?></td>
	</tr>
<?
if ($cell->rate_option == 2) {
?>
	<tr>
		<td class="m1" colspan="2">発信割合設定</td>
		<td class="n1">未婚<?=number_format($cell->send_rate[1])?>% ／ 既婚<?=number_format($cell->send_rate[2])?>% ／ その他<?=number_format($cell->send_rate[3])?>%</td>
	</tr>
<?
}
?>
	<tr>
		<td class="m1" colspan="2">居住地域</td>
		<td class="n1"><? disp_area($search->jitaku_area) ?></td>
	</tr>
<?
if ($cell->rate_option == 3) {
?>
	<tr>
		<td class="m1" colspan="2">発信割合設定</td>
		<td class="n1">
<?
	if (is_array($cell->send_rate)) {
		foreach ($cell->send_rate as $area_cd => $rate)
			$rate_area_ary[] = decode_area3($area_cd) . $rate . '%';

		echo join(' ／ ', $rate_area_ary);
	}
?>
		</td>
	</tr>
<?
}
?>
<!-- jeon_start seq=mp3-->
<!--郵便番号画面表示追加-->
	<tr>
		<td class="m1" rowspan='3' <?if($search->zip=='' && $search->zip_kinmuchi=='')echo "colspan='2'"?>>郵便番号</td>
		<?if($search->zip!='' && $search->zip_kinmuchi!=''){?>
		<td class="m1" width='15%'>条件</td>
		<td class="n1"><? if($search->zip_kinmuchi_andor==1)echo "居住地域 AND 勤務地";
							else echo"居住地域  OR 勤務地";?></td>
		<?}else if($search->zip=='' && $search->zip_kinmuchi==''){?><td rowspan='3' class="n1">&nbsp;</td><?}?>
	</tr>
	<tr>
		<?if($search->zip!=''){?>
		<td class="m1" width='15%'>居住地域</td>
		<td class="n1"><?=$search->zip;?></td>
		<?}?>
	</tr>
	<tr>
		<?if($search->zip_kinmuchi!=''){?>
		<td class="m1" width='15%'>勤務地</td>
		<td class="n1"><?=$search->zip_kinmuchi;?></td>
		<?}?>
	</tr>
<!-- jeon_end seq=mp3-->
	<tr>
		<td class="m1" colspan="2" >職業</td>
		<td class="n1"><? disp_shokugyou($search->shokugyou) ?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >業種</td>
		<td class="n1"><? disp_gyoushu($search->gyoushu) ?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >職種</td>
		<td class="n1"><? disp_shokushu($search->shokushu) ?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >子供の有無</td>
		<td class="n1"><?=multi_decode(decode_have_child, $search->have_child)?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >住居形態</td>
		<td class="n1"><?=multi_decode(decode_housing_form, $search->housing_form)?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >車の所有</td>
		<td class="n1"><?=multi_decode(decode_have_car, $search->have_car)?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >コンビニ</td>
		<td class="n1"><? disp_conveni($search->conveni) ?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >スーパー</td>
		<td class="n1"><? disp_super($search->super) ?></td>
	</tr>
<?
// 追加プロファイル
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
	<tr>
		<td class="m1" colspan="2" ><?=htmlspecialchars($profile_name)?></td>
		<td class="n1"><? disp_ma_profile($profile_id, $search->ma_profile) ?></td>
	</tr>
<?
}
?>
	<tr>
		<td class="m1" colspan="2" >興味ジャンル</td>
		<td class="n1"><? disp_genre($search->genre) ?></td>
	</tr>
	<tr>
		<td class="m0" colspan=3>■メール内容</td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >メールタイトル</td>
		<td class="n1"><?=htmlspecialchars($mail_title)?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >ヘッダ</td>
		<td class="n1"><span class="small"><?=nl2br(htmlspecialchars($mail_header))?></span></td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >本文</td>
		<td class="n1"><span class="small"><?=nl2br(htmlspecialchars($mail_contents))?></span></td>
	</tr>
	<tr>
		<td class="m1" colspan="2" >フッタ</td>
		<td class="n1"><span class="small"><?=nl2br(htmlspecialchars($mail_footer))?></span></td>
	</tr>
</table>
<br>
<!--kim_start seq no=50 技能中止 及び button追加 <input type="button" value="設問CSV取得" onclick="onclick_csv3()">
<?// if ($enquete->status >= 5) { ?>
<input type="button" value="CSV取得" onclick="onclick_csv()">
<input type="button" value="CSV取得(0,1)" onclick="onclick_csv2()"> kim_end seq no=50//-->
<? //} ?>
<?
 /**	 kim_start seq no=49
	設問CSV取得、回答QR,DRCSV取得カンマ、(0,1)形式select文で修正および技能追加
*/
?>
<select name="ss">
	<option unselected>CSVファイル取得</option>
	<option value="1">設問</option>

	<?if($enquete->status >= 5){?>
	<option>----------------------</option>
	<option value="2">回答（DRのみ、カンマ区きり）</option>
	<option value="3">回答（DRのみ、0,1形式）</option>
	<?}?>
</select>
	<input type="button" value="CSV取得" onclick="func_csv()">
<br>
<?
//kim_end seq no=49
?>
<? if ($enquete->enq_type == ENQ_TYPE_MAIL) { ?>
<input type="button" value="メールプレビュー" onclick="onclick_mail_prev()">
<? } elseif ($enquete->enq_type == ENQ_TYPE_WEB) { ?>
<input type="button" value="WEBプレビュー" onclick="onclick_web_prev()">
<? } ?>
<input type="button" value="メールテスト送信" onclick="onclick_test_mail()">
<input type="button" value="対象人数検索" onclick="onclick_search()">
<input type="button" value="　変更　" onclick="onclick_edit()">
<? if ($enquete->status == 2 || $enquete->status == 3) { ?>
<input type="button" value="　承認　" onclick="onclick_shounin()">
<? } ?>
<input type="button" value="事例メールテンプレート" onclick="onclick_mailTemplate()">
<?
// kim_start seq no=50 回答数表示Button追加
?>
<input type="button" value="回答数表示" onclick="onclick_dr_replyview();">
<?
//kim_end seq no=50
?>
<input type="button" value="　戻る　" onclick="location.href='list.php'">
<input type="hidden" name="next_action" value="app">
<input type="hidden" name="marketer_id" value="<?=$marketer_id?>">
<input type="hidden" name="my_enq_no" value="<?=$my_enq_no?>">
<input type="hidden" name="enquete_id" value="<?=$myenq->enquete_id?>">
<input type="hidden" name="start_date_y" <?=value($enquete->start_date_y)?>>
<input type="hidden" name="start_date_m" <?=value($enquete->start_date_m)?>>
<input type="hidden" name="start_date_d" <?=value($enquete->start_date_d)?>>
<input type="hidden" name="start_date_h" <?=value($enquete->start_date_h)?>>
</form>
</div>

<? page_footer() ?>
</body>
</html>
