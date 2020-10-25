<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:処理メニュー画面
'******************************************************

SetHeaderTitle "処理メニュー", "darkblue"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
<style type="text/css">
<!--
TH.m1
{
    BACKGROUND-COLOR: #<%=TC_HAKKOUSHA%>;
    COLOR: white;
    FONT-WEIGHT: bolder
}
TH.m2
{
    BACKGROUND-COLOR: #<%=TC_KOUKOKU%>;
    COLOR: white;
    FONT-WEIGHT: bolder
}
TH.m3
{
    BACKGROUND-COLOR: #<%=TC_MASTER%>;
    COLOR: white;
    FONT-WEIGHT: bolder
}
TH.m4
{
    BACKGROUND-COLOR: #<%=TC_OTHER%>;
    COLOR: white;
    FONT-WEIGHT: bolder
}
TD.n1
{
    BACKGROUND-COLOR: #fff0f8;
    padding-left: 10px;
}
TD.n2
{
    BACKGROUND-COLOR: #f0fff8;
    padding-left: 10px;
}
TD.n3
{
    BACKGROUND-COLOR: #fff8f0;
    padding-left: 10px;
}
TD.n4
{
    BACKGROUND-COLOR: #eeeeee;
    padding-left: 10px;
}
-->
</style>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<table align="center" border=0 cellpadding=8 cellspacing=0 width="75%">
  <tr>
    <td>
      <table align="center" border=1 cellpadding=2 cellspacing=1 width="100%">
        <tr><th class="m1" colspan=3 align="left">■発行者・メルマガ管理</th></tr>
        <tr>
          <td class="n1" width="33%"><A href="kari_koudoku.asp">仮登録メルマガ購読手続き</A></td>
          <td class="n1" width="33%"><A href="kari_print.asp">仮登録メルマガ印刷</A></td>
          <td class="n1" width="33%"><A href="shinsa.asp">仮登録審査結果処理</A></td>
        </tr>
        <tr>
          <td class="n1" width="33%"><A href="init_check.asp">本登録初期チェック</A></td>
          <td class="n1"><A href="random_output.asp">ランダムアウトプット</A></td>
          <td class="n1"><A href="cancel.asp">本登録解除処理</A></td>
        </tr>
        <tr>
          <td class="n1"><A href="busuu_check.asp">自己申告部数チェック</A></td>
          <td class="n1"><A href="seisan.asp">精算要求リスト</A></td>
          <td class="n1"><A href="h_mail.asp">発行者宛てメール</A></td>
        </tr>
        <tr>
          <td class="n1"><A href="point.asp">ポイント管理</A></td>
          <td class="n1"><A href="merge.asp">発行者ID統合</A></td>
          <td class="n1"><br></A></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table align="center" border=1 cellpadding=2 cellspacing=1 width="100%">
        <tr><th class="m2" colspan=3 align="left">■広告入稿・掲載依頼</th></tr>
        <tr>
          <td class="n2" width="33%"><A href="akiwaku.asp">空き枠状況確認</A></td>
          <td class="n2" width="33%"><A href="yoyaku.asp">予約</A></td>
          <td class="n2" width="33%"><A href="nyuukou.asp">広告入稿</A></td>
        </tr>
        <tr>
          <td class="n2"><A href="shukkou.asp">出稿処理</A></td>
          <td class="n2"><A href="sokuhou.asp">掲載状況速報</A></td>
          <td class="n2"><A href="keisai_check.asp">掲載状況チェック</A></td>
        </tr>
        <tr>
          <td class="n2"><A href="tsuika.asp">追加依頼</A></td>
          <td class="n2"><A href="report.asp">掲載実績報告書作成</A></td>
          <td class="n2"><A href="cc_koukoku.asp">クリック数表示</A></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table align="center" border=1 cellpadding=2 cellspacing=1 width="100%">
        <tr><th class="m3" colspan=3 align="left">■マスタメンテナンス</th></tr>
        <tr>
          <td class="n3" width="33%"><a href="mel_master.asp">発行者マスタ</a></td>
          <td class="n3" width="33%"><a href="mag_master.asp">メルマガマスタ</a></td>
          <td class="n3" width="33%"><a href="koukoku_master.asp">広告マスタ</a></td>
        </tr>
        <tr>
          <td class="n3"><a href="keisen_master.asp">広告罫線マスタ</a></td>
          <td class="n3"><a href="control_master.asp">コントロールマスタ</a></td>
          <td class="n3"><a href="mailtemp_master.asp">送信メールテンプレート</a></td>
        </tr>
        <tr>
          <td class="n3"><a href="client_master.asp">クライアントマスタ</a></td>
          <td class="n3"><br></td>
          <td class="n3"><br></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table align="center" border=1 cellpadding=2 cellspacing=1 width="100%">
        <tr><th class="m4" colspan=3 align="left">■その他</th></tr>
        <tr>
          <td class="n4" width="33%"><a href="auto_log.asp">自動処理ログ表示</a></td>
          <td class="n4" width="33%"><a href="kanri_suuji.asp">最新管理数字表示</a></td>
          <td class="n4" width="33%"><br></td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
