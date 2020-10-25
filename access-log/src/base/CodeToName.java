package base;
import java.sql.*;

/**
 * �R�[�h���疼�̂����蓖�Ă�֐��������Ɋi�[
 */

public class CodeToName {

  //�J�e�S���R�[�h����J�e�S�����擾
  static public String BCToName(DataBase db, String BCCode){
    try{
      Statement st = db.createStatement();

      StringBuffer sql=new StringBuffer();
      sql.append("select MBC_DISPNAME_TXT \n");
      sql.append(" from M_BUSINESSCATEGORY \n");
      sql.append("WHERE MBC_BUSINESSCATEGORY_CODE ='").append(BCCode).append("' \n");
      System.out.println(sql.toString());
      ResultSet rs = st.executeQuery(sql.toString());
      if(rs.next()){
          System.out.println(rs.getString("MBC_DISPNAME_TXT").trim());
         return rs.getString("MBC_DISPNAME_TXT").trim();
      }
    }catch(SQLException e){
			e.printStackTrace();
		}//try
      return "";
  }

  //�R�[�i�R�[�h����R�[�i�[���擾
  static public String CNToName(DataBase db, String CNCode){
    try{
      Statement st = db.createStatement();

      StringBuffer sql=new StringBuffer();
      sql.append("select MCN_CORNERNAME_TXT \n");
      sql.append(" from M_CORNER \n");
      sql.append("WHERE MCN_CORNER_CODE ='").append(CNCode).append("' \n");
      System.out.println(sql.toString());
      ResultSet rs = st.executeQuery(sql.toString());
      if(rs.next()){
         return rs.getString("MCN_CORNERNAME_TXT").trim();
      }else{
         return "����";
      }
    }catch(SQLException e){
			e.printStackTrace();
		}//try
    return "";
  }

  //��ƃR�[�h�����Ɩ��擾
  static public String CCToName(DataBase db, String CCCode){
    try{
      Statement st = db.createStatement();

      StringBuffer sql=new StringBuffer();
      sql.append("select MCM_DISPNAME_TXT \n");
      sql.append("from M_COMPANY ");
      sql.append(" WHERE MCM_COMPANY_CODE ='").append(CCCode).append("' \n");
      System.out.println(sql.toString());
      ResultSet rs = st.executeQuery(sql.toString());
      if(rs.next()){
         return rs.getString("MCM_DISPNAME_TXT").trim();
      }
    }catch(SQLException e){
			e.printStackTrace();
		}//try
    return "";
  }

  //�Ǝ�R�[�h����Ǝ햼�擾
  static public String CAToName(DataBase db, String CACode){
    try{
      Statement st = db.createStatement();

      StringBuffer sql=new StringBuffer();
      sql.append("select MBC_DISPNAME_TXT \n");
      sql.append("from M_BUSINESSCATEGORY \n");
      sql.append(" WHERE MBC_BUSINESSCATEGORY_CODE ='").append(CACode).append("' \n");
      System.out.println(sql.toString());
      ResultSet rs = st.executeQuery(sql.toString());
      if(rs.next()){
         return rs.getString("MBC_DISPNAME_TXT").trim();
      }
    }catch(SQLException e){
			e.printStackTrace();
		}//try
    return "";
  }

  //����R�[�h���畔�喼�擾
  static public String GRToName(DataBase db, String GRCode) {
    if (GRCode.equals(""))
      return "�S��";

    try {
      Statement st = db.createStatement();

      StringBuffer sql = new StringBuffer();
      sql.append("select MGR_GROUPNAME_TXT \n");
      sql.append("from M_GROUP \n");
      sql.append(" WHERE MGR_GROUP_CODE ='").append(GRCode).append("' \n");
      System.out.println(sql.toString());
      ResultSet rs = st.executeQuery(sql.toString());
      if(rs.next()) {
         return rs.getString("MGR_GROUPNAME_TXT").trim();
      }
    } catch (SQLException e) {
			e.printStackTrace();
		}//try
    return "";
  }

}