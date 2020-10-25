package xxx;
import base.*;
import java.sql.*;
import java.text.*;

/**
 * コーナー別アクセス推移グラフを作成
 */
public class CornerGraphBean implements I_Access_Page {
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
   * コーナー別アクセス推移グラフの中身を作成
   */
  public void searchDB(DataBase db, SelectCondBean selectcondBean) {
    try {
      Statement st = db.createStatement();
      ResultSet rs;
      NumberFormat numFormat = NumberFormat.getNumberInstance();
      int i;

      // コーナー名、階層を取得
      String corner = selectcondBean.getCorner();
      int cornerDepth = Integer.parseInt(selectcondBean.getCornerdepth());

      // テーブル別のヘッダ　日・週・月別変化
      String table = selectcondBean.getTable();
      String prefix = "";
      if (selectcondBean.getTable().equals("MONTH")) {
        prefix = "SMCC";
      } else if (selectcondBean.getTable().equals("WEEK")) {
        prefix = "SWCC";
      } else if (selectcondBean.getTable().equals("DATE")) {
        prefix = "SDCC";
      }

      // 行ごとのアクセス数を取得するためのサブクエリ
      StringBuffer q01 = new StringBuffer();
      q01.append("select ");
      q01.append("[PF]_CORNER_CODE as S_CODE \n");
      q01.append(",sum([PF]_ACCESSCOUNT_NUM) as ALLCOUNT \n");
      q01.append("from S_[TB]_CORNER_COMPANY \n");
      q01.append("where ");
      q01.append("[PF]_ACCESS[TB]_DATE between '" + selectcondBean.getDateFrom() + "' and '" + selectcondBean.getDateTo() + "'\n");
      q01.append("and [PF]_CORNERPARENT_CODE='" + corner + "'\n");
      q01.append("and [PF]_CORNER_CODE<>'/' \n");
      CommonSql.setAttributeSql(q01, selectcondBean);
      q01.append("group by [PF]_CORNER_CODE");

      // カラムの接頭辞とテーブル種別名を置換
      CommonFunc.substitute(q01, "[PF]", prefix);
      CommonFunc.substitute(q01, "[TB]", table);

      // 表示順
      String c_sort;
      if (selectcondBean.getOrder().equals("ALLCOUNT"))
        c_sort = "nvl(ALLCOUNT,0) DESC";
      else
        c_sort = "MCN_SORT" + cornerDepth + "_NUM";

      // 全アクセス数と最大アクセス数を取得するためのSQL
      StringBuffer sql_ttl = new StringBuffer();
      sql_ttl.append("select SUM(ALLCOUNT) as TOTAL,MAX(ALLCOUNT) as MAXCOUNT \n");
      sql_ttl.append("from (" + q01 + ") \n");

      // コーナ名を取得するためのサブクエリ化
      StringBuffer q02 = new StringBuffer();
      q02.append("select MCN_CORNER_CODE as C_CODE,MCN_CORNERNAME_TXT as C_TEXT,q01.*,decode(MCN_LASTLEVEL_FLG,'0',1,0) as CHILD \n");
      q02.append("from M_CORNER t1,(" + q01 + ") q01 \n");
      q02.append("where comm.getParentCornerCode(MCN_CORNER_CODE)='" + corner + "' \n");
      q02.append(" and S_CODE(+)=MCN_CORNER_CODE \n");
      q02.append(" and MCN_DEPTH_NUM=" + cornerDepth + "\n");
      q02.append("order by " + c_sort + "\n");

      // 必要な範囲のデータのみを抽出
      StringBuffer sql_row = new StringBuffer();
      int pageSize = selectcondBean.getPageSize();
      int offset = selectcondBean.getPageNo() * pageSize;
      sql_row.append(CommonSql.RowRange(q02.toString(), offset, pageSize + 1)); // 次ページがあるかチェックするため１つ多く読む

      // htmlテーブルのヘッダ部作成
      StringBuffer tags= new StringBuffer();
      tags.append("<tr class='header'>");
      tags.append("<td class='header_no'>No.</td>\n");
      tags.append("<td class='header_txt'>コーナー名</td>\n");
      tags.append("<td class='header_txt'>アクセス</td>\n");
      tags.append("<td class='header_txt'>％</td>\n");
      tags.append("<td class='header_txt'>グラフ</td>\n");
      tags.append("<td class='header_txt'>推移<br>グラフ<br>ボタン</td>\n");
      tags.append("</tr>\n");

      // htmlテーブルの合計部作成
      int total = 0, maxCount = 0;
      System.out.println(sql_ttl);
      rs = st.executeQuery(sql_ttl.toString());
      if (rs.next()) {
        // 全アクセス数と最大アクセス数取得
        total = rs.getInt("TOTAL");
        maxCount = rs.getInt("MAXCOUNT");

        // 行開始
        tags.append("<tr class='total'>");

        // 番号（何もなし）
        tags.append("<td><br></td>");

        // "合計"
        tags.append("<td class='total_txt'>合計</td>\n");

        // 合計アクセス数とパーセント
        tags.append("<td class='total_num'>").append(numFormat.format(total)).append("</td>\n");
        tags.append("<td class='total_num'>").append(CommonFunc.getParcent(total, total)).append("</td>\n");

        // グラフ（何もなし）
        tags.append("<td><br></td>");

        // 推移グラフボタン（何もなし）
        tags.append("<td><br></td>");

        // 行終了
        tags.append("</tr>\n");
      }

      // htmlテーブルのデータ部作成
      int subTotal = 0;
      int no = 1;
      forwardPage = false;
      backPage = (offset != 0);
      System.out.println(sql_row);
      rs = st.executeQuery(sql_row.toString());
      while(rs.next()){
        // 次ページがあるかチェック
        if (pageSize != 0 && no > pageSize) {
          forwardPage = true;
          break;
        }

        tags.append("<tr class='data'>");

        // 番号
        tags.append("<td class='data_no'>").append(offset + no).append("</td>\n");

        // コーナ名
        tags.append("<td class='data_corner'>");
        tags.append(CommonFunc.linkCornerValue(
              rs.getBoolean("CHILD"),
              "CrossCornerAccess",
              rs.getString("C_CODE").trim(),
              CommonFunc.getLaxxxornerName(rs.getString("C_TEXT")),
              cornerDepth + 1
           )
        );
        tags.append("</td>\n");

        // 合計アクセス数とパーセント
        int allCount = rs.getInt("ALLCOUNT");
        tags.append("<td class='data_access'>").append(numFormat.format(allCount)).append("</td>\n");
        tags.append("<td class='data_percent'>").append(CommonFunc.getParcent(total, allCount)).append("</td>\n");
        subTotal += allCount;

        // 棒グラフ
        tags.append("<td class='data_graph'>");
        tags.append(CommonFunc.genGraphImage(allCount, maxCount));//GIFの幅を計算
        tags.append("</td>\n");

        // 推移グラフボタン
        tags.append("<td class='data_suii'>");
        if (allCount != 0) {
          tags.append("<input type='button' onclick='open_graph(");
          tags.append("\"" + rs.getString("C_CODE").trim() + "\"");
          tags.append(")' value='表示'>");
        }
        tags.append("</td>\n");

        tags.append("</tr>\n");

        no++;
      }

      // htmlテーブルの小計部作成
      {
        // 行開始
        tags.append("<tr class='subtotal'>");

        // 番号（何もなし）
        tags.append("<td><br></td>");

        // "小計"
        tags.append("<td class='subtotal_txt'>小計</td>\n");

        // 小計アクセス数とパーセント
        tags.append("<td class='subtotal_num'>").append(numFormat.format(subTotal)).append("</td>\n");
        tags.append("<td class='subtotal_num'>").append(CommonFunc.getParcent(total, subTotal)).append("</td>\n");

        // グラフ（何もなし）
        tags.append("<td><br></td>");

        // 推移グラフボタン（何もなし）
        tags.append("<td><br></td>");

        // 行終了
        tags.append("</tr>\n");
      }

      dbdata = tags.toString();
    } catch (SQLException e) {
      dbdata = "<font color='red'>集計処理中にエラーが発生しました。<br>" + e + "</font><br>";
			e.printStackTrace();
		}//try
  }
}