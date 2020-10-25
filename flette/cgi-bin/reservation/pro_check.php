#!/usr/local/bin/php4
<?
/******************************************************
' System :flette予約入力ページ
' Content:基本情報入力チェック処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/reserve.php");
include("$inc/check.php");

// セッションから予約情報取得
session_start();
if ($reserve == null)
	redirect('timeout.php');

// エラーリセット
$reserve->error = null;

// フォームからデータ取得
$reserve->name_1 = $name1;
$reserve->name_2 = $name2;
$reserve->kana_1 = $kana1;
$reserve->kana_2 = $kana2;
$reserve->mail_addr = $mail_addr;
$reserve->mail_addr2 = $mail_addr2;
$reserve->contact_flag = $contact_flag;
$reserve->contact_mail = $contact_mail;
$reserve->contact_keitai = $contact_keitai;
$reserve->contact_jitaku = $contact_jitaku;
$reserve->contact_time = $contact_time;
$reserve->contact_time_memo = $contact_time_memo;

// 名前入力チェック
if ($reserve->name_1 == '' || $reserve->name_2 == '')
	$reserve->error['name'] = '＜お名前を入力してください＞';

// ふりがな入力チェック
if ($reserve->kana_1 == '' || $reserve->kana_2 == '')
	$reserve->error['kana'] = '＜フリガナを入力してください＞';

// メールアドレス入力チェック
if ($reserve->mail_addr == '')
	$reserve->error['mail_addr'] = '＜メールアドレスを入力してください＞';
elseif ($reserve->mail_addr2 == '')
	$reserve->error['mail_addr'] = '＜メールアドレス（確認用）を入力してください＞';
elseif ($reserve->mail_addr != $reserve->mail_addr2)
	$reserve->error['mail_addr'] = '＜メールアドレスが一致していないようです＞';
elseif (!check_mail_addr($reserve->mail_addr))
	$reserve->error['mail_addr'] = '＜メールアドレスが正しくないようです＞';

// 連絡先入力チェック
if (!$reserve->contact_flag[0]
			&& (!$reserve->contact_flag[1] || $reserve->contact_mail == '')
			&& (!$reserve->contact_flag[2] || $reserve->contact_keitai == '')
			&& (!$reserve->contact_flag[3] || $reserve->contact_jitaku == ''))
	$reserve->error['contact'] = '＜連絡先を入力してください＞';
else {
	if ($reserve->contact_flag[1] && $reserve->contact_mail == '')
		$reserve->error['contact'] = '＜メールアドレスを入力してください＞';
	elseif ($reserve->contact_flag[1] && !check_mail_addr($reserve->contact_mail))
		$reserve->error['contact'] = '＜メールアドレスが正しくないようです＞';
	elseif ($reserve->contact_flag[2] && $reserve->contact_keitai == '')
		$reserve->error['contact'] = '＜携帯電話番号を入力してください＞';
	elseif ($reserve->contact_flag[3] && $reserve->contact_jitaku == '')
		$reserve->error['contact'] = '＜自宅電話番号を入力してください＞';
}

// 希望連絡時間帯入力チェック
if (($reserve->contact_flag[2] && $reserve->contact_keitai != '')
		|| ($reserve->contact_flag[3] && $reserve->contact_jitaku != '')) {
	if ($reserve->contact_time == '')
		$reserve->error['contact_time'] = '＜時間帯をお選びください＞';
}

// エラーがあった場合は戻る
if ($reserve->error)
	redirect('pro_form.php');

// 次ページへ
redirect('check.php');
?>