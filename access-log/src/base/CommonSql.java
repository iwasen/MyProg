package base;

import java.sql.*;
import java.util.*;


 /**
  * 共通的な関数のうちSQLを使用するものを置いておく
  */
public class CommonSql {

  /**
   * 指定された業種の配下の企業の数を得る　第一階層の場合は業種数
  */
  static public int getCategoryCount(Statement st, String Category) throws SQLException {
      StringBuffer sql = new StringBuffer();

      if (Category.equals("/")) {
        // 業種
        sql.append("select Count(*) \n");
        sql.append("from M_BUSINESSCATEGORY \n");
      } else {
        // 企業
        sql.append("select Count(*) \n");
        sql.append("from M_COMPANY \n");
        sql.append("where MCM_BUSINESSCATEGORY_CODE='" + Category + "' \n");
      }

      System.out.println(sql.toString());
      ResultSet rs = st.executeQuery(sql.toString());
      if(rs.next())
        return rs.getInt(1);

      return 0;
  }

  /**
   * 指定されたコーナーの配下のコーナの数を得る（コーナーの深さと親コーナが条件）
   *
   */
  static public int getCornerCount(Statement st, String ParentCode, int cornerdepth) throws SQLException {
      StringBuffer sql = new StringBuffer();

      sql.append("select Count(*) \n");
      sql.append("from m_corner \n");
      sql.append("where \n");
      sql.append("  MCN_DEPTH_NUM=").append(cornerdepth);
      sql.append("  and MCN_CORNER_CODE like '").append(ParentCode).append("%' \n");

      System.out.println(sql.toString());
      ResultSet rs = st.executeQuery(sql.toString());
      if(rs.next())
        return rs.getInt(1);

      return 0;
  }

  /**
   * 指定されたコーナーの配下のコーナコードと、その配下にまだコーナーがあるかを知る（コーナーの深さと親コーナが条件）
   */
  static public ResultSet getCornerResult(Statement st, String ParentCode, int cornerdepth) throws SQLException {

    String sql;

    sql = "select MCN_CORNER_CODE as cornercode,MCN_CORNERNAME_TXT as cornername,decode(MCN_LASTLEVEL_FLG,'0',1,0) as link \n"
        + "from m_corner t1 \n"
        + "where MCN_DEPTH_NUM=" + cornerdepth + " and MCN_CORNER_CODE like '" + ParentCode + "%' \n"
        + "order by MCN_SORT" + cornerdepth + "_NUM";

    System.out.println(sql);
    return st.executeQuery(sql);
  }

  /**
   * 指定された業種の配下の企業一覧を取得、　第一階層の場合は業種一覧
   */
  static public ResultSet getCategoryResult(Statement st, String Category) throws SQLException {
      StringBuffer sql = new StringBuffer();

      // 業種コードをかえす
      if (Category.equals("/")) {
        // 業種の種類をサーチ
        sql.append("select MBC_BUSINESSCATEGORY_CODE as CODE,MBC_DISPNAME_TXT as NAME from M_BUSINESSCATEGORY ORDER BY MBC_SORT_NUM ");
      } else {
        // 会社の種類をサーチ
        sql.append("select MCM_COMPANY_CODE as CODE,MCM_DISPNAME_TXT as NAME from M_COMPANY where MCM_BUSINESSCATEGORY_CODE ='").append(Category).append("' ORDER BY MCM_SORT_NUM");
      }

      System.out.println(sql.toString());
      return st.executeQuery(sql.toString());
  }

 /**
  * 企業属性条件SQL
  */
  static public void setAttributeSql(StringBuffer sql, SelectCondBean selectcondBean) {
    if (selectcondBean.getAttributeFlg()) {
      sql.append(" and exists (select * from M_COMPANY where MCM_COMPANY_CODE=[PF]_COMPANY_CODE \n");
      sql.append(setAttributeSql2(selectcondBean));
      sql.append(") \n");
    }
  }

  static public String setAttributeSql2(SelectCondBean selectcondBean) {
    StringBuffer sql = new StringBuffer();

    if (selectcondBean.getAttributeFlg()) {
      for (int i = 0; i < selectcondBean.getAttribute().length; i++) {
        if (!selectcondBean.getAttributeValue(i).equals("0")) {
          sql.append("and MCM_ATTRIBUTE" + (i + 1) + "_CODE='");
          sql.append(AttributeGenerate.getAttributeVal(selectcondBean.getAttributeValue(i))).append("' \n");
        }
      }
    }

    return sql.toString();
  }

 /**
  * 指定された範囲の行のみ返すようにする
  */
  static public String RowRange(String sql, int offset, int limit) {
      return "select * \n"
           + "from (select ROWNUM as ROW_NO,r01.* from (" + sql + ") r01) \n"
           + "where ROW_NO between " + (offset + 1) + " and " + (offset + limit) + "\n";
  }

 /**
  * コーナーコードからコーナー階層を取得する
  */
  static public int getCornerDepth(Statement st, String cornerCode) throws SQLException {
    String sql = "select MCN_DEPTH_NUM from M_CORNER where MCN_CORNER_CODE='" + cornerCode + "'";
    ResultSet rs = st.executeQuery(sql);
    if (rs.next())
      return rs.getInt(1);
    return 0;
  }

 /**
  * URLからコーナーコードを取得
  */
  static public String getCornerFromUrl(Statement st, String url) throws SQLException {
    int len = url.length();
    int n;

    // URLが空ならエラー
    if (len == 0)
      return null;

    // "http://"で始まっていたらホスト名までを取り除く
    if (len >= 7 && url.substring(0, 7).equals("http://")) {
      n = url.indexOf('/', 7);
      if (n == -1) {
        return null;
      }
      url = url.substring(n);
    }

    // 先頭に"/"が無ければ付加する
    if (url.charAt(0) != '/')
      url = "/" + url;

    do {
      String sql = "select TUC_CORNER_CODE \n"
                 + "from T_UNITCORNER_CORNER,T_URL_UNITCORNER \n"
                 + "where lower(TUU_URL_TXT)='" + url + "' AND TUC_UNITCORNER_CODE=TUU_UNITCORNER_CODE \n";
      ResultSet rs = st.executeQuery(sql);
      if (rs.next())
        return rs.getString(1);

      n = url.lastIndexOf('/', url.length() - 2);
      if (n == -1)
        break;

      url = url.substring(0, n + 1);
    } while (!url.equals("/"));

    return null;
  }

  // 次のシーケンス番号を取得する
  static public int getNextSeq(Statement st, String seqName) throws SQLException {
    String sql = "select " + seqName + ".nextval from dual";
    ResultSet rs = st.executeQuery(sql);
    if (rs.next())
      return rs.getInt(1);
    else
      return 0;
  }
}
