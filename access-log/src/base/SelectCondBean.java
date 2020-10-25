package base;

import java.util.*;
import java.util.Date;
import java.text.*;
import java.sql.*;
import javax.servlet.http.*;

/**
 * �^�C�g��:�I�������i�[�N���X
 * ����:�e�[�u���̑I���������i�[���Ă���
 * ���쌠:   Copyright (c) 2003
 * ��Ж�:
 * @author
 * @version 1.0
 */

public class SelectCondBean {

  private String corner;
  private String category;
  private String table;
  private String cornerdepth;
  private String[] attribute;
  private String order;
  private String page;
  private String group;
  private String menu;

  private String monthFromY;
  private String monthFromM;
  private String monthToY;
  private String monthToM;

  private String weekFromY;
  private String weekFromW;
  private String weekToY;
  private String weekToW;

  private String dayFromY;
  private String dayFromM;
  private String dayFromD;
  private String dayToY;
  private String dayToM;
  private String dayToD;

  private String hourFromY;
  private String hourFromM;
  private String hourFromD;
  private String hourFromH;
  private String hourToY;
  private String hourToM;
  private String hourToD;
  private String hourToH;

  private int pageNo;
  private int pageNo2;
  private int mode;
  private String subMode;
  private String targetCorner;
  private String moveFromCorner;
  private String moveToCorner;

  private String[] streamParentCorner;
  private String[] streamSelectCorner;
  private String[] streamUrl;
  private String streamErrMsg;
  private String streamDispLevel;
  private String streamTableID;
  private boolean streamRecycle;
  private int streamCounting;

  public final static int modeCondition = 2;
  public final static int modeResult = 1;

  // �R���X�g���N�^
  public SelectCondBean(DataBase db) throws SQLException {
    initCondition(db);
  }

  // ��������������
  public void initCondition(DataBase db) throws SQLException {
    ResultSet rs = db.getContrxxxaster();

    corner = "/";
    cornerdepth = "1";
    category = "/";
    pageNo = 0;
    pageNo2 = 0;
    targetCorner = "";
    moveFromCorner = "";
    moveToCorner = "";
    order = rs.getString("MCR_DEFAULTVORDER_CLS").equals("A") ? "ALLCOUNT" : "SORT";
    attribute = new String []{"0", "0", "0", "0", "0"};
    streamParentCorner = new String []{"/", "/", "/", "/", "/"};
    streamSelectCorner = new String []{"", "", "", "", ""};
    streamUrl = new String []{"", "", "", "", ""};
    streamDispLevel = "6";
    streamTableID = "";
    streamRecycle = false;
    streamCounting = 0;

    // ���ԁi���j�̔N��
    monthFromY = "";
    monthFromM = "";
    monthToY = "";
    monthToM = "";

    // ���ԁi�T�j�̔N�T
    weekFromY = "";
    weekFromW = "";
    weekToY = "";
    weekToW = "";

    // ���ԁi���j�̔N����
    dayFromY = "";
    dayFromM = "";
    dayFromD = "";
    dayToY = "";
    dayToM = "";
    dayToD = "";

    // ���ԁi���j�̔N������
    hourFromY = "";
    hourFromM = "";
    hourFromD = "";
    hourFromH = "";
    hourToY = "";
    hourToM = "";
    hourToD = "";
    hourToH = "";
  }

  // ����l�ŕ\������ꍇ�̏����ݒ�
  public void setDefaultCondition(DataBase db) throws SQLException {
    table = "DATE";

    Statement st = db.createStatement();

    // �R���g���[���}�X�^����ŐV�W�v���t�Ɗ���l�̓������擾
    ResultSet rs = db.getContrxxxaster();
    GregorianCalendar cal = new GregorianCalendar();
    cal.setTime(rs.getDate("MCR_DATETO_DATE"));

    // ����̊��ԏI������
    dayToY = String.valueOf(cal.get(Calendar.YEAR));
    dayToM = CommonFunc.numToString(cal.get(Calendar.MONTH) + 1);
    dayToD = CommonFunc.numToString(cal.get(Calendar.DATE));

    // ����̊��ԊJ�n����
    cal.add(Calendar.DATE, -rs.getInt("MCR_DEFAULTGETDAYS_NUM"));
    dayFromY = String.valueOf(cal.get(Calendar.YEAR));
    dayFromM = CommonFunc.numToString(cal.get(Calendar.MONTH) + 1);
    dayFromD = CommonFunc.numToString(cal.get(Calendar.DATE));
  }

  // �R�[�i�[�R�[�h�ݒ�
  public void setCorner(String corner) {
    if (corner != null && corner.length() != 0)
      this.corner = corner;
  }

  // �R�[�i�[�R�[�h�擾
  public String getCorner() {
    return corner;
  }

  // �J�e�S���R�[�h�ݒ�
  public void setCategory(String category) {
    if (category != null && category.length() != 0)
      this.category = category;
  }

  // �J�e�S���R�[�h�擾
  public String getCategory() {
    return category;
  }

  // �J�e�S���R�[�h�擾�i�Ȃ���jsp����Ăяo���Ƃ��ɕK�v�j
  public String getcategory() {
    return category;
  }

  // �W�v�e�[�u����ʐݒ�
  public void setTable(String table) {
    if (table != null && table.length() != 0)
      this.table = table;
  }

  // �W�v�e�[�u����ʎ擾
  public String getTable() {
    return table;
  }

  // �R�[�i�[�K�w�ݒ�
  public void setCornerdepth(String cornerdepth) {
    if (cornerdepth != null && cornerdepth.length() != 0)
      this.cornerdepth = cornerdepth;
  }

  // �R�[�i�[�K�w�擾
  public String getCornerdepth() {
    return cornerdepth;
  }

  // ��Ƒ����ݒ�
  public void setAttribute(int n, String attribute) {
    if (attribute != null && attribute.length() != 0)
      this.attribute[n] = attribute;
  }

  // ��Ƒ����z��擾
  public String[] getAttribute() {
   return attribute;
  }

  // ��Ƒ����z��擾
  public String getAttributeValue(int i) {
    return attribute[i];
  }

  // ��Ƒ����R�[�h��"/"��؂�őS�Ď擾
  public String getAttributeCode() {
    StringBuffer strbuf = new StringBuffer();
    for (int i = 0; i < attribute.length; i++) {
      if (i != 0)
        strbuf.append("/");
      int index = attribute[i].indexOf(':');
      if (index == -1)
        strbuf.append(attribute[i]);
      else
        strbuf.append(attribute[i].substring(0, index));
    }
    return strbuf.toString();
  }

  // ��Ƒ����w��̗L�����擾
  public boolean getAttributeFlg() {
    if (attribute != null) {
      for (int i = 0; i < attribute.length; i++) {
        if (!attribute[i].equals("0"))
          return true;
      }
    }
    return false;
  }

  // �\�����ݒ�
  public void setOrder(String order) {
    if (order != null && order.length() != 0)
      this.order = order;
  }

  // �\�����擾
  public String getOrder() {
    return order;
  }

  // �\���y�[�W�ݒ�
  public void setPage(String page) {
    if (page != null && page.length() != 0)
      this.page = page;
  }

  // �\���y�[�W�擾
  public String getPage() {
    return page;
  }

  // �O���[�v�ݒ�
  public void setGroup(HttpServletRequest request, HttpServletResponse response, String group) {
    if (group != null) {
      // �O���[�v�̓Z�b�V�����؂�ŏ�����Ƒ��s�ł��Ȃ��Ȃ�̂ŃN�b�L�[�ɓ����
      CommonFunc.setCookie(response, "GroupCode", group);
      this.group = group;
    } else {
      this.group = CommonFunc.getCookie(request, "GroupCode");
    }
  }

  // �O���[�v�擾
  public String getGroup() {
    return group;
  }

  // �O���[�v�ݒ�
  public void setMenu(HttpServletRequest request, HttpServletResponse response, String menu) {
    if (menu != null) {
      // �O���[�v�̓Z�b�V�����؂�ŏ�����Ƒ��s�ł��Ȃ��Ȃ�̂ŃN�b�L�[�ɓ����
      CommonFunc.setCookie(response, "GroupMenu", menu);
      this.menu = menu;
    } else {
      this.menu = CommonFunc.getCookie(request, "GroupMenu");
    }
  }

  // �O���[�v�擾
  public String getMenu() {
    return menu;
  }

  // ���ԁi���j�̊J�n�N�ݒ�
  public void setMonthFromY(String monthFromY) {
    if (monthFromY != null && monthFromY.length() != 0)
      this.monthFromY = monthFromY;
  }

  // ���ԁi���j�̊J�n�N�擾
  public String getMonthFromY() {
    return monthFromY;
  }

  // ���ԁi���j�̊J�n���ݒ�
  public void setMonthFromM(String monthFromM) {
    if (monthFromM != null && monthFromM.length() != 0)
      this.monthFromM = monthFromM;
  }

  // ���ԁi���j�̊J�n���擾
  public String getMonthFromM() {
    return monthFromM;
  }

  // ���ԁi���j�̏I���N�ݒ�
  public void setMonthToY(String monthToY) {
    if (monthToY != null && monthToY.length() != 0)
      this.monthToY = monthToY;
  }

  // ���ԁi���j�̏I���N�擾
  public String getMonthToY() {
    return monthToY;
  }

  // ���ԁi���j�̏I�����ݒ�
  public void setMonthToM(String monthToM) {
    if (monthToM != null && monthToM.length() != 0)
      this.monthToM = monthToM;
  }

  // ���ԁi���j�̏I�����擾
  public String getMonthToM() {
    return monthToM;
  }

  // ���ԁi�T�j�̊J�n�N�ݒ�
  public void setWeekFromY(String weekFromY) {
    if (weekFromY != null && weekFromY.length() != 0)
      this.weekFromY = weekFromY;
  }

  // ���ԁi�T�j�̊J�n�N�擾
  public String getWeekFromY() {
    return weekFromY;
  }

  // ���ԁi�T�j�̊J�n�T�ݒ�
  public void setWeekFromW(String weekFromW) {
    if (weekFromW != null && weekFromW.length() != 0)
      this.weekFromW = weekFromW;
  }

  // ���ԁi�T�j�̊J�n�T�擾
  public String getWeekFromW() {
    return weekFromW;
  }

  // ���ԁi�T�j�̏I���N�ݒ�
  public void setWeekToY(String weekToY) {
    if (weekToY != null && weekToY.length() != 0)
      this.weekToY = weekToY;
  }

  // ���ԁi�T�j�̏I���N�擾
  public String getWeekToY() {
    return weekToY;
  }

  // ���ԁi�T�j�̏I���T�ݒ�
  public void setWeekToW(String weekToW) {
    if (weekToW != null && weekToW.length() != 0)
      this.weekToW = weekToW;
  }

  // ���ԁi�T�j�̏I���T�擾
  public String getWeekToW() {
    return weekToW;
  }

  // ���ԁi���j�̊J�n�N�ݒ�
  public void setDayFromY(String dayFromY) {
    if (dayFromY != null && dayFromY.length() != 0)
      this.dayFromY = dayFromY;
  }

  // ���ԁi���j�̊J�n�N�擾
  public String getDayFromY() {
    return dayFromY;
  }

  // ���ԁi���j�̊J�n���ݒ�
  public void setDayFromM(String dayFromM) {
    if (dayFromM != null && dayFromM.length() != 0)
      this.dayFromM = dayFromM;
  }

  // ���ԁi���j�̊J�n���擾
  public String getDayFromM() {
    return dayFromM;
  }

  // ���ԁi���j�̊J�n���ݒ�
  public void setDayFromD(String dayFromD) {
    if (dayFromD != null && dayFromD.length() != 0)
      this.dayFromD = dayFromD;
  }

  // ���ԁi���j�̊J�n���擾
  public String getDayFromD() {
    return dayFromD;
  }

  // ���ԁi���j�̏I���N�ݒ�
  public void setDayToY(String dayToY) {
    if (dayToY != null && dayToY.length() != 0)
      this.dayToY = dayToY;
  }

  // ���ԁi���j�̏I���N�擾
  public String getDayToY() {
    return dayToY;
  }

  // ���ԁi���j�̏I�����ݒ�
  public void setDayToM(String dayToM) {
    if (dayToM != null && dayToM.length() != 0)
      this.dayToM = dayToM;
  }

  // ���ԁi���j�̏I�����擾
  public String getDayToM() {
    return dayToM;
  }

  // ���ԁi���j�̏I�����ݒ�
  public void setDayToD(String dayToD) {
    if (dayToD != null && dayToD.length() != 0)
      this.dayToD = dayToD;
  }

  // ���ԁi���j�̏I�����擾
  public String getDayToD() {
    return dayToD;
  }

  // ���ԁi���j�̊J�n�N�ݒ�
  public void setHourFromY(String hourFromY) {
    if (hourFromY != null && hourFromY.length() != 0)
      this.hourFromY = hourFromY;
  }

  // ���ԁi���j�̊J�n�N�擾
  public String getHourFromY() {
    return hourFromY;
  }

  // ���ԁi���j�̊J�n���ݒ�
  public void setHourFromM(String hourFromM) {
    if (hourFromM != null && hourFromM.length() != 0)
      this.hourFromM = hourFromM;
  }

  // ���ԁi���j�̊J�n���擾
  public String getHourFromM() {
    return hourFromM;
  }

  // ���ԁi���j�̊J�n���ݒ�
  public void setHourFromD(String hourFromD) {
    if (hourFromD != null && hourFromD.length() != 0)
      this.hourFromD = hourFromD;
  }

  // ���ԁi���j�̊J�n���擾
  public String getHourFromD() {
    return hourFromD;
  }

  // ���ԁi���j�̊J�n���ݒ�
  public void setHourFromH(String hourFromH) {
    if (hourFromH != null && hourFromH.length() != 0)
      this.hourFromH = hourFromH;
  }

  // ���ԁi���j�̊J�n���擾
  public String getHourFromH() {
    return hourFromH;
  }

  // ���ԁi���j�̏I���N�ݒ�
  public void setHourToY(String hourToY) {
    if (hourToY != null && hourToY.length() != 0)
      this.hourToY = hourToY;
  }

  // ���ԁi���j�̏I���N�擾
  public String getHourToY() {
    return hourToY;
  }

  // ���ԁi���j�̏I�����ݒ�
  public void setHourToM(String hourToM) {
    if (hourToM != null && hourToM.length() != 0)
      this.hourToM = hourToM;
  }

  // ���ԁi���j�̏I�����擾
  public String getHourToM() {
    return hourToM;
  }

  // ���ԁi���j�̏I�����ݒ�
  public void setHourToD(String hourToD) {
    if (hourToD != null && hourToD.length() != 0)
      this.hourToD = hourToD;
  }

  // ���ԁi���j�̏I�����擾
  public String getHourToD() {
    return hourToD;
  }

  // ���ԁi���j�̏I�����ݒ�
  public void setHourToH(String hourToH) {
    if (hourToH != null && hourToH.length() != 0)
      this.hourToH = hourToH;
  }

  // ���ԁi���j�̏I�����擾
  public String getHourToH() {
    return hourToH;
  }

  // �c���y�[�W�ԍ��ݒ�
  public int getPageNo() {
    return pageNo;
  }

  // �c���y�[�W�ԍ������Z�b�g
  public void resetPageNo() {
    this.pageNo = 0;
  }

  // �c�����y�[�W�ԍ��ݒ�
  public void pageForward() {
    pageNo++;
  }

  // �c���O�y�[�W�ԍ��ݒ�
  public void pageBack() {
    if (pageNo != 0)
      pageNo--;
  }

  // �����y�[�W�ԍ��ݒ�
  public int getPageNo2() {
    return pageNo2;
  }

  // �����y�[�W�ԍ������Z�b�g
  public void resetPageNo2() {
    this.pageNo2 = 0;
  }

  // �������y�[�W�ԍ��ݒ�
  public void pageForward2() {
    pageNo2++;
  }

  // �����O�y�[�W�ԍ��ݒ�
  public void pageBack2() {
    if (pageNo2 != 0)
      pageNo2--;
  }

  // ����l�^�ڍאݒ胂�[�h�ݒ�
  public void setMode(String mode) {
    if (mode != null && mode.length() != 0)
      this.mode = Integer.parseInt(mode);
  }

  // ����l�^�ڍאݒ胂�[�h�擾
  public int getMode() {
    return mode;
  }

  // �T�u�R�[�h�ݒ�
  public void setSubMode(String subMode) {
    if (subMode != null)
      this.subMode = subMode;
  }

  // �T�u�R�[�h�擾
  public String getSubMode() {
    return subMode;
  }

  // �ΏۃR�[�i�[�ݒ�
  public void setTargetCorner(String targetCorner) {
    if (targetCorner != null)
      this.targetCorner = targetCorner;
  }

  // �ΏۃR�[�i�[�擾
  public String getTargetCorner() {
    return targetCorner;
  }

  // �Ώۃe�[�u����ʁi�R�[�i�[��URL���j�擾
  public String getTargetCornerTable() {
    int index = targetCorner.indexOf(':');
    if (index != -1)
      return targetCorner.substring(0, index);
    else
      return "";
  }

  // �ΏۃR�[�i�R�[�h�܂���URL�擾
  public String getTargetCornerOrUrl() {
    int index = targetCorner.indexOf(':');
    if (index != -1)
      return targetCorner.substring(index + 1);
    else
      return "";
  }

  // �J�ڌ��R�[�i�[�ݒ�
  public void setMoveFromCorner(String moveFromCorner) {
    if (moveFromCorner != null)
      this.moveFromCorner = moveFromCorner;
  }

  // �J�ڌ��R�[�i�[�擾
  public String getMoveFromCorner() {
    return moveFromCorner;
  }

  // �J�ڌ��e�[�u����ʁi�R�[�i�[��URL���j�擾
  public String getMoveFromCornerTable() {
    int index = moveFromCorner.indexOf(':');
    if (index != -1)
      return moveFromCorner.substring(0, index);
    else
      return "";
  }

  // �J�ڌ��R�[�i�R�[�h�܂���URL�擾
  public String getMoveFromCornerOrUrl() {
    int index = moveFromCorner.indexOf(':');
    if (index != -1)
      return moveFromCorner.substring(index + 1);
    else
      return "";
  }

  // �J�ڐ�R�[�i�[�ݒ�
  public void setMoveToCorner(String moveToCorner) {
    if (moveToCorner != null)
      this.moveToCorner = moveToCorner;
  }

  // �J�ڐ�R�[�i�[�擾
  public String getMoveToCorner() {
    return moveToCorner;
  }

  // �J�ڐ�e�[�u����ʁi�R�[�i�[��URL���j�擾
  public String getMoveToCornerTable() {
    int index = moveToCorner.indexOf(':');
    if (index != -1)
      return moveToCorner.substring(0, index);
    else
      return "";
  }

  // �J�ڐ�R�[�i�R�[�h�܂���URL�擾
  public String getMoveToCornerOrUrl() {
    int index = moveToCorner.indexOf(':');
    if (index != -1)
      return moveToCorner.substring(index + 1);
    else
      return "";
  }

  // �N���b�N�X�g���[���e�R�[�i�[�R�[�h�ݒ�
  public void setStreamParentCorner(int index, String streamParentCorner) {
    if (streamParentCorner != null)
      this.streamParentCorner[index] = streamParentCorner;
  }

  // �N���b�N�X�g���[���e�R�[�i�[�R�[�h�擾
  public String getStreamParentCorner(int index) {
    return streamParentCorner[index];
  }

  // �N���b�N�X�g���[���I���R�[�i�[�R�[�h�ݒ�
  public void setStreamSelectCorner(int index, String streamSelectCorner) {
    this.streamSelectCorner[index] = streamSelectCorner;
  }

  // �N���b�N�X�g���[���I���R�[�i�[�R�[�h�擾
  public String getStreamSelectCorner(int index) {
    return streamSelectCorner[index];
  }

  // �N���b�N�X�g���[��URL�ݒ�
  public void setStreamUrl(int index, String streamUrl) {
    if (streamUrl != null)
      this.streamUrl[index] = streamUrl;
  }

  // �N���b�N�X�g���[��URL�擾
  public String getStreamUrl(int index) {
    return streamUrl[index];
  }

  // �N���b�N�X�g���[���G���[���b�Z�[�W�擾
  public void setStreamErrMsg(String streamErrMsg) {
    this.streamErrMsg = streamErrMsg;
  }

  // �N���b�N�X�g���[���G���[���b�Z�[�W�擾
  public String getStreamErrMsg() {
    return streamErrMsg;
  }

  // �N���b�N�X�g���[���\�����x���ݒ�
  public void setStreamDispLevel(String streamDispLevel) {
    if (streamDispLevel != null)
      this.streamDispLevel = streamDispLevel;
  }

  // �N���b�N�X�g���[���\�����x���擾
  public String getStreamDispLevel() {
    return streamDispLevel;
  }

  // �N���b�N�X�g���[���e�[�u��ID�ݒ�
  public void setStreamTableID(String streamTableID) {
    if (streamTableID != null)
      this.streamTableID = streamTableID;
  }

  // �N���b�N�X�g���[���e�[�u��ID�擾
  public String getStreamTableID() {
    return streamTableID;
  }

  // �N���b�N�X�g���[���W�v�e�[�u���ė��p�t���O�ݒ�
  public void setStreamRecycle(String streamRecycle) {
    if (streamRecycle != null)
      this.streamRecycle = true;
    else
      this.streamRecycle = false;
  }

  // �N���b�N�X�g���[���W�v�e�[�u���ė��p�t���O�擾
  public boolean getStreamRecycle() {
    return streamRecycle;
  }

  // �N���b�N�X�g���[���W�v�L���t���O�ݒ�
  public void setStreamCounting(int streamCounting) {
     this.streamCounting = streamCounting;
  }

  // �N���b�N�X�g���[���W�v�L���t���O�擾
  public int getStreamCounting() {
    return streamCounting;
  }

  // �����J�n�N�����擾
  public String getDateFrom() {
    if (table != null) {
      if (table.equals("MONTH"))
        return monthFromY + "/" + monthFromM + "/01";
      else if (table.equals("WEEK"))
        return weekFromW;
      else if (table.equals("DATE"))
        return dayFromY + "/" + dayFromM + "/" + dayFromD;
      else if (table.equals("HOUR"))
        return hourFromY + "/" + hourFromM + "/" + hourFromD  + " " + hourFromH + ":00:00";
    }
    return "";
  }

  // �����I���N�����擾
  public String getDateTo() {
    if (table != null) {
      if (table.equals("MONTH")) {
        // �w��N���̍ŏI�����擾
        GregorianCalendar cal = new GregorianCalendar(Integer.parseInt(monthToY), Integer.parseInt(monthToM) - 1, 1);
        cal.set(Calendar.DATE, cal.getActualMaximum(Calendar.DATE));
        SimpleDateFormat fmt = new SimpleDateFormat("yyyy/MM/dd");
        return fmt.format(cal.getTime());
      } else if (table.equals("WEEK")) {
        // �w��T�̍ŏI�����擾
        SimpleDateFormat fmt = new SimpleDateFormat("yyyy/MM/dd");
        GregorianCalendar cal = new GregorianCalendar();
        try {
          cal.setTime(fmt.parse(weekToW));
          cal.add(Calendar.DATE, 6);
          return fmt.format(cal.getTime());
        } catch (ParseException e) {
          return "";
        }
      } else if (table.equals("DATE"))
        return dayToY + "/" + dayToM + "/" + dayToD;
      else if (table.equals("HOUR"))
        return hourToY + "/" + hourToM + "/" + hourToD  + " " + hourToH + ":00:00";
    }
    return "";
  }

  // �c���P�y�[�W�ɕ\������s���擾
  public int getPageSize() {
    return 20;
  }

  // �����P�y�[�W�ɕ\������s���擾
  public int getPageSize2() {
    return 20;
  }

  // ���及�L�R�[�i�[�R�[�h�擾
  public String getGroupCorner(DataBase db) throws SQLException {
    if (subMode.equals("GROUP")) {
      String sql = "select MGR_GROUPCONER_CODE from M_GROUP where MGR_GROUP_CODE='" + group + "'";
      Statement st = db.createStatement();
      ResultSet rs = st.executeQuery(sql);
      if (rs.next())
        return rs.getString("MGR_GROUPCONER_CODE");
    }
    return "/";
  }

  // �Ǝ�i��Ɓj�\���i�����N�L��j
  public String getCondCategoryLink(DataBase db) {
    String str;

    if (category.equals("/"))
      str = "�Ǝ��";
    else
      str = "<a href='javascript:categorypost(\"/\")' class='link'>�Ǝ��</a>��" + CodeToName.BCToName(db, category);

    return str;
  }

  // �Ǝ�i��Ɓj�\���i�����N�����j
  public String getCondategoryName(DataBase db) throws SQLException {
    StringBuffer strbuf = new StringBuffer();

    if (category != null && category.length() != 0) {
      if (category.equals("/")) {
        // "/"�݂̂̏ꍇ�͎w�薳��
        strbuf.append("�w�肵�Ȃ�");
      } else {
        // ����ȊO�͋Ǝ�R�[�h
        strbuf.append(CodeToName.BCToName(db, category));
      }
    }

    return strbuf.toString();
  }

  // �R�[�i�[���\���i�����N�L��j
  public String getCondCornerLink(DataBase db) throws SQLException {
    StringBuffer strbuf = new StringBuffer();
    int index = 0;
    int depth = 0;
    int len = corner.length();
    int lenGroupCorner = getGroupCorner(db).length();
    String cornerCode;
    String cornerName;

    while (true) {
      // ����"/"��T���B������ΏI��
      index = corner.indexOf('/', index);
      if (index == -1)
        break;

      // �R�[�i�[�K�w���{�P
      depth++;

      // �R�[�i�[�R�[�h�擾
      index++;
      cornerCode = corner.substring(0, index);

      // �O���[�v���L�̃R�[�i�[�R�[�h����ʂ͕\�����Ȃ�
      if (cornerCode.length() < lenGroupCorner)
        continue;

      // �R�[�i�[���擾
      if (index == 1)
        cornerName = "�R�[�i�[��";
      else
        cornerName = CommonFunc.getLaxxxornerName(CodeToName.CNToName(db, cornerCode));

      // ��؂蕶���t��
      if (strbuf.length() != 0)
        strbuf.append("��");

      // �R�[�i�[����A���B�Ō�ȊO�̓����N
      if (len != index)
        strbuf.append("<a href='javascript:cornerpost(\"" + cornerCode + "\"," + depth + ")' class='link'>" + cornerName + "</a>");
      else
        strbuf.append(cornerName);
    }

    return strbuf.toString();
  }

  // �R�[�i�[���擾�i�����N�����j
  public String getCondCornerName(DataBase db) {
    StringBuffer strbuf = new StringBuffer();
    int index = 1;

    while (true) {
      // ����"/"��T���B������ΏI��
      index = corner.indexOf('/', index);
      if (index == -1)
        break;

      // ��؂蕶���t��
      if (strbuf.length() != 0)
        strbuf.append("�F");

      // �R�[�i�[����A��
      strbuf.append(CommonFunc.getLaxxxornerName(CodeToName.CNToName(db, corner.substring(0, ++index))));
    }

    return strbuf.toString();
  }

  // ��Ƒ����\��
  public String getCondAttribute(DataBase db) {
    StringBuffer strbuf = new StringBuffer();

    try {
      // ��Ƒ������̎擾
      Statement st = db.createStatement();
      ResultSet rs = db.getContrxxxaster();

      for (int i = 0; i < 5; i++) {
        String attrName = rs.getString("MCR_ATTRIBUTENAME" + (i + 1) + "_TXT");
        if (attrName != null) {
          if (strbuf.length() != 0)
            strbuf.append("��");

          strbuf.append(attrName).append("�F");

          if (!attribute[i].equals("0"))
            strbuf.append("<b>" + AttributeGenerate.getAttributeText(attribute[i]) + "</b>");
        }
      }
    } catch (SQLException e) {
			e.printStackTrace();
    }

    return strbuf.toString();
  }

  // �\�����\��
  public String getCondOrder() {
    if (order.equals("SORT"))
      return "����l��";
    else
      return "�A�N�Z�X����";
  }

  // ���Ԏw��i���A�T�A���j�\��
  public String getDateSpec() {
    if (table.equals("MONTH"))
      return "��";
    else if (table.equals("WEEK"))
      return "�T";
    else if (table.equals("DATE"))
      return "��";
    else if (table.equals("HOUR"))
      return "��";
    else
      return "";
  }

  // ���Ԏw��i���A�T�A���j�\��
  public String getDateSpec2() {
    if (table.equals("MONTH"))
      return "��";
    else if (table.equals("WEEK"))
      return "�T";
    else if (table.equals("DATE"))
      return "���t";
    else if (table.equals("HOUR"))
      return "����";
    else
      return "";
  }

  // ���ԁiYMD�`YMD�j�\��
  public String getDateSpan() throws ParseException {
    SimpleDateFormat fmtIn;
    SimpleDateFormat fmtOut;

    if (table.equals("HOUR")) {
      fmtIn = new SimpleDateFormat("yyyy/MM/dd HH:mm:ss");
      fmtOut = new SimpleDateFormat("yyyy'�N'MM'��'dd'��'HH'��'");
    } else {
      fmtIn = new SimpleDateFormat("yyyy/MM/dd");
      fmtOut = new SimpleDateFormat("yyyy'�N'MM'��'dd'��'");
    }

    return fmtOut.format(fmtIn.parse(getDateFrom())) + CommonFunc.mojibakeTaisaku("�`") + fmtOut.format(fmtIn.parse(getDateTo()));
  }

  // ���喼�擾
  public String getGroupName(DataBase db) {
    return CodeToName.GRToName(db, group);
  }

  // �ΏۃR�[�i�[(URL)���擾
  public String getTargetCornerName(DataBase db) throws SQLException {
    return getCornerName(db, targetCorner);
  }

  // �J�ڌ��R�[�i�[(URL)���擾
  public String getMoveFromCornerName(DataBase db) throws SQLException {
    return getCornerName(db, moveFromCorner);
  }

  // �J�ڐ�R�[�i�[(URL)���擾
  public String getMoveToCornerName(DataBase db) throws SQLException {
    return getCornerName(db, moveToCorner);
  }

  // �R�[�i�[���擾
  private String getCornerName(DataBase db, String cornerOrUrl) throws SQLException {
    int index = cornerOrUrl.indexOf(':');
    String kind = cornerOrUrl.substring(0, index);
    String corner = cornerOrUrl.substring(index + 1);
    if (kind.equals("CORNER")) {
      String sql = "select MCN_CORNERNAME_TXT \n"
                 + "from M_CORNER \n"
                 + "where MCN_CORNER_CODE='" + corner + "' \n";
      Statement st = db.createStatement();
      ResultSet rs = st.executeQuery(sql);
      if (rs.next())
        return rs.getString(1);
    }

    return corner;
  }

  // �ΏۃR�[�i�[(URL)�̕\�������擾
  public String getTargetLimit(DataBase db) throws SQLException {
    return getLimitDate(db, targetCorner,
        "select MGC_LIMIT_DATE from M_GETHOUR_CORNER where MGC_CORNER_CODE=",
        "select MGU_LIMIT_DATE from M_GETHOUR_URL where MGU_URL_TXT=");
  }

  // �J�ڌ��R�[�i�[(URL)�̕\�������擾
  public String getMoveFromLimit(DataBase db) throws SQLException {
    return getLimitDate(db, moveFromCorner,
        "select MMFC_LIMIT_DATE from M_MOVEFR_CORNER where MMFC_CORNER_CODE=",
        "select MMFU_LIMIT_DATE from M_MOVEFR_URL where MMFU_URL_TXT=");
  }

  // �J�ڐ�R�[�i�[(URL)�̕\�������擾
  public String getMoveToLimit(DataBase db) throws SQLException {
    return getLimitDate(db, moveToCorner,
        "select MMTC_LIMIT_DATE from M_MOVETO_CORNER where MMTC_CORNER_CODE=",
        "select MMTU_LIMIT_DATE from M_MOVETO_URL where MMTU_URL_TXT=");
  }

  // �ΏۃR�[�i�[(URL)�̕\�������擾
  public String getLimitDate(DataBase db, String cornerUrl, String sqlCorner, String sqlUrl) throws SQLException {
    int index = cornerUrl.indexOf(':');
    String kind = cornerUrl.substring(0, index);
    String corner = cornerUrl.substring(index + 1);
    String sql;
    if (kind.equals("CORNER"))
      sql = sqlCorner + "'" + corner + "'";
    else
      sql = sqlUrl + "'" + corner + "'";
    Statement st = db.createStatement();
    ResultSet rs = st.executeQuery(sql);
    if (rs.next()) {
      SimpleDateFormat fmt = new SimpleDateFormat("yyyy'�N'MM'��'dd'��'");
      return fmt.format(rs.getDate(1));
    } else
      return "";
  }

  // URL����R�[�i�[�ɕϊ�
  public void setTransCorner(DataBase db, int index) throws SQLException {
    Statement st = db.createStatement();

    // URL����R�[�i�[�R�[�h���擾
    String corner = CommonSql.getCornerFromUrl(st, streamUrl[index].toLowerCase());

    // null�Ȃ�ϊ��G���[
    if (corner == null) {
      streamErrMsg = "URL����R�[�i�[�ɕϊ��ł��܂���ł����B";
      streamParentCorner[index] = "/";
      streamSelectCorner[index] = "";
    } else {
      streamParentCorner[index] = CommonFunc.getParentCorner(corner);
      streamSelectCorner[index] = corner;
    }
  }

  // ���ʃR�[�i�[�ݒ�
  public void setLowerCorner(int index) throws SQLException {
    streamParentCorner[index] = streamSelectCorner[index];
    streamSelectCorner[index] = "";
  }

  // ��ʃR�[�i�[�ݒ�
  public void setUpperCorner(int index) throws SQLException {
    streamSelectCorner[index] = streamParentCorner[index];
    streamParentCorner[index] = CommonFunc.getParentCorner(streamParentCorner[index]);
  }
}//class
