<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:システムマスタ登録変更画面
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/format.php");

// メイン処理
set_global('etc', 'その他管理｜マスタメンテナンス', 'システムマスタ', BACK_TOP);

$sql = "SELECT * FROM m_system WHERE sy_system_id=$sys_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('システムＩＤが不正', __FILE__);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.sy_system_name.value == "") {
		alert("システム名称を入力してください。");
		f.sy_system_name.focus();
		return false;
	}
	if (f.sy_url_monitor.value == "") {
		alert("モニターページURLを入力してください。");
		f.sy_url_monitor.focus();
		return false;
	}
	if (f.sy_url_marketer.value == "") {
		alert("マーケターページURLを入力してください。");
		f.sy_url_marketer.focus();
		return false;
	}
	if (f.sy_url_agant.value == "") {
		alert("エージェントページURLを入力してください。");
		f.sy_url_agant.focus();
		return false;
	}
	if (f.sy_url_admin.value == "") {
		alert("事務局ページURLを入力してください。");
		f.sy_url_admin.focus();
		return false;
	}
	if (f.sy_news1_from.value == "") {
		alert("モニター宛てメールニュース送信元アドレスを入力してください。");
		f.sy_news1_from.focus();
		return false;
	}
	if (f.sy_news2_from.value == "") {
		alert("マーケタ−宛てメールニュース送信元アドレスを入力してください。");
		f.sy_news2_from.focus();
		return false;
	}
	if (f.sy_mail_from.value == "") {
		alert("全配信送信元アドレスを入力してください。");
		f.sy_mail_from.focus();
		return false;
	}
	return confirm("システムマスタ情報を更新します。よろしいですか？");
}

//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■システムマスタ情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width=35%>システムID<?=MUST_ITEM?></td>
		<td class="n1" width=65%><?=$fetch->sy_system_id?></td>
	</tr>
	<tr>
		<td class="m1">システム名称<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="sy_system_name" size=70 maxlength=50 <?=value($fetch->sy_system_name)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■ＷｅｂＵＲＬ設定</td>
	</tr>
	<tr>
		<td class="m1">サイトトップURL<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_url_top" size=70 maxlength=100 <?=value($fetch->sy_url_top)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">モニターページURL<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_url_monitor" size=70 maxlength=100 <?=value($fetch->sy_url_monitor)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">マーケターページURL<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_url_marketer" size=70 maxlength=100 <?=value($fetch->sy_url_marketer)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">エージェントページURL<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_url_agant" size=70 maxlength=100 <?=value($fetch->sy_url_agant)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">事務局ページURL<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_url_admin" size=70 maxlength=100 <?=value($fetch->sy_url_admin)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■メールニュース設定</td>
	</tr>
	<tr>
		<td class="m1">モニター宛てメールニュース送信元アドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_news1_from" size=70 maxlength=100 <?=value($fetch->sy_news1_from)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">モニター宛てメールニュース送信元名称</td>
		<td class="n1">
			<input class="kanji" type="text" name="sy_news1_name" size=70 maxlength=50 <?=value($fetch->sy_news1_name)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">マーケタ−宛てメールニュース送信元アドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_news2_from" size=70 maxlength=100 <?=value($fetch->sy_news2_from)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">マーケタ−宛てメールニュース送信元名称</td>
		<td class="n1">
			<input class="kanji" type="text" name="sy_news2_name" size=70 maxlength=50 <?=value($fetch->sy_news2_name)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■全配信設定</td>
	</tr>
	<tr>
		<td class="m1">全配信送信元アドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_mail_from" size=70 maxlength=100 <?=value($fetch->sy_mail_from)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">全配信送信元名称</td>
		<td class="n1">
			<input class="kanji" type="text" name="sy_mail_name" size=70 maxlength=50 <?=value($fetch->sy_mail_name)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■通知先アドレス設定</td>
	</tr>
	<tr>
		<td class="m1">マーケター登録通知先アドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_marketer_reg_to" size=70 maxlength=100 <?=value($fetch->sy_marketer_reg_to)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">エージェント登録通知先アドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_agent_reg_to" size=70 maxlength=100 <?=value($fetch->sy_agent_reg_to)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">Ｍｙアンケート申請通知先アドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_myenq_to" size=70 maxlength=100 <?=value($fetch->sy_myenq_to)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">メール送信完了通知先アドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_mail_notify_to" size=70 maxlength=100 <?=value($fetch->sy_mail_notify_to)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■問い合わせ設定</td>
	</tr>
	<tr>
		<td class="m1">マーケタートップページ問い合わせ送信先アドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_mk_inq_to" size=70 maxlength=100 <?=value($fetch->sy_mk_inq_to)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">マーケター無料ページ問い合わせ送信先アドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_mku_inq_to" size=70 maxlength=100 <?=value($fetch->sy_mku_inq_to)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">マーケター有料ページ問い合わせ送信先アドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_mkk_inq_to" size=70 maxlength=100 <?=value($fetch->sy_mkk_inq_to)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■メールサーバ設定</td>
	</tr>
	<tr>
		<td class="m1">送信メールエンベロープアカウント</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_envelope_addr" size=70 maxlength=100 <?=value($fetch->sy_envelope_addr)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">エンベロープアドレスパスワード</td>
		<td class="n1">
			<input class="alpha" type="password" name="sy_envelope_pwd" size=20 maxlength=20 <?=value($fetch->sy_envelope_pwd)?>>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">POP3サーバのホスト名</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_pop_server" size=70 maxlength=100 <?=value($fetch->sy_pop_server)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■希望メールアカウント設定</td>
	</tr>
	<tr>
		<td class="m1">希望メールドメイン</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_mail_domain" size=70 maxlength=100 <?=value($fetch->sy_mail_domain)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■アンケートメール設定</td>
	</tr>
	<tr>
		<td class="m1">アンケート受信ドメイン</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_enq_domain" size=70 maxlength=100 <?=value($fetch->sy_enq_domain)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">アンケート受信アカウント</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_enq_account" size=70 maxlength=100 <?=value($fetch->sy_enq_account)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">アンケート受信パスワード</td>
		<td class="n1">
			<input class="alpha" type="password" name="sy_enq_pwd" size=70 maxlength=100 <?=value($fetch->sy_enq_pwd)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">メールアンケート送信元名称</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_enq_mail" size=70 maxlength=100 <?=value($fetch->sy_enq_mail)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">Webアンケート送信元名称</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_enq_web" size=70 maxlength=100 <?=value($fetch->sy_enq_web)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■はいめーる用設定</td>
	</tr>
	<tr>
		<td class="m1">送信メールアドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_haimail_addr" size=70 maxlength=100 <?=value($fetch->sy_haimail_addr)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">メールアンケート送信元名称</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_haimail_mail" size=70 maxlength=100 <?=value($fetch->sy_haimail_mail)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">WEBアンケート送信元名称</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_haimail_web" size=70 maxlength=100 <?=value($fetch->sy_haimail_web)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">アンケート受信ドメイン</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_haimail_domain" size=70 maxlength=100 <?=value($fetch->sy_haimail_domain)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">アンケート受信アカウント</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_haimail_account" size=70 maxlength=100 <?=value($fetch->sy_haimail_account)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">アンケート受信パスワード</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_haimail_pwd" size=70 maxlength=100 <?=value($fetch->sy_haimail_pwd)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■Ｍｙモニターメール設定</td>
	</tr>
	<tr>
		<td class="m1">返信メール受信アカウント</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_mym_account" size=70 maxlength=100 <?=value($fetch->sy_mym_account)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">返信メール受信パスワード</td>
		<td class="n1">
			<input class="alpha" type="password" name="sy_mym_pwd" size=70 maxlength=100 <?=value($fetch->sy_mym_pwd)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">メンバー返信ポイント</td>
		<td class="n1">
			<input class="number" type="text" name="sy_mym_point" size=5 maxlength=5 <?=value($fetch->sy_mym_point)?>>
			<span class="note">（数字）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■Ｍｙパートナー設定</td>
	</tr>
	<tr>
		<td class="m1">リクルートメール最大発信数</td>
		<td class="n1">
			<input class="number" type="text" name="sy_myp_max_send" size=5 maxlength=5 <?=value($fetch->sy_myp_max_send)?>>
			<span class="note">（数字）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">リクルートジョブ最大質問数</td>
		<td class="n1">
			<input class="number" type="text" name="sy_myp_max_question" size=5 maxlength=5 <?=value($fetch->sy_myp_max_question)?>>
			<span class="note">（数字）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ICPプロジェクトID</td>
		<td class="n1">
			<input class="number" type="text" name="sy_myp_icp_pjt_id" size=5 maxlength=5 <?=value($fetch->sy_myp_icp_pjt_id)?>>
			<span class="note">（数字）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ICP組織ID</td>
		<td class="n1">
			<input class="number" type="text" name="sy_myp_icp_org_id" size=5 maxlength=5 <?=value($fetch->sy_myp_icp_org_id)?>>
			<span class="note">（数字）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">月ぎめポイント基準日数</td>
		<td class="n1">
			<input class="number" type="text" name="sy_myp_monthly_point" size=5 maxlength=5 <?=value($fetch->sy_myp_monthly_point)?>>
			<span class="note">（数字）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■ポイント失効設定</td>
	</tr>
	<tr>
		<td class="m1">ポイント失効期間</td>
		<td class="n1">
			<input class="number" type="text" name="sy_point_validity_period" size=5 maxlength=5 <?=value($fetch->sy_point_validity_period)?>>ヶ月
			<span class="note">（数字）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ポイント失効延期期間</td>
		<td class="n1">
			<input class="number" type="text" name="sy_point_extension" size=5 maxlength=5 <?=value($fetch->sy_point_extension)?>>ヶ月
			<span class="note">（数字）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ポイント更新キャンペーン期間</td>
		<td class="n1">
			<input class="number" type="text" name="sy_point_campaign_period1" size=12 maxlength=10 <?=value(format_date($fetch->sy_point_campaign_period1, ''))?>>
			〜
			<input class="number" type="text" name="sy_point_campaign_period2" size=12 maxlength=10 <?=value(format_date($fetch->sy_point_campaign_period2, ''))?>>
			<span class="note">（YYYY/MM/DD）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ポイント更新キャンペーン用の付与ポイント</td>
		<td class="n1">
			<input class="number" type="text" name="sy_point_campaign_point" size=5 maxlength=5 <?=value($fetch->sy_point_campaign_point)?>>
			<span class="note">（数字）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ポイント更新キャンペーンポイント付与済みフラグ</td>
		<td class="n1">
			<input type="checkbox" name="campaign_point_reset" <?=value($fetch->sy_point_campaign_point)?>>リセットする
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="sys_id" <?=value($sys_id)?>>
<input type="submit" value="　更新　">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
