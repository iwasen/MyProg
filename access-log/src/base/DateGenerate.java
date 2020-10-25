package base;
import xxx.*;
import java.sql.*;
import java.util.Date;
import java.util.*;
import java.text.*;
/**
 * �^�C�g��:  ���t�쐬�r�[��
 * ����:   ���t�Ɋւ���֐��Q�������ɂ܂Ƃ߂�
 */
public class DateGenerate {
    static GregorianCalendar cal=null;

  /**
   *�����̏��߂̓���Ԃ��֐��@
   */
  static public String getMonthFirst(){
    cal =  new GregorianCalendar();
    cal.getInstance();
    cal.setTime(new Date());

    //����N���擾����Ƃ��Ɏg���܂��B
    int year = cal.get(Calendar.YEAR); // 2001/4/28 �̂Ƃ� 2001 �𓾂܂�
    //�����擾����Ƃ��Ɏg���܂��B(0�`11)
    int month = cal.get(Calendar.MONTH) + 1; // 2001/4/28 �̂Ƃ� 4 �𓾂܂�

    return String.valueOf(year) + "/" + CommonFunc.numToString(month) + "/01";
  }

  /**
   *�����̍Ō�̓���Ԃ��֐��@
   */
  static public String getMonthLast(){
    cal =  new GregorianCalendar();
    cal.getInstance();
    cal.setTime(new Date());

    //����N���擾����Ƃ��Ɏg���܂��B
    int year = cal.get(Calendar.YEAR); // 2001/4/28 �̂Ƃ� 2001 �𓾂܂�
    //�����擾����Ƃ��Ɏg���܂��B(0�`11)
    int month = cal.get(Calendar.MONTH) + 1; // 2001/4/28 �̂Ƃ� 4 �𓾂܂�
    cal.set(year,month,0); //0���Z�b�g����Ɓ@���̍Ō�̓��t�ɂȂ�炵���B
    //�����擾����Ƃ��Ɏg���܂��B(1�`31)
    int date= cal.get(Calendar.DATE); // 2001/4/28 �̂Ƃ� 28 �𓾂܂�
    return String.valueOf(year) + "/" + CommonFunc.numToString(month) + "/" + CommonFunc.numToString(date);
  }

  /**
   *�����Ώہ@�ƂȂ�T�̌������X�g�\��
   */
  static public String getSearchWeek(DataBase db, String DateParam, SelectCondBean SelectCondBeanId){
    cal =  new GregorianCalendar();
    cal.getInstance();
    cal.setTime(new Date());

    //����N���擾����Ƃ��Ɏg���܂��B
    int to_year = cal.get(Calendar.YEAR); // 2001/4/28 �̂Ƃ� 2001 �𓾂܂�
    //�����擾����Ƃ��Ɏg���܂��B(0�`11)
    int to_month = cal.get(Calendar.MONTH) + 1; // 2001/4/28 �̂Ƃ� 4 �𓾂܂�
    int to_date= cal.get(Calendar.DATE); // 2001/4/28 �̂Ƃ� 28 �𓾂܂�

    cal.add(Calendar.MONTH,-2); //2�����O�̓��t�쐬

    //����N���擾����Ƃ��Ɏg���܂��B
    int from_year = cal.get(Calendar.YEAR); // 2001/4/28 �̂Ƃ� 2001 �𓾂܂�
    //�����擾����Ƃ��Ɏg���܂��B(0�`11)
    int from_month = cal.get(Calendar.MONTH) + 1; // 2001/4/28 �̂Ƃ� 4 �𓾂܂�
    int from_date= cal.get(Calendar.DATE); // 2001/4/28 �̂Ƃ� 28 �𓾂܂�

    String sessionVal="";
    String SELECT_POINT="MWD_WEEKSTARTDATE_DATE";
    if(DateParam.equals("dateFrom")){
      sessionVal=SelectCondBeanId.getDateFrom();
//      SELECT_POINT="MWD_WEEKSTARTDATE_DATE";
    }else{
      sessionVal=SelectCondBeanId.getDateTo();
//      SELECT_POINT="MWD_WEEKENDDATE_DATE";
    }
    SimpleDateFormat formatter
     = new SimpleDateFormat ("yyyy/MM/dd");
    try{
      //�ΏۂƂȂ�T�͍������Q�����O�܂�
      StringBuffer sql=new StringBuffer();
      sql.append("select * from M_WEEKDATE \n");
      sql.append(" where MWD_WEEKSTARTDATE_DATE >='");
      sql.append(from_year).append("/").append(from_month).append("/").append(from_date).append("' \n");
      sql.append(" and MWD_WEEKENDDATE_DATE <='");
      sql.append(to_year).append("/").append(to_month).append("/").append(to_date).append("' \n");

      Statement st = db.createStatement();
      System.out.println(sql.toString());
      ResultSet rs = st.executeQuery(sql.toString());
      StringBuffer tags=new StringBuffer();
      tags.append("<SELECT name='").append(DateParam).append("'>");
      while(rs.next()){
        tags.append("<option value='");
        tags.append(formatter.format(rs.getDate("MWD_WEEKSTARTDATE_DATE")));
        tags.append("' ");
        tags.append(CommonFunc.selectedFunc(formatter.format(rs.getDate(SELECT_POINT)),sessionVal)).append(">");
        tags.append(formatter.format(rs.getDate("MWD_WEEKSTARTDATE_DATE"))).append("-");
        tags.append(formatter.format(rs.getDate("MWD_WEEKENDDATE_DATE")));
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
   *�����Ώہ@�ƂȂ錎�̌������X�g�\��
   *
   */
  static public String getSearchMonth(String DateParam,SelectCondBean SelectCondBeanId){
    cal =  new GregorianCalendar();
    cal.getInstance();
    cal.setTime(new Date());

    //����N���擾����Ƃ��Ɏg���܂��B
    int one_year = cal.get(Calendar.YEAR); // 2001/4/28 �̂Ƃ� 2001 �𓾂܂�
    //�����擾����Ƃ��Ɏg���܂��B(0�`11)
    int one_month = cal.get(Calendar.MONTH) + 1; // 2001/4/28 �̂Ƃ� 4 �𓾂܂�

    cal.add(Calendar.MONTH,-1); //1�����O�̓��t�쐬

    //����N���擾����Ƃ��Ɏg���܂��B
    int two_year = cal.get(Calendar.YEAR); // 2001/4/28 �̂Ƃ� 2001 �𓾂܂�
    //�����擾����Ƃ��Ɏg���܂��B(0�`11)
    int two_month = cal.get(Calendar.MONTH) + 1; // 2001/4/28 �̂Ƃ� 4 �𓾂܂�
    cal.add(Calendar.MONTH,-1); //1�����O�̓��t�쐬

    //����N���擾����Ƃ��Ɏg���܂��B
    int three_year = cal.get(Calendar.YEAR); // 2001/4/28 �̂Ƃ� 2001 �𓾂܂�
    //�����擾����Ƃ��Ɏg���܂��B(0�`11)
    int three_month = cal.get(Calendar.MONTH) + 1; // 2001/4/28 �̂Ƃ� 4 �𓾂܂�

    String sessionVal="";
    if(DateParam.equals("dateFrom")){
      sessionVal=SelectCondBeanId.getDateFrom();
    }else{
      sessionVal=SelectCondBeanId.getDateTo();
    }
      StringBuffer tags=new StringBuffer();
      tags.append("<SELECT name=").append(DateParam).append(">");
      tags.append("<option value='");
      tags.append(one_year).append("/").append(CommonFunc.numToString(one_month)).append("/01");
      tags.append("' ").append(
          CommonFunc.selectedFunc(one_year + "/" + CommonFunc.numToString(one_month) + "/01",sessionVal)
      );
      tags.append(">");
      tags.append(one_year).append("/").append(CommonFunc.numToString(one_month));
      tags.append("</option>");
      tags.append("<option value='");
      tags.append(two_year).append("/").append(CommonFunc.numToString(two_month)).append("/01");
      tags.append("' ").append(
          CommonFunc.selectedFunc(two_year + "/" + CommonFunc.numToString(two_month) + "/01",sessionVal)
          );
      tags.append(">");
      tags.append(two_year).append("/").append(CommonFunc.numToString(two_month));
      tags.append("</option>");
      tags.append("<option value='");
      tags.append(three_year).append("/").append(CommonFunc.numToString(three_month)).append("/01");
      tags.append("' ").append(
               CommonFunc.selectedFunc(three_year + "/" + CommonFunc.numToString(three_month) + "/01",sessionVal)
               );
      tags.append(">");
      tags.append(three_year).append("/").append(CommonFunc.numToString(three_month));
      tags.append("</option>");
      tags.append("</SELECT>");
      return tags.toString();
  }


  /**
   *�^����ꂽ���t���x�����ǂ����m�F���@�x���Ȃ�F���ŕԂ�
   *
   */
  static public String getHolday(Statement st, Date DateParam){

   // Format the current time.
    SimpleDateFormat formatter
     = new SimpleDateFormat ("yyyy�NMM��dd��");


    try{
      //�ΏۂƂȂ�T�͍������Q�����O�܂�
      StringBuffer sql=new StringBuffer();
      sql.append("select * from M_HOLIDAY \n");
      sql.append(" where MHD_HOLIDAY_DATE ='").append(DateParam).append("' \n");

      System.out.println(sql.toString());
      ResultSet rs = st.executeQuery(sql.toString());
      StringBuffer tags=new StringBuffer();
      if(rs.next()){
          if(rs.getString("MHD_HOLIDAY_CLS").equals("1")) //�y�j��
            tags.append("<font color='blue'>").append(formatter.format(DateParam)).append("</font>");
          if(rs.getString("MHD_HOLIDAY_CLS").equals("2")) //���j��
            tags.append("<font color='red'>").append(formatter.format(DateParam)).append("</font>");
      }else{
            //����
            tags.append("<font color='black'>").append(formatter.format(DateParam)).append("</font>");
      }
      return tags.toString();
    }catch(SQLException e){
			e.printStackTrace();
		}//try
      return "";
  }

  /**
   *�^����ꂽ���t���x�����ǂ����m�F���@�x���Ȃ�F���ŕԂ��@�����܂ŃJ�o�[����
   */
  static public String getHoldayTime(Statement st, Date DateParam){

   // Format the current time.
    SimpleDateFormat formatter
     = new SimpleDateFormat ("yyyy�NMM��dd��HH��");

    try{
      StringBuffer sql=new StringBuffer();
      sql.append("select * from M_HOLIDAY \n");
      sql.append(" where MHD_HOLIDAY_DATE ='").append(DateParam).append("' \n");

      System.out.println(sql.toString());
      ResultSet rs = st.executeQuery(sql.toString());
      StringBuffer tags=new StringBuffer();
      if(rs.next()){
          if(rs.getString("MHD_HOLIDAY_CLS").equals("1")) //�y�j��
            tags.append("<font color='blue'>").append(formatter.format(DateParam)).append("</font>");
          if(rs.getString("MHD_HOLIDAY_CLS").equals("2")) //���j��
            tags.append("<font color='red'>").append(formatter.format(DateParam)).append("</font>");
      }else{
            //����
            tags.append("<font color='black'>").append(formatter.format(DateParam)).append("</font>");
      }
      return tags.toString();
    }catch(SQLException e){
			e.printStackTrace();
		}//try
      return "";
  }


}//class