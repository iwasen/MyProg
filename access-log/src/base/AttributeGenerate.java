package base;
import java.sql.*;

/**
 * 企業属性検索用のリストボックスを作成
 */

public class AttributeGenerate {

  /**
   *検索対象　となる企業属性のリスト表示
   *
   */
  static public String getAttribute(DataBase db, int attri_code, SelectCondBean SelectCondBeanId) {

    try {
      Statement st = db.createStatement();

      // 選択中の企業属性コード取得
      String sessionVal = getAttributeVal(SelectCondBeanId.getAttributeValue(attri_code));

      // 企業属性名称取得
      ResultSet rs = db.getContrxxxaster();
      String attrName = rs.getString("MCR_ATTRIBUTENAME" + (attri_code + 1) + "_TXT");

      // nullは未使用
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
      tags.append("<SELECT name='attribute" + attri_code + "'><option value='0'>" + attrName + "を指定</option>");
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
 * 企業属性は　HTML内では　　属性値：属性名　で書き込まれている
 * この関数はこれから属性値だけを返す
 */
  static public String getAttributeVal(String sessionVal){
      if(sessionVal.indexOf(":")>0){
        sessionVal=sessionVal.substring(0,sessionVal.indexOf(":"));
      }
      return sessionVal;
  }

/**
 * 企業属性は　HTML内では　　属性値：属性名　で書き込まれている
 * この関数はこれから属性名だけを返す
 */
  static public String getAttributeText(String sessionVal){
      if(sessionVal.indexOf(":")>0){
        sessionVal=sessionVal.substring(sessionVal.indexOf(":")+1,sessionVal.length());
      }
      return sessionVal;
  }

}