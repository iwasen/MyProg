package base;

import java.sql.*;
import java.util.*;


 /**
  * ���ʓI�Ȋ֐��̂���SQL���g�p������̂�u���Ă���
  */
public class CommonSql {

  /**
   * �w�肳�ꂽ�Ǝ�̔z���̊�Ƃ̐��𓾂�@���K�w�̏ꍇ�͋Ǝ퐔
  */
  static public int getCategoryCount(Statement st, String Category) throws SQLException {
      StringBuffer sql = new StringBuffer();

      if (Category.equals("/")) {
        // �Ǝ�
        sql.append("select Count(*) \n");
        sql.append("from M_BUSINESSCATEGORY \n");
      } else {
        // ���
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
   * �w�肳�ꂽ�R�[�i�[�̔z���̃R�[�i�̐��𓾂�i�R�[�i�[�̐[���Ɛe�R�[�i�������j
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
   * �w�肳�ꂽ�R�[�i�[�̔z���̃R�[�i�R�[�h�ƁA���̔z���ɂ܂��R�[�i�[�����邩��m��i�R�[�i�[�̐[���Ɛe�R�[�i�������j
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
   * �w�肳�ꂽ�Ǝ�̔z���̊�ƈꗗ���擾�A�@���K�w�̏ꍇ�͋Ǝ�ꗗ
   */
  static public ResultSet getCategoryResult(Statement st, String Category) throws SQLException {
      StringBuffer sql = new StringBuffer();

      // �Ǝ�R�[�h��������
      if (Category.equals("/")) {
        // �Ǝ�̎�ނ��T�[�`
        sql.append("select MBC_BUSINESSCATEGORY_CODE as CODE,MBC_DISPNAME_TXT as NAME from M_BUSINESSCATEGORY ORDER BY MBC_SORT_NUM ");
      } else {
        // ��Ђ̎�ނ��T�[�`
        sql.append("select MCM_COMPANY_CODE as CODE,MCM_DISPNAME_TXT as NAME from M_COMPANY where MCM_BUSINESSCATEGORY_CODE ='").append(Category).append("' ORDER BY MCM_SORT_NUM");
      }

      System.out.println(sql.toString());
      return st.executeQuery(sql.toString());
  }

 /**
  * ��Ƒ�������SQL
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
  * �w�肳�ꂽ�͈͂̍s�̂ݕԂ��悤�ɂ���
  */
  static public String RowRange(String sql, int offset, int limit) {
      return "select * \n"
           + "from (select ROWNUM as ROW_NO,r01.* from (" + sql + ") r01) \n"
           + "where ROW_NO between " + (offset + 1) + " and " + (offset + limit) + "\n";
  }

 /**
  * �R�[�i�[�R�[�h����R�[�i�[�K�w���擾����
  */
  static public int getCornerDepth(Statement st, String cornerCode) throws SQLException {
    String sql = "select MCN_DEPTH_NUM from M_CORNER where MCN_CORNER_CODE='" + cornerCode + "'";
    ResultSet rs = st.executeQuery(sql);
    if (rs.next())
      return rs.getInt(1);
    return 0;
  }

 /**
  * URL����R�[�i�[�R�[�h���擾
  */
  static public String getCornerFromUrl(Statement st, String url) throws SQLException {
    int len = url.length();
    int n;

    // URL����Ȃ�G���[
    if (len == 0)
      return null;

    // "http://"�Ŏn�܂��Ă�����z�X�g���܂ł���菜��
    if (len >= 7 && url.substring(0, 7).equals("http://")) {
      n = url.indexOf('/', 7);
      if (n == -1) {
        return null;
      }
      url = url.substring(n);
    }

    // �擪��"/"��������Εt������
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

  // ���̃V�[�P���X�ԍ����擾����
  static public int getNextSeq(Statement st, String seqName) throws SQLException {
    String sql = "select " + seqName + ".nextval from dual";
    ResultSet rs = st.executeQuery(sql);
    if (rs.next())
      return rs.getInt(1);
    else
      return 0;
  }
}
