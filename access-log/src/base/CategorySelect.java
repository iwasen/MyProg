package base;

import java.sql.*;

/**
 * <p>�^�C�g��: �Ǝ�̑I�����X�g��\������</p>
 * <p>����: </p>
 * <p>���쌠: Copyright (c) 2003</p>
 * <p>��Ж�: </p>
 * @author aizawa
 * @version 1.0
 */

public class CategorySelect {
  static public String getCategorySelect(DataBase db, String categoryCode, String name) throws SQLException {
    StringBuffer strbuf = new StringBuffer();
    Statement st = db.createStatement();

    // <select>�^�O
    strbuf.append("<select name='" + name + "'>\n");

    // "�w�肵�Ȃ�"�ǉ�
    strbuf.append("<option value='/'>�w�肵�Ȃ�</option>\n");

    // �Ǝ�̈ꗗ�擾
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