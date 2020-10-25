package xxx;
import base.*;
import java.sql.*;
import java.text.*;

/**
 * �R�[�i�[�ʃA�N�Z�X���ڃO���t���쐬
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
   * �R�[�i�[�ʃA�N�Z�X���ڃO���t�̒��g���쐬
   */
  public void searchDB(DataBase db, SelectCondBean selectcondBean) {
    try {
      Statement st = db.createStatement();
      ResultSet rs;
      NumberFormat numFormat = NumberFormat.getNumberInstance();
      int i;

      // �R�[�i�[���A�K�w���擾
      String corner = selectcondBean.getCorner();
      int cornerDepth = Integer.parseInt(selectcondBean.getCornerdepth());

      // �e�[�u���ʂ̃w�b�_�@���E�T�E���ʕω�
      String table = selectcondBean.getTable();
      String prefix = "";
      if (selectcondBean.getTable().equals("MONTH")) {
        prefix = "SMCC";
      } else if (selectcondBean.getTable().equals("WEEK")) {
        prefix = "SWCC";
      } else if (selectcondBean.getTable().equals("DATE")) {
        prefix = "SDCC";
      }

      // �s���Ƃ̃A�N�Z�X�����擾���邽�߂̃T�u�N�G��
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

      // �J�����̐ړ����ƃe�[�u����ʖ���u��
      CommonFunc.substitute(q01, "[PF]", prefix);
      CommonFunc.substitute(q01, "[TB]", table);

      // �\����
      String c_sort;
      if (selectcondBean.getOrder().equals("ALLCOUNT"))
        c_sort = "nvl(ALLCOUNT,0) DESC";
      else
        c_sort = "MCN_SORT" + cornerDepth + "_NUM";

      // �S�A�N�Z�X���ƍő�A�N�Z�X�����擾���邽�߂�SQL
      StringBuffer sql_ttl = new StringBuffer();
      sql_ttl.append("select SUM(ALLCOUNT) as TOTAL,MAX(ALLCOUNT) as MAXCOUNT \n");
      sql_ttl.append("from (" + q01 + ") \n");

      // �R�[�i�����擾���邽�߂̃T�u�N�G����
      StringBuffer q02 = new StringBuffer();
      q02.append("select MCN_CORNER_CODE as C_CODE,MCN_CORNERNAME_TXT as C_TEXT,q01.*,decode(MCN_LASTLEVEL_FLG,'0',1,0) as CHILD \n");
      q02.append("from M_CORNER t1,(" + q01 + ") q01 \n");
      q02.append("where comm.getParentCornerCode(MCN_CORNER_CODE)='" + corner + "' \n");
      q02.append(" and S_CODE(+)=MCN_CORNER_CODE \n");
      q02.append(" and MCN_DEPTH_NUM=" + cornerDepth + "\n");
      q02.append("order by " + c_sort + "\n");

      // �K�v�Ȕ͈͂̃f�[�^�݂̂𒊏o
      StringBuffer sql_row = new StringBuffer();
      int pageSize = selectcondBean.getPageSize();
      int offset = selectcondBean.getPageNo() * pageSize;
      sql_row.append(CommonSql.RowRange(q02.toString(), offset, pageSize + 1)); // ���y�[�W�����邩�`�F�b�N���邽�߂P�����ǂ�

      // html�e�[�u���̃w�b�_���쐬
      StringBuffer tags= new StringBuffer();
      tags.append("<tr class='header'>");
      tags.append("<td class='header_no'>No.</td>\n");
      tags.append("<td class='header_txt'>�R�[�i�[��</td>\n");
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
      while(rs.next()){
        // ���y�[�W�����邩�`�F�b�N
        if (pageSize != 0 && no > pageSize) {
          forwardPage = true;
          break;
        }

        tags.append("<tr class='data'>");

        // �ԍ�
        tags.append("<td class='data_no'>").append(offset + no).append("</td>\n");

        // �R�[�i��
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

        // ���v�A�N�Z�X���ƃp�[�Z���g
        int allCount = rs.getInt("ALLCOUNT");
        tags.append("<td class='data_access'>").append(numFormat.format(allCount)).append("</td>\n");
        tags.append("<td class='data_percent'>").append(CommonFunc.getParcent(total, allCount)).append("</td>\n");
        subTotal += allCount;

        // �_�O���t
        tags.append("<td class='data_graph'>");
        tags.append(CommonFunc.genGraphImage(allCount, maxCount));//GIF�̕����v�Z
        tags.append("</td>\n");

        // ���ڃO���t�{�^��
        tags.append("<td class='data_suii'>");
        if (allCount != 0) {
          tags.append("<input type='button' onclick='open_graph(");
          tags.append("\"" + rs.getString("C_CODE").trim() + "\"");
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