<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｐｒｏアンケート表示処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/pro_enquete.php");
include("$inc/my_search.php");
include("$inc/mye_temp.php");
include("$inc/ma_profile.php");
include("$inc/pro_enq_target.php");
include("$inc/mail.php");
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

// ファインディング有無
function decode_finding_flag($flag) {
    switch ($flag) {
    case DBTRUE:
        return 'あり';
    case DBFALSE:
        return 'なし';
    }
    return '';
}

// アンケート内容表示
//jeon_start seq=81
//function disp_enquete($enquete) {
function disp_enquete($enquete,$enquete2=null) {
//jeon_end seq=81
    if (is_array($enquete->question)) {
        $page_break = '';

        foreach ($enquete->question as $qno => $question) {
            // 全角の質問番号
            $qno_zenkaku = 'Ｑ' . mb_convert_kana($qno, 'N');

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
        <td class="m1" colspan="2" rowspan="2">分岐条件</td>
        <td class="n1">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
    </tr>
    <tr><!--jeon_start seq=81-->
        <td class="n1"><?=htmlspecialchars($enquete->branch_cond_text($qno, 'を選択した方のみ',$enquete2, true))?></td>
    </tr><!--jeon_end seq=81-->
<?
            } elseif ($question->page_break == DBTRUE) {
?>
    <tr>
       <td class="m1" 　align="center" colspan="3"><p align="center">改ページ</p></td>
    </tr>
<?
            }

            if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7) {
?>
    <tr>
        <td class="m1" colspan="2" rowspan="4"><?=$qno_zenkaku?>
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
        <td class="m1" colspan="2" rowspan="3"><?=$qno_zenkaku?>
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
        <td class="m1" colspan="2" rowspan="4"><?=$qno_zenkaku?>
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
}

// Proアンケート終了処理
function end_pro_enquete($enquete_id) {
	// マーケターとエージェントのメールアドレス取得
	$sql = "SELECT mep_pro_enq_no,mep_finding_flag,mr_marketer_id,mr_mail_addr,mr_name1,ag_mail_addr,ag_name1,ag_name2"
			. " FROM t_pro_enquete JOIN t_marketer ON mr_marketer_id=mep_marketer_id LEFT JOIN t_agent ON ag_agent_id=mr_agent_id"
			. " WHERE mep_enquete_id=$enquete_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$pro_enq_no = $fetch->mep_pro_enq_no;
		$finding_flag = $fetch->mep_finding_flag;
		$marketer_id = $fetch->mr_marketer_id;
		$marketer_addr = $fetch->mr_mail_addr;
		$marketer_name = $fetch->mr_name1;
		$agent_addr = $fetch->ag_mail_addr;
		$agent_name = trim("$fetch->ag_name1 $fetch->ag_name2");
	} else
		return;

	// マーケターページのURL取得
	$fetch = get_system_info('sy_url_marketer');
	$url_marketer = $fetch->sy_url_marketer;

	// 結果ページ取得
	$ary = file("${url_marketer}pe_resdet_pv2.php?owner_id=$marketer_id&pro_enq_no=$pro_enq_no");
	if (!is_array($ary))
		return;
	$html = join("", $ary);

	$pro_enq = new pro_enquete_class;
	$pro_enq->read_db($marketer_id, $pro_enq_no);
	$enquete = &$pro_enq->enquete;

	get_mail_template('pe_res', $subject, $from, $cc, $bcc, $repty_to, $body);
	separate_address($from, $from_addr, $from_name);
	$body = nl2br($body);
	$body = str_replace('%MARKETER_NAME%', $marketer_name, $body);
	$body = str_replace('%MAIL_ADDR%', $marketer_addr, $body);
	$body = str_replace('%TITLE%', $enquete->title, $body);
	$body = str_replace('%START_DATE%', "$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d", $body);
	$body = str_replace('%END_DATE%', "$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d", $body);
	$body = str_replace('%AGENT%', "$agent_name", $body);
	$body = str_replace('%ENQUETE_RESULT%', $html, $body);
	$body = str_replace('%FINDING_NUM%', $pro_enq->finding_flag == DBTRUE ? number_format($pro_enq->appearance_ratio ? $pro_enq->sample_num * 100 / $pro_enq->appearance_ratio: 0) : '-', $body);
	$body = str_replace('%APPEARANCE%', $pro_enq->finding_flag == DBTRUE ? "$pro_enq->appearance_ratio%" : '-', $body);
	$body = str_replace('%SAMPLE_NUM%', number_format($pro_enq->sample_num), $body);
	$body = str_replace('%ENQTEST_URL%', "{$url_marketer}pe_remlenq3a.php?m=1&enquete_id=$pro_enq->enquete_id&pro_enq_no=$pro_enq->pro_enq_no", $body);
	$body = str_replace('%ESTIMATION%', number_format($pro_enq->get_total_price()), $body);

	// 送信メールセット
	$send_mail_id = send_mail_data($subject, $from_addr, $repty_to, $body, '', '', $from_name, '1');

	// マーケターとエージェントにメール送信
	if ($agent_addr != '')
		send_mail_list($send_mail_id, $agent_addr);

	$pro_enq->get_notify_mail_addr($to, $cc);
	if ($to) {
		if ($cc)
			$cc = "Cc: $cc";
		send_mail_list($send_mail_id, $to, '', $cc);
	}

	send_mail_ok($send_mail_id);
}

set_global('proenquete', 'Ｐｒｏアンケート管理', 'Ｐｒｏアンケート表示', BACK_TOP);

$pro_enq  = new pro_enquete_class;
$pro_enq->read_db($marketer_id, $pro_enq_no);

$enquete = &$pro_enq->enquete;
$enquete2 = &$pro_enq->enquete2;
$search = &$pro_enq->search;
$cell = &$pro_enq->cell;
$cleaning = &$pro_enq->cleaning;

// 修正及び追加後
//$search->get_monitor_num($marketer_id, $pro_enq_no);
$get_target_flg = $search->get_target_flg();

/* 修正前
// 申請中を事務局チェック中に変更
if ($check == '1' && $enquete->status == 2) {
    $sql = "UPDATE t_enquete SET en_status=3 WHERE en_enquete_id=$pro_enq->enquete_id";
    db_exec($sql);
}
*/

// 修正及び追加後
// 申請中を事務局チェック中に変更
$updated = false;
if ($check == '1' && $enquete->status == 2) {
    $sql = "UPDATE t_enquete SET en_status=3,en_sub_status=1 WHERE en_enquete_id=$pro_enq->enquete_id";
    db_exec($sql);
    $updated = true;
}
// 事務局確認中をデータ格納確認中に変更(sub_status=1 -> sub_status=2)
if ($sub_status == '1' && $enquete->status == 3) {
    $sql = "UPDATE t_enquete SET en_sub_status=2 WHERE en_enquete_id=$pro_enq->enquete_id";
    db_exec($sql);
    $updated = true;
}
// データ格納確認中を事務局承認待ちに変更(sub_status=2 -> sub_status=3)
if ($sub_status == '2' && $enquete->status == 3) {
    $sql = "UPDATE t_enquete SET en_sub_status=3 WHERE en_enquete_id=$pro_enq->enquete_id";
    db_exec($sql);

    // ネットワーク識別テーブルのフラグ(pet_target_flg)を立てる
    $sql = "UPDATE t_pro_enq_target SET pet_target_flg='$get_target_flg' WHERE pet_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
    db_exec($sql);
    $updated = true;
}
// 「アンケート内容確認完了に戻る」の追加 2006/04/26 BTI
if ($back_confirmed == '1') {
    // アンケート回答格納テストデータを削除する
    $sql = "DELETE FROM t_answer WHERE an_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
    db_exec($sql);
    $sql = "DELETE FROM t_ans_select WHERE as_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
    db_exec($sql);
    $sql = "DELETE FROM t_ans_matrix WHERE ax_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
    db_exec($sql);

    // 事務局確認中に戻る
    $sql = "UPDATE t_enquete SET en_status=3,en_sub_status=1 WHERE en_enquete_id=$pro_enq->enquete_id";
    db_exec($sql);
    $updated = true;
}
if ( $updated ) {
    $pro_enq->read_db($marketer_id, $pro_enq_no);
    $enquete = &$pro_enq->enquete;
    $enquete2 = &$pro_enq->enquete2;
    $search = &$pro_enq->search;
    $cell = &$pro_enq->cell;
    $cleaning = &$pro_enq->cleaning;
//    $search->get_monitor_num($marketer_id, $pro_enq_no);
    $get_target_flg = $search->get_target_flg();
}

// アンケート終了処理
if ($enquete_end) {
	if ($enquete->status == 5) {
		$sql = "UPDATE t_enquete SET en_status=7, en_end_date=current_timestamp WHERE en_enquete_id=$pro_enq->enquete_id";
		db_exec($sql);

		end_pro_enquete($pro_enq->enquete_id);

		redirect("show.php?marketer_id=$marketer_id&pro_enq_no=$pro_enq_no");
	}
}

// 承認待ち時、修正ボタン出さない 2006/03/13 BTI
// データ格納確認中は修正ボタン 2006/04/26 BTI
$isSyounin = false;
if (  ($enquete->status == 3 && ($enquete->sub_status == 2 || $enquete->sub_status == 3)) ||  ($enquete->status >= 4 && $enquete->status <= 12)) {
    $isSyounin = true;
}
// 修正及び追加済み

// メールタイトルが設定されていない場合はアンケートタイトルを表示
$mail_title = ($pro_enq->mail_title != '') ? $pro_enq->mail_title : $enquete->title;

// メール分が設定されていない場合はテンプレートを表示
if ($pro_enq->mail_contents == '')
    get_enq_body($pro_enq, $mail_header, $mail_contents, $mail_footer);
else {
    $mail_header = $pro_enq->mail_header;
    $mail_contents = $pro_enq->mail_contents;
    $mail_footer = $pro_enq->mail_footer;
}

// マーケターページのURL取得
$fetch = get_system_info('sy_url_marketer');
$url_marketer = $fetch->sy_url_marketer;

// 作成完了フラグ取得
if ($enquete->title != '')
    $make_flag |= 0x01;
if ($pro_enq->finding_flag == DBTRUE) {
    if ($enquete->get_question_num() != 0)
        $make_flag |= 0x02;
    if ($pro_enq->res_cond)
        $make_flag |= 0x04;
} else
    $make_flag |= 0x06;
if ($enquete2 && $enquete2->get_question_num() != 0)
    $make_flag |= 0x08;
if ($cleaning->exist_flag)
    $make_flag |= 0x10;
if ($pro_enq->search_id)
    $make_flag |= 0x20;
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
    return confirm("このＰｒｏアンケートを承認します。よろしいですか？");
}

<?
//seq no 49 kim_start
//回答（QRのみ、カンマ区きり）case1利用してcsv.phpで移動する
//回答（QRのみ,0,1形式）case2利用してcsv２.phpで移動する。
//設問　case3:利用してcsv3.phpで移動する
//回答（QRのみ、カンマ区きり）case4:利用してcsv4.phpで移動する。
//回答（QRのみ,0,1形式）case5:利用してcsv５.phpで移動する。
//回答（DRのみ、カンマ区きり）case6:利用してcsv6.phpで移動する。
//回答（DRのみ、0,1形式）case7:利用してcsv7.phpで移動する。
?>

function func_csv(){
	var f=document.form1;
	switch(f.ss.value){
		case "1":location.href ="csvt2.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>&mode=1";
		break;
		case "2":location.href ="csvt1.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>&mode=1";
		break;
		case "3":location.href ="csvt1.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>&mode=2";
		break;
		case "4":location.href="csvt2.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>&mode=2";
		break;
		case "5":location.href="csvt2.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>&mode=3";
		break;
		case "6":location.href="csvt1.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>&mode=3";
		break;
		case "7":location.href="csvt1.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>&mode=4";
		break;
//		default:redirect("show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>");
	}
}

<? // kim_end
?>

function onclick_mail_prev() {
    location.href = "mail_prev.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>";
}
function onclick_web_prev() {
    location.href = "web_prev.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>";
}
function onclick_test_mail() {
    location.href = "test_mail.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>";
}
function onclick_search() {
    window.open("search.php?search_id=<?=$pro_enq->search_id?>&marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>", "search", "width=300,height=230");
}
function onclick_mailTemplate() {
    location.href = "case.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>";
}

<?// kim_start seq no=50 関数　追加
?>
function onclick_qr_replyview(){
	location.href = "pe_cell_count.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>";
	<!--window.open('pe_cell_count.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>','_new','menubar=no toolbar=no location=no scrollbars=no status=no resizable=no width=800 height=400 left=200  top=300'); //-->

}
function onclick_dr_replyview(){
	location.href="pe_recv_count_detail.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>&age_cd=<?=$search->age_cd?>";
	<!--window.open('pe_recv_count_detail.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>','_new','menubar=no toolbar=no location=no scrollbars=no status=no resizable=no width=800 height=400 left=200  top=300'); //-->
}

<?
//kim_end seq no=50
?>

function onclick_shounin() {
    var f = document.form1;
    if (showModalDialog("shounin.php",f,"status:off;help:off;dialogWidth:350px;dialogHeight:150px"))
        f.submit();
}
function onclick_web_test() {
    window.open('<?=$url_marketer?>pe_remlenq3a.php?enquete_id=<?=$pro_enq->enquete_id?>&pro_enq_no=<?=$pro_enq_no?>', 'webtest');
}

<?
// 関数追加 2006/03/03 BTI
?>
function onclick_edit() {
    location.href = "edit1.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>";
}
function onclick_confirmed() {
    location.href = "show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>&sub_status=1";
}
function onclick_confirmed2() {
    location.href = "show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>&sub_status=2";
}
function onclick_kakunou() {
    location.href = "kakunou.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>";
}

<?
// 「実施状態の変更」の追加 2006/03/28 BTI
?>
function onclick_update_status() {
    location.href = "edit_status.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>";
}

<?
// 「アンケート内容確認完了に戻る」の追加 2006/04/26 BTI
?>
function onclick_back_confirmed(){
    location.href = "show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>&back_confirmed=1";
}
<?
// 関数追加済み
?>
function enquete_end() {
	if (confirm("このＰｒｏアンケートを終了します。よろしいですか？")) {
		location.href = "show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>&enquete_end=1";
	}
}
//-->
</script>
</head>
<body>
<? page_header() ?>


<div align="center">
<form method="post" name="form1" action="update.php" onSubmit="return onsubmit_form1(this)">

<table border=0 cellspacing=2 cellpadding=3 width="80%" frame="box">
    <tr>
        <td colspan=3>
            <table border=0 cellspacing=0 cellpadding=0 width=100%>
                <tr class="m0">
                    <td colspan="2">■アンケート概要</td>
                    <td align="right">
                    <? if ( !$isSyounin ) { ?>
                         <input type="button" name="" value="<?=$make_flag & 0x01 ? '　修正　' : '　設定　'?>" onClick="location.href='edit1.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
                    <? } ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="m1" colspan="2" width="20%">アンケートタイトル</td>
        <td class="n1" width="80%"><?=htmlspecialchars($enquete->title)?>　についてのアンケート</td>
    </tr>
    <tr>
        <td class="m1" colspan="2">ファインディング有無</td>
        <td class="n1"><?=decode_finding_flag($pro_enq->finding_flag)?></td>
    </tr>
    <tr>
        <td class="m1" colspan="2">開始日時</td>
        <td class="n1"><? disp_date1($enquete->start_date_y, $enquete->start_date_m, $enquete->start_date_d)?></td>
    </tr>
    <tr>
        <td class="m1" colspan="2">終了日時</td>
        <td class="n1"><? disp_date2($enquete->end_date_y, $enquete->end_date_m, $enquete->end_date_d, $enquete->end_date_h)?></td>
    </tr>
    <tr>
        <td class="m1" colspan="2">回答条件</td>
        <td class="n1">
<?
$ans_cond_ary = explode("\n", $pro_enq->ans_cond);
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
        <td class="m1" colspan="2">ポイント数</td>
        <td class="n1"><?=$pro_enq->finding_flag == DBTRUE ? "$enquete->point / $enquete2->point" : $enquete->point?>ポイント</td>
    </tr>
    <tr>
        <td class="m1" colspan="2">実施状態</td>
        <td class="n1"><?=decode_enq_status($enquete->status)?>
<?
if ($enquete->status == 5) {
?>
			&nbsp;&nbsp;<input type="button" value="終了" onclick="enquete_end()">
<?
}
?>
		</td>
    </tr>
<?
if ($pro_enq->finding_flag == DBTRUE) {
?>
    <tr>
        <td colspan=3>
            <table border=0 cellspacing=0 cellpadding=0 width="100%">
                <tr class="m0">
                    <td colspan="2">■ファインディングアンケート内容</td>
                    <td align="right">
<?
    if ( !$isSyounin ) {
        if ($make_flag & 0x02) {
?>
                        <input type="button" value="分岐・改ページの設定" onClick="location.href='edit_branch.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
<?
        }
?>
                        <input type="button" value="<?=$make_flag & 0x02 ? '　修正　' : '　設定　'?>" onClick="location.href='edit2.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
<?  } ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
<?
    if ($pro_enq->finding_flag == DBTRUE)
        disp_enquete($enquete);
}

if ($pro_enq->finding_flag == DBTRUE) {
?>
    <tr>
        <td colspan=3>
            <table border=0 cellspacing=0 cellpadding=0 width="100%">
                <tr class="m0">
                    <td>■本アンケート対象条件</td>
                    <td align="right">
                    <? if ( !$isSyounin ) { ?>
			<input type="button" value="<?=$make_flag & 0x04 ? '　修正　' : '　設定　'?>" onclick="location.href='edit_res.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
                    <? } ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
<?
    if ($pro_enq->finding_flag == DBTRUE && is_array($pro_enq->res_cond)) {
?>
    <tr>
        <td class="m1" colspan="2">設問間結合条件</td>
        <td class="n1"><?=decode_and_or($pro_enq->res_and_or)?></td>
    </tr>
    <tr>
        <td class="m1" colspan="2">回答条件</td>
        <td class="n1">
            <table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
<?
        foreach ($pro_enq->res_cond as $qno => $res_cond) {
            $question = &$enquete->question[$qno];

            $sno_ary = my_explode(',', $res_cond->select_no);
            $sel_text_ary = array();
            foreach ($sno_ary as $sno) {
                if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7)
                    $sel_text_ary[] = $question->sel_text[$sno];
                elseif ($question->question_type == 4 || $question->question_type == 5) {
                    list($sno, $tno) = explode(':', $sno);
                    $sel_text_ary[] = $question->hyousoku[$sno] . '：' . $question->hyoutou[$tno];
                }
            }
            $sel_text = join('，', $sel_text_ary);
?>
                <tr>
                    <td width="10%" class="n1" align="center">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
                    <td width="10%" class="n1" align="center">
					<!-- SEQ 82 START 回答条件で通常/Not条件を表示  -->
					<?=decode_not_cond($res_cond->not_cond)?><br>
					<!-- SEQ 82 END 回答条件で通常/Not条件を表示  -->
					<?=decode_and_or($res_cond->and_or)?></td>
                    <td width="60%" class="n1"><?=htmlspecialchars($sel_text)?></td>
                </tr>
<?
        }
?>
            </table>
        </td>
    </tr>
<?
}
?>
    <tr>
        <td class="m1" colspan="2">想定出現率</td>
        <td class="n1"><?=number_format($pro_enq->appearance_ratio)?> %</td>
    </tr>
<?
}
?>
    <tr>
        <td colspan=3>
            <table border=0 cellspacing=0 cellpadding=0 width="100%">
                <tr class="m0">
                    <td>■本アンケート内容</td>
                    <td align="right">
<?
if ( !$isSyounin ) {
    if ($make_flag & 0x08) {
?>
                        <input type="button" value="分岐・改ページの設定" onClick="location.href='edit_branch.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>&main=1'">
<?
    }
?>
                        <input type="button" value="<?=$make_flag & 0x08 ? '　修正　' : '　設定　'?>" onClick="location.href='edit2.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>&main=1'">
<?
}
?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
<?
 //jeon_start seq=81
// disp_enquete($enquete2);
 disp_enquete($enquete2,$enquete);
 //jeon_end seq=81
?>
    <tr>
        <td colspan=3>
            <table border=0 cellspacing=0 cellpadding=0 width="100%">
                <tr class="m0">
                    <td>■サンプルクリーニング条件</td>
                    <td align="right">
                    <? if ( !$isSyounin ) { ?>
                        <input type="button" value="<?=$make_flag & 0x10 ? '　修正　' : '　設定　'?>" onClick="location.href='edit_cleaning.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
                    <? } ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
<?
if ($cleaning->exist_flag) {
?>
    <tr>
        <td class="m1" colspan="2">条件種別</td>
        <td class="n1"><?=$cleaning->valid_flag == DBFALSE ? '無効回答を設定する' : '有効回答を設定する'?></td>
    </tr>
<?
    $cleaning_ary = array();
    if (is_array($cleaning->cleaning_cond)) {
        foreach ($cleaning->cleaning_cond as $cond_no => $cleaning_cond) {
            $cleaning_ary[$cond_no]->and_or = $cleaning_cond->and_or;
            $cleaning_ary[$cond_no]->cond = array();
            if (is_array($cleaning_cond->ans_cond)) {
                foreach ($cleaning_cond->ans_cond as $seq_no => $ans_cond) {
                    if ($ans_cond->select_no != '') {
                        if ($ans_cond->ans_enquete_id == $pro_enq->enquete_id)
                            $question = &$enquete->question[$ans_cond->question_no];
                        elseif ($ans_cond->ans_enquete_id == $pro_enq->enquete2_id)
                            $question = &$enquete2->question[$ans_cond->question_no];
                        else
                            continue;

                        $sno_ary = my_explode(',', $ans_cond->select_no);
                        $sel_text_ary = array();
                        foreach ($sno_ary as $sno) {
                            if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7)
                                $sel_text_ary[] = $question->sel_text[$sno];
                            elseif ($question->question_type == 4 || $question->question_type == 5)
                                $sel_text_ary[] = $question->hyoutou[$sno];
                        }

                        if ($ans_cond->ans_enquete_id == $pro_enq->enquete_id && $pro_enq->enquete_id != $pro_enq->enquete2_id)
                            $question_no = "FDQ$ans_cond->question_no";
                        else
                            $question_no = "Q$ans_cond->question_no";

                        if ($ans_cond->hyousoku_no)
                            $question_no .= "M-$ans_cond->hyousoku_no";

                        $cleaning_ary[$cond_no]->cond[$seq_no]->question_no = $question_no;
                        $cleaning_ary[$cond_no]->cond[$seq_no]->and_or = $ans_cond->and_or;
                        $cleaning_ary[$cond_no]->cond[$seq_no]->not_cond = ($ans_cond->not_cond == DBTRUE) ? 'Not条件' : '通常条件';
                        $cleaning_ary[$cond_no]->cond[$seq_no]->sel_text = join('，', $sel_text_ary);
                    }
                }
            }
        }
    }

    foreach ($cleaning_ary as $cond_no => $cleaning) {
        if (count($cleaning->cond) > 0) {
            if ($cond_no > 1) {
?>
    <tr>
        <td class="m1" colspan="2">条件間</td>
        <td class="n1"><?=decode_and_or($cleaning->and_or)?></td>
    </tr>
<?
        }
?>
    <tr>
        <td class="m1" colspan="2">クリーニング条件<?=mb_convert_kana($cond_no, 'N')?></td>
        <td class="n1">
            <table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
<?
            foreach ($cleaning->cond as $cond) {
?>
                <tr>
                    <td width="15%" class="n1" align="center"><?=mb_convert_kana($cond->question_no, 'N')?></td>
                    <td width="10%" class="n1" align="center"><?=$cond->not_cond?><br><?=decode_and_or($cond->and_or)?></td>
                    <td width="55%" class="n1"><?=htmlspecialchars($cond->sel_text)?></td>
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
        <td colspan=3>
            <table border=0 cellspacing=0 cellpadding=0 width="100%">
                <tr class="m0">
                    <td>■対象条件・サンプル割付</td>
                    <td align="right">
                    <? if ( !$isSyounin ) { ?>
                        <input type="button" value="<?=$make_flag & 0x20 ? '　修正　' : '　設定　'?>" onClick="location.href='edit3.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
                    <? } ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
<?
if ($pro_enq->finding_flag == DBTRUE) {
    if ($pro_enq->appearance_ratio != 0 && $pro_enq->sample_num != 0)
        $finding_sample_num = number_format($pro_enq->sample_num / ($pro_enq->appearance_ratio / 100));
    else
        $finding_sample_num = '';
?>
    <tr>
        <td class="m1" colspan="2">ファインディングサンプル数</td>
        <td class="n1"><?=$finding_sample_num?></td>
    </tr>
<?
}
?>
    <tr>
        <td class="m1" colspan="2">本アンケート<br>回収目標サンプル数</td>
        <td class="n1"><?=$pro_enq->sample_num?></td>
    </tr>
    <tr>
        <td class="m1" colspan="2">セル指定</td>
        <td class="n1">
            <? if ($make_flag & 0x20){
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
        <td class="m1" colspan="2">性別</td>
        <td class="n1">
            <?
                if ($make_flag & 0x20)
                    echo(decode_sex($search->sex, '指定なし'))
            ?>
            </td>
    </tr>
    <tr>
        <td class="m1" colspan="2">年代</td>
        <td class="n1"><? disp_age2($search->age_cd, $search->age_from, $search->age_to) ?></td>
    </tr>
<?
} elseif ($cell->cell_option == 2) {
    $send_cell = $cell->get_send_cell();
    $cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e, 2);
?>
    <tr>
        <td class="m1" colspan="2">性別／年代</td>
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

<? // GMO(infoQ)向けのアンケートは発信数表示しない 2006/03/10 BTI
   if ( $get_target_flg != 'g' ) { ?>
    <tr>
        <td class="m1" colspan="2">発信数</td>
        <td class="n1">
            <?=number_format($pro_enq->real_send ? $pro_enq->real_send : $pro_enq->send_num)?>人
            &nbsp;&nbsp;<input type="button" value="発信数詳細" onClick="location.href='send_detail.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
            &nbsp;&nbsp;<input type="button" value="追加発信数表示" onClick="location.href='add_send.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
        </td>
    </tr>
<? } ?>
    <tr>
        <td class="m1" colspan="2">対象モニター</td>
        <td class="n1"><?=$search->decode_monitor_type($search->type, '')?></td>
    </tr>
    <tr>
        <td class="m1" colspan="2">未既婚</td>
        <td class="n1">
<?
if ($make_flag & 0x20)
    echo(decode_mikikon($search->mikikon, '指定しない'));
?>
        </td>
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
	<tr>
		<td class="m1"  rowspan='3' <?if($search->zip=='' && $search->zip_kinmuchi=='')echo "colspan='2'"?>>郵便番号</td>
		<?if($search->zip!='' && $search->zip_kinmuchi!=''){?>
		<td class="m1"  width='15%'>条件</td>
		<td class="n1"><? if($search->zip_kinmuchi_andor==1)echo "居住地域 AND 動務地";
			else echo"居住地域  OR 動務地";?></td>
		<?}else if($search->zip=='' && $search->zip_kinmuchi==''){?><td rowspan='3'  class="n1">&nbsp;</td><?}?>
	</tr>
	<tr>
		<?if($search->zip!=''){?>
		<td class="m1" width='15%'>居住地域</td>
		<td class="n1"><?=$search->zip;?></td>
		<?}?>
	</tr>
	<tr>
		<?if($search->zip_kinmuchi!=''){?>
		<td class="m1" width='15%'>動務地</td>
		<td class="n1"><?=$search->zip_kinmuchi;?></td>
		<?}?>
	</tr>
			<!-- jeon_end seq=mp3-->
    <tr>
        <td class="m1" colspan="2">職業</td>
        <td class="n1"><? disp_shokugyou($search->shokugyou) ?></td>
    </tr>
    <tr>
        <td class="m1" colspan="2">業種</td>
        <td class="n1"><? disp_gyoushu($search->gyoushu) ?></td>
    </tr>
    <tr>
        <td class="m1" colspan="2">職種</td>
        <td class="n1"><? disp_shokushu($search->shokushu) ?></td>
    </tr>
<!--
    <tr>
        <td class="m1" colspan="2">子供の有無</td>
        <td class="n1"><?=multi_decode(decode_have_child, $search->have_child)?></td>
    </tr>
    <tr>
        <td class="m1" colspan="2">住居形態</td>
        <td class="n1"><?=multi_decode(decode_housing_form, $search->housing_form)?></td>
    </tr>
    <tr>
        <td class="m1" colspan="2">車の所有</td>
        <td class="n1"><?=multi_decode(decode_have_car, $search->have_car)?></td>
    </tr>
-->
    <tr>
        <td class="m1" colspan="2">よく行くコンビニ</td>
        <td class="n1"><? disp_conveni($search->conveni) ?></td>
    </tr>
    <tr>
        <td class="m1" colspan="2">よく行くスーパー</td>
        <td class="n1"><? disp_super($search->super) ?></td>
    </tr>
<?
// 追加プロファイル
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
    <tr>
        <td class="m1" colspan="2"><?=htmlspecialchars($profile_name)?></td>
        <td class="n1"><? disp_ma_profile($profile_id, $search->ma_profile) ?></td>
    </tr>
<?
}
?>
    <tr>
        <td class="m1" colspan="2">興味ジャンル</td>
        <td class="n1"><? disp_genre($search->genre) ?></td>
    </tr>
	<tr>
		<td class="m1" colspan="2">対象メンバーリスト</td>
		<td class="n1"><?=$search->taisho_myenq_list?></td>
	</tr>
	<tr>
		<td class="m1" colspan="2">除外メンバーリスト</td>
		<td class="n1"><?=$search->jogai_myenq_list?></td>
	</tr>
    <tr>
        <td colspan=3>
            <table border=0 cellspacing=0 cellpadding=0 width="100%">
                <tr class="m0">
                    <td>■アンケートＷＥＢ画面とメール文のカスタマイズ</td>
                    <td align="right">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="m1" colspan="2">アンケートＷＥＢ画面のカスタマイズ</td>

        <td class="n1">
<?
if (($make_flag & 0x2f) == 0x2f) {
?>
            <input name="button" type="button" onClick="location.href='edit_web.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'" value="ＷＥＢ画面の手動修正">
<?
}
?>
        </td>
    </tr>
    <tr>
        <td class="m1" colspan="2">メール文のカスタマイズ</td>

        <td class="n1">
<?
if (($make_flag & 0x2f) == 0x2f) {
?>
            <input name="button2" type="button" onClick="location.href='edit_mail.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'" value="メール文面の手動修正"> <span class="footer_text">
<?
}
?>
        </td>
    </tr>
    <tr>
        <td class="m0" colspan=2>■メール内容</td>
    </tr>
    <tr>
        <td class="m1" colspan="2">メールタイトル</td>
        <td class="n1"><?=htmlspecialchars($mail_title)?></td>
    </tr>
    <tr>
        <td class="m1" colspan="2">ヘッダ</td>
        <td class="n1"><span class="small"><?=nl2br(htmlspecialchars($mail_header))?></span></td>
    </tr>
    <tr>
        <td class="m1" colspan="2">本文</td>
        <td class="n1"><span class="small"><?=nl2br(htmlspecialchars($mail_contents))?></span></td>
    </tr>
    <tr>
        <td class="m1" colspan="2">フッタ</td>
        <td class="n1"><span class="small"><?=nl2br(htmlspecialchars($mail_footer))?></span></td>
    </tr>
</table>
<br>

<!-- kim_start seq no=50 作動中止<input type="button" value="設問CSV取得" onclick="onclick_csv3()">//-->
<? //if ($enquete->status >= 5) { ?>
<!--<input type="button" value="CSV取得" onclick="onclick_csv()">
<input type="button" value="CSV取得(0,1)" onclick="onclick_csv2()">
kim_end seq no=50
//-->

<?
 /** kim_start	seq no=49
	設問CSV取得、回答QR,DRCSV取得カンマ、(0,1)形式select文で修正および技能追加
*/
?>
	<select name="ss">
		<option unselected>CSVファイル取得</option>
		<option value="1">設問</option>
		function decode_finding_flag($flag) {
	    switch ($flag) {
	    case DBTRUE:
	        return 'あり';
	    case DBFALSE:
	        return 'なし';
	    }
	    return '';
<?
	if($enquete->status >=5){
		if(decode_finding_flag($pro_enq->finding_flag)=='あり'){?>
			<option>----------------------</option>
			<option value="2">回答（QRのみ、カンマ区きり）</option>
			<option value="3">回答（QRのみ、0,1形式）</option>

		<?}if(decode_finding_flag($pro_enq->finding_flag)=='なし'||decode_finding_flag($pro_enq->finding_flag)=='あり'){?>
			<option>----------------------</option>
			<option value="4">回答（DRのみ、カンマ区きり）</option>
			<option value="5">回答（DRのみ、0,1形式）</option>

		<?}if(decode_finding_flag($pro_enq->finding_flag)=='あり'){?>
			<option>----------------------</option>
			<option value="6">回答（QR＋DRカンマ区きり）</option>
			<option value="7">回答（QR＋DR0,1形式）</option>
		<?}
	}
?>
	</select>
		<input type="button" value="CSV取得" onClick="func_csv()">
<br>
<?
//kim_end seq no=49
?>

<? //} ?>
<? if ($enquete->enq_type == ENQ_TYPE_MAIL) { ?>
<input type="button" value="メールプレビュー" onClick="onclick_mail_prev()">
<? } elseif ($enquete->enq_type == ENQ_TYPE_WEB) { ?>
<input type="button" value="WEBプレビュー" onClick="onclick_web_prev()">
<input type="button" value="WEB動作確認" onClick="onclick_web_test()">
<? } ?>
<input type="button" value="メールテスト送信" onClick="onclick_test_mail()">
<input type="button" value="対象人数検索" onClick="onclick_search()">
<? if ( $enquete->status == 2 ){ ?>
<input type="button" value="　承認　" onClick="onclick_shounin()">
<? }elseif ( $enquete->status == 3 && $enquete->sub_status == 2 ) { ?>
<input type="button" value="　変更　" onClick="onclick_edit()">
<? }elseif ( $enquete->status == 3 && $enquete->sub_status == 3 ){ ?>
<input type="button" value="　承認　" onClick="onclick_shounin()">
<? } ?>
<input type="button" value="事例メールテンプレート" onClick="onclick_mailTemplate()">
<?
// kim_start seq no=50 QR回答数表示,DR回答数表示Button追加
?>

<?if(decode_finding_flag($pro_enq->finding_flag)=='あり'){?>
<input type="button" value="QR回答数表示" onClick="onclick_qr_replyview();">
<?}?>
<input type="button" value="DR回答数表示" onClick="onclick_dr_replyview();">
<?
//kim_end seq no=50
?>
<div style="margin:0 auto;">
<? if ($enquete->status == 3 && $enquete->sub_status == 1) { ?>
<input type="button" value="アンケート内容確認完了" onClick="onclick_confirmed()">
<? }elseif ($enquete->status == 3 && $enquete->sub_status == 2) { ?>
<input type="button" value="回答格納テスト" onClick="onclick_kakunou()">
<input type="button" value="データ格納確認完了" onClick="onclick_confirmed2()">
<input type="button" value="アンケート内容確認完了に戻る" onClick="onclick_back_confirmed()">
<? // 「回答格納テスト履歴」の追加 2006/03/28 BTI
   }elseif ( ($enquete->status == 3 && $enquete->sub_status == 3) || $enquete->status == 4 || $enquete->status == 5 || $enquete->status == 6 || $enquete->status == 7 ) { ?>
    <input type="button" value="回答格納テスト履歴" onClick="onclick_kakunou()">
        <? // 「アンケート内容確認完了に戻る」の追加 2006/04/26 BTI
        if( ($enquete->status == 3 && $enquete->sub_status == 3) || $enquete->status == 4) { ?>
        <input type="button" value="アンケート内容確認完了に戻る" onClick="onclick_back_confirmed()">
        <? } ?>
        <? // 「実施状態の変更」の変更 2006/04/26 BTI
        if( $enquete->status == 5 ) { ?>
        <input type="button" value="実施状態の変更" onClick="onclick_update_status()">
        <? } ?>
<? } ?>
<input type="button" value="　戻る　" onClick="location.href='list.php'">
</div>
<input type="hidden" name="next_action" value="app">
<input type="hidden" name="marketer_id" value="<?=$marketer_id?>">
<input type="hidden" name="pro_enq_no" value="<?=$pro_enq_no?>">
<input type="hidden" name="enquete_id" value="<?=$pro_enq->enquete_id?>">
<input type="hidden" name="start_date_y" <?=value($enquete->start_date_y)?>>
<input type="hidden" name="start_date_m" <?=value($enquete->start_date_m)?>>
<input type="hidden" name="start_date_d" <?=value($enquete->start_date_d)?>>
<input type="hidden" name="start_date_h" <?=value($enquete->start_date_h)?>>
</form>
</div>

<? page_footer() ?>
</body>
</html>
