package xxx;

import base.*;
import java.sql.*;
import java.text.*;
import java.util.*;
import java.util.Date;
import java.io.*;

/**
 * クリックストリームランキング表を作成
 */
public class ClickStreamBean implements I_Access_Page {
  private String dbdata = "DB value";
  private boolean forwardPage;
  private boolean backPage;

  public String getDbdata() {
    return dbdata;
  }

  public String getDisableForwardPage() {
    return forwardPage ? "" : " disabled";
  }

  public String getDisableBackPage() {
    return backPage ? "" : " disabled";
  }

  /**
   * クリックストリームランキング表の中身を作成
   */
  public void searchDB(DataBase db, SelectCondBean selectcondBean) {
    try {
      Statement st = db.createStatement();
      NumberFormat numFormat = NumberFormat.getNumberInstance();
      int maxCorner = 0;
      int i;

      selectcondBean.setStreamCounting(0);

      // 既定値モードだったら詳細モードに変更
      if (selectcondBean.getMode() == 1) {
        selectcondBean.initCondition(db);
        selectcondBean.setMode("2");
        return;
      }

      // 表示ボタンチェック
      String tableID = selectcondBean.getStreamTableID();
      if (tableID.length() == 0) {
        String status = "1";
        tableID = getRecycleTableID(st, selectcondBean, status);
        int streamCounting = (tableID == null) ? 1 : 2;
        if (tableID == null || !selectcondBean.getStreamRecycle()) {
          status = "0";
          tableID = getRecycleTableID(st, selectcondBean, status);
        }
        if (tableID == null) {
          selectcondBean.setStreamTableID(Integer.toString(CommonSql.getNextSeq(st, "SQ_STREAMTABLE")));
          selectcondBean.setMode("2");
          selectcondBean.setStreamCounting(streamCounting);
          return;
        }
        selectcondBean.setStreamTableID(tableID);
        if (status.equals("0")) {
          selectcondBean.setMode("2");
          selectcondBean.setStreamErrMsg("同一条件で集計処理中です。\\n\\nしばらくたってから、同一条件にて表示ボタンを押下してください。");
          return;
        }
      } else {
        // 集計テーブルステータスチェック
        String status = checkTableID(st, selectcondBean, tableID);
        if (status == null) {
          selectcondBean.setMode("2");
          selectcondBean.setStreamErrMsg("指定された処理番号は未登録です。");
          return;
        } else if (status.equals("0")) {
          selectcondBean.setMode("2");
          selectcondBean.setStreamErrMsg("指定された処理番号は現在集計処理中です。\\n\\nしばらくたってから、同じ処理番号を入力して表示ボタンを押下してください。");
          return;
        }
      }

      // 表示レベル取得
      String dispLevel = selectcondBean.getStreamDispLevel();

      // プロパティファイルからURLのホスト名を取得
      String urlHost = CommonFunc.getUrlHost();

      // 最大のコーナー数（横軸の数）を取得
      StringBuffer sql = new StringBuffer();
      sql.append("select max(decode('-'");
      for (i = 1; i <= 30; i++)
        sql.append(",DSR_CODE" + i + "_CODE," + (i - 1));
      sql.append(",30)) \n");
      sql.append("from S_STREAMRESULT_" + tableID + " \n");
      sql.append("where DSR_CORNERLEVEL_NUM=" + dispLevel + " \n");
      System.out.println(sql);
      ResultSet rs = st.executeQuery(sql.toString());
      if (rs.next())
        maxCorner = rs.getInt(1);

      // 表データを取得するSQL（/*+ RULE */が無いと企業属性を指定した場合に異常に時間がかかる）
      StringBuffer q01 = new StringBuffer();
      q01.append("select /*+ RULE */ sum(DSR_ACCESSCOUNT_NUM) as S_ACCESSCOUNT_NUM \n");
      for (i = 1; i <= maxCorner; i++)
        q01.append(",DSR_CODE" + i + "_CODE \n");
      q01.append("from S_STREAMRESULT_" + tableID + " \n");
      q01.append("where DSR_CORNERLEVEL_NUM=" + dispLevel + " \n");
      CommonSql.setAttributeSql(q01, selectcondBean);
      if (maxCorner > 0) {
        q01.append("group by ");
        for (i = 1; i <= maxCorner; i++) {
          if (i != 1)
            q01.append(",");
          q01.append("DSR_CODE" + i + "_CODE \n");
        }
      }

      StringBuffer q02 = new StringBuffer();
      q02.append("select S_ACCESSCOUNT_NUM \n");
      for (i = 1; i <= maxCorner; i++) {
        q02.append(",t" + i + ".MCN_CORNERNAME_TXT as CORNERNAME" + i);
        q02.append(",t" + i + ".MCN_CORNERCOLOR_CODE as CORNERCOLOR" + i);
        q02.append(",t" + i + ".MCN_TOPPAGEURL_TXT as TOPPAGEURL" + i + "\n");
      }
      q02.append("from (" + q01 + ") q01 \n");
      for (i = 1; i <= maxCorner; i++)
        q02.append(",M_CORNER t" + i + "\n");
      if (maxCorner > 0) {
        q02.append("where ");
        for (i = 1; i <= maxCorner; i++) {
          if (i != 1)
            q02.append("and ");
          q02.append("t" + i + ".MCN_CORNER_CODE(+)=DSR_CODE" + i + "_CODE \n");
        }
      }
      q02.append("order by S_ACCESSCOUNT_NUM DESC \n");

      // カラムの接頭辞とテーブル種別名を置換
      CommonFunc.substitute(q02, "[PF]", "DSR");

      // 必要な範囲のデータのみを抽出
      StringBuffer sql_row = new StringBuffer();
      int pageSize = selectcondBean.getPageSize();
      int offset = selectcondBean.getPageNo() * pageSize;
      sql_row.append(CommonSql.RowRange(q02.toString(), offset, pageSize + 1)); // 次ページがあるかチェックするため１つ多く読む

      // htmlテーブルのヘッダ部作成
      StringBuffer tags = new StringBuffer();
      tags.append("<tr class='header'>");
      tags.append("<td class='header_no'>No.</td>\n");
      tags.append("<td class='header_txt' nowrap>件数</td>\n");
      for (i = 1; i <= maxCorner; i++)
          tags.append("<td class='header_txt' nowrap>コーナー<br>" + CommonFunc.toFullANS(Integer.toString(i)) + "</td>\n");
      tags.append("</tr>\n");

      // htmlテーブルのデータ部作成
      int cornerWidth = getCornerWidth();
      int no = 1;
      System.out.println(sql_row);
      rs = st.executeQuery(sql_row.toString());
      while (rs.next()) {
        tags.append("<tr class='data'>");

        // 番号
        tags.append("<td class='data_no2'>").append(offset + no).append("</td>\n");

        tags.append("<td class='data_access'>").append(numFormat.format(rs.getInt("S_ACCESSCOUNT_NUM"))).append("</td>\n");

        for (i = 1; i <= maxCorner; i++) {
          String cornerName = rs.getString("CORNERNAME" + i);
          if (cornerName == null)
            tags.append("<td bgcolor='#CCCCC'><br></td>\n");
          else
            tags.append("<td align='center' bgcolor='#" + rs.getString("CORNERCOLOR" + i) + "'><a href='" + urlHost + rs.getString("TOPPAGEURL" + i) + "' class='link' target='_blank'>" + addLineFeed(CommonFunc.getLaxxxornerName(cornerName), cornerWidth) + "</a></td> \n");
        }

        tags.append("</tr>\n");

        no++;
      }

      dbdata = tags.toString();
    } catch (Exception e) {
      dbdata = "<font color='red'>集計処理中にエラーが発生しました。<br>" + e + "</font><br>";
			e.printStackTrace();
		}//try
  }

  // 集計テーブルの検索
  private String getRecycleTableID(Statement st, SelectCondBean selectcondBean, String status) throws SQLException {
    String sql;
    String tableID = null;

    sql = "select max(MSC_REQUESTID_NUM) from M_STREAMCONTROL WHERE MSC_STATUS_CLS='" + status + "'" + getStreamWhere(st, selectcondBean);
    ResultSet rs = st.executeQuery(sql.toString());
    if (rs.next())
      tableID = rs.getString(1);

    return tableID;
  }

  // 集計テーブルID存在チェック
  private String checkTableID(Statement st, SelectCondBean selectcondBean, String tableID) throws SQLException {
    String sql;

    sql = "select MSC_START_CODE,MSC_MID01_CODE,MSC_MID02_CODE,MSC_MID03_CODE,MSC_END_CODE,MSC_FROMDATE_DATE,MSC_TODATE_DATE,MSC_STATUS_CLS from M_STREAMCONTROL where MSC_REQUESTID_NUM=" + tableID;
    ResultSet rs = st.executeQuery(sql.toString());
    if (rs.next()) {
      selectcondBean.setStreamSelectCorner(0, rs.getString("MSC_START_CODE"));
      selectcondBean.setStreamSelectCorner(1, rs.getString("MSC_MID01_CODE"));
      selectcondBean.setStreamSelectCorner(2, rs.getString("MSC_MID02_CODE"));
      selectcondBean.setStreamSelectCorner(3, rs.getString("MSC_MID03_CODE"));
      selectcondBean.setStreamSelectCorner(4, rs.getString("MSC_END_CODE"));

      Date date;
      SimpleDateFormat formatY = new SimpleDateFormat("yyyy");
      SimpleDateFormat formatM = new SimpleDateFormat("MM");
      SimpleDateFormat formatD = new SimpleDateFormat("dd");

      date = rs.getDate("MSC_FROMDATE_DATE");
      selectcondBean.setDayFromY(formatY.format(date));
      selectcondBean.setDayFromM(formatM.format(date));
      selectcondBean.setDayFromD(formatD.format(date));

      date = rs.getDate("MSC_TODATE_DATE");
      selectcondBean.setDayToY(formatY.format(date));
      selectcondBean.setDayToM(formatM.format(date));
      selectcondBean.setDayToD(formatD.format(date));

      return rs.getString("MSC_STATUS_CLS");
    }
    return null;
  }

  // ストリームコントロールマスタ検索条件取得
  private String getStreamWhere(Statement st, SelectCondBean selectcondBean) {
    // 中間点を取得（中間点は前に詰める）
    String streamMid[] = new String[3];
    for (int n = 0, i = 1; i <= 3; i++) {
      if (selectcondBean.getStreamSelectCorner(i).length() != 0)
        streamMid[n++] = selectcondBean.getStreamSelectCorner(i);
    }

    StringBuffer where = new StringBuffer();
    where.append(" and MSC_START_CODE" + getCondition(selectcondBean.getStreamSelectCorner(0)));
    where.append(" and MSC_MID01_CODE" + getCondition(streamMid[0]));
    where.append(" and MSC_MID02_CODE" + getCondition(streamMid[1]));
    where.append(" and MSC_MID03_CODE" + getCondition(streamMid[2]));
    where.append(" and MSC_END_CODE" + getCondition(selectcondBean.getStreamSelectCorner(4)));
    where.append(" and MSC_FROMDATE_DATE='" + selectcondBean.getDateFrom() + "'");
    where.append(" and MSC_TODATE_DATE='" + selectcondBean.getDateTo() + "'");

    return where.toString();
  }

  // 条件式
  private String getCondition(String str) {
    if (str == null || str.length() == 0)
      return " is null";
    else
      return "='" + str + "'";
  }

  // 指定文字数で改行
  private String addLineFeed(String str, int size) {
    StringBuffer strBuf = new StringBuffer();
    int i;

    strBuf.append("<nobr>");
    int len = str.length();
    for (i = 0; i < len - size; i += size) {
      strBuf.append(str.substring(i, i + size));
      strBuf.append("<wbr>");
    }
    strBuf.append(str.substring(i, Math.min(i + size, len)));
    strBuf.append("</nobr>");

    return strBuf.toString();
  }

  // コーナー名の幅を取得
  private int getCornerWidth() {
    int cornerWidth;

    try {
      // プロパティファイルからコーナー名の幅を取得
			FileInputStream fin = new FileInputStream(new File(WebAppInitializer.propertyPath));
			Properties prop = new Properties();
			prop.load(fin);
			cornerWidth = Integer.parseInt(prop.getProperty("stream_corner_width", "3"));
      fin.close();
    } catch (Exception ex) {
      cornerWidth = 3;
    }

    return cornerWidth;
  }
}
