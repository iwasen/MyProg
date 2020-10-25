package xxx;

import base.*;
import java.sql.*;
import java.text.*;
import java.io.*;
import java.util.*;

/**
 * アクセス元ランキンググラフを作成
 */
public class RefererInBean implements I_Access_Page {
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
   * アクセス元ランキンググラフの中身作成
   */
  public void searchDB(DataBase db, SelectCondBean selectcondBean) {
    try {
      // 対象コーナーが選択されていなければ何もしない
      String targetCorner = selectcondBean.getMoveToCorner();
      if (targetCorner.length() == 0)
        return;

      Statement st = db.createStatement();
      ResultSet rs;
      NumberFormat numFormat = NumberFormat.getNumberInstance();
      int i;

      // 対象コーナー or URLを取得
      String table = selectcondBean.getMoveToCornerTable();
      String corner = selectcondBean.getMoveToCornerOrUrl();

      // プロパティファイルからURLのホスト名を取得
      String urlHost = CommonFunc.getUrlHost();

      //テーブル別のヘッダ（URL・コーナー）
      String prefix = "", urlCorner = "";
      if (table.equals("CORNER")) {
        prefix = "SMTC";
        urlCorner = "[PF]_TO[TB]_CODE";
      } else {
        prefix = "SMTU";
        urlCorner = "[PF]_TO[TB]_TXT";
      }

      // 行ごとのアクセス数を取得するためのサブクエリ
      StringBuffer q01 = new StringBuffer();
      q01.append("select ");
      q01.append("[PF]_FROMCORNER_CODE as S_CORNER_CODE \n");
      q01.append(",sum([PF]_ACCESSCOUNT_NUM) as ALLCOUNT \n");
      q01.append("from S_MOVETO_[TB] \n");
      q01.append("where ");
      q01.append("[PF]_ACCESSDATE_DATE between '" + selectcondBean.getDateFrom() + "' and '" + selectcondBean.getDateTo() + "' \n");
      q01.append("and " + urlCorner + "='" + corner + "' \n");
      q01.append("and [PF]_FROMCORNER_CODE<>'-' \n");
      q01.append("group by [PF]_FROMCORNER_CODE");

      // カラムの接頭辞とテーブル種別名を置換
      CommonFunc.substitute(q01, "[PF]", prefix);
      CommonFunc.substitute(q01, "[TB]", table);

      // 全アクセス数と最大アクセス数を取得するためのSQL
      StringBuffer sql_ttl = new StringBuffer();
      sql_ttl.append("select SUM(ALLCOUNT) as TOTAL,MAX(ALLCOUNT) as MAXCOUNT \n");
      sql_ttl.append("from (" + q01 + ") \n");

      // 表を作成するためのサブクエリ
      StringBuffer q02 = new StringBuffer();
      q02.append("select nvl(MCN_CORNERNAME_TXT,S_CORNER_CODE) as S_CORNER_TXT,coalesce(MCN_TOPPAGEURL_TXT,MPO_LINKURL_TXT,S_CORNER_CODE) as S_LINKURL_TXT,q01.* \n");
      q02.append("from M_CORNER,M_PORTAL,(" + q01 + ") q01 \n");
      q02.append("where S_CORNER_CODE=MCN_CORNER_CODE(+) and S_CORNER_CODE=MPO_PORTAL_CODE(+) \n");
      q02.append("order by NVL(ALLCOUNT,0) DESC,S_CORNER_CODE \n");

      // 必要な範囲のデータのみを抽出
      StringBuffer sql_row = new StringBuffer();
      int pageSize;
      int offset;
      pageSize = selectcondBean.getPageSize();
      offset = selectcondBean.getPageNo() * pageSize;
      sql_row.append(CommonSql.RowRange(q02.toString(), offset, pageSize + 1)); // 次ページがあるかチェックするため１つ多く読む

      // htmlテーブルのヘッダ部作成
      StringBuffer tags= new StringBuffer();
      tags.append("<tr class='header'>");
      tags.append("<td class='header_no'>No.</td>\n");
      tags.append("<td class='header_txt'>コーナー名･ＵＲＬ</td>\n");
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
        tags.append("<td class='data_no'>").append(no + offset).append("</td>\n");

        // URL取得（先頭が"/"ならWebホスト名を付加）
        String url = rs.getString("S_LINKURL_TXT");
        if (url.charAt(0) == '/')
          url = urlHost + url;

        // コーナー名を取得（先頭が"/"ならWebホスト名を付加）
        String cornerName = rs.getString("S_CORNER_TXT");
        if (cornerName.charAt(0) == '/')
          cornerName = urlHost + cornerName;
        else
          cornerName = CommonFunc.getLaxxxornerName(cornerName);

        // コーナー名とURLのリンク
        tags.append("<td class='data_corner'>");
        tags.append("<a href='" + url + "' class='link' target='_blank'>" + cornerName + "</a>\n");
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
    } catch (Exception e) {
      dbdata = "<font color='red'>集計処理中にエラーが発生しました。<br>" + e + "</font><br>";
			e.printStackTrace();
		}//try
  }
}
