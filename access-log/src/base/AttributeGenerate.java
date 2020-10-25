package base;
import java.sql.*;

/**
 * ��Ƒ��������p�̃��X�g�{�b�N�X���쐬
 */

public class AttributeGenerate {

  /**
   *�����Ώہ@�ƂȂ��Ƒ����̃��X�g�\��
   *
   */
  static public String getAttribute(DataBase db, int attri_code, SelectCondBean SelectCondBeanId) {

    try {
      Statement st = db.createStatement();

      // �I�𒆂̊�Ƒ����R�[�h�擾
      String sessionVal = getAttributeVal(SelectCondBeanId.getAttributeValue(attri_code));

      // ��Ƒ������̎擾
      ResultSet rs = db.getContrxxxaster();
      String attrName = rs.getString("MCR_ATTRIBUTENAME" + (attri_code + 1) + "_TXT");

      // null�͖��g�p
      if (attrName == null)
        return "";

      StringBuffer sql=new StringBuffer();
      sql.append("select * ");
      sql.append(" from M_COMPANYATTRIBUTE \n");
      sql.append(" where \n");
      sql.append("MCA_ATTRIBUTE_CLS ='").append(attri_code + 1).append("' \n");
      sql.append("order by MCA_ATTRIBUTE_CODE \n");

      System.out.println(sql.toString());
      rs = st.executeQuery(sql.toString());
      StringBuffer tags=new StringBuffer();
      tags.append("<SELECT name='attribute" + attri_code + "'><option value='0'>" + attrName + "���w��</option>");
      while(rs.next()){
        tags.append("<option value='");
        tags.append(rs.getString("MCA_ATTRIBUTE_CODE")).append(":").append(rs.getString("MCA_ATTRIBUTENAME_TXT"));
        tags.append("' ");
        tags.append(CommonFunc.selectedFunc(rs.getString("MCA_ATTRIBUTE_CODE"),sessionVal));
        tags.append(">");
        tags.append(CommonFunc.escapeHtml(rs.getString("MCA_ATTRIBUTENAME_TXT")));
        tags.append("</option>");
      }
      tags.append("</SELECT>");
      return tags.toString();
    } catch (SQLException e) {
			e.printStackTrace();
		}//try
      return "";
  }


/**
 * ��Ƒ����́@HTML���ł́@�@�����l�F�������@�ŏ������܂�Ă���
 * ���̊֐��͂��ꂩ�瑮���l������Ԃ�
 */
  static public String getAttributeVal(String sessionVal){
      if(sessionVal.indexOf(":")>0){
        sessionVal=sessionVal.substring(0,sessionVal.indexOf(":"));
      }
      return sessionVal;
  }

/**
 * ��Ƒ����́@HTML���ł́@�@�����l�F�������@�ŏ������܂�Ă���
 * ���̊֐��͂��ꂩ�瑮����������Ԃ�
 */
  static public String getAttributeText(String sessionVal){
      if(sessionVal.indexOf(":")>0){
        sessionVal=sessionVal.substring(sessionVal.indexOf(":")+1,sessionVal.length());
      }
      return sessionVal;
  }

}