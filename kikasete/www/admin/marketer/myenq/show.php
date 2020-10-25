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

// 日付表示（年月日）
function disp_date1($year, $month, $day) {
	if ($year)
		echo sprintf('%d年%d月%d日', $year, $month, $day);
}

// 日付表示（年月日時）
function disp_date2($year, $month, $day, $hour) {
	if ($year)
		echo sprintf('%d年%d月%d日 %d時', $year, $month, $day, $hour);
}

// 地域名
function decode_area3($code) {
	if ($code != '') {
		$sql = "SELECT ar3_area3_name FROM m_area3 WHERE ar3_area3_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

set_global('myenquete', 'Ｍｙアンケート管理', 'Ｍｙアンケート表示', BACK_TOP);

$myenq  = new my_enquete_class;
$myenq->read_db($marketer_id, $my_enq_no);

$enquete = &$myenq->enquete;
$search = &$myenq->search;
$cell = &$myenq->cell;

// メールタイトルが設定されていない場合はアンケートタイトルを表示
$mail_title = ($myenq->mail_title != '') ? $myenq->mail_title : $enquete->title;

// メール文が設定されていない場合はテンプレートを表示
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

$enquete->status = 3;
}

// マーケターページのURL取得
$fetch = get_system_info('sy_url_marketer');
$url_marketer = $fetch->sy_url_marketer;

// 編集可能フラグ取得
//$edit_flag = ($enquete->status == 0 || $enquete->status == 8) && !$show;
$edit_flag = true;

// 作成完了フラグ取得
if ($enquete->title != '')
	$make_flag |= 0x01;
if ($enquete->get_question_num() != 0)
	$make_flag |= 0x02;
if ($myenq->search_id)
	$make_flag |= 0x04;
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
	if (showModalDialog("shounin.php",f,"status:off;help:off;dialogWidth:350px;dialogHeight:150px")) {
		f.next_action.value = "app";
		f.submit();
	}
}
function onclick_web_test() {
    window.open('<?=$url_marketer?>mkk_myenq_remlenq3a.php?enquete_id=<?=$myenq->enquete_id?>&my_enq_no=<?=$my_enq_no?>', 'webtest');
}
function add_send() {
	var f = document.form1;
	if (showModalDialog("add_send.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>",f,"status:off;help:off;dialogWidth:400px;dialogHeight:150px")) {
		f.next_action.value = "add_send";
		f.submit();
	}
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
		<td colspan="2">
			<table border=0 cellspacing=0 cellpadding=0 width=100%>
				<tr class="m0">
					<td>■アンケート概要</b></td>
					<td align="right">
<?
if ($edit_flag) {
?>
						<input type="button" name="" value="<?=$make_flag & 0x01 ? '　修正　' : '　設定　'?>" onclick="location.href='edit1.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>'">
<?
}
?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">アンケートタイトル</td>
		<td class="n1" width="80%"><?=htmlspecialchars($enquete->title)?>　についてのアンケート</td>
	</tr>
	<tr>
		<td class="m1">開始日時</td>
		<td class="n1"><? disp_date2($enquete->start_date_y, $enquete->start_date_m, $enquete->start_date_d, $enquete->start_date_h)?></td>
	</tr>
	<tr>
		<td class="m1">終了日時</td>
		<td class="n1"><? disp_date2($enquete->end_date_y, $enquete->end_date_m, $enquete->end_date_d, $enquete->end_date_h)?></td>
	</tr>
	<tr>
		<td class="m1">回答条件</td>
		<td class="n1">
<?
$ans_cond_ary = explode("\n", $myenq->ans_cond);
foreach ($ans_cond_ary as $ans_cond) {
	if ($ans_cond != '') {
?>
								・<?=htmlspecialchars($ans_cond)?><br>
<?
	}
}
?>
		</td>
	</tr>
	<tr>
		<td class="m1">ポイント数</td>
		<td class="n1"><?=$enquete->point?>ポイント</td>
	</tr>
    <tr>
        <td class="m1">実施状態</td>
        <td class="n1"><?=decode_enq_status($enquete->status)?></td>
    </tr>
	<tr>
		<td colspan="2">
			<table border=0 cellspacing=0 cellpadding=0 width="100%">
				<tr class="m0">
					<td>■アンケート内容</td>
					<td align="right">
<?
if ($edit_flag) {
	if ($make_flag & 0x02) {
?>
						<input type="button" value="分岐・改ページの設定" onclick="location.href='edit_branch.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>'">
<?
	}
?>
						<input type="button" value="<?=$make_flag & 0x02 ? '　修正　' : '　設定　'?>" onclick="location.href='edit2.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>'" >
<?
}
?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
<?
if (is_array($enquete->question)) {
	foreach ($enquete->question as $qno => $question) {
		// 画像・リンクの指定
		$image_msg = '画像・リンクの指定：';
		if ($question->url)
			$image_msg .= "URLを指定する [$question->url]";
		elseif ($question->image_id) {
			$sql = "SELECT im_name FROM t_image WHERE im_image_id=$question->image_id";
			$image_msg .= '画像を埋め込む [' . htmlspecialchars(db_fetch1($sql)) . ']';
		} else
			$image_msg .= 'なし';

			if (isset($enquete->branch_cond[$qno])) {
?>
	<tr>
		<td class="m1" rowspan="2">分岐条件</td>
		<td class="n1">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
	</tr>
	<tr>
		<td class="n1"><?=htmlspecialchars($enquete->branch_cond_text($qno, 'を選択した方のみ', true))?></td>
	</tr>
<?
			} elseif ($question->page_break == DBTRUE) {
?>
	<tr>
		<td class="m1" colspan="2" align="center">改ページ</td>
	</tr>
<?
			}

		if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7) {
?>
	<tr>
		<td class="m1" rowspan="4">Ｑ<?=mb_convert_kana($qno, 'N')?>
			<div><?=$question->must_flag == 't' ? '（必須回答）' : '（任意回答）'?></div></td>
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
		<td class="m1" rowspan="3">Ｑ<?=mb_convert_kana($qno, 'N')?>
			<div><?=$question->must_flag == 't' ? '（必須回答）' : '（任意回答）'?></div></td>
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
		<td class="m1" rowspan="4">Ｑ<?=mb_convert_kana($qno, 'N')?>
			<div><?=$question->must_flag == 't' ? '（必須回答）' : '（任意回答）'?></div></td>
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
			<table border="0" cellspacing="2" cellpadding="2" width="100%">
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
		} elseif ($question->question_type == 6 || $question->question_type == 8) {
?>
	<tr>
		<td class="m1" rowspan="4">Ｑ<?=mb_convert_kana($qno, 'N')?>
			<div><?=$question->must_flag == 't' ? '（必須回答）' : '（任意回答）'?></div></td>
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
		<td colspan="2">
			<table border=0 cellspacing=0 cellpadding=0 width="100%">
				<tr class="m0">
					<td>■対象条件</td>
					<td align="right">
<?
if ($edit_flag) {
?>
						<input type="button" value="<?=$make_flag & 0x04 ? '　修正　' : '　設定　'?>" onclick="location.href='edit3.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>'">
<?
}
?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m1">最大発信数</td>
		<td class="n1"><?=$myenq->send_num?>
<?
if ($enquete->status == 5) {
?>
			<input type="button" value="追加配信" onclick="add_send()">
<?
}
?>
		</td>
	</tr>
	<tr>
		<td class="m1">対象モニター</td>
		<td class="n1"><?=$search->decode_monitor_type($search->type, '')?></td>
	</tr>
	<tr>
		<td class="m1">発信数割付(セル指定)</td>
		<td class="n1">
			<? if ($make_flag & 0x04){
					if ($cell->cell_option == 1)
						echo('しない');
					else
						 echo('する');
				}
			?>
		</td>
	</tr>
<?
if ($cell->cell_option == 1) {
?>
	<tr>
		<td class="m1">性別</td>
		<td class="n1">
			<?
				if ($make_flag & 0x04)
					echo(decode_sex($search->sex, '指定なし'))
			?>
		</td>
	</tr>
	<tr>
		<td class="m1">年代</td>
		<td class="n1"><? disp_age2($search->age_cd, $search->age_from, $search->age_to) ?></td>
	</tr>
<?
} elseif ($cell->cell_option == 2) {
	$send_cell = $cell->get_send_cell();
	$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e, 2);
?>
	<tr>
		<td class="m1">性別／年代</td>
		<td class="n1">
		<?=($search->age_from || $search->age_to) ? "任意の年齢を指定する　{$search->age_from}歳 〜 {$search->age_to}歳" : "任意の年齢を指定しない"?>
			<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
<?
	if ($cell->age_option == 2) {
?>
				<tr class="n1" align="center">
					<td nowrap width="5%" rowspan=2>&nbsp;</td>
					<td nowrap width="5%">10代</td>
					<td nowrap width="10%" colspan=2>20代</td>
					<td nowrap width="10%" colspan=2>30代</td>
					<td nowrap width="10%" colspan=2>40代</td>
					<td nowrap width="10%" colspan=2>50代</td>
					<td nowrap width="10%" colspan=2>60代</td>
					<td nowrap width="5%" rowspan=2>70代<br>以上</td>
					<td nowrap width="5%" rowspan=2>合計</td>
				</tr>
				<tr class="n1" align="center">
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
				<tr class="n1" align="center">
					<td nowrap width="10%">&nbsp;</td>
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
				<tr class="n1" align="center">
					<td nowrap width="10%">&nbsp;</td>
					<td nowrap width="10%">年代指定なし</td>
					<td nowrap width="10%">合計</td>
				</tr>
<?
	}

	$sum_row = array();
	for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
?>
				<tr bgcolor="#ffffff">
					<td class="n1" nowrap align="center"><?=decode_sex($sex, '性別指定なし')?></td>
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
				<tr bgcolor="#ffffff">
					<td class="n1" nowrap align="center">合計</td>
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
		<td class="m1">未既婚</td>
		<td class="n1">
			<?
				if ($make_flag & 0x04)
					echo(decode_mikikon($search->mikikon, '指定しない'));
			?>
		</td>
	</tr>
<?
if ($cell->rate_option == 2) {
?>
	<tr>
		<td class="m1">発信割合設定</td>
		<td class="n1">未婚<?=$cell->send_rate[1]?>% ／ 既婚<?=$cell->send_rate[2]?>% ／ その他<?=$cell->send_rate[3]?>%</td>
	</tr>
<?
}
?>
	<tr>
		<td class="m1">居住地域</td>
		<td class="n1"><? disp_area($search->jitaku_area) ?></td>
	</tr>
	<!-- jeon_start seq=mp3-->
	<!-- commentout by namiki 20070807 <tr>
		<td rowspan='3' <?if($search->zip=='' && $search->zip_kinmuchi=='')echo "colspan='2'"?>>郵便番号</td>
		<?if($search->zip!='' && $search->zip_kinmuchi!=''){?>
		<td width='10%'>条件</td>
		<td><? if($search->zip_kinmuchi_andor==1)echo "居住地域 AND 勤務地";
							else echo"居住地域  OR 勤務地";?></td>
		<?}else if($search->zip=='' && $search->zip_kinmuchi==''){?><td rowspan='3'>&nbsp;</td><?}?>
	</tr> -->
		<?if($search->zip!=''){?>
	<tr>
		<td class="m1">居住地域</td>
		<td class="n1"><?=$search->zip;?></td>
	</tr>
		<?}?>
		<?if($search->zip_kinmuchi!=''){?>
	<tr>
		<td class="m1">勤務地</td>
		<td class="n1"><?=$search->zip_kinmuchi;?></td>
	</tr>
		<?}?>
	<!-- jeon_end seq=mp3-->
<?
if ($cell->rate_option == 3) {
?>
	<tr>
		<td class="m1">発信割合設定</td>
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
	<tr>
		<td class="m1">職業</td>
		<td class="n1"><? disp_shokugyou($search->shokugyou) ?></td>
	</tr>
	<tr>
		<td class="m1">業種</td>
		<td class="n1"><? disp_gyoushu($search->gyoushu) ?></td>
	</tr>
<? /*
	<tr>
		<td class="m1">職種</td>
		<td class="n1">disp_shokushu($search->shokushu) ?></td>
	</tr>
*/ ?>
<? /*
	<tr>
		<td class="m1">子供の有無</td>
		<td class="n1"><?=multi_decode(decode_have_child, $search->have_child)?></td>
	</tr>
	<tr>
		<td class="m1">住居形態</td>
		<td class="n1"><?=multi_decode(decode_housing_form, $search->housing_form)?></td>
	</tr>
	<tr>
		<td class="m1">車の所有</td>
		<td class="n1"><?=multi_decode(decode_have_car, $search->have_car)?></td>
	</tr>
<?
// 追加プロファイル
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
	<tr>
		<td class="m1"><?=htmlspecialchars($profile_name)?></td>
		<td class="n1"><? disp_ma_profile($profile_id, $search->ma_profile) ?></td>
	</tr>
<?
}
?>
	<tr>
		<td class="m1">よく行くコンビニ</td>
		<td class="n1"><? disp_conveni($search->conveni) ?></td>
	</tr>
	<tr>
		<td class="m1">よく行くスーパー</td>
		<td class="n1"><? disp_super($search->super) ?></td>
	</tr>
	<tr>
		<td class="m1">興味ジャンル</td>
		<td class="n1"><? disp_genre($search->genre) ?></td>
	</tr>
*/ ?>
<?
$sql = "SELECT pnc_category_id,pnc_category_name FROM t_panel_category WHERE pnc_display_order IS NOT NULL AND pnc_display_status=1 ORDER BY pnc_display_order";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$special_panel_name = array();
	if ($search->special_panel != '') {
		$sql = "SELECT pnl_panel_name FROM t_panel WHERE pnl_category_id={$fetch->pnc_category_id} AND pnl_display_order IS NOT NULL AND pnl_display_status=1 AND pnl_panel_id IN ($search->special_panel) ORDER BY pnl_display_order";
		$result2 = db_exec($sql);
		$nrow2 = pg_numrows($result2);
		for ($j = 0; $j < $nrow2; $j++) {
			$fetch2 = pg_fetch_object($result2, $j);
			$special_panel_name[] = $fetch2->pnl_panel_name;
		}
	}
?>
	<tr>
		<td class="m1"><?=htmlspecialchars($fetch->pnc_category_name)?></td>
		<td class="n1"><?=htmlspecialchars(join('　', $special_panel_name))?></td>
	</tr>
<?
}
?>
	<tr>
		<td class="m1">対象メンバーリスト</td>
		<td class="n1"><?=$search->taisho_myenq_list?></td>
	</tr>
	<tr>
		<td class="m1">除外メンバーリスト</td>
		<td class="n1"><?=$search->jogai_myenq_list?></td>
	</tr>
<?

if ($edit_flag) {
?>
	<tr>
		<td colspan="2">
			<table border=0 cellspacing=0 cellpadding=0 width="100%">
				<tr class="m0">
					<td>■アンケートＷＥＢ画面とメール文のカスタマイズ</td>
					<td align="right">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m1">アンケートＷＥＢ画面のカスタマイズ</td>
		<td class="n1">
<?
	if ($make_flag & 0x01) {
?>
			<input name="button" type="button" onClick="location.href='edit_web.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>'" value="ＷＥＢ画面の手動修正">
<?
	}
?>
		</td>
	</tr>
	<tr>
		<td class="m1">メール文のカスタマイズ</td>
		<td class="n1">
<?
	if ($make_flag & 0x01) {
?>
			<input name="button2" type="button" onClick="location.href='edit_mail.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>'" value="メール文面の手動修正">
<?
	}
?>
		</td>
	</tr>
<?
}
?>
    <tr>
        <td class="m0" colspan="2">■メール内容</td>
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
<input type="button" value="WEB動作確認" onClick="onclick_web_test()">
<? } ?>
<input type="button" value="メールテスト送信" onclick="onclick_test_mail()">
<input type="button" value="対象人数検索" onclick="onclick_search()">
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
<input type="hidden" name="next_action">
<input type="hidden" name="marketer_id" value="<?=$marketer_id?>">
<input type="hidden" name="my_enq_no" value="<?=$my_enq_no?>">
<input type="hidden" name="enquete_id" value="<?=$myenq->enquete_id?>">
<input type="hidden" name="start_date_y" <?=value($enquete->start_date_y)?>>
<input type="hidden" name="start_date_m" <?=value($enquete->start_date_m)?>>
<input type="hidden" name="start_date_d" <?=value($enquete->start_date_d)?>>
<input type="hidden" name="start_date_h" <?=value($enquete->start_date_h)?>>
<input type="hidden" name="add_send_num">
</div>

<? page_footer() ?>
</body>
</html>
