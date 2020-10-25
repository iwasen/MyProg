package xxx;
import base.*;
import java.sql.*;
import java.text.*;

/**
 * コーナグラフから呼び出される時系列グラフ統計表を作成
 */
public class CornerGraphAccessBean  implements I_Access_Page {
  private String dbdata = "DB value";

  public String getDbdata() {
    return dbdata;
  }

  /**
   * コーナー時系列アクセスのテーブルの中身を作成
   */
  public void searchDB(DataBase db, SelectCondBean selectcondBean) {
    try {
      Statement st = db.createStatement();
      ResultSet rs;
      NumberFormat numFormat = NumberFormat.getNumberInstance();
      int i;

      // コーナーコードを取得
      String corner = selectcondBean.getCorner();

      // テーブル別のヘッダ　日・週・月別変化
      String table = selectcondBean.getTable();
      String prefix = "", dateUnit = "";
      if (selectcondBean.getTable().equals("MONTH")) {
        prefix = "SMCC";
        dateUnit = "月";
      } else if (selectcondBean.getTable().equals("WEEK")) {
        prefix = "SWCC";
        dateUnit = "週";
      } else if (selectcondBean.getTable().equals("DATE")) {
        prefix = "SDCC";
        dateUnit = "日";
      }

      // 表データ取得SQL　縦軸：期間
      StringBuffer sbuf;
      String select, where;

      // select句編集
      sbuf = new StringBuffer();
      sbuf.append("[PF]_ACCESS[TB]_DATE as C_DATE \n");
      sbuf.append(",sum([PF]_ACCESSCOUNT_NUM) as ALLCOUNT \n");
      select = sbuf.toString();

      // where句編集
      sbuf = new StringBuffer();
      sbuf.append("[PF]_ACCESS[TB]_DATE between '" + selectcondBean.getDateFrom() + "' and '" + selectcondBean.getDateTo() + "'\n");
      sbuf.append("and [PF]_CORNER_CODE='" + corner + "'\n");
//      sbuf.append("and [PF]_BUSINESSCATEGORY_CODE<>'UNDEFINED' \n");
      CommonSql.setAttributeSql(sbuf, selectcondBean);
      where = sbuf.toString();

      // 行ごとのアクセス数を取得するためのサブクエリ
      StringBuffer s01 = new StringBuffer();
      s01.append("select " + select);
      s01.append("from S_[TB]_CORNER_COMPANY \n");
      s01.append("where " + where);
      s01.append("group by [PF]_ACCESS[TB]_DATE");

      // 最大アクセス数を取得するためのサブクエリ
      StringBuffer s02 = new StringBuffer();
      s02.append("select MAX(ALLCOUNT) as MAXCOUNT \n");
      s02.append("from (" + s01.toString() + ") \n");

      // サブクエリをまとめて実行SQL作成（休日もここで取得）
      StringBuffer sql = new StringBuffer();
      sql.append("select s01.*,s02.*,MHD_HOLIDAY_CLS \n");
      sql.append("from (" + s01.toString() + ") s01 \n");
      sql.append(",(" + s02.toString() + ") s02 \n");
      sql.append(",M_HOLIDAY \n");
      sql.append("where C_DATE=MHD_HOLIDAY_DATE(+) \n");
      sql.append("order by C_DATE \n");

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
      while(rs.next()){
        tags.append("<tr class='data'>");

        // 期間
        tags.append("<td class='data_date'>");
        tags.append(CommonFunc.getTimePeriod(table, rs.getDate("C_DATE"), rs.getString("MHD_HOLIDAY_CLS")));
        tags.append("</td>\n");

        // 合計アクセス数
        int allCount = rs.getInt("ALLCOUNT");
        tags.append("<td class='data_access'>").append(numFormat.format(allCount)).append("</td>\n");

        // 棒グラフ
        tags.append("<td class='data_graph'>");
        tags.append(CommonFunc.genGraphImage(allCount, rs.getInt("MAXCOUNT")));//GIFの幅を計算
        tags.append("</td>\n");

        tags.append("</tr>\n");
      }

      dbdata = tags.toString();
    } catch (SQLException e) {
      dbdata = "<font color='red'>集計処理中にエラーが発生しました。<br>" + e + "</font><br>";
			e.printStackTrace();
		}//try
  }

}