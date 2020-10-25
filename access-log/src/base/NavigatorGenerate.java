package base;

/**
 * <p>�^�C�g��: �㕔�i�r�Q�[�^���j���[�\������</p>
 * <p>����: </p>
 * <p>���쌠: Copyright (c) 2003</p>
 * <p>��Ж�: </p>
 * @author aizawa
 * @version 1.0
 */

public class NavigatorGenerate {

  // ���I���́u����l�v�܂��́u�ڍאݒ�v
  static private final String[] menuModeOff = {
    "<a href=\"javascript:modechg(1)\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuKiteichi','','images/btn_tmenu_kiteichiB.gif',1)\"><img src=\"images/btn_tmenu_kiteichiA.gif\" alt=\"����l�ŕ\������\" name=\"TmenuKiteichi\" width=\"81\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:modechg(2)\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuSyousai','','images/btn_tmenu_syousaiB.gif',1)\"><img src=\"images/btn_tmenu_syousaiA.gif\" alt=\"���ԁE��Ƒ����Ȃǂ�ݒ肵�ĕ\������\" name=\"TmenuSyousai\" width=\"81\" height=\"25\" border=\"0\"></a>"
  };

  // �I�𒆂́u����l�v�܂��́u�ڍאݒ�v
  static private final String[] menuModeOn = {
    "<img src=\"images/btn_tmenu_kiteichiC.gif\" alt=\"�I������Ă��܂�\" width=\"81\" height=\"25\">",
    "<img src=\"images/btn_tmenu_syousaiC.gif\" alt=\"�I������Ă��܂�\" width=\"81\" height=\"25\">"
  };

  // ���I���̊e�y�[�W�ւ̃����N�i�S�́j
  static private final String[] menuPageOffAll = {
    "<a href=\"javascript:pagechg('CrossCategoryAccess')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuGyouCoBunpu','','images/btn_tmenu_gyou_co_bunpuB.gif',1)\"><img src=\"images/btn_tmenu_gyou_co_bunpuA.gif\" alt=\"�Ǝ�ʃR�[�i�[�ʃA�N�Z�X���z\" name=\"TmenuGyouCoBunpu\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('CategoryAccess')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuGyouSuii','','images/btn_tmenu_gyou_suiiB.gif',1)\"><img src=\"images/btn_tmenu_gyou_suiiA.gif\" alt=\"�Ǝ�ʃA�N�Z�X���ڕ\\" name=\"TmenuGyouSuii\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('CategoryGraph')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuGyouGraph','','images/btn_tmenu_gyou_graphB.gif',1)\"><img src=\"images/btn_tmenu_gyou_graphA.gif\" alt=\"�Ǝ�ʃA�N�Z�X���ڃO���t\" name=\"TmenuGyouGraph\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('CategoryAccess/GROUP')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuBumonGyouSuii','','images/btn_tmenu_bumon_gyou_suiiB.gif',1)\"><img src=\"images/btn_tmenu_bumon_gyou_suiiA.gif\" alt=\"����I���Ǝ�ʃA�N�Z�X���ڕ\\" name=\"TmenuBumonGyouSuii\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('CategoryGraph/GROUP')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuBumonGyouGraph','','images/btn_tmenu_bumon_gyou_graphB.gif',1)\"><img src=\"images/btn_tmenu_bumon_gyou_graphA.gif\" alt=\"����I���Ǝ�ʃA�N�Z�X���ڃO���t\" name=\"TmenuBumonGyouGraph\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('CrossCategoryAccess/GROUP')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuBumonGyouSucoBunpu','','images/btn_tmenu_bumon_gyou_suco_bunpuB.gif',1)\"><img src=\"images/btn_tmenu_bumon_gyou_suco_bunpuA.gif\" alt=\"����I���Ǝ�ʃT�u�R�[�i�[�ʃA�N�Z�X���z\" name=\"TmenuBumonGyouSucoBunpu\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('TargetAccess/GROUP')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuBumonTokuSuii','','images/btn_tmenu_bumon_toku_suiiB.gif',1)\"><img src=\"images/btn_tmenu_bumon_toku_suiiA.gif\" alt=\"����I�����y�[�W�A�N�Z�X���ڕ\\" name=\"TmenuBumonTokuSuii\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('TargetGraph/GROUP')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuBumonTokuGraph','','images/btn_tmenu_bumon_toku_graphB.gif',1)\"><img src=\"images/btn_tmenu_bumon_toku_graphA.gif\" alt=\"����I�����y�[�W�A�N�Z�X���ڃO���t\" name=\"TmenuBumonTokuGraph\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('CrossCornerAccess')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuCoGyouBunpu','','images/btn_tmenu_co_gyou_bunpuB.gif',1)\"><img src=\"images/btn_tmenu_co_gyou_bunpuA.gif\" alt=\"�R�[�i�[�ʋƎ�ʃA�N�Z�X���z\" name=\"TmenuCoGyouBunpu\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('CornerAccess')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuCoSuii','','images/btn_tmenu_co_suiiB.gif',1)\"><img src=\"images/btn_tmenu_co_suiiA.gif\" alt=\"�R�[�i�[�ʃA�N�Z�X���ڕ\\" name=\"TmenuCoSuii\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('CornerGraph')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuCoGraph','','images/btn_tmenu_co_graphB.gif',1)\"><img src=\"images/btn_tmenu_co_graphA.gif\" alt=\"�R�[�i�[�ʃA�N�Z�X���ڃO���t\" name=\"TmenuCoGraph\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('RefererIn')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuAcMotoGraph','','images/btn_tmenu_ac_moto_graphB.gif',1)\"><img src=\"images/btn_tmenu_ac_moto_graphA.gif\" alt=\"�A�N�Z�X�������L���O�O���t\" name=\"TmenuAcMotoGraph\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('RefererOut')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuAcSakiGraph','','images/btn_tmenu_ac_saki_graphB.gif',1)\"><img src=\"images/btn_tmenu_ac_saki_graphA.gif\" alt=\"�A�N�Z�X�惉���L���O�O���t\" name=\"TmenuAcSakiGraph\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('ClickStream')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuStream','','images/btn_tmenu_streamB.gif',1)\"><img src=\"images/btn_tmenu_streamA.gif\" alt=\"�N���b�N�X�g���[�������L���O\" name=\"TmenuStream\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('StartPage')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuKaishiPage','','images/btn_tmenu_kaishi_pageB.gif',1)\"><img src=\"images/btn_tmenu_kaishi_pageA.gif\" alt=\"�{���J�n�y�[�W�����L���O\" name=\"TmenuKaishiPage\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('EndPage')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuSyuuryouPage','','images/btn_tmenu_syuuryou_pageB.gif',1)\"><img src=\"images/btn_tmenu_syuuryou_pageA.gif\" alt=\"�{���I���y�[�W�����L���O\" name=\"TmenuSyuuryouPage\" width=\"66\" height=\"25\" border=\"0\"></a>"
  };

  // ���I���̊e�y�[�W�ւ̃����N�i����ʁj
  static private final String[] menuPageOffGroup = {
    "<a href=\"javascript:pagechg('CategoryAccess/GROUP')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuBumonGyouSuii','','images/btn_tmenu_bumon_gyou_suiiB.gif',1)\"><img src=\"images/btn_tmenu_bumon_gyou_suiiA.gif\" alt=\"����I���Ǝ�ʃA�N�Z�X���ڕ\\" name=\"TmenuBumonGyouSuii\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('CategoryGraph/GROUP')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuBumonGyouGraph','','images/btn_tmenu_bumon_gyou_graphB.gif',1)\"><img src=\"images/btn_tmenu_bumon_gyou_graphA.gif\" alt=\"����I���Ǝ�ʃA�N�Z�X���ڃO���t\" name=\"TmenuBumonGyouGraph\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('CrossCategoryAccess/GROUP')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuBumonGyouSucoBunpu','','images/btn_tmenu_bumon_gyou_suco_bunpuB.gif',1)\"><img src=\"images/btn_tmenu_bumon_gyou_suco_bunpuA.gif\" alt=\"����I���Ǝ�ʃT�u�R�[�i�[�ʃA�N�Z�X���z\" name=\"TmenuBumonGyouSucoBunpu\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('TargetAccess/GROUP')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuBumonTokuSuii','','images/btn_tmenu_bumon_toku_suiiB.gif',1)\"><img src=\"images/btn_tmenu_bumon_toku_suiiA.gif\" alt=\"����I�����y�[�W�A�N�Z�X���ڕ\\" name=\"TmenuBumonTokuSuii\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('TargetGraph/GROUP')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuBumonTokuGraph','','images/btn_tmenu_bumon_toku_graphB.gif',1)\"><img src=\"images/btn_tmenu_bumon_toku_graphA.gif\" alt=\"����I�����y�[�W�A�N�Z�X���ڃO���t\" name=\"TmenuBumonTokuGraph\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('RefererIn/GROUP')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuAcMotoGraph','','images/btn_tmenu_ac_moto_graphB.gif',1)\"><img src=\"images/btn_tmenu_ac_moto_graphA.gif\" alt=\"�A�N�Z�X�������L���O�O���t\" name=\"TmenuAcMotoGraph\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('RefererOut/GROUP')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuAcSakiGraph','','images/btn_tmenu_ac_saki_graphB.gif',1)\"><img src=\"images/btn_tmenu_ac_saki_graphA.gif\" alt=\"�A�N�Z�X�惉���L���O�O���t\" name=\"TmenuAcSakiGraph\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('ClickStream/GROUP')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuStream','','images/btn_tmenu_streamB.gif',1)\"><img src=\"images/btn_tmenu_streamA.gif\" alt=\"�N���b�N�X�g���[�������L���O\" name=\"TmenuStream\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('StartPage/GROUP')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuKaishiPage','','images/btn_tmenu_kaishi_pageB.gif',1)\"><img src=\"images/btn_tmenu_kaishi_pageA.gif\" alt=\"�{���J�n�y�[�W�����L���O\" name=\"TmenuKaishiPage\" width=\"66\" height=\"25\" border=\"0\"></a>",
    "<a href=\"javascript:pagechg('EndPage/GROUP')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('TmenuSyuuryouPage','','images/btn_tmenu_syuuryou_pageB.gif',1)\"><img src=\"images/btn_tmenu_syuuryou_pageA.gif\" alt=\"�{���I���y�[�W�����L���O\" name=\"TmenuSyuuryouPage\" width=\"66\" height=\"25\" border=\"0\"></a>"
  };

  // �I�𒆂̃y�[�W�i�S�́j
  static private final String[] menuPageOnAll = {
    "<img src=\"images/btn_tmenu_gyou_co_bunpuC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_gyou_suiiC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_gyou_graphC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_bumon_gyou_suiiC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_bumon_gyou_graphC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_bumon_gyou_suco_bunpuC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_bumon_toku_suiiC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_bumon_toku_graphC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_co_gyou_bunpuC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_co_suiiC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_co_graphC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_ac_moto_graphC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_ac_saki_graphC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_streamC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_kaishi_pageC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_syuuryou_pageC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">"
  };

  // �I�𒆂̃y�[�W�i����ʁj
  static private final String[] menuPageOnGroup = {
    "<img src=\"images/btn_tmenu_bumon_gyou_suiiC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_bumon_gyou_graphC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_bumon_gyou_suco_bunpuC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_bumon_toku_suiiC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_bumon_toku_graphC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_ac_moto_graphC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_ac_saki_graphC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_streamC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_kaishi_pageC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">",
    "<img src=\"images/btn_tmenu_syuuryou_pageC.gif\" alt=\"�I������Ă��܂�\" width=\"66\" height=\"25\">"
  };

  // �㕔�i�r�Q�[�^���j���[��html����
  static public String getNavigator(SelectCondBean selectCondBean) {
    StringBuffer html = new StringBuffer();
    String page = selectCondBean.getPage();
    int mode = selectCondBean.getMode();

    // ���j���[�̃��[�h("ALL"or"GROUP")��URL���擾
    String menu = selectCondBean.getMenu();
    int index = menu.indexOf(":");
    String menuMode = menu.substring(0, index);
    String menuUrl = menu.substring(index + 1);

    html.append("<table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">\n");
    html.append("<tr>\n");
    html.append("<td width=\"100\" height=\"51\" rowspan=\"2\" align=\"center\"><a href=\"" + menuUrl + "\"><img src=\"images/lgo_top.jpg\" alt=\"�g�b�v���j���[�ɖ߂�\" width=\"100\" height=\"51\" border=\"0\"></a></td>\n");
    html.append("<td width=\"5\"></td>\n");
    html.append("<td width=\"81\" height=\"25\" align=\"center\">").append((mode & SelectCondBean.modeCondition) == 0 ? menuModeOn[0] : menuModeOff[0]).append("</td>\n");
    html.append("<td width=\"5\"></td>\n");

    if (menuMode.equals("ALL"))
      getNavigatorAll1(html, page);
    else
      getNavigatorGroup1(html, page);

    html.append("</tr>\n");
    html.append("<tr>\n");
    html.append("<td></td>\n");
    html.append("<td height=\"25\" align=\"center\">").append((mode & SelectCondBean.modeCondition) != 0 ? menuModeOn[1] : menuModeOff[1]).append("</td>\n");
    html.append("<td></td>\n");

    if (menuMode.equals("ALL"))
      getNavigatorAll2(html, page);
    else
      getNavigatorGroup2(html, page);

    html.append("</tr>\n");
    html.append("</table>\n");

    return html.toString();
  }

  // �S�̗p�i�r�Q�[�^��i���j���[
  static private void getNavigatorAll1(StringBuffer html, String page) {
    html.append("<td width=\"66\" align=\"center\">").append(page.equals("CrossCategoryAccess") ? menuPageOnAll[0] : menuPageOffAll[0]).append("</td>\n");
    html.append("<td width=\"66\" align=\"center\">").append(page.equals("CategoryAccess") ? menuPageOnAll[1] : menuPageOffAll[1]).append("</td>\n");
    html.append("<td width=\"66\" align=\"center\">").append(page.equals("CategoryGraph") ? menuPageOnAll[2] : menuPageOffAll[2]).append("</td>\n");
    html.append("<td width=\"66\" align=\"center\">").append(page.equals("CategoryAccess/GROUP") ? menuPageOnAll[3] : menuPageOffAll[3]).append("</td>\n");
    html.append("<td width=\"66\" align=\"center\">").append(page.equals("CategoryGraph/GROUP") ? menuPageOnAll[4] : menuPageOffAll[4]).append("</td>\n");
    html.append("<td width=\"66\" align=\"center\">").append(page.equals("CrossCategoryAccess/GROUP") ? menuPageOnAll[5] : menuPageOffAll[5]).append("</td>\n");
    html.append("<td width=\"66\" align=\"center\">").append(page.equals("TargetAccess/GROUP") ? menuPageOnAll[6] : menuPageOffAll[6]).append("</td>\n");
    html.append("<td width=\"66\" align=\"center\">").append(page.equals("TargetGraph/GROUP") ? menuPageOnAll[7] : menuPageOffAll[7]).append("</td>\n");
  }

  // �S�̗p�i�r�Q�[�^���i���j���[
  static private void getNavigatorAll2(StringBuffer html, String page) {
    html.append("<td align=\"center\">").append(page.equals("CrossCornerAccess") ? menuPageOnAll[8] : menuPageOffAll[8]).append("</td>\n");
    html.append("<td align=\"center\">").append(page.equals("CornerAccess") ? menuPageOnAll[9] : menuPageOffAll[9]).append("</td>\n");
    html.append("<td align=\"center\">").append(page.equals("CornerGraph") ? menuPageOnAll[10] : menuPageOffAll[10]).append("</td>\n");
    html.append("<td align=\"center\">").append(page.equals("RefererIn") ? menuPageOnAll[11] : menuPageOffAll[11]).append("</td>\n");
    html.append("<td align=\"center\">").append(page.equals("RefererOut") ? menuPageOnAll[12] : menuPageOffAll[12]).append("</td>\n");
    html.append("<td align=\"center\">").append(page.equals("ClickStream") ? menuPageOnAll[13] : menuPageOffAll[13]).append("</td>\n");
    html.append("<td align=\"center\">").append(page.equals("StartPage") ? menuPageOnAll[14] : menuPageOffAll[14]).append("</td>\n");
    html.append("<td align=\"center\">").append(page.equals("EndPage") ? menuPageOnAll[15] : menuPageOffAll[15]).append("</td>\n");
  }

  // ����ʗp�i�r�Q�[�^��i���j���[
  static private void getNavigatorGroup1(StringBuffer html, String page) {
    html.append("<td width=\"66\" align=\"center\">").append(page.equals("CategoryAccess/GROUP") ? menuPageOnGroup[0] : menuPageOffGroup[0]).append("</td>\n");
    html.append("<td width=\"66\" align=\"center\">").append(page.equals("CategoryGraph/GROUP") ? menuPageOnGroup[1] : menuPageOffGroup[1]).append("</td>\n");
    html.append("<td width=\"66\" align=\"center\">").append(page.equals("CrossCategoryAccess/GROUP") ? menuPageOnGroup[2] : menuPageOffGroup[2]).append("</td>\n");
    html.append("<td width=\"66\" align=\"center\">").append(page.equals("TargetAccess/GROUP") ? menuPageOnGroup[3] : menuPageOffGroup[3]).append("</td>\n");
    html.append("<td width=\"66\" align=\"center\">").append(page.equals("TargetGraph/GROUP") ? menuPageOnGroup[4] : menuPageOffGroup[4]).append("</td>\n");
  }

  // ����ʗp�i�r�Q�[�^���i���j���[
  static private void getNavigatorGroup2(StringBuffer html, String page) {
    html.append("<td align=\"center\">").append(page.equals("RefererIn/GROUP") ? menuPageOnGroup[5] : menuPageOffGroup[5]).append("</td>\n");
    html.append("<td align=\"center\">").append(page.equals("RefererOut/GROUP") ? menuPageOnGroup[6] : menuPageOffGroup[6]).append("</td>\n");
    html.append("<td align=\"center\">").append(page.equals("ClickStream/GROUP") ? menuPageOnGroup[7] : menuPageOffGroup[7]).append("</td>\n");
    html.append("<td align=\"center\">").append(page.equals("StartPage/GROUP") ? menuPageOnGroup[8] : menuPageOffGroup[8]).append("</td>\n");
    html.append("<td align=\"center\">").append(page.equals("EndPage/GROUP") ? menuPageOnGroup[9] : menuPageOffGroup[9]).append("</td>\n");
  }
}