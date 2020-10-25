package base;

import java.sql.*;
import java.util.Date;

 /**
  * ��������������O���[�v�̕\���Ώۃf�[�^��Ԃ��֐��Q
  *
  */

public class GroupSelect {

  /**
   *�@���炩���ߓo�^���Ă��錟���Ώ�URL�̂����@�����̃O���[�v�̂��̂̂ݎ擾
   */
  static public String getTargetUrlList(DataBase db, String GroupCode){
    try{
		  Statement st = db.createStatement();
      //�ΏۂƂȂ�T�͍������Q�����O�܂�
      StringBuffer sql= new StringBuffer();
      sql.append("select * from M_GETHOUR_URL \n");
      sql.append(" where MGU_GROUP_CODE ='").append(GroupCode).append("' \n");
      sql.append(" and MGU_STARTDH_DATE < sysdate \n");
      System.out.println(sql.toString());
      ResultSet rs = st.executeQuery(sql.toString());
      StringBuffer tags = new StringBuffer();
      tags.append("<SELECT name=URLLIST>");
      while(rs.next()){
        tags.append("<option value='").append(rs.getString("MGU_URL_TXT").trim()).append("'>");
        tags.append(rs.getString("MGU_URL_TXT").trim());
        tags.append("</option>");
      }
      tags.append("</SELECT>");
      return tags.toString();
    }catch(SQLException e){
			e.printStackTrace();
		}//try
      return "";
  }

  /**
   *�@���炩���ߓo�^���Ă��錟���ΏۃR�[�i�[�̂����@�����̃O���[�v�̂��̂̂ݎ擾
   */
  static public String getTargetCornerList(DataBase db, String GroupCode){
    try{
		  Statement st = db.createStatement();
      //�ΏۂƂȂ�T�͍������Q�����O�܂�
      StringBuffer sql= new StringBuffer();
      sql.append("select * from M_GETHOUR_CORNER \n");
      sql.append(" where MGC_GROUP_CODE ='").append(GroupCode).append("' \n");
      sql.append(" and MGC_STARTDH_DATE < sysdate \n");
      System.out.println(sql.toString());
      ResultSet rs = st.executeQuery(sql.toString());
      StringBuffer tags= new StringBuffer();
      tags.append("<SELECT name=CORNERLIST>");
      while(rs.next()){
        tags.append("<option value='").append(rs.getString("MGC_CORNER_CODE").trim()).append("'>");
        tags.append(rs.getString("MGC_CORNER_CODE").trim());
        tags.append("</option>");
      }
      tags.append("</SELECT>");
      return tags.toString();
    }catch(SQLException e){
			e.printStackTrace();
		}//try
      return "";
  }


}