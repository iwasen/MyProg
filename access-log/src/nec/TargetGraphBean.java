package xxx;
import base.*;
import java.sql.*;
import java.text.*;

/**
 * ����y�[�W�A�N�Z�X���ڃO���t���쐬
 */
public class TargetGraphBean implements I_Access_Page {
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
   * ����y�[�W�A�N�Z�X���ڃO���t�̒��g���쐬
   */
  public void searchDB(DataBase db, SelectCondBean selectcondBean) {
    try {
      // �ΏۃR�[�i�[���I������Ă��Ȃ���Ή������Ȃ�
      String targetCorner = selectcondBean.getTargetCorner();
      if (targetCorner.length() == 0)
        return;

      Statement st = db.createStatement();
      ResultSet rs;
      NumberFormat numFormat = NumberFormat.getNumberInstance();
      int i;

      // �J�e�S�����擾
      String category = selectcondBean.getCategory();

      // �ΏۃR�[�i�[ or URL���擾
      String table = selectcondBean.getTargetCornerTable();
      String corner = selectcondBean.getTargetCornerOrUrl();

      //�e�[�u���ʂ̃w�b�_�iURL�E�R�[�i�[�j
      String prefix = "", urlCorner = "";
      if (table.equals("CORNER")) {
        prefix = "xxxC";
        urlCorner = "[PF]_[TB]_CODE";
      } else {
        prefix = "STUC";
        urlCorner = "[PF]_[TB]_TXT";
      }

      // ���Ԃɂ��O���[�s���O����
      String dateTable = selectcondBean.getTable();
      String dateCol, dateFmt;
      if (dateTable.equals("DATE")) {
        dateCol = "trunc([PF]_ACCESSDH_DATE,'DD')";
        dateFmt = "YYYY/MM/DD";
      } else {
        dateCol = "[PF]_ACCESSDH_DATE";
        dateFmt = "YYYY/MM/DD HH24:MI:SS";
      }

      // �Ǝ�i��Ɓj�R�[�h�̃J������
      String s_code;
      if (category.equals("/"))
        s_code = "[PF]_BUSINESSCATEGORY_CODE";
      else
        s_code = "[PF]_COMPANY_CODE";

      // �s���Ƃ̃A�N�Z�X�����擾���邽�߂̃T�u�N�G��
      StringBuffer q01 = new StringBuffer();
      q01.append("select ");
      q01.append(s_code + " as S_CODE \n");
      q01.append(",sum([PF]_ACCESSCOUNT_NUM) as ALLCOUNT \n");
      q01.append("from S_TIME_[TB]_COMPANY \n");
      q01.append("where ");
      q01.append(dateCol + " between to_date('" + selectcondBean.getDateFrom() + "','" + dateFmt + "') and to_date('" + selectcondBean.getDateTo() + "','" + dateFmt + "') \n");
      q01.append("and " + urlCorner + "='" + corner + "' \n");
      if (!category.equals("/"))
        q01.append("and [PF]_BUSINESSCATEGORY_CODE='" + category + "' \n");
      CommonSql.setAttributeSql(q01, selectcondBean);
      q01.append("group by " + s_code);

      // �J�����̐ړ����ƃe�[�u����ʖ���u��
      CommonFunc.substitute(q01, "[PF]", prefix);
      CommonFunc.substitute(q01, "[TB]", table);

      // �Ǝ�i��Ɓj�����擾���邽�߂̏���
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

      // �A�N�Z�X���̏ꍇ�i���̑��͈�ԉ��ɂ���j
      if (selectcondBean.getOrder().equals("ALLCOUNT"))
        c_sort = "DECODE(" + c_code + ",'UNDEFINED', -1, NVL(ALLCOUNT,0)) DESC";

      // �S�A�N�Z�X���ƍő�A�N�Z�X�����擾���邽�߂�SQL�i���̑��͏����j
      StringBuffer sql_ttl = new StringBuffer();
      sql_ttl.append("select SUM(ALLCOUNT) as TOTAL,MAX(DECODE(S_CODE,'UNDEFINED', 0, ALLCOUNT)) as MAXCOUNT \n");
      sql_ttl.append("from (" + q01 + ") \n");

      // ���sSQL�쐬
      StringBuffer q02 = new StringBuffer();
      q02.append("select " + c_code + " as C_CODE," + c_text + " as C_TEXT,q01.* \n");
      q02.append("from " + c_table + ",(" + q01 + ") q01 \n");
      q02.append("where S_CODE(+)=" + c_code + "\n");
      q02.append(c_where);
      q02.append("order by " + c_sort + "\n");

      // �K�v�Ȕ͈͂̃f�[�^�݂̂𒊏o
      StringBuffer sql_row = new StringBuffer();
      int pageSize;
      int offset;
      if (category.equals("/")) {
        pageSize = 0;
        offset = 0;
        sql_row.append(q02);
      } else {
        pageSize = selectcondBean.getPageSize();
        offset = selectcondBean.getPageNo() * pageSize;
        sql_row.append(CommonSql.RowRange(q02.toString(), offset, pageSize + 1)); // ���y�[�W�����邩�`�F�b�N���邽�߂P�����ǂ�
      }

      // html�e�[�u���̃w�b�_���쐬
      StringBuffer tags= new StringBuffer();
      tags.append("<tr class='header'>");
      tags.append("<td class='header_no'>No.</td>\n");
      tags.append("<td class='header_txt'>�Ǝ햼</td>\n");
      tags.append("<td class='header_txt'>�A�N�Z�X</td>\n");
      tags.append("<td class='header_txt'>��</td>\n");
      tags.append("<td class='header_txt'>�O���t</td>\n");
      tags.append("<td class='header_txt'>����<br>�O���t<br>�{�^��</td>\n");
      tags.append("</tr>\n");

      // html�e�[�u���̍��v���쐬
      int total = 0, maxCount = 0;
      System.out.println(sql_ttl);
      rs = st.executeQuery(sql_ttl.toString());
      if (rs.next()) {
        // �S�A�N�Z�X���ƍő�A�N�Z�X���擾
        total = rs.getInt("TOTAL");
        maxCount = rs.getInt("MAXCOUNT");

        // �s�J�n
        tags.append("<tr class='total'>");

        // �ԍ��i�����Ȃ��j
        tags.append("<td><br></td>");

        // "���v"
        tags.append("<td class='total_txt'>���v</td>\n");

        // ���v�A�N�Z�X���ƃp�[�Z���g
        tags.append("<td class='total_num'>").append(numFormat.format(total)).append("</td>\n");
        tags.append("<td class='total_num'>").append(CommonFunc.getParcent(total, total)).append("</td>\n");

        // �O���t�i�����Ȃ��j
        tags.append("<td><br></td>");

        // ���ڃO���t�{�^���i�����Ȃ��j
        tags.append("<td><br></td>");

        // �s�I��
        tags.append("</tr>\n");
      }

      // html�e�[�u���̃f�[�^���쐬
      int subTotal = 0;
      int no = 1;
      forwardPage = false;
      backPage = (offset != 0);
      System.out.println(sql_row);
      rs = st.executeQuery(sql_row.toString());
      while(rs.next()) {
        // ���y�[�W�����邩�`�F�b�N
        if (pageSize != 0 && no > pageSize) {
          forwardPage = true;
          break;
        }

        tags.append("<tr class='data'>");

        // �ԍ�
        tags.append("<td class='data_no'>").append(no + offset).append("</td>\n");

        // �Ǝ�i��Ɓj��
        String categoryCode = rs.getString("C_CODE");
        tags.append("<td class='data_category'>");
        tags.append(CommonFunc.linkCategoryValue(
            category,
            "CrossCategoryAccess",
            categoryCode,
            rs.getString("C_TEXT").trim()
          )
        );
        tags.append("</td>\n");

        // ���v�A�N�Z�X���ƃp�[�Z���g
        int allCount = rs.getInt("ALLCOUNT");
        tags.append("<td class='data_access'>").append(numFormat.format(allCount)).append("</td>\n");
        tags.append("<td class='data_percent'>").append(CommonFunc.getParcent(total, allCount)).append("</td>\n");
        subTotal += allCount;

        // �_�O���t
        if (!categoryCode.equals("UNDEFINED")) {
          tags.append("<td class='data_graph'>");
          tags.append(CommonFunc.genGraphImage(allCount, maxCount));//GIF�̕����v�Z
        } else
          tags.append("<td>");
        tags.append("</td>\n");

        // ���ڃO���t�{�^��
        tags.append("<td class='data_suii'>");
        if (allCount != 0) {
          tags.append("<input type='button' onclick='open_graph(");
          tags.append("\"" + categoryCode + "\"");
          tags.append(")' value='�\��'>");
        }
        tags.append("</td>\n");

        tags.append("</tr>\n");

        no++;
      }

      // html�e�[�u���̏��v���쐬
      {
        // �s�J�n
        tags.append("<tr class='subtotal'>");

        // �ԍ��i�����Ȃ��j
        tags.append("<td><br></td>");

        // "���v"
        tags.append("<td class='subtotal_txt'>���v</td>\n");

        // ���v�A�N�Z�X���ƃp�[�Z���g
        tags.append("<td class='subtotal_num'>").append(numFormat.format(subTotal)).append("</td>\n");
        tags.append("<td class='subtotal_num'>").append(CommonFunc.getParcent(total, subTotal)).append("</td>\n");

        // �O���t�i�����Ȃ��j
        tags.append("<td><br></td>");

        // ���ڃO���t�{�^���i�����Ȃ��j
        tags.append("<td><br></td>");

        // �s�I��
        tags.append("</tr>\n");
      }

      dbdata = tags.toString();
    } catch (SQLException e) {
      dbdata = "<font color='red'>�W�v�������ɃG���[���������܂����B<br>" + e + "</font><br>";
			e.printStackTrace();
		}//try
  }

}