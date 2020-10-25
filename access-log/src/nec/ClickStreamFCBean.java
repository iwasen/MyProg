package xxx;

import base.*;
import java.sql.*;

/**
 * �N���b�N�X�g���[���W�v�e�[�u�����쐬
 */
public class ClickStreamFCBean {
  public void searchDB(DataBase db, SelectCondBean selectcondBean) {
    try {
      Statement st = db.createStatement();

      // �e�[�u��ID���擾
      String tableID = selectcondBean.getStreamTableID();

      // �n�_�A�I�_�A���ԓ_���擾�i���ԓ_�͑O�ɋl�߂�j
      String streamStart = BlankToNull(selectcondBean.getStreamSelectCorner(0));
      String streamEnd = BlankToNull(selectcondBean.getStreamSelectCorner(4));
      String streamMid[] = new String[3];
      for (int n = 0, i = 1; i <= 3; i++) {
        if (selectcondBean.getStreamSelectCorner(i).length() != 0)
          streamMid[n++] = BlankToNull(selectcondBean.getStreamSelectCorner(i));
      }

      // �X�g�A�h(CreateStreamTableFC)�����s
      StringBuffer sql = new StringBuffer();
      sql.append("declare \n");
      sql.append("ret integer; \n");
      sql.append("begin \n");
      sql.append("ret:=CreateStreamTableFC( \n");
      sql.append(" " + streamStart);
      sql.append("," + streamEnd);
      sql.append("," + streamMid[0]);
      sql.append("," + streamMid[1]);
      sql.append("," + streamMid[2]);
      sql.append(",'" + selectcondBean.getDateFrom() + "'");
      sql.append(",'" + selectcondBean.getDateTo() + "'");
      sql.append("," + tableID + "); \n");
      sql.append("end; \n");
      System.out.println(sql);
      st.execute(sql.toString());

      // �e�[�u��ID��ۑ�
      selectcondBean.setStreamTableID(tableID);
    } catch (Exception e) {
      e.printStackTrace();
    }
  }

  // �u�����N��null�ɕϊ�
  private String BlankToNull(String str) {
    if (str == null || str.length() == 0)
      return "null";
    else
      return "'" + str + "'";
  }
}