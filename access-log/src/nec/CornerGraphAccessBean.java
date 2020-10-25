package xxx;
import base.*;
import java.sql.*;
import java.text.*;

/**
 * �R�[�i�O���t����Ăяo����鎞�n��O���t���v�\���쐬
 */
public class CornerGraphAccessBean  implements I_Access_Page {
  private String dbdata = "DB value";

  public String getDbdata() {
    return dbdata;
  }

  /**
   * �R�[�i�[���n��A�N�Z�X�̃e�[�u���̒��g���쐬
   */
  public void searchDB(DataBase db, SelectCondBean selectcondBean) {
    try {
      Statement st = db.createStatement();
      ResultSet rs;
      NumberFormat numFormat = NumberFormat.getNumberInstance();
      int i;

      // �R�[�i�[�R�[�h���擾
      String corner = selectcondBean.getCorner();

      // �e�[�u���ʂ̃w�b�_�@���E�T�E���ʕω�
      String table = selectcondBean.getTable();
      String prefix = "", dateUnit = "";
      if (selectcondBean.getTable().equals("MONTH")) {
        prefix = "SMCC";
        dateUnit = "��";
      } else if (selectcondBean.getTable().equals("WEEK")) {
        prefix = "SWCC";
        dateUnit = "�T";
      } else if (selectcondBean.getTable().equals("DATE")) {
        prefix = "SDCC";
        dateUnit = "��";
      }

      // �\�f�[�^�擾SQL�@�c���F����
      StringBuffer sbuf;
      String select, where;

      // select��ҏW
      sbuf = new StringBuffer();
      sbuf.append("[PF]_ACCESS[TB]_DATE as C_DATE \n");
      sbuf.append(",sum([PF]_ACCESSCOUNT_NUM) as ALLCOUNT \n");
      select = sbuf.toString();

      // where��ҏW
      sbuf = new StringBuffer();
      sbuf.append("[PF]_ACCESS[TB]_DATE between '" + selectcondBean.getDateFrom() + "' and '" + selectcondBean.getDateTo() + "'\n");
      sbuf.append("and [PF]_CORNER_CODE='" + corner + "'\n");
//      sbuf.append("and [PF]_BUSINESSCATEGORY_CODE<>'UNDEFINED' \n");
      CommonSql.setAttributeSql(sbuf, selectcondBean);
      where = sbuf.toString();

      // �s���Ƃ̃A�N�Z�X�����擾���邽�߂̃T�u�N�G��
      StringBuffer s01 = new StringBuffer();
      s01.append("select " + select);
      s01.append("from S_[TB]_CORNER_COMPANY \n");
      s01.append("where " + where);
      s01.append("group by [PF]_ACCESS[TB]_DATE");

      // �ő�A�N�Z�X�����擾���邽�߂̃T�u�N�G��
      StringBuffer s02 = new StringBuffer();
      s02.append("select MAX(ALLCOUNT) as MAXCOUNT \n");
      s02.append("from (" + s01.toString() + ") \n");

      // �T�u�N�G�����܂Ƃ߂Ď��sSQL�쐬�i�x���������Ŏ擾�j
      StringBuffer sql = new StringBuffer();
      sql.append("select s01.*,s02.*,MHD_HOLIDAY_CLS \n");
      sql.append("from (" + s01.toString() + ") s01 \n");
      sql.append(",(" + s02.toString() + ") s02 \n");
      sql.append(",M_HOLIDAY \n");
      sql.append("where C_DATE=MHD_HOLIDAY_DATE(+) \n");
      sql.append("order by C_DATE \n");

      // �J�����̐ړ����ƃe�[�u����ʖ���u��
      CommonFunc.substitute(sql, "[PF]", prefix);
      CommonFunc.substitute(sql, "[TB]", table);

      // SQL���s
      System.out.println(sql);
      rs = st.executeQuery(sql.toString());

      // html�e�[�u���̃w�b�_���쐬
      StringBuffer tags= new StringBuffer();
      tags.append("<tr class='header'>");
      tags.append("<td class='header_txt' width='100'>" + dateUnit + "</td>\n");
      tags.append("<td class='header_txt'>�A�N�Z�X</td>\n");
      tags.append("<td class='header_txt'>�O���t</td>\n");
      tags.append("</tr>\n");

      // html�e�[�u���̃f�[�^���쐬
      while(rs.next()){
        tags.append("<tr class='data'>");

        // ����
        tags.append("<td class='data_date'>");
        tags.append(CommonFunc.getTimePeriod(table, rs.getDate("C_DATE"), rs.getString("MHD_HOLIDAY_CLS")));
        tags.append("</td>\n");

        // ���v�A�N�Z�X��
        int allCount = rs.getInt("ALLCOUNT");
        tags.append("<td class='data_access'>").append(numFormat.format(allCount)).append("</td>\n");

        // �_�O���t
        tags.append("<td class='data_graph'>");
        tags.append(CommonFunc.genGraphImage(allCount, rs.getInt("MAXCOUNT")));//GIF�̕����v�Z
        tags.append("</td>\n");

        tags.append("</tr>\n");
      }

      dbdata = tags.toString();
    } catch (SQLException e) {
      dbdata = "<font color='red'>�W�v�������ɃG���[���������܂����B<br>" + e + "</font><br>";
			e.printStackTrace();
		}//try
  }

}