<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:広告内容確認画面
'******************************************************

SetHeaderTitle "広告内容確認", TC_KOUKOKU
Response.CacheControl = "no-cache"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
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
  <table border=0 cellspacing=0 cellpadding=0 width='800'>
    <tr>
      <td>■広告依頼主情報</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width='100%'>
          <tr>
            <td class="m2" width="20%">クライアントID</td>
            <td class="n2"><%=RS("KK_client_id")%></td>
          </tr>
          <tr>
            <td class="m2">広告依頼主名</td>
            <td class="n2"><%=ReplaceTag(RS("KK_client_name"))%></td>
          </tr>
          <tr>
            <td class="m2">組織名</td>
            <td class="n2"><%=ReplaceTag(RS("KK_company"))%></td>
          </tr>
          <tr>
            <td class="m2">担当者名</td>
            <td class="n2"><%=ReplaceTag(RS("KK_tantousha"))%></td>
          </tr>
          <tr>
            <td class="m2">連絡先ﾒｰﾙｱﾄﾞﾚｽ</td>
            <td class="n2"><%=ReplaceTag(RS("KK_mail_adr"))%></td>
          </tr>
          <tr>
            <td class="m2">連絡先電話番号</td>
            <td class="n2"><%=RS("KK_tel_no")%></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td>■広告情報</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width='100%'>
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
					</tr>
          <tr>
            <td class="m2">発行部数</td>
            <td class="n2"><%=NumFormat(RS("KK_busuu"))%></td>
          </tr>
          <tr>
            <td class="m2">掲載依頼部数</td>
            <td class="n2"><%=RS("KK_keisai_irai")%></td>
          </tr>
          <tr>
            <td class="m2" width=160>広告タイプ</td>
            <td class="n2">タイプ<%=RS("KK_koukoku_type")%>-<%=RS("KK_koukoku_line")%>行</td>
          </tr>
          <tr>
            <td class="m2">広告文</td>
            <td class="n2"><textarea name="koukoku_msg" cols=80 rows=<%=RS("KK_koukoku_line")%> class="np" readonly><%=RS("KK_koukoku_msg")%></textarea></td>
          </tr>
          <tr>
            <td class="m2">広告URL</td>
            <td class="n2"><a href="<%=RS("KK_koukoku_url")%>" target="koukoku_url"><%=ReplaceTag(RS("KK_koukoku_url"))%></a></td>
          </tr>
          <tr>
            <td class="m2">発行システム</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_melonpan"))%> onclick="return false">めろんぱん</td>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_mag"))%> onclick="return false">まぐまぐ</td>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_pubz"))%> onclick="return false">Pubzine</td>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_melma"))%> onclick="return false">melma!</td>
              </tr>
              <tr>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_macky"))%> onclick="return false">Macky</td>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_kapu"))%> onclick="return false">カプライト</td>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_emag"))%> onclick="return false">E-MAGAZINE</td>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_tengoku"))%> onclick="return false">メルマガ天国</td>
              </tr>
              <tr>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_tiara"))%> onclick="return false">ティアラオンライン</td>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_merubox"))%> onclick="return false">MERU-BOX</td>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_dokuji"))%> onclick="return false">独自配信</td>
              <td class="n2c" width=130><input type="checkbox"<%=Checked(RS("KK_mms_other"))%> onclick="return false">その他</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">メルマガカテゴリ</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_int"))%> onclick="return false">インターネット</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_pcc"))%> onclick="return false">パソコン/コンピュータ全般</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_soft"))%> onclick="return false">ソフトウェア/プログラミング</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_mvl"))%> onclick="return false">携帯/モバイル</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_bns"))%> onclick="return false">ビジネス全般</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_seiji"))%> onclick="return false">政治/経済</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_kabu"))%> onclick="return false">株式/投資</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_fsn"))%> onclick="return false">ファッション/美容</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_lif"))%> onclick="return false">暮らし/日記</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_shumi"))%> onclick="return false">趣味</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_res"))%> onclick="return false">グルメ/レストラン</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_tvl"))%> onclick="return false">旅行/地域</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_spt"))%> onclick="return false">スポーツ/レジャー</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_gamble"))%> onclick="return false">ギャンブル</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_hlt"))%> onclick="return false">健康/医療/育児</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_art"))%> onclick="return false">アート/文芸</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_shp"))%> onclick="return false">ショッピング</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_ent"))%> onclick="return false">エンターテイメント</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_game"))%> onclick="return false">ゲーム/クイズ</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_movie"))%> onclick="return false">映画/音楽/芸能</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_uranai"))%> onclick="return false">占い</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_std"))%> onclick="return false">学習/教育</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_shikaku"))%> onclick="return false">資格</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_gogaku"))%> onclick="return false">語学</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_nws"))%> onclick="return false">ニュース</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_zatsu"))%> onclick="return false">雑学</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_kenshou"))%> onclick="return false">懸賞/プレゼント</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_homepage"))%> onclick="return false">ホームページ紹介</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_hitokoto"))%> onclick="return false">一日ひとこと系</td>
              <td class="n2c" width=170><input type="checkbox" <%=Checked(RS("KK_cat_other"))%> onclick="return false">その他</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">読者層（年代）</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=90><input type="checkbox" <%=Checked(RS("KK_age_10m"))%> onclick="return false">10代男</td>
              <td class="n2c" width=90><input type="checkbox" <%=Checked(RS("KK_age_20m"))%> onclick="return false">20代男</td>
              <td class="n2c" width=90><input type="checkbox" <%=Checked(RS("KK_age_30m"))%> onclick="return false">30代男</td>
              <td class="n2c" width=90><input type="checkbox" <%=Checked(RS("KK_age_40m"))%> onclick="return false">40代男</td>
              <td class="n2c" width=90><input type="checkbox" <%=Checked(RS("KK_age_50m"))%> onclick="return false">50代以上男</td>
              </tr>
              <tr>
              <td class="n2c" width=90><input type="checkbox" <%=Checked(RS("KK_age_10f"))%> onclick="return false">10代女</td>
              <td class="n2c" width=90><input type="checkbox" <%=Checked(RS("KK_age_20f"))%> onclick="return false">20代女</td>
              <td class="n2c" width=90><input type="checkbox" <%=Checked(RS("KK_age_30f"))%> onclick="return false">30代女</td>
              <td class="n2c" width=90><input type="checkbox" <%=Checked(RS("KK_age_40f"))%> onclick="return false">40代女</td>
              <td class="n2c" width=90><input type="checkbox" <%=Checked(RS("KK_age_50f"))%> onclick="return false">50代以上女</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">読者層（職業）</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_shk_stu"))%> onclick="return false">学生</td>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_shk_enp"))%> onclick="return false">社会人</td>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_shk_syf"))%> onclick="return false">主婦</td>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_shk_other"))%> onclick="return false">その他</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">読者層（エリア）</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_are_ht"))%> onclick="return false">北海道/東北</td>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_are_kt"))%> onclick="return false">関東</td>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_are_cb"))%> onclick="return false">中部</td>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_are_kk"))%> onclick="return false">近畿</td>
              </tr>
              <tr>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_are_cs"))%> onclick="return false">中国/四国</td>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_are_ks"))%> onclick="return false">九州</td>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_are_fr"))%> onclick="return false">海外</td>
              <td class="n2c" width=100><input type="checkbox" <%=Checked(RS("KK_are_zk"))%> onclick="return false">全国</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">依頼除外広告ID</td>
            <td class="n2"><%=RS("KK_jogai_id")%></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</center>
<%
End If
RS.Close
%>
</body>
</html>
