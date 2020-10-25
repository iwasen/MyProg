package base;
import xxx.*;
import java.sql.*;
import java.util.Date;
import java.util.*;
import java.text.*;
/**
 * タイトル:  日付作成ビーン
 * 説明:   日付に関する関数群をここにまとめる
 */
public class DateGenerate {
    static GregorianCalendar cal=null;

  /**
   *今月の初めの日を返す関数　
   */
  static public String getMonthFirst(){
    cal =  new GregorianCalendar();
    cal.getInstance();
    cal.setTime(new Date());

    //西暦年を取得するときに使います。
    int year = cal.get(Calendar.YEAR); // 2001/4/28 のとき 2001 を得ます
    //月を取得するときに使います。(0～11)
    int month = cal.get(Calendar.MONTH) + 1; // 2001/4/28 のとき 4 を得ます

    return String.valueOf(year) + "/" + CommonFunc.numToString(month) + "/01";
  }

  /**
   *今月の最後の日を返す関数　
   */
  static public String getMonthLast(){
    cal =  new GregorianCalendar();
    cal.getInstance();
    cal.setTime(new Date());

    //西暦年を取得するときに使います。
    int year = cal.get(Calendar.YEAR); // 2001/4/28 のとき 2001 を得ます
    //月を取得するときに使います。(0～11)
    int month = cal.get(Calendar.MONTH) + 1; // 2001/4/28 のとき 4 を得ます
    cal.set(year,month,0); //0をセットすると　月の最後の日付になるらしい。
    //日を取得するときに使います。(1～31)
    int date= cal.get(Calendar.DATE); // 2001/4/28 のとき 28 を得ます
    return String.valueOf(year) + "/" + CommonFunc.numToString(month) + "/" + CommonFunc.numToString(date);
  }

  /**
   *検索対象　となる週の候補をリスト表示
   */
  static public String getSearchWeek(DataBase db, String DateParam, SelectCondBean SelectCondBeanId){
    cal =  new GregorianCalendar();
    cal.getInstance();
    cal.setTime(new Date());

    //西暦年を取得するときに使います。
    int to_year = cal.get(Calendar.YEAR); // 2001/4/28 のとき 2001 を得ます
    //月を取得するときに使います。(0～11)
    int to_month = cal.get(Calendar.MONTH) + 1; // 2001/4/28 のとき 4 を得ます
    int to_date= cal.get(Calendar.DATE); // 2001/4/28 のとき 28 を得ます

    cal.add(Calendar.MONTH,-2); //2ヶ月前の日付作成

    //西暦年を取得するときに使います。
    int from_year = cal.get(Calendar.YEAR); // 2001/4/28 のとき 2001 を得ます
    //月を取得するときに使います。(0～11)
    int from_month = cal.get(Calendar.MONTH) + 1; // 2001/4/28 のとき 4 を得ます
    int from_date= cal.get(Calendar.DATE); // 2001/4/28 のとき 28 を得ます

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
      //対象となる週は今日より２ヶ月前まで
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
   *検索対象　となる月の候補をリスト表示
   *
   */
  static public String getSearchMonth(String DateParam,SelectCondBean SelectCondBeanId){
    cal =  new GregorianCalendar();
    cal.getInstance();
    cal.setTime(new Date());

    //西暦年を取得するときに使います。
    int one_year = cal.get(Calendar.YEAR); // 2001/4/28 のとき 2001 を得ます
    //月を取得するときに使います。(0～11)
    int one_month = cal.get(Calendar.MONTH) + 1; // 2001/4/28 のとき 4 を得ます

    cal.add(Calendar.MONTH,-1); //1ヶ月前の日付作成

    //西暦年を取得するときに使います。
    int two_year = cal.get(Calendar.YEAR); // 2001/4/28 のとき 2001 を得ます
    //月を取得するときに使います。(0～11)
    int two_month = cal.get(Calendar.MONTH) + 1; // 2001/4/28 のとき 4 を得ます
    cal.add(Calendar.MONTH,-1); //1ヶ月前の日付作成

    //西暦年を取得するときに使います。
    int three_year = cal.get(Calendar.YEAR); // 2001/4/28 のとき 2001 を得ます
    //月を取得するときに使います。(0～11)
    int three_month = cal.get(Calendar.MONTH) + 1; // 2001/4/28 のとき 4 を得ます

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
   *与えられた日付が休日かどうか確認し　休日なら色つきで返す
   *
   */
  static public String getHolday(Statement st, Date DateParam){

   // Format the current time.
    SimpleDateFormat formatter
     = new SimpleDateFormat ("yyyy年MM月dd日");


    try{
      //対象となる週は今日より２ヶ月前まで
      StringBuffer sql=new StringBuffer();
      sql.append("select * from M_HOLIDAY \n");
      sql.append(" where MHD_HOLIDAY_DATE ='").append(DateParam).append("' \n");

      System.out.println(sql.toString());
      ResultSet rs = st.executeQuery(sql.toString());
      StringBuffer tags=new StringBuffer();
      if(rs.next()){
          if(rs.getString("MHD_HOLIDAY_CLS").equals("1")) //土曜日
            tags.append("<font color='blue'>").append(formatter.format(DateParam)).append("</font>");
          if(rs.getString("MHD_HOLIDAY_CLS").equals("2")) //日曜日
            tags.append("<font color='red'>").append(formatter.format(DateParam)).append("</font>");
      }else{
            //平日
            tags.append("<font color='black'>").append(formatter.format(DateParam)).append("</font>");
      }
      return tags.toString();
    }catch(SQLException e){
			e.printStackTrace();
		}//try
      return "";
  }

  /**
   *与えられた日付が休日かどうか確認し　休日なら色つきで返す　時刻までカバーする
   */
  static public String getHoldayTime(Statement st, Date DateParam){

   // Format the current time.
    SimpleDateFormat formatter
     = new SimpleDateFormat ("yyyy年MM月dd日HH時");

    try{
      StringBuffer sql=new StringBuffer();
      sql.append("select * from M_HOLIDAY \n");
      sql.append(" where MHD_HOLIDAY_DATE ='").append(DateParam).append("' \n");

      System.out.println(sql.toString());
      ResultSet rs = st.executeQuery(sql.toString());
      StringBuffer tags=new StringBuffer();
      if(rs.next()){
          if(rs.getString("MHD_HOLIDAY_CLS").equals("1")) //土曜日
            tags.append("<font color='blue'>").append(formatter.format(DateParam)).append("</font>");
          if(rs.getString("MHD_HOLIDAY_CLS").equals("2")) //日曜日
            tags.append("<font color='red'>").append(formatter.format(DateParam)).append("</font>");
      }else{
            //平日
            tags.append("<font color='black'>").append(formatter.format(DateParam)).append("</font>");
      }
      return tags.toString();
    }catch(SQLException e){
			e.printStackTrace();
		}//try
      return "";
  }


}//class