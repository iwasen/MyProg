package base;

import java.util.*;
import java.util.Date;
import java.text.*;
import java.sql.*;
import javax.servlet.http.*;

/**
 * タイトル:選択条件格納クラス
 * 説明:テーブルの選択条件を格納しておく
 * 著作権:   Copyright (c) 2003
 * 会社名:
 * @author
 * @version 1.0
 */

public class SelectCondBean {

  private String corner;
  private String category;
  private String table;
  private String cornerdepth;
  private String[] attribute;
  private String order;
  private String page;
  private String group;
  private String menu;

  private String monthFromY;
  private String monthFromM;
  private String monthToY;
  private String monthToM;

  private String weekFromY;
  private String weekFromW;
  private String weekToY;
  private String weekToW;

  private String dayFromY;
  private String dayFromM;
  private String dayFromD;
  private String dayToY;
  private String dayToM;
  private String dayToD;

  private String hourFromY;
  private String hourFromM;
  private String hourFromD;
  private String hourFromH;
  private String hourToY;
  private String hourToM;
  private String hourToD;
  private String hourToH;

  private int pageNo;
  private int pageNo2;
  private int mode;
  private String subMode;
  private String targetCorner;
  private String moveFromCorner;
  private String moveToCorner;

  private String[] streamParentCorner;
  private String[] streamSelectCorner;
  private String[] streamUrl;
  private String streamErrMsg;
  private String streamDispLevel;
  private String streamTableID;
  private boolean streamRecycle;
  private int streamCounting;

  public final static int modeCondition = 2;
  public final static int modeResult = 1;

  // コンストラクタ
  public SelectCondBean(DataBase db) throws SQLException {
    initCondition(db);
  }

  // 検索条件初期化
  public void initCondition(DataBase db) throws SQLException {
    ResultSet rs = db.getContrxxxaster();

    corner = "/";
    cornerdepth = "1";
    category = "/";
    pageNo = 0;
    pageNo2 = 0;
    targetCorner = "";
    moveFromCorner = "";
    moveToCorner = "";
    order = rs.getString("MCR_DEFAULTVORDER_CLS").equals("A") ? "ALLCOUNT" : "SORT";
    attribute = new String []{"0", "0", "0", "0", "0"};
    streamParentCorner = new String []{"/", "/", "/", "/", "/"};
    streamSelectCorner = new String []{"", "", "", "", ""};
    streamUrl = new String []{"", "", "", "", ""};
    streamDispLevel = "6";
    streamTableID = "";
    streamRecycle = false;
    streamCounting = 0;

    // 期間（月）の年月
    monthFromY = "";
    monthFromM = "";
    monthToY = "";
    monthToM = "";

    // 期間（週）の年週
    weekFromY = "";
    weekFromW = "";
    weekToY = "";
    weekToW = "";

    // 期間（日）の年月日
    dayFromY = "";
    dayFromM = "";
    dayFromD = "";
    dayToY = "";
    dayToM = "";
    dayToD = "";

    // 期間（時）の年月日時
    hourFromY = "";
    hourFromM = "";
    hourFromD = "";
    hourFromH = "";
    hourToY = "";
    hourToM = "";
    hourToD = "";
    hourToH = "";
  }

  // 既定値で表示する場合の条件設定
  public void setDefaultCondition(DataBase db) throws SQLException {
    table = "DATE";

    Statement st = db.createStatement();

    // コントロールマスタから最新集計日付と既定値の日数を取得
    ResultSet rs = db.getContrxxxaster();
    GregorianCalendar cal = new GregorianCalendar();
    cal.setTime(rs.getDate("MCR_DATETO_DATE"));

    // 既定の期間終了日時
    dayToY = String.valueOf(cal.get(Calendar.YEAR));
    dayToM = CommonFunc.numToString(cal.get(Calendar.MONTH) + 1);
    dayToD = CommonFunc.numToString(cal.get(Calendar.DATE));

    // 既定の期間開始日時
    cal.add(Calendar.DATE, -rs.getInt("MCR_DEFAULTGETDAYS_NUM"));
    dayFromY = String.valueOf(cal.get(Calendar.YEAR));
    dayFromM = CommonFunc.numToString(cal.get(Calendar.MONTH) + 1);
    dayFromD = CommonFunc.numToString(cal.get(Calendar.DATE));
  }

  // コーナーコード設定
  public void setCorner(String corner) {
    if (corner != null && corner.length() != 0)
      this.corner = corner;
  }

  // コーナーコード取得
  public String getCorner() {
    return corner;
  }

  // カテゴリコード設定
  public void setCategory(String category) {
    if (category != null && category.length() != 0)
      this.category = category;
  }

  // カテゴリコード取得
  public String getCategory() {
    return category;
  }

  // カテゴリコード取得（なぜかjspから呼び出すときに必要）
  public String getcategory() {
    return category;
  }

  // 集計テーブル種別設定
  public void setTable(String table) {
    if (table != null && table.length() != 0)
      this.table = table;
  }

  // 集計テーブル種別取得
  public String getTable() {
    return table;
  }

  // コーナー階層設定
  public void setCornerdepth(String cornerdepth) {
    if (cornerdepth != null && cornerdepth.length() != 0)
      this.cornerdepth = cornerdepth;
  }

  // コーナー階層取得
  public String getCornerdepth() {
    return cornerdepth;
  }

  // 企業属性設定
  public void setAttribute(int n, String attribute) {
    if (attribute != null && attribute.length() != 0)
      this.attribute[n] = attribute;
  }

  // 企業属性配列取得
  public String[] getAttribute() {
   return attribute;
  }

  // 企業属性配列取得
  public String getAttributeValue(int i) {
    return attribute[i];
  }

  // 企業属性コードを"/"区切りで全て取得
  public String getAttributeCode() {
    StringBuffer strbuf = new StringBuffer();
    for (int i = 0; i < attribute.length; i++) {
      if (i != 0)
        strbuf.append("/");
      int index = attribute[i].indexOf(':');
      if (index == -1)
        strbuf.append(attribute[i]);
      else
        strbuf.append(attribute[i].substring(0, index));
    }
    return strbuf.toString();
  }

  // 企業属性指定の有無を取得
  public boolean getAttributeFlg() {
    if (attribute != null) {
      for (int i = 0; i < attribute.length; i++) {
        if (!attribute[i].equals("0"))
          return true;
      }
    }
    return false;
  }

  // 表示順設定
  public void setOrder(String order) {
    if (order != null && order.length() != 0)
      this.order = order;
  }

  // 表示順取得
  public String getOrder() {
    return order;
  }

  // 表示ページ設定
  public void setPage(String page) {
    if (page != null && page.length() != 0)
      this.page = page;
  }

  // 表示ページ取得
  public String getPage() {
    return page;
  }

  // グループ設定
  public void setGroup(HttpServletRequest request, HttpServletResponse response, String group) {
    if (group != null) {
      // グループはセッション切れで消えると続行できなくなるのでクッキーに入れる
      CommonFunc.setCookie(response, "GroupCode", group);
      this.group = group;
    } else {
      this.group = CommonFunc.getCookie(request, "GroupCode");
    }
  }

  // グループ取得
  public String getGroup() {
    return group;
  }

  // グループ設定
  public void setMenu(HttpServletRequest request, HttpServletResponse response, String menu) {
    if (menu != null) {
      // グループはセッション切れで消えると続行できなくなるのでクッキーに入れる
      CommonFunc.setCookie(response, "GroupMenu", menu);
      this.menu = menu;
    } else {
      this.menu = CommonFunc.getCookie(request, "GroupMenu");
    }
  }

  // グループ取得
  public String getMenu() {
    return menu;
  }

  // 期間（月）の開始年設定
  public void setMonthFromY(String monthFromY) {
    if (monthFromY != null && monthFromY.length() != 0)
      this.monthFromY = monthFromY;
  }

  // 期間（月）の開始年取得
  public String getMonthFromY() {
    return monthFromY;
  }

  // 期間（月）の開始月設定
  public void setMonthFromM(String monthFromM) {
    if (monthFromM != null && monthFromM.length() != 0)
      this.monthFromM = monthFromM;
  }

  // 期間（月）の開始月取得
  public String getMonthFromM() {
    return monthFromM;
  }

  // 期間（月）の終了年設定
  public void setMonthToY(String monthToY) {
    if (monthToY != null && monthToY.length() != 0)
      this.monthToY = monthToY;
  }

  // 期間（月）の終了年取得
  public String getMonthToY() {
    return monthToY;
  }

  // 期間（月）の終了月設定
  public void setMonthToM(String monthToM) {
    if (monthToM != null && monthToM.length() != 0)
      this.monthToM = monthToM;
  }

  // 期間（月）の終了月取得
  public String getMonthToM() {
    return monthToM;
  }

  // 期間（週）の開始年設定
  public void setWeekFromY(String weekFromY) {
    if (weekFromY != null && weekFromY.length() != 0)
      this.weekFromY = weekFromY;
  }

  // 期間（週）の開始年取得
  public String getWeekFromY() {
    return weekFromY;
  }

  // 期間（週）の開始週設定
  public void setWeekFromW(String weekFromW) {
    if (weekFromW != null && weekFromW.length() != 0)
      this.weekFromW = weekFromW;
  }

  // 期間（週）の開始週取得
  public String getWeekFromW() {
    return weekFromW;
  }

  // 期間（週）の終了年設定
  public void setWeekToY(String weekToY) {
    if (weekToY != null && weekToY.length() != 0)
      this.weekToY = weekToY;
  }

  // 期間（週）の終了年取得
  public String getWeekToY() {
    return weekToY;
  }

  // 期間（週）の終了週設定
  public void setWeekToW(String weekToW) {
    if (weekToW != null && weekToW.length() != 0)
      this.weekToW = weekToW;
  }

  // 期間（週）の終了週取得
  public String getWeekToW() {
    return weekToW;
  }

  // 期間（日）の開始年設定
  public void setDayFromY(String dayFromY) {
    if (dayFromY != null && dayFromY.length() != 0)
      this.dayFromY = dayFromY;
  }

  // 期間（日）の開始年取得
  public String getDayFromY() {
    return dayFromY;
  }

  // 期間（日）の開始月設定
  public void setDayFromM(String dayFromM) {
    if (dayFromM != null && dayFromM.length() != 0)
      this.dayFromM = dayFromM;
  }

  // 期間（日）の開始月取得
  public String getDayFromM() {
    return dayFromM;
  }

  // 期間（日）の開始日設定
  public void setDayFromD(String dayFromD) {
    if (dayFromD != null && dayFromD.length() != 0)
      this.dayFromD = dayFromD;
  }

  // 期間（日）の開始日取得
  public String getDayFromD() {
    return dayFromD;
  }

  // 期間（日）の終了年設定
  public void setDayToY(String dayToY) {
    if (dayToY != null && dayToY.length() != 0)
      this.dayToY = dayToY;
  }

  // 期間（日）の終了年取得
  public String getDayToY() {
    return dayToY;
  }

  // 期間（日）の終了月設定
  public void setDayToM(String dayToM) {
    if (dayToM != null && dayToM.length() != 0)
      this.dayToM = dayToM;
  }

  // 期間（日）の終了月取得
  public String getDayToM() {
    return dayToM;
  }

  // 期間（日）の終了日設定
  public void setDayToD(String dayToD) {
    if (dayToD != null && dayToD.length() != 0)
      this.dayToD = dayToD;
  }

  // 期間（日）の終了日取得
  public String getDayToD() {
    return dayToD;
  }

  // 期間（時）の開始年設定
  public void setHourFromY(String hourFromY) {
    if (hourFromY != null && hourFromY.length() != 0)
      this.hourFromY = hourFromY;
  }

  // 期間（時）の開始年取得
  public String getHourFromY() {
    return hourFromY;
  }

  // 期間（時）の開始月設定
  public void setHourFromM(String hourFromM) {
    if (hourFromM != null && hourFromM.length() != 0)
      this.hourFromM = hourFromM;
  }

  // 期間（時）の開始月取得
  public String getHourFromM() {
    return hourFromM;
  }

  // 期間（時）の開始日設定
  public void setHourFromD(String hourFromD) {
    if (hourFromD != null && hourFromD.length() != 0)
      this.hourFromD = hourFromD;
  }

  // 期間（時）の開始日取得
  public String getHourFromD() {
    return hourFromD;
  }

  // 期間（時）の開始時設定
  public void setHourFromH(String hourFromH) {
    if (hourFromH != null && hourFromH.length() != 0)
      this.hourFromH = hourFromH;
  }

  // 期間（時）の開始時取得
  public String getHourFromH() {
    return hourFromH;
  }

  // 期間（時）の終了年設定
  public void setHourToY(String hourToY) {
    if (hourToY != null && hourToY.length() != 0)
      this.hourToY = hourToY;
  }

  // 期間（時）の終了年取得
  public String getHourToY() {
    return hourToY;
  }

  // 期間（時）の終了月設定
  public void setHourToM(String hourToM) {
    if (hourToM != null && hourToM.length() != 0)
      this.hourToM = hourToM;
  }

  // 期間（時）の終了月取得
  public String getHourToM() {
    return hourToM;
  }

  // 期間（時）の終了日設定
  public void setHourToD(String hourToD) {
    if (hourToD != null && hourToD.length() != 0)
      this.hourToD = hourToD;
  }

  // 期間（時）の終了日取得
  public String getHourToD() {
    return hourToD;
  }

  // 期間（時）の終了時設定
  public void setHourToH(String hourToH) {
    if (hourToH != null && hourToH.length() != 0)
      this.hourToH = hourToH;
  }

  // 期間（時）の終了時取得
  public String getHourToH() {
    return hourToH;
  }

  // 縦軸ページ番号設定
  public int getPageNo() {
    return pageNo;
  }

  // 縦軸ページ番号をリセット
  public void resetPageNo() {
    this.pageNo = 0;
  }

  // 縦軸次ページ番号設定
  public void pageForward() {
    pageNo++;
  }

  // 縦軸前ページ番号設定
  public void pageBack() {
    if (pageNo != 0)
      pageNo--;
  }

  // 横軸ページ番号設定
  public int getPageNo2() {
    return pageNo2;
  }

  // 横軸ページ番号をリセット
  public void resetPageNo2() {
    this.pageNo2 = 0;
  }

  // 横軸次ページ番号設定
  public void pageForward2() {
    pageNo2++;
  }

  // 横軸前ページ番号設定
  public void pageBack2() {
    if (pageNo2 != 0)
      pageNo2--;
  }

  // 既定値／詳細設定モード設定
  public void setMode(String mode) {
    if (mode != null && mode.length() != 0)
      this.mode = Integer.parseInt(mode);
  }

  // 既定値／詳細設定モード取得
  public int getMode() {
    return mode;
  }

  // サブコード設定
  public void setSubMode(String subMode) {
    if (subMode != null)
      this.subMode = subMode;
  }

  // サブコード取得
  public String getSubMode() {
    return subMode;
  }

  // 対象コーナー設定
  public void setTargetCorner(String targetCorner) {
    if (targetCorner != null)
      this.targetCorner = targetCorner;
  }

  // 対象コーナー取得
  public String getTargetCorner() {
    return targetCorner;
  }

  // 対象テーブル種別（コーナーかURLか）取得
  public String getTargetCornerTable() {
    int index = targetCorner.indexOf(':');
    if (index != -1)
      return targetCorner.substring(0, index);
    else
      return "";
  }

  // 対象コーナコードまたはURL取得
  public String getTargetCornerOrUrl() {
    int index = targetCorner.indexOf(':');
    if (index != -1)
      return targetCorner.substring(index + 1);
    else
      return "";
  }

  // 遷移元コーナー設定
  public void setMoveFromCorner(String moveFromCorner) {
    if (moveFromCorner != null)
      this.moveFromCorner = moveFromCorner;
  }

  // 遷移元コーナー取得
  public String getMoveFromCorner() {
    return moveFromCorner;
  }

  // 遷移元テーブル種別（コーナーかURLか）取得
  public String getMoveFromCornerTable() {
    int index = moveFromCorner.indexOf(':');
    if (index != -1)
      return moveFromCorner.substring(0, index);
    else
      return "";
  }

  // 遷移元コーナコードまたはURL取得
  public String getMoveFromCornerOrUrl() {
    int index = moveFromCorner.indexOf(':');
    if (index != -1)
      return moveFromCorner.substring(index + 1);
    else
      return "";
  }

  // 遷移先コーナー設定
  public void setMoveToCorner(String moveToCorner) {
    if (moveToCorner != null)
      this.moveToCorner = moveToCorner;
  }

  // 遷移先コーナー取得
  public String getMoveToCorner() {
    return moveToCorner;
  }

  // 遷移先テーブル種別（コーナーかURLか）取得
  public String getMoveToCornerTable() {
    int index = moveToCorner.indexOf(':');
    if (index != -1)
      return moveToCorner.substring(0, index);
    else
      return "";
  }

  // 遷移先コーナコードまたはURL取得
  public String getMoveToCornerOrUrl() {
    int index = moveToCorner.indexOf(':');
    if (index != -1)
      return moveToCorner.substring(index + 1);
    else
      return "";
  }

  // クリックストリーム親コーナーコード設定
  public void setStreamParentCorner(int index, String streamParentCorner) {
    if (streamParentCorner != null)
      this.streamParentCorner[index] = streamParentCorner;
  }

  // クリックストリーム親コーナーコード取得
  public String getStreamParentCorner(int index) {
    return streamParentCorner[index];
  }

  // クリックストリーム選択コーナーコード設定
  public void setStreamSelectCorner(int index, String streamSelectCorner) {
    this.streamSelectCorner[index] = streamSelectCorner;
  }

  // クリックストリーム選択コーナーコード取得
  public String getStreamSelectCorner(int index) {
    return streamSelectCorner[index];
  }

  // クリックストリームURL設定
  public void setStreamUrl(int index, String streamUrl) {
    if (streamUrl != null)
      this.streamUrl[index] = streamUrl;
  }

  // クリックストリームURL取得
  public String getStreamUrl(int index) {
    return streamUrl[index];
  }

  // クリックストリームエラーメッセージ取得
  public void setStreamErrMsg(String streamErrMsg) {
    this.streamErrMsg = streamErrMsg;
  }

  // クリックストリームエラーメッセージ取得
  public String getStreamErrMsg() {
    return streamErrMsg;
  }

  // クリックストリーム表示レベル設定
  public void setStreamDispLevel(String streamDispLevel) {
    if (streamDispLevel != null)
      this.streamDispLevel = streamDispLevel;
  }

  // クリックストリーム表示レベル取得
  public String getStreamDispLevel() {
    return streamDispLevel;
  }

  // クリックストリームテーブルID設定
  public void setStreamTableID(String streamTableID) {
    if (streamTableID != null)
      this.streamTableID = streamTableID;
  }

  // クリックストリームテーブルID取得
  public String getStreamTableID() {
    return streamTableID;
  }

  // クリックストリーム集計テーブル再利用フラグ設定
  public void setStreamRecycle(String streamRecycle) {
    if (streamRecycle != null)
      this.streamRecycle = true;
    else
      this.streamRecycle = false;
  }

  // クリックストリーム集計テーブル再利用フラグ取得
  public boolean getStreamRecycle() {
    return streamRecycle;
  }

  // クリックストリーム集計有無フラグ設定
  public void setStreamCounting(int streamCounting) {
     this.streamCounting = streamCounting;
  }

  // クリックストリーム集計有無フラグ取得
  public int getStreamCounting() {
    return streamCounting;
  }

  // 検索開始年月日取得
  public String getDateFrom() {
    if (table != null) {
      if (table.equals("MONTH"))
        return monthFromY + "/" + monthFromM + "/01";
      else if (table.equals("WEEK"))
        return weekFromW;
      else if (table.equals("DATE"))
        return dayFromY + "/" + dayFromM + "/" + dayFromD;
      else if (table.equals("HOUR"))
        return hourFromY + "/" + hourFromM + "/" + hourFromD  + " " + hourFromH + ":00:00";
    }
    return "";
  }

  // 検索終了年月日取得
  public String getDateTo() {
    if (table != null) {
      if (table.equals("MONTH")) {
        // 指定年月の最終日を取得
        GregorianCalendar cal = new GregorianCalendar(Integer.parseInt(monthToY), Integer.parseInt(monthToM) - 1, 1);
        cal.set(Calendar.DATE, cal.getActualMaximum(Calendar.DATE));
        SimpleDateFormat fmt = new SimpleDateFormat("yyyy/MM/dd");
        return fmt.format(cal.getTime());
      } else if (table.equals("WEEK")) {
        // 指定週の最終日を取得
        SimpleDateFormat fmt = new SimpleDateFormat("yyyy/MM/dd");
        GregorianCalendar cal = new GregorianCalendar();
        try {
          cal.setTime(fmt.parse(weekToW));
          cal.add(Calendar.DATE, 6);
          return fmt.format(cal.getTime());
        } catch (ParseException e) {
          return "";
        }
      } else if (table.equals("DATE"))
        return dayToY + "/" + dayToM + "/" + dayToD;
      else if (table.equals("HOUR"))
        return hourToY + "/" + hourToM + "/" + hourToD  + " " + hourToH + ":00:00";
    }
    return "";
  }

  // 縦軸１ページに表示する行数取得
  public int getPageSize() {
    return 20;
  }

  // 横軸１ページに表示する行数取得
  public int getPageSize2() {
    return 20;
  }

  // 部門所有コーナーコード取得
  public String getGroupCorner(DataBase db) throws SQLException {
    if (subMode.equals("GROUP")) {
      String sql = "select MGR_GROUPCONER_CODE from M_GROUP where MGR_GROUP_CODE='" + group + "'";
      Statement st = db.createStatement();
      ResultSet rs = st.executeQuery(sql);
      if (rs.next())
        return rs.getString("MGR_GROUPCONER_CODE");
    }
    return "/";
  }

  // 業種（企業）表示（リンク有り）
  public String getCondCategoryLink(DataBase db) {
    String str;

    if (category.equals("/"))
      str = "業種別";
    else
      str = "<a href='javascript:categorypost(\"/\")' class='link'>業種別</a>＞" + CodeToName.BCToName(db, category);

    return str;
  }

  // 業種（企業）表示（リンク無し）
  public String getCondategoryName(DataBase db) throws SQLException {
    StringBuffer strbuf = new StringBuffer();

    if (category != null && category.length() != 0) {
      if (category.equals("/")) {
        // "/"のみの場合は指定無し
        strbuf.append("指定しない");
      } else {
        // それ以外は業種コード
        strbuf.append(CodeToName.BCToName(db, category));
      }
    }

    return strbuf.toString();
  }

  // コーナー名表示（リンク有り）
  public String getCondCornerLink(DataBase db) throws SQLException {
    StringBuffer strbuf = new StringBuffer();
    int index = 0;
    int depth = 0;
    int len = corner.length();
    int lenGroupCorner = getGroupCorner(db).length();
    String cornerCode;
    String cornerName;

    while (true) {
      // 後ろの"/"を探す。無ければ終了
      index = corner.indexOf('/', index);
      if (index == -1)
        break;

      // コーナー階層を＋１
      depth++;

      // コーナーコード取得
      index++;
      cornerCode = corner.substring(0, index);

      // グループ所有のコーナーコードより上位は表示しない
      if (cornerCode.length() < lenGroupCorner)
        continue;

      // コーナー名取得
      if (index == 1)
        cornerName = "コーナー毎";
      else
        cornerName = CommonFunc.getLaxxxornerName(CodeToName.CNToName(db, cornerCode));

      // 区切り文字付加
      if (strbuf.length() != 0)
        strbuf.append("＞");

      // コーナー名を連結。最後以外はリンク
      if (len != index)
        strbuf.append("<a href='javascript:cornerpost(\"" + cornerCode + "\"," + depth + ")' class='link'>" + cornerName + "</a>");
      else
        strbuf.append(cornerName);
    }

    return strbuf.toString();
  }

  // コーナー名取得（リンク無し）
  public String getCondCornerName(DataBase db) {
    StringBuffer strbuf = new StringBuffer();
    int index = 1;

    while (true) {
      // 後ろの"/"を探す。無ければ終了
      index = corner.indexOf('/', index);
      if (index == -1)
        break;

      // 区切り文字付加
      if (strbuf.length() != 0)
        strbuf.append("：");

      // コーナー名を連結
      strbuf.append(CommonFunc.getLaxxxornerName(CodeToName.CNToName(db, corner.substring(0, ++index))));
    }

    return strbuf.toString();
  }

  // 企業属性表示
  public String getCondAttribute(DataBase db) {
    StringBuffer strbuf = new StringBuffer();

    try {
      // 企業属性名称取得
      Statement st = db.createStatement();
      ResultSet rs = db.getContrxxxaster();

      for (int i = 0; i < 5; i++) {
        String attrName = rs.getString("MCR_ATTRIBUTENAME" + (i + 1) + "_TXT");
        if (attrName != null) {
          if (strbuf.length() != 0)
            strbuf.append("┃");

          strbuf.append(attrName).append("：");

          if (!attribute[i].equals("0"))
            strbuf.append("<b>" + AttributeGenerate.getAttributeText(attribute[i]) + "</b>");
        }
      }
    } catch (SQLException e) {
			e.printStackTrace();
    }

    return strbuf.toString();
  }

  // 表示順表示
  public String getCondOrder() {
    if (order.equals("SORT"))
      return "既定値順";
    else
      return "アクセス数順";
  }

  // 期間指定（月、週、日）表示
  public String getDateSpec() {
    if (table.equals("MONTH"))
      return "月";
    else if (table.equals("WEEK"))
      return "週";
    else if (table.equals("DATE"))
      return "日";
    else if (table.equals("HOUR"))
      return "時";
    else
      return "";
  }

  // 期間指定（月、週、日）表示
  public String getDateSpec2() {
    if (table.equals("MONTH"))
      return "月";
    else if (table.equals("WEEK"))
      return "週";
    else if (table.equals("DATE"))
      return "日付";
    else if (table.equals("HOUR"))
      return "時間";
    else
      return "";
  }

  // 期間（YMD～YMD）表示
  public String getDateSpan() throws ParseException {
    SimpleDateFormat fmtIn;
    SimpleDateFormat fmtOut;

    if (table.equals("HOUR")) {
      fmtIn = new SimpleDateFormat("yyyy/MM/dd HH:mm:ss");
      fmtOut = new SimpleDateFormat("yyyy'年'MM'月'dd'日'HH'時'");
    } else {
      fmtIn = new SimpleDateFormat("yyyy/MM/dd");
      fmtOut = new SimpleDateFormat("yyyy'年'MM'月'dd'日'");
    }

    return fmtOut.format(fmtIn.parse(getDateFrom())) + CommonFunc.mojibakeTaisaku("～") + fmtOut.format(fmtIn.parse(getDateTo()));
  }

  // 部門名取得
  public String getGroupName(DataBase db) {
    return CodeToName.GRToName(db, group);
  }

  // 対象コーナー(URL)名取得
  public String getTargetCornerName(DataBase db) throws SQLException {
    return getCornerName(db, targetCorner);
  }

  // 遷移元コーナー(URL)名取得
  public String getMoveFromCornerName(DataBase db) throws SQLException {
    return getCornerName(db, moveFromCorner);
  }

  // 遷移先コーナー(URL)名取得
  public String getMoveToCornerName(DataBase db) throws SQLException {
    return getCornerName(db, moveToCorner);
  }

  // コーナー名取得
  private String getCornerName(DataBase db, String cornerOrUrl) throws SQLException {
    int index = cornerOrUrl.indexOf(':');
    String kind = cornerOrUrl.substring(0, index);
    String corner = cornerOrUrl.substring(index + 1);
    if (kind.equals("CORNER")) {
      String sql = "select MCN_CORNERNAME_TXT \n"
                 + "from M_CORNER \n"
                 + "where MCN_CORNER_CODE='" + corner + "' \n";
      Statement st = db.createStatement();
      ResultSet rs = st.executeQuery(sql);
      if (rs.next())
        return rs.getString(1);
    }

    return corner;
  }

  // 対象コーナー(URL)の表示期限取得
  public String getTargetLimit(DataBase db) throws SQLException {
    return getLimitDate(db, targetCorner,
        "select MGC_LIMIT_DATE from M_GETHOUR_CORNER where MGC_CORNER_CODE=",
        "select MGU_LIMIT_DATE from M_GETHOUR_URL where MGU_URL_TXT=");
  }

  // 遷移元コーナー(URL)の表示期限取得
  public String getMoveFromLimit(DataBase db) throws SQLException {
    return getLimitDate(db, moveFromCorner,
        "select MMFC_LIMIT_DATE from M_MOVEFR_CORNER where MMFC_CORNER_CODE=",
        "select MMFU_LIMIT_DATE from M_MOVEFR_URL where MMFU_URL_TXT=");
  }

  // 遷移先コーナー(URL)の表示期限取得
  public String getMoveToLimit(DataBase db) throws SQLException {
    return getLimitDate(db, moveToCorner,
        "select MMTC_LIMIT_DATE from M_MOVETO_CORNER where MMTC_CORNER_CODE=",
        "select MMTU_LIMIT_DATE from M_MOVETO_URL where MMTU_URL_TXT=");
  }

  // 対象コーナー(URL)の表示期限取得
  public String getLimitDate(DataBase db, String cornerUrl, String sqlCorner, String sqlUrl) throws SQLException {
    int index = cornerUrl.indexOf(':');
    String kind = cornerUrl.substring(0, index);
    String corner = cornerUrl.substring(index + 1);
    String sql;
    if (kind.equals("CORNER"))
      sql = sqlCorner + "'" + corner + "'";
    else
      sql = sqlUrl + "'" + corner + "'";
    Statement st = db.createStatement();
    ResultSet rs = st.executeQuery(sql);
    if (rs.next()) {
      SimpleDateFormat fmt = new SimpleDateFormat("yyyy'年'MM'月'dd'日'");
      return fmt.format(rs.getDate(1));
    } else
      return "";
  }

  // URLからコーナーに変換
  public void setTransCorner(DataBase db, int index) throws SQLException {
    Statement st = db.createStatement();

    // URLからコーナーコードを取得
    String corner = CommonSql.getCornerFromUrl(st, streamUrl[index].toLowerCase());

    // nullなら変換エラー
    if (corner == null) {
      streamErrMsg = "URLからコーナーに変換できませんでした。";
      streamParentCorner[index] = "/";
      streamSelectCorner[index] = "";
    } else {
      streamParentCorner[index] = CommonFunc.getParentCorner(corner);
      streamSelectCorner[index] = corner;
    }
  }

  // 下位コーナー設定
  public void setLowerCorner(int index) throws SQLException {
    streamParentCorner[index] = streamSelectCorner[index];
    streamSelectCorner[index] = "";
  }

  // 上位コーナー設定
  public void setUpperCorner(int index) throws SQLException {
    streamSelectCorner[index] = streamParentCorner[index];
    streamParentCorner[index] = CommonFunc.getParentCorner(streamParentCorner[index]);
  }
}//class
