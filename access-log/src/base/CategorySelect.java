package base;

import java.sql.*;

/**
 * <p>タイトル: 業種の選択リストを表示する</p>
 * <p>説明: </p>
 * <p>著作権: Copyright (c) 2003</p>
 * <p>会社名: </p>
 * @author aizawa
 * @version 1.0
 */

public class CategorySelect {
  static public String getCategorySelect(DataBase db, String categoryCode, String name) throws SQLException {
    StringBuffer strbuf = new StringBuffer();
    Statement st = db.createStatement();

    // <select>タグ
    strbuf.append("<select name='" + name + "'>\n");

    // "指定しない"追加
    strbuf.append("<option value='/'>指定しない</option>\n");

    // 業種の一覧取得
    String sql = "select MBC_BUSINESSCATEGORY_CODE,MBC_DISPNAME_TXT \n"
               + "from M_BUSINESSCATEGORY \n"
               + "order by MBC_SORT_NUM \n";
    ResultSet rs = st.executeQuery(sql);
    while (rs.next()) {
      strbuf.append(CommonFunc.getOptionTag(rs.getString("MBC_BUSINESSCATEGORY_CODE"), rs.getString("MBC_DISPNAME_TXT"), categoryCode));
    }

    strbuf.append("</select>\n");

    return strbuf.toString();
  }
}