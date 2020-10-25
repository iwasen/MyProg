package xxx;
import base.*;
import java.sql.*;
import java.text.*;
import java.util.*;

/**
 * コーナグラフから呼び出される時系列グラフ統計表を作製
 */
public class TargetGraphAccessBean  implements I_Access_Page {
  private String dbdata = "DB value";
  private boolean forwardPage;
  private boolean backPage;
  private int pageSize;

  public String getDbdata() {
    return dbdata;
  }

  public String getDisableForwardPage() {
    return forwardPage ? "" : " disabled";
  }

  public String getDisableBackPage() {
    return backPage ? "" : " disabled";
  }

  public int getPageSize() {
    return pageSize;
  }

  /**
   * コーナー時系列アクセスのテーブルの中身をつくる
   */
  public void searchDB(DataBase db, SelectCondBean selectcondBean) {
    if (selectcondBean.getTable().equals("DATE"))
      SearchDate(db, selectcondBean);
    else
      SearchHour(db, selectcondBean);
  }

  private void SearchDate(DataBase db, SelectCondBean selectcondBean) {
    try {
      Statement st = db.createStatement();
      ResultSet rs;
      NumberFormat numFormat = NumberFormat.getNumberInstance();
      int i;

      // カテゴリコードを取得
      String category = selectcondBean.getCategory();

      // 対象コーナーをテーブル種別とコーナーコード(URL)に分解
      String targetCorner = selectcondBean.getTargetCorner();
      int index = targetCorner.indexOf(":");
      String table = targetCorner.substring(0, index);
      String corner = targetCorner.substring(index + 1);

      // テーブル別のヘッダ（URL・コーナー）
      String prefix = "", urlCorner = "";
      if (table.equals("CORNER")) {
        prefix = "xxxC";
        urlCorner = "[PF]_[TB]_CODE";
      } else {
        prefix = "STUC";
        urlCorner = "[PF]_[TB]_TXT";
      }

      // 期間によるグルーピング分け
      String dateTable = selectcondBean.getTable();
      String dateCol, dateFmt, dateUnit;
      if (dateTable.equals("DATE")) {
        dateCol = "trunc([PF]_ACCESSDH_DATE,'DD')";
        dateFmt = "YYYY/MM/DD";
        dateUnit = "日付";
      } else {
        dateCol = "[PF]_ACCESSDH_DATE";
        dateFmt = "YYYY/MM/DD HH24:MI:SS";
        dateUnit = "時間";
      }

      // select句編集
      StringBuffer select = new StringBuffer();
      select.append(dateCol + " as C_DATE \n");
      select.append(",sum([PF]_ACCESSCOUNT_NUM) as ALLCOUNT \n");

      // where句編集
      StringBuffer where = new StringBuffer();
      where.append(dateCol + " between to_date('" + selectcondBean.getDateFrom() + "','" + dateFmt + "') and to_date('" + selectcondBean.getDateTo() + "','" + dateFmt + "') \n");
      where.append("and " + urlCorner + "='" + corner + "'\n");
      if (!category.equals("/"))
        where.append("and [PF]_BUSINESSCATEGORY_CODE='" + category + "' \n");
      CommonSql.setAttributeSql(where, selectcondBean);

      // 行ごとのアクセス数を取得するためのサブクエリ
      StringBuffer q01 = new StringBuffer();
      q01.append("select " + select);
      q01.append("from S_TIME_[TB]_COMPANY \n");
      q01.append("where " + where);
      q01.append("group by " + dateCol);

      // 最大アクセス数を取得するためのサブクエリ
      StringBuffer q02 = new StringBuffer();
      q02.append("select MAX(ALLCOUNT) as MAXCOUNT \n");
      q02.append("from (" + q01.toString() + ") \n");

      // サブクエリをまとめて実行SQL作成（休日もここで取得）
      StringBuffer q03 = new StringBuffer();
      q03.append("select q01.*,q02.*,MHD_HOLIDAY_CLS \n");
      q03.append("from (" + q01.toString() + ") q01 \n");
      q03.append(",(" + q02.toString() + ") q02 \n");
      q03.append(",M_HOLIDAY \n");
      q03.append("where C_DATE=MHD_HOLIDAY_DATE(+) \n");
      q03.append("order by C_DATE \n");

      // 必要な範囲のデータのみを抽出
      StringBuffer sql = new StringBuffer();
      pageSize = selectcondBean.getPageSize();
      int offset = selectcondBean.getPageNo() * pageSize;
      sql.append(CommonSql.RowRange(q03.toString(), offset, pageSize + 1)); // 次ページがあるかチェックするため１つ多く読む

      // カラムの接頭辞とテーブル種別名を置換
      CommonFunc.substitute(sql, "[PF]", prefix);
      CommonFunc.substitute(sql, "[TB]", table);

      // SQL実行
      System.out.println(sql);
      rs = st.executeQuery(sql.toString());

      // htmlテーブルのヘッダ部作成
      StringBuffer tags= new StringBuffer();
      tags.append("<tr class='header'>");
      tags.append("<td class='header_txt' width='100'>" + dateUnit + "</td>\n");
      tags.append("<td class='header_txt'>アクセス</td>\n");
      tags.append("<td class='header_txt'>グラフ</td>\n");
      tags.append("</tr>\n");

      // htmlテーブルのデータ部作成
      int no = 1;
      forwardPage = false;
      backPage = (offset != 0);
      while(rs.next()){
        // 次ページがあるかチェック
        if (pageSize != 0 && no > pageSize) {
          forwardPage = true;
          break;
        }

        tags.append("<tr class='data'>");

        // 期間
        tags.append("<td class='data_date'>");
        tags.append(CommonFunc.getTimePeriod(dateTable, rs.getTimestamp("C_DATE"), rs.getString("MHD_HOLIDAY_CLS")));
        tags.append("</td>\n");

        // 合計アクセス数
        int allCount = rs.getInt("ALLCOUNT");
        tags.append("<td class='data_access'>").append(numFormat.format(allCount)).append("</td>\n");

        // 棒グラフ
        tags.append("<td class='data_graph'>");
        tags.append(CommonFunc.genGraphImage(allCount, rs.getInt("MAXCOUNT")));//GIFの幅を計算
        tags.append("</td>\n");

        tags.append("</tr>\n");

        no++;
      }

      dbdata = tags.toString();
    } catch (SQLException e) {
      dbdata = "<font color='red'>集計処理中にエラーが発生しました。<br>" + e + "</font><br>";
			e.printStackTrace();
		}//try
  }

  private void SearchHour(DataBase db, SelectCondBean selectcondBean) {
    try {
      Statement st = db.createStatement();
      ResultSet rs;
      NumberFormat numFormat = NumberFormat.getNumberInstance();
      int i;

      // カテゴリコードを取得
      String category = selectcondBean.getCategory();

      // 対象コーナーをテーブル種別とコーナーコード(URL)に分解
      String targetCorner = selectcondBean.getTargetCorner();
      int index = targetCorner.indexOf(":");
      String table = targetCorner.substring(0, index);
      String corner = targetCorner.substring(index + 1);

      // テーブル別のヘッダ（URL・コーナー）
      String prefix = "", urlCorner = "";
      if (table.equals("CORNER")) {
        prefix = "xxxC";
        urlCorner = "[PF]_[TB]_CODE";
      } else {
        prefix = "STUC";
        urlCorner = "[PF]_[TB]_TXT";
      }

      // 期間によるグルーピング分け
      String dateTable = selectcondBean.getTable();
      String dateCol, dateFmt, dateUnit;
      dateCol = "[PF]_ACCESSDH_DATE";
      dateFmt = "YYYY/MM/DD HH24:MI:SS";
      dateUnit = "時間";

      // 表示対象日付
      SimpleDateFormat formatter = new SimpleDateFormat("yyyy/MM/dd HH:mm:ss");
      GregorianCalendar dateFrom = new GregorianCalendar();
      dateFrom.setTime(formatter.parse(selectcondBean.getDateFrom()));
      if (selectcondBean.getPageNo() != 0) {
        dateFrom.set(Calendar.HOUR_OF_DAY, 0);
        dateFrom.add(Calendar.DATE, selectcondBean.getPageNo());
        backPage = true;
      } else
        backPage = false;
      GregorianCalendar dateTo = new GregorianCalendar();
      dateTo.setTime(formatter.parse(selectcondBean.getDateTo()));
      GregorianCalendar dateTmp = (GregorianCalendar)dateFrom.clone();
      dateTmp.set(Calendar.HOUR_OF_DAY, 23);
      if (dateTo.after(dateTmp)) {
        dateTo = dateTmp;
        forwardPage = true;
      } else
        forwardPage = false;

      pageSize = 24;

      // select句編集
      StringBuffer select = new StringBuffer();
      select.append(dateCol + " as C_DATE \n");
      select.append(",sum([PF]_ACCESSCOUNT_NUM) as ALLCOUNT \n");

      // where句編集
      StringBuffer where = new StringBuffer();
      where.append(dateCol + " between to_date('" + selectcondBean.getDateFrom() + "','" + dateFmt + "') and to_date('" + selectcondBean.getDateTo() + "','" + dateFmt + "') \n");
      where.append("and " + urlCorner + "='" + corner + "'\n");
      if (!category.equals("/"))
        where.append("and [PF]_BUSINESSCATEGORY_CODE='" + category + "' \n");
      CommonSql.setAttributeSql(where, selectcondBean);

      // 行ごとのアクセス数を取得するためのサブクエリ
      StringBuffer q01 = new StringBuffer();
      q01.append("select " + select);
      q01.append("from S_TIME_[TB]_COMPANY \n");
      q01.append("where " + where);
      q01.append("group by " + dateCol);

      // カラムの接頭辞とテーブル種別名を置換
      CommonFunc.substitute(q01, "[PF]", prefix);
      CommonFunc.substitute(q01, "[TB]", table);

      // 最大アクセス数を取得するためのクエリ
      StringBuffer sql_ttl = new StringBuffer();
      sql_ttl.append("select MAX(ALLCOUNT) as MAXCOUNT \n");
      sql_ttl.append("from (" + q01.toString() + ") \n");

      // サブクエリをまとめて実行SQL作成
      StringBuffer sql_row = new StringBuffer();
      sql_row.append("select q01.*,to_char(C_DATE,'HH24') as C_HOUR \n");
      sql_row.append("from (" + q01.toString() + ") q01 \n");
      sql_row.append("where C_DATE between to_date('" + formatter.format(dateFrom.getTime()) + "','" + dateFmt + "') and to_date('" + formatter.format(dateTo.getTime()) + "','" + dateFmt + "') \n");
      sql_row.append("order by C_DATE \n");

      // 最大値取得
      int maxCount = 0;
      rs = st.executeQuery(sql_ttl.toString());
      if (rs.next())
        maxCount = rs.getInt("MAXCOUNT");

      // SQL実行
      System.out.println(sql_row);
      rs = st.executeQuery(sql_row.toString());

      // 時間ごとのアクセス数取得
      int[] accessCount = new int[24];
      while(rs.next()) {
        accessCount[rs.getInt("C_HOUR")] = rs.getInt("ALLCOUNT");
      }

      // htmlテーブルのヘッダ部作成
      StringBuffer tags= new StringBuffer();
      tags.append("<tr class='header'>");
      tags.append("<td class='header_txt' width='100'>" + dateUnit + "</td>\n");
      tags.append("<td class='header_txt'>アクセス</td>\n");
      tags.append("<td class='header_txt'>グラフ</td>\n");
      tags.append("</tr>\n");

      // htmlテーブルのデータ部作成
      GregorianCalendar cal = new GregorianCalendar();
      for (cal = dateFrom; cal.before(dateTo) || cal.equals(dateTo); cal.add(Calendar.HOUR_OF_DAY, 1)) {
        tags.append("<tr class='data'>");

        // 期間
        tags.append("<td class='data_date'>");
        tags.append(CommonFunc.getTimePeriod(dateTable, cal.getTime(), null));
        tags.append("</td>\n");

        // 合計アクセス数
        int hour = cal.get(Calendar.HOUR_OF_DAY);
        tags.append("<td class='data_access'>").append(numFormat.format(accessCount[hour])).append("</td>\n");

        // 棒グラフ
        tags.append("<td class='data_graph'>");
        tags.append(CommonFunc.genGraphImage(accessCount[hour], maxCount));//GIFの幅を計算
        tags.append("</td>\n");

        tags.append("</tr>\n");
      }

      dbdata = tags.toString();
    } catch (Exception e) {
      dbdata = "<font color='red'>集計処理中にエラーが発生しました。<br>" + e + "</font><br>";
			e.printStackTrace();
		}//try
  }
}