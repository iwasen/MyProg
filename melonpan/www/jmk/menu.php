<?php
include ("inc/header_jmk.php");
include ("inc/footer_jmk.php");
include ("inc/common.php");
include ("inc/database_inc.php");
/******************************************************
' System めろんぱん事務局用ページ
' Content:処理メニュー画面
'******************************************************/

$title_text = "めろんぱん事務局メニュー";
$title_color = "#9fc741";

if ($cookie_admin_id == '')
	redirect('login.php');

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><? echo ("$title_text") ?></title>
<link rel="stylesheet" type="text/css" href="css/melonpan.css">
<style type="text/css">
<!--
TH.m1
{
    BACKGROUND-COLOR: #aa4488;
    COLOR: white;
    FONT-WEIGHT: bolder
}
TH.m2
{
    BACKGROUND-COLOR: #44aa88;
    COLOR: white;
    FONT-WEIGHT: bolder
}
TH.m3
{
    BACKGROUND-COLOR: #aa8844;
    COLOR: white;
    FONT-WEIGHT: bolder
}
TH.m4
{
    BACKGROUND-COLOR: #9f8fc0;
    COLOR: white;
    FONT-WEIGHT: bolder
}
TH.m5
{
    BACKGROUND-COLOR: #888888;
    COLOR: white;
    FONT-WEIGHT: bolder
}

-->
</style>
</head>
<body>

<? header_jmk($title_text,$title_color,2); ?>

<table align="center" border=0 cellpadding=8 cellspacing=0 width="85%">
  <tr>
    <td>
      <table align="center" border=1 cellpadding=2 cellspacing=0 width="100%">
        <tr><th class="m1" colspan=3 align="left">■発行者・メルマガ管理</th></tr>
        <tr>
          <td width="33%"><A href="kari_touroku/kari_touroku1.php"><nobr>仮登録承認処理</nobr></a></td>
          <td width="33%"><A href="hon_touroku/hon_touroku1.php"><nobr>本登録承認処理</nobr></a></td>
          <td width="33%"><A href="shounin_list/shounin_list.php"><nobr>承認済みメルマガ一覧</nobr></A></td>
        </tr>
        <tr>
          <td><A href="teishi/teishi1.php"><nobr>メルマガ発行停止作業</nobr></a></td>
          <td><A href="riba_regist/index.php"><nobr>りばっチャ登録</nobr></a></td>
          <td><A href="hinsitsu/index.php"><nobr>品質チェック</nobr></a></td>
        </tr>
        <tr>
          <td><A href="redcard_list/redcard_list.php"><nobr>過去のレッドカード一覧</nobr></a></td>
          <td><a href="pub_msg/pub_msg1.php"><nobr>「事務局からのお知らせ」入力</nobr></a></td>
          <td><A href="hakkousha_mainte/hakkousha_mainte1.php"><nobr>発行者情報メンテナンス</nobr></a></td>
        </tr>
        <tr>
          <td><A href="letter_hakkousha/letter_hakkousha.php"><nobr>発行者宛てメール</nobr></A></td>
          <td><br></td>
          <td><br></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table align="center" border=1 cellpadding=2 cellspacing=0 width="100%">
        <tr><th class="m2" colspan=3 align="left">■読者・メルマガナビ管理</nobr></a></th></tr>
        <tr>
          <td width="33%"><A href="dokusha_mainte/dokusha_mainte1.php"><nobr>読者情報メンテナンス</nobr></a></td>
          <td width="33%"><A href="dokusha_ikkatsu/dokusha_ikkatsu1.php"><nobr>読者一括登録/削除</nobr></a></td>
          <td width="33%"><A href="addr_search/addr_search1.php"><nobr>読者検索/代理解除</nobr></A></td>
        </tr>
        <tr>
          <td><A href="disabled_adr/index.php"><nobr>購読禁止メールアドレス登録</nobr></a></td>
          <td><br></td>
          <td><br></td>
        </tr>
        <tr>
          <td><A href="melonpai_regist/index.php"><nobr>メルマガナビ登録</nobr></a></td>
          <td><A href="melonpai_kannri/melonpai_kannri1.php"><nobr>メルマガナビ管理</nobr></a></td>
          <td><A href="max_setting/index.php"><nobr>メルマガナビ上限設定</nobr></a></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table align="center" border=1 cellpadding=2 cellspacing=0 width="100%">
        <tr><th class="m3" colspan=3 align="left">■事務局メンテナンス</th></tr>
        <tr>
          <td width="33%"><A href="letter_melonpan/index.php"><nobr>めろんぱんレター配信</nobr></a></td>
          <td width="33%"><A href="letter_bakyun/index.php"><nobr>バ･キューン配信</nobr></a></td>
          <td width="33%"><A href="letter_tsuushin/index.php"><nobr>めろんぱん通信配信</nobr></a></td>
				</tr>
				<tr>
          <td width="33%"><A href="haishin_log/index.php"><nobr>事務局メール配信ログ</nobr></a></td>
          <td width="33%"><A href="hitorigoto/index.php"><nobr>「今日のひとりごと」入力</nobr></a></td>
          <td><A href="whats_new/index.php"><nobr>「What's new」入力</nobr></a></td>
        </tr>
        <tr>
          <td><A href="footer/index.php"><nobr>ヘッダー／フッターデータ登録</nobr></a></td>
          <td><a href="haishin_yoyaku/haishin_list.php"><nobr>配信予約一覧</nobr></a></td>
          <td><a href="kanri_suuji/kanri_suuji1.php"><nobr>管理数字出力</nobr></a></td>
        </tr>
        <tr>
					<td><A href="err_addr_edit/index.php"><nobr>不正アドレス修正</nobr></a></td>
					<td><A href="osusume_log/index.php"><nobr>オススメログ一覧</nobr></a></td>
          <td><a href="henkou_list/index.php"><nobr>登録情報変更一覧出力</nobr></a></td>
        </tr>
        <tr>
					<td><a href="add_point/index.php"><nobr>ポイント付与</nobr></a></td>
					<td><a href="point_list/index.php"><nobr>ポイント状況閲覧</nobr></a></td>
					<td><a href="letter_html/index.php"><nobr>めろんぱんHTMLレター</nobr></a></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table align="center" border=1 cellpadding=2 cellspacing=0 width="100%">
        <tr><th class="m4" colspan=3 align="left">■マスタメンテナンス</th></tr>
          <td width="33%"><a href="mail_template/index.php "><nobr>送信メールテンプレート</nobr></a></td>
          <td width="33%"><a href="batch_schedule/index.php "><nobr>バッチスケジュール</nobr></a></td>
          <td width="33%"><br></td>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table align="center" border=1 cellpadding=2 cellspacing=0 width="100%">
        <tr><th class="m5" colspan=3 align="left">■その他</th></tr>
          <td width="33%"><a href="present/index.php"><nobr>プレゼント応募管理</nobr></a></td>
          <td width="33%"><a href="cleaning/index.php"><nobr>メールクリーニングサービス</nobr></a></td>
          <td width="33%"><a href="banner_set/index.php"><nobr>広告バナー設定</nobr></a></td>
				</tr>
        <tr>
          <td width="33%"><a href="click/index.php "><nobr>広告クリックカウンタ</nobr></a></td>
          <td width="33%"><a href="server_mainte/index.php">配信予約禁止設定</a></td>
          <td width="33%"><a href="tassei_mail/index.php">読者達成メール設定</a></td>
				</tr>
      </table>
    </td>
  </tr>
</table>

<? footer_jmk(2); ?>

</body>
</html>
