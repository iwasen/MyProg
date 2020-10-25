<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
'******************************************************
' System :めるめる・net広告主向けページ
' Content:広告内容確認画面
'******************************************************

Response.CacheControl = "no-cache"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title>広告内容確認</title>
<LINK rel="stylesheet" href="../melmel.css">
<link rel="stylesheet" type="text/css" href="client.css">
</head>
<body>

<%
Dim DB, RS, SQL

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")

SQL = "SELECT *" & vbCrLf & _
      "FROM V_KOUKOKU" & vbCrLf & _
      "WHERE KK_koukoku_id='" & Request.QueryString("id") & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

If Not RS.EOF Then
%>
<center>
<form>
  <table border=0 cellspacing=0 cellpadding=0 width='650' class="kihon">
    <tr>
      <td>■広告情報</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width='100%' class="kihon">
          <tr>
            <td class="m2" width="22%">広告ID</td>
            <td class="n2"><%=RS("KK_koukoku_id")%></td>
          </tr>
          <tr>
            <td class="m2">広告名</td>
            <td class="n2"><%=ReplaceTag(RS("KK_koukoku_name"))%></td>
          </tr>
          <tr>
            <td class="m2">広告掲載週</td>
            <td class="n2"><%=RS("KK_koukoku_shuu")%></td>
          </tr>
          <tr>
            <td class="m2">配信開始日</td>
            <td class="n2"><%=DateFormat(RS("KK_pub_start"))%></td>
          </tr>
          <tr>
            <td class="m2">配信終了日</td>
            <td class="n2"><%=DateFormat(RS("KK_pub_end"))%></td>
          </tr>
          <tr>
            <td class="m2" width=160>報告期限</td>
            <td class="n2"><%=DateFormat(RS("KK_limit_date"))%></td>
          <tr>
            <td class="m2">発行部数</td>
            <td class="n2"><%=NumFormat(RS("KK_busuu"))%></td>
          </tr>
          <tr>
            <td class="m2" width=160>広告タイプ</td>
            <td class="n2">タイプ<%=RS("KK_koukoku_type")%>-<%=RS("KK_koukoku_line")%>行</td>
          </tr>
          <tr>
            <td class="m2">広告文</td>
            <td class="n2"><font class="np"><%=ReplaceTag(RS("KK_koukoku_msg"))%></font></td>
          </tr>
          <tr>
            <td class="m2">発行システム</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_melonpan"))%>>めろんぱん</td>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_mag"))%>>まぐまぐ</td>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_pubz"))%>>Pubzine</td>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_melma"))%>>melma!</td>
              </tr>
              <tr>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_macky"))%>>Macky</td>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_kapu"))%>>カプライト</td>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_emag"))%>>E-MAGAZINE</td>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_tengoku"))%>>メルマガ天国</td>
              </tr>
              <tr>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_tiara"))%>>ティアラオンライン</td>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_merubox"))%>>MERU-BOX</td>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_dokuji"))%>>独自配信</td>
              <td class="n2c" width=130><input type="checkbox" <%=Checked(RS("KK_mms_other"))%>>その他</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">メルマガカテゴリ</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_int"))%>>インターネット</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_pcc"))%>>パソコン/コンピュータ全般</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_soft"))%>>ソフトウェア/プログラミング</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_mvl"))%>>携帯/モバイル</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_bns"))%>>ビジネス全般</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_seiji"))%>>政治/経済</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_kabu"))%>>株式/投資</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_fsn"))%>>ファッション/美容</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_lif"))%>>暮らし/日記</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_shumi"))%>>趣味</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_res"))%>>グルメ/レストラン</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_tvl"))%>>旅行/地域</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_spt"))%>>スポーツ/レジャー</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_gamble"))%>>ギャンブル</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_hlt"))%>>健康/医療/育児</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_art"))%>>アート/文芸</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_shp"))%>>ショッピング</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_ent"))%>>エンターテイメント</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_game"))%>>ゲーム/クイズ</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_movie"))%>>映画/音楽/芸能</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_uranai"))%>>占い</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_std"))%>>学習/教育</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_shikaku"))%>>資格</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_gogaku"))%>>語学</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_nws"))%>>ニュース</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_zatsu"))%>>雑学</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_kenshou"))%>>懸賞/プレゼント</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_homepage"))%>>ホームページ紹介</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_hitokoto"))%>>一日ひとこと系</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_other"))%>>その他</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">読者層（年代）</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=80><input type="checkbox"<%=Checked(RS("KK_age_10m"))%>>10代男</td>
              <td class="n2c" width=80><input type="checkbox"<%=Checked(RS("KK_age_20m"))%>>20代男</td>
              <td class="n2c" width=80><input type="checkbox"<%=Checked(RS("KK_age_30m"))%>>30代男</td>
              <td class="n2c" width=80><input type="checkbox"<%=Checked(RS("KK_age_40m"))%>>40代男</td>
              <td class="n2c" width=80><input type="checkbox"<%=Checked(RS("KK_age_50m"))%>>50代以上男</td>
              </tr>
              <tr>
              <td class="n2c" width=80><input type="checkbox"<%=Checked(RS("KK_age_10f"))%>>10代女</td>
              <td class="n2c" width=80><input type="checkbox"<%=Checked(RS("KK_age_20f"))%>>20代女</td>
              <td class="n2c" width=80><input type="checkbox"<%=Checked(RS("KK_age_30f"))%>>30代女</td>
              <td class="n2c" width=80><input type="checkbox"<%=Checked(RS("KK_age_40f"))%>>40代女</td>
              <td class="n2c" width=80><input type="checkbox"<%=Checked(RS("KK_age_50f"))%>>50代以上女</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">読者層（職業）</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_shk_stu"))%>>学生</td>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_shk_enp"))%>>社会人</td>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_shk_syf"))%>>主婦</td>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_shk_other"))%>>その他</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">読者層（エリア）</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_are_ht"))%>>北海道/東北</td>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_are_kt"))%>>関東</td>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_are_cb"))%>>中部</td>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_are_kk"))%>>近畿</td>
              </tr>
              <tr>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_are_cs"))%>>中国/四国</td>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_are_ks"))%>>九州</td>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_are_fr"))%>>海外</td>
              <td class="n2c" width=100><input type="checkbox"<%=Checked(RS("KK_are_zk"))%>>全国</td>
              </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
</center>
<%
End If
RS.Close
%>
</body>
</html>
