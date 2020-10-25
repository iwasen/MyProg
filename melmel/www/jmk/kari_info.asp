<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:仮登録情報参照画面
'******************************************************

SetHeaderTitle "仮登録情報", TC_HAKKOUSHA
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
Dim DB, RS, SQL, seq_no

seq_no = Request.QueryString("id")
If IsEmpty(seq_no) Then
  Writeln "指定した仮登録ＩＤのデータがみつかりません。"
Else
  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")
  SQL = "SELECT * FROM T_PROVREG WHERE PR_seq_no=" & CLng(seq_no)
  RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
%>
<center>
<form>
  <table border=0 cellspacing=2 cellpadding=3 width="700">
    <tr>
      <td class="m1" width="23%">発行者名</td>
      <td class="n1"><%=RS("PR_h_name")%></td>
    </tr>
    <tr>
      <td class="m1" width="23%">e-mailアドレス</td>
      <td class="n1"><%=RS("PR_mail_adr")%></td>
    </tr>
    <tr>
      <td class="m1">メルマガ名</td>
      <td class="n1"><%=ReplaceTag(RS("PR_m_name"))%></td>
    </tr>
    <tr>
      <td class="m1">発行頻度</td>
      <td class="n1"><%=RS("PR_hindo")%></td>
    </tr>
    <tr>
      <td class="m1">発行部数</td>
      <td class="n1"><%=NumFormat(RS("PR_busuu"))%></td>
    </tr>
    <tr>
      <td class="m1">めろんぱんID</td>
      <td class="n1"><%=RS("PR_melonpan_id")%></td>
    </tr>
    <tr>
      <td class="m1">発行システム</td>
      <td class="n1">
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td class="n1c" width=130><input type="checkbox" name="mms_melonpan"<%=Checked(RS("PR_mms_melonpan"))%> onclick="return false">めろんぱん</td>
        <td class="n1c" width=130><input type="checkbox" name="mms_mag"<%=Checked(RS("PR_mms_mag"))%> onclick="return false">まぐまぐ</td>
        <td class="n1c" width=130><input type="checkbox" name="mms_pubz"<%=Checked(RS("PR_mms_pubz"))%> onclick="return false">Pubzine</td>
        <td class="n1c" width=130><input type="checkbox" name="mms_melma"<%=Checked(RS("PR_mms_melma"))%> onclick="return false">melma!</td>
        </tr>
        <tr>
        <td class="n1c" width=130><input type="checkbox" name="mms_macky"<%=Checked(RS("PR_mms_macky"))%> onclick="return false">Macky</td>
        <td class="n1c" width=130><input type="checkbox" name="mms_kapu"<%=Checked(RS("PR_mms_kapu"))%> onclick="return false">カプライト</td>
        <td class="n1c" width=130><input type="checkbox" name="mms_emag"<%=Checked(RS("PR_mms_emag"))%> onclick="return false">E-MAGAZINE</td>
        <td class="n1c" width=130><input type="checkbox" name="mms_tengoku"<%=Checked(RS("PR_mms_tengoku"))%> onclick="return false">メルマガ天国</td>
        </tr>
        <tr>
        <td class="n1c" width=130><input type="checkbox" name="mms_tiara"<%=Checked(RS("PR_mms_tiara"))%> onclick="return false">ティアラオンライン</td>
        <td class="n1c" width=130><input type="checkbox" name="mms_merubox"<%=Checked(RS("PR_mms_merubox"))%> onclick="return false">MERU-BOX</td>
        <td class="n1c" width=130><input type="checkbox" name="mms_dokuji"<%=Checked(RS("PR_mms_dokuji"))%> onclick="return false">独自配信</td>
        <td class="n1c" width=130><input type="checkbox" name="mms_other"<%=Checked(RS("PR_mms_other"))%> onclick="return false">その他</td>
        </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="m1">メルマガカテゴリ</td>
      <td class="n1">
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td class="n1c" width=170><input type="checkbox" name="cat_int"<%=Checked(RS("PR_cat_int"))%> onclick="return false">インターネット</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_pcc"<%=Checked(RS("PR_cat_pcc"))%> onclick="return false">パソコン/コンピュータ全般</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_soft"<%=Checked(RS("PR_cat_soft"))%> onclick="return false">ソフトウェア/プログラミング</td>
        </tr>
        <tr>
        <td class="n1c" width=170><input type="checkbox" name="cat_mvl"<%=Checked(RS("PR_cat_mvl"))%> onclick="return false">携帯/モバイル</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_bns"<%=Checked(RS("PR_cat_bns"))%> onclick="return false">ビジネス全般</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_seiji"<%=Checked(RS("PR_cat_seiji"))%> onclick="return false">政治/経済</td>
        </tr>
        <tr>
        <td class="n1c" width=170><input type="checkbox" name="cat_kabu"<%=Checked(RS("PR_cat_kabu"))%> onclick="return false">株式/投資</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_fsn"<%=Checked(RS("PR_cat_fsn"))%> onclick="return false">ファッション/美容</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_lif"<%=Checked(RS("PR_cat_lif"))%> onclick="return false">暮らし/日記</td>
        </tr>
        <tr>
        <td class="n1c" width=170><input type="checkbox" name="cat_shumi"<%=Checked(RS("PR_cat_shumi"))%> onclick="return false">趣味</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_res"<%=Checked(RS("PR_cat_res"))%> onclick="return false">グルメ/レストラン</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_tvl"<%=Checked(RS("PR_cat_tvl"))%> onclick="return false">旅行/地域</td>
        </tr>
        <tr>
        <td class="n1c" width=170><input type="checkbox" name="cat_spt"<%=Checked(RS("PR_cat_spt"))%> onclick="return false">スポーツ/レジャー</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_gamble"<%=Checked(RS("PR_cat_gamble"))%> onclick="return false">ギャンブル</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_hlt"<%=Checked(RS("PR_cat_hlt"))%> onclick="return false">健康/医療/育児</td>
        </tr>
        <tr>
        <td class="n1c" width=170><input type="checkbox" name="cat_art"<%=Checked(RS("PR_cat_art"))%> onclick="return false">アート/文芸</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_shp"<%=Checked(RS("PR_cat_shp"))%> onclick="return false">ショッピング</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_ent"<%=Checked(RS("PR_cat_ent"))%> onclick="return false">エンターテイメント</td>
        </tr>
        <tr>
        <td class="n1c" width=170><input type="checkbox" name="cat_game"<%=Checked(RS("PR_cat_game"))%> onclick="return false">ゲーム/クイズ</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_movie"<%=Checked(RS("PR_cat_movie"))%> onclick="return false">映画/音楽/芸能</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_uranai"<%=Checked(RS("PR_cat_uranai"))%> onclick="return false">占い</td>
        </tr>
        <tr>
        <td class="n1c" width=170><input type="checkbox" name="cat_std"<%=Checked(RS("PR_cat_std"))%> onclick="return false">学習/教育</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_shikaku"<%=Checked(RS("PR_cat_shikaku"))%> onclick="return false">資格</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_gogaku"<%=Checked(RS("PR_cat_gogaku"))%> onclick="return false">語学</td>
        </tr>
        <tr>
        <td class="n1c" width=170><input type="checkbox" name="cat_nws"<%=Checked(RS("PR_cat_nws"))%> onclick="return false">ニュース</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_zatsu"<%=Checked(RS("PR_cat_zatsu"))%> onclick="return false">雑学</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_kenshou"<%=Checked(RS("PR_cat_kenshou"))%> onclick="return false">懸賞/プレゼント</td>
        </tr>
        <tr>
        <td class="n1c" width=170><input type="checkbox" name="cat_homepage"<%=Checked(RS("PR_cat_homepage"))%> onclick="return false">ホームページ紹介</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_hitokoto"<%=Checked(RS("PR_cat_hitokoto"))%> onclick="return false">一日ひとこと系</td>
        <td class="n1c" width=170><input type="checkbox" name="cat_other"<%=Checked(RS("PR_cat_other"))%> onclick="return false">その他</td>
        </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="m1">読者層（年代）</td>
      <td class="n1">
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td class="n1c" width=90><input type="checkbox" name="age_10m"<%=Checked(RS("PR_age_10m"))%> onclick="return false">10代男</td>
        <td class="n1c" width=90><input type="checkbox" name="age_20m"<%=Checked(RS("PR_age_20m"))%> onclick="return false">20代男</td>
        <td class="n1c" width=90><input type="checkbox" name="age_30m"<%=Checked(RS("PR_age_30m"))%> onclick="return false">30代男</td>
        <td class="n1c" width=90><input type="checkbox" name="age_40m"<%=Checked(RS("PR_age_40m"))%> onclick="return false">40代男</td>
        <td class="n1c" width=90><input type="checkbox" name="age_50m"<%=Checked(RS("PR_age_50m"))%> onclick="return false">50代以上男</td>
        </tr>
        <tr>
        <td class="n1c" width=90><input type="checkbox" name="age_10f"<%=Checked(RS("PR_age_10f"))%> onclick="return false">10代女</td>
        <td class="n1c" width=90><input type="checkbox" name="age_20f"<%=Checked(RS("PR_age_20f"))%> onclick="return false">20代女</td>
        <td class="n1c" width=90><input type="checkbox" name="age_30f"<%=Checked(RS("PR_age_30f"))%> onclick="return false">30代女</td>
        <td class="n1c" width=90><input type="checkbox" name="age_40f"<%=Checked(RS("PR_age_40f"))%> onclick="return false">40代女</td>
        <td class="n1c" width=90><input type="checkbox" name="age_50f"<%=Checked(RS("PR_age_50f"))%> onclick="return false">50代以上女</td>
        </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="m1">読者層（職業）</td>
      <td class="n1">
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td class="n1c" width=100><input type="checkbox" name="shk_stu"<%=Checked(RS("PR_shk_stu"))%> onclick="return false">学生</td>
        <td class="n1c" width=100><input type="checkbox" name="shk_enp"<%=Checked(RS("PR_shk_enp"))%> onclick="return false">社会人</td>
        <td class="n1c" width=100><input type="checkbox" name="shk_syf"<%=Checked(RS("PR_shk_syf"))%> onclick="return false">主婦</td>
        <td class="n1c" width=100><input type="checkbox" name="shk_other"<%=Checked(RS("PR_shk_other"))%> onclick="return false">その他</td>
        </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="m1">読者層（エリア）</td>
      <td class="n1">
        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td class="n1c" width=100><input type="checkbox" name="are_ht"<%=Checked(RS("PR_are_ht"))%> onclick="return false">北海道/東北</td>
        <td class="n1c" width=100><input type="checkbox" name="are_kt"<%=Checked(RS("PR_are_kt"))%> onclick="return false">関東</td>
        <td class="n1c" width=100><input type="checkbox" name="are_cb"<%=Checked(RS("PR_are_cb"))%> onclick="return false">中部</td>
        <td class="n1c" width=100><input type="checkbox" name="are_kk"<%=Checked(RS("PR_are_kk"))%> onclick="return false">近畿</td>
        </tr>
        <tr>
        <td class="n1c" width=100><input type="checkbox" name="are_cs"<%=Checked(RS("PR_are_cs"))%> onclick="return false">中国/四国</td>
        <td class="n1c" width=100><input type="checkbox" name="are_ks"<%=Checked(RS("PR_are_ks"))%> onclick="return false">九州</td>
        <td class="n1c" width=100><input type="checkbox" name="are_fr"<%=Checked(RS("PR_are_fr"))%> onclick="return false">海外</td>
        <td class="n1c" width=100><input type="checkbox" name="are_zk"<%=Checked(RS("PR_are_zk"))%> onclick="return false">全国</td>
        </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="m1" width="23%">登録日</td>
      <td class="n1"><%=DateFormat(RS("PR_regist_date"))%></td>
    </tr>
    <tr>
      <td class="m1" width="23%">パスワード</td>
      <td class="n1"><%=RS("PR_password")%></td>
    </tr>
  </table>
</form>
</center>

<%
  RS.Close
End If
%>

</body>
</html>
