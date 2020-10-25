package xxx;
import base.*;
import java.sql.*;
import java.text.*;


/**
 * 業種別アクセス推移グラフを作成
 */
public class CategoryGraphBean implements I_Access_Page {
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
   * 業種別アクセス推移グラフの中身を作成
   */
  public void searchDB(DataBase db, SelectCondBean selectcondBean) {
    try {
      Statement st = db.createStatement();
      ResultSet rs;
      NumberFormat numFormat = NumberFormat.getNumberInstance();
      int i;

      // カテゴリを取得
      String category = selectcondBean.getCategory();

      // 部門所有のコーナーコードを取得
      String cornerCode = selectcondBean.getGroupCorner(db);

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

      // 業種（企業）コードのカラム名
      String s_code;
      if (category.equals("/"))
        s_code = "[PF]_BUSINESSCATEGORY_CODE";
      else
        s_code = "[PF]_COMPANY_CODE";

      // 行ごとのアクセス数を取得するためのサブクエリ
      StringBuffer q01 = new StringBuffer();
      q01.append("select ");
      q01.append(s_code + " as S_CODE \n");
      q01.append(",sum([PF]_ACCESSCOUNT_NUM) as ALLCOUNT \n");
      q01.append("from S_[TB]_CORNER_COMPANY \n");
      q01.append("where ");
      q01.append("[PF]_ACCESS[TB]_DATE between '" + selectcondBean.getDateFrom() + "' and '" + selectcondBean.getDateTo() + "'\n");
      q01.append("and [PF]_CORNER_CODE='" + cornerCode + "' \n");
      if (!category.equals("/"))
        q01.append("and [PF]_BUSINESSCATEGORY_CODE='" + category + "' \n");
      CommonSql.setAttributeSql(q01, selectcondBean);
      q01.append("group by " + s_code);

      // カラムの接頭辞とテーブル種別名を置換
      CommonFunc.substitute(q01, "[PF]", prefix);
      CommonFunc.substitute(q01, "[TB]", table);

      // 全アクセス数と最大アクセス数（その他は除く）を取得するためのSQL
      StringBuffer sql_ttl = new StringBuffer();
      sql_ttl.append("select SUM(ALLCOUNT) as TOTAL,MAX(DECODE(S_CODE,'UNDEFINED', 0, ALLCOUNT)) as MAXCOUNT \n");
      sql_ttl.append("from (" + q01 + ") \n");

      // 業種（企業）名を取得するための処理
      String c_table, c_code, c_text, c_sort, c_where;
      if (category.equals("/")) {
        c_table = "M_BUSINESSCATEGORY";
        c_code = "MBC_BUSINESSCATEGORY_CODE";
        c_text = "MBC_DISPNAME_TXT";
        c_sort = "MBC_SORT_NUM";
        c_where = "";
      } else {
        c_table = "M_COMPANY";
        c_code = "MCM_COMPANY_CODE";
        c_text = "MCM_DISPNAME_TXT";
        c_sort = "MCM_SORT_NUM";
        c_where = "and MCM_BUSINESSCATEGORY_CODE='" + category + "' \n" + CommonSql.setAttributeSql2(selectcondBean);
      }

      // アクセス順の場合（その他は一番下にする）
      if (selectcondBean.getOrder().equals("ALLCOUNT"))
        c_sort = "DECODE(" + c_code + ",'UNDEFINED', -1, NVL(ALLCOUNT,0)) DESC";

      // 実行SQL作成
      StringBuffer q02 = new StringBuffer();
      q02.append("select " + c_code + " as C_CODE," + c_text + " as C_TEXT,q01.* \n");
      q02.append("from " + c_table + ",(" + q01 + ") q01 \n");
      q02.append("where S_CODE(+)=" + c_code + "\n");
      q02.append(c_where);
      q02.append("order by " + c_sort + "\n");

      // 必要な範囲のデータのみを抽出
      int pageSize;
      int offset;
      StringBuffer sql_row = new StringBuffer();
      if (category.equals("/")) {
        pageSize = 0;
        offset = 0;
        sql_row.append(q02);
      } else {
        pageSize = selectcondBean.getPageSize();
        offset = selectcondBean.getPageNo() * pageSize;
        sql_row.append(CommonSql.RowRange(q02.toString(), offset, pageSize + 1)); // 次ページがあるかチェックするため１つ多く読む
      }

      // htmlテーブルのヘッダ部作成
      StringBuffer tags = new StringBuffer();
      tags.append("<tr class='header'>");
      tags.append("<td class='header_no'>No.</td>\n");
      tags.append("<td class='header_txt'>業種名</td>\n");
      tags.append("<td class='header_txt'>アクセス</td>\n");
      tags.append("<td class='header_txt'>％</td>\n");
      tags.append("<td class='header_txt'>グラフ</td>\n");
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
      while(rs.next()) {
        // 次ページがあるかチェック
        if (pageSize != 0 && no > pageSize) {
          forwardPage = true;
          break;
        }

        tags.append("<tr class='data'>");

        // 番号
        tags.append("<td class='data_no'>").append(offset + no).append("</td>\n");

        // 業種（企業）名
        tags.append("<td class='data_category'>");
        tags.append(CommonFunc.linkCategoryValue(
            category,
            "CrossCategoryAccess",
            rs.getString("C_CODE").trim(),
            rs.getString("C_TEXT").trim()
          )
        );
        tags.append("</td>\n");

        // 合計アクセス数とパーセント
        int allCount = rs.getInt("ALLCOUNT");
        tags.append("<td class='data_access'>").append(numFormat.format(allCount)).append("</td>\n");
        tags.append("<td class='data_percent'>").append(CommonFunc.getParcent(total, allCount)).append("</td>\n");
        subTotal += allCount;

        // 棒グラフ
        if (!rs.getString("C_CODE").equals("UNDEFINED")) {
          tags.append("<td class='data_graph'>");
          tags.append(CommonFunc.genGraphImage(allCount, maxCount));//GIFの幅を計算
          tags.append("</td>\n");
        }
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