# Microsoft Developer Studio Project File - Name="WorkScheduler" - Package Owner=<4>
# Microsoft Developer Studio Generated Build File, Format Version 6.00
# ** 編集しないでください **

# TARGTYPE "Win32 (x86) Application" 0x0101

CFG=WorkScheduler - Win32 Debug
!MESSAGE これは有効なﾒｲｸﾌｧｲﾙではありません。 このﾌﾟﾛｼﾞｪｸﾄをﾋﾞﾙﾄﾞするためには NMAKE を使用してください。
!MESSAGE [ﾒｲｸﾌｧｲﾙのｴｸｽﾎﾟｰﾄ] ｺﾏﾝﾄﾞを使用して実行してください
!MESSAGE 
!MESSAGE NMAKE /f "WorkScheduler.mak".
!MESSAGE 
!MESSAGE NMAKE の実行時に構成を指定できます
!MESSAGE ｺﾏﾝﾄﾞ ﾗｲﾝ上でﾏｸﾛの設定を定義します。例:
!MESSAGE 
!MESSAGE NMAKE /f "WorkScheduler.mak" CFG="WorkScheduler - Win32 Debug"
!MESSAGE 
!MESSAGE 選択可能なﾋﾞﾙﾄﾞ ﾓｰﾄﾞ:
!MESSAGE 
!MESSAGE "WorkScheduler - Win32 Release" ("Win32 (x86) Application" 用)
!MESSAGE "WorkScheduler - Win32 Debug" ("Win32 (x86) Application" 用)
!MESSAGE 

# Begin Project
# PROP AllowPerConfigDependencies 0
# PROP Scc_ProjName ""$/KumitateSimV1/Source", CAAAAAAA"
# PROP Scc_LocalPath "."
CPP=cl.exe
F90=df.exe
MTL=midl.exe
RSC=rc.exe

!IF  "$(CFG)" == "WorkScheduler - Win32 Release"

# PROP BASE Use_MFC 6
# PROP BASE Use_Debug_Libraries 0
# PROP BASE Output_Dir "Release"
# PROP BASE Intermediate_Dir "Release"
# PROP BASE Target_Dir ""
# PROP Use_MFC 6
# PROP Use_Debug_Libraries 0
# PROP Output_Dir "Release"
# PROP Intermediate_Dir "Release"
# PROP Ignore_Export_Lib 0
# PROP Target_Dir ""
# ADD BASE CPP /nologo /MD /W3 /GX /O2 /D "WIN32" /D "NDEBUG" /D "_WINDOWS" /D "_AFXDLL" /Yu"stdafx.h" /FD /c
# ADD CPP /nologo /MD /W3 /GX /O2 /D "WIN32" /D "NDEBUG" /D "_WINDOWS" /D "_AFXDLL" /D "_MBCS" /Yu"stdafx.h" /FD /c
# ADD BASE MTL /nologo /D "NDEBUG" /mktyplib203 /win32
# ADD MTL /nologo /D "NDEBUG" /mktyplib203 /win32
# ADD BASE RSC /l 0x411 /d "NDEBUG" /d "_AFXDLL"
# ADD RSC /l 0x411 /d "NDEBUG" /d "_AFXDLL"
BSC32=bscmake.exe
# ADD BASE BSC32 /nologo
# ADD BSC32 /nologo
LINK32=link.exe
# ADD BASE LINK32 /nologo /subsystem:windows /machine:I386
# ADD LINK32 shlwapi.lib /nologo /subsystem:windows /machine:I386 /out:"../Release/作業編成.exe"

!ELSEIF  "$(CFG)" == "WorkScheduler - Win32 Debug"

# PROP BASE Use_MFC 6
# PROP BASE Use_Debug_Libraries 1
# PROP BASE Output_Dir "Debug"
# PROP BASE Intermediate_Dir "Debug"
# PROP BASE Target_Dir ""
# PROP Use_MFC 6
# PROP Use_Debug_Libraries 1
# PROP Output_Dir "Debug"
# PROP Intermediate_Dir "Debug"
# PROP Ignore_Export_Lib 0
# PROP Target_Dir ""
# ADD BASE CPP /nologo /MDd /W3 /Gm /GX /ZI /Od /D "WIN32" /D "_DEBUG" /D "_WINDOWS" /D "_AFXDLL" /Yu"stdafx.h" /FD /GZ /c
# ADD CPP /nologo /MDd /W3 /Gm /GX /ZI /Od /D "WIN32" /D "_DEBUG" /D "_WINDOWS" /D "_AFXDLL" /D "_MBCS" /Yu"stdafx.h" /FD /GZ /c
# ADD BASE MTL /nologo /D "_DEBUG" /mktyplib203 /win32
# ADD MTL /nologo /D "_DEBUG" /mktyplib203 /win32
# ADD BASE RSC /l 0x411 /d "_DEBUG" /d "_AFXDLL"
# ADD RSC /l 0x411 /d "_DEBUG" /d "_AFXDLL"
BSC32=bscmake.exe
# ADD BASE BSC32 /nologo
# ADD BSC32 /nologo
LINK32=link.exe
# ADD BASE LINK32 /nologo /subsystem:windows /debug /machine:I386 /pdbtype:sept
# ADD LINK32 shlwapi.lib /nologo /subsystem:windows /debug /machine:I386 /out:"../Debug/作業編成.exe" /pdbtype:sept

!ENDIF 

# Begin Target

# Name "WorkScheduler - Win32 Release"
# Name "WorkScheduler - Win32 Debug"
# Begin Group "Source Files"

# PROP Default_Filter "cpp;c;cxx;rc;def;r;odl;idl;hpj;bat"
# Begin Group "DataManagerSource"

# PROP Default_Filter ""
# Begin Source File

SOURCE=.\DataManager.cpp
# End Source File
# Begin Source File

SOURCE=.\DataManagerEdit.cpp
# End Source File
# Begin Source File

SOURCE=.\DataManagerFile.cpp
# End Source File
# Begin Source File

SOURCE=.\DataManagerJidoHensei.cpp
# End Source File
# Begin Source File

SOURCE=.\DataManagerKitting.cpp
# End Source File
# Begin Source File

SOURCE=.\DataManagerKojunKisei.cpp
# End Source File
# Begin Source File

SOURCE=.\DataManagerUndo.cpp
# End Source File
# End Group
# Begin Group "ManualEditSource"

# PROP Default_Filter ""
# Begin Source File

SOURCE=.\ManualBunkatsuDlg.cpp
# End Source File
# Begin Source File

SOURCE=.\ManualDstrDlg.cpp
# End Source File
# Begin Source File

SOURCE=.\ManualEditChildWnd.cpp
# End Source File
# Begin Source File

SOURCE=.\ManualEditChildWnd1.cpp
# End Source File
# Begin Source File

SOURCE=.\ManualEditDlg.cpp
# End Source File
# Begin Source File

SOURCE=.\ManualEditDocument.cpp
# End Source File
# Begin Source File

SOURCE=.\ManualEditFrameWnd.cpp
# End Source File
# Begin Source File

SOURCE=.\ManualEditKishuBar.cpp
# End Source File
# Begin Source File

SOURCE=.\ManualEditSublineDlg.cpp
# End Source File
# Begin Source File

SOURCE=.\ManualEditSublineInfo.cpp
# End Source File
# Begin Source File

SOURCE=.\ManualEditView.cpp
# End Source File
# Begin Source File

SOURCE=.\ManualEditView1.cpp
# End Source File
# Begin Source File

SOURCE=.\ManualEditView2.cpp
# End Source File
# Begin Source File

SOURCE=.\ManualEditView3.cpp
# End Source File
# Begin Source File

SOURCE=.\ManualMoveDlg.cpp
# End Source File
# Begin Source File

SOURCE=.\ManualSchedulingDlg.cpp
# End Source File
# Begin Source File

SOURCE=.\ManualViewTypeDlg.cpp
# End Source File
# End Group
# Begin Group "ExcelFileSource"

# PROP Default_Filter ""
# Begin Source File

SOURCE=.\ExcelBuhinhyoLink.cpp
# End Source File
# Begin Source File

SOURCE=.\ExcelBuhinKitting.cpp
# End Source File
# Begin Source File

SOURCE=.\ExcelDSTRSanshutsu.cpp
# End Source File
# Begin Source File

SOURCE=.\ExcelExportGraphTemplate.cpp
# End Source File
# Begin Source File

SOURCE=.\ExcelFile.cpp
# End Source File
# Begin Source File

SOURCE=.\ExcelKatashikiKiseiError.cpp
# End Source File
# Begin Source File

SOURCE=.\ExcelKatashikiKiseiMatrix.cpp
# End Source File
# Begin Source File

SOURCE=.\ExcelKinmuTaisei.cpp
# End Source File
# Begin Source File

SOURCE=.\ExcelKittingTaishoBuhin.cpp
# End Source File
# Begin Source File

SOURCE=.\ExcelKittingTaishogai.cpp
# End Source File
# Begin Source File

SOURCE=.\ExcelKittingTemplate.cpp
# End Source File
# Begin Source File

SOURCE=.\ExcelKyotsuKotei.cpp
# End Source File
# Begin Source File

SOURCE=.\ExcelLib.cpp
# End Source File
# Begin Source File

SOURCE=.\ExcelLineKiseiError.cpp
# End Source File
# Begin Source File

SOURCE=.\ExcelLineKiseiMatrix.cpp
# End Source File
# Begin Source File

SOURCE=.\ExcelSeisanDaisu.cpp
# End Source File
# Begin Source File

SOURCE=.\ExcelSeisanJoken.cpp
# End Source File
# Begin Source File

SOURCE=.\ExcelYosoSagyohyo.cpp
# End Source File
# Begin Source File

SOURCE=.\ExcelYosoSagyoTemplate.cpp
# End Source File
# End Group
# Begin Group "SetteiSource"

# PROP Default_Filter ""
# Begin Source File

SOURCE=.\SetConditionsDlg.cpp
# End Source File
# Begin Source File

SOURCE=.\SetKittingDlg.cpp
# End Source File
# Begin Source File

SOURCE=.\SetKyotsuKoteiDlg.cpp
# End Source File
# Begin Source File

SOURCE=.\SetSeisanJoken1Dlg.cpp
# End Source File
# Begin Source File

SOURCE=.\SetSeisanJoken2Dlg.cpp
# End Source File
# Begin Source File

SOURCE=.\SetSeisanJokenDlg.cpp
# End Source File
# End Group
# Begin Source File

SOURCE=.\AboutDlg.cpp
# End Source File
# Begin Source File

SOURCE=.\ActiveFrameWnd.cpp
# End Source File
# Begin Source File

SOURCE=.\ArrayEx.cpp
# End Source File
# Begin Source File

SOURCE=.\AutoSchedulingDlg.cpp
# End Source File
# Begin Source File

SOURCE=.\CInPlaceList.cpp
# End Source File
# Begin Source File

SOURCE=.\ColorButton.cpp
# End Source File
# Begin Source File

SOURCE=.\Const.cpp
# End Source File
# Begin Source File

SOURCE=.\DataCheck.cpp
# End Source File
# Begin Source File

SOURCE=.\DialogEx.cpp
# End Source File
# Begin Source File

SOURCE=.\EVal.cpp
# End Source File
# Begin Source File

SOURCE=.\FileExportDlg.cpp
# End Source File
# Begin Source File

SOURCE=.\Global.cpp
# End Source File
# Begin Source File

SOURCE=.\HeaderCtrlEx.cpp
# End Source File
# Begin Source File

SOURCE=.\IniFile.cpp
# End Source File
# Begin Source File

SOURCE=.\InPlaceEdit.cpp
# End Source File
# Begin Source File

SOURCE=.\KatashikiKiseiMatrixDlg.cpp
# End Source File
# Begin Source File

SOURCE=.\ListCtrlEx.cpp
# End Source File
# Begin Source File

SOURCE=.\ListCtrlEx1.cpp
# End Source File
# Begin Source File

SOURCE=.\LoginDlg.cpp
# End Source File
# Begin Source File

SOURCE=.\MenuDispCtrl.cpp
# End Source File
# Begin Source File

SOURCE=.\Message.cpp
# End Source File
# Begin Source File

SOURCE=.\MyCtrl.cpp
# End Source File
# Begin Source File

SOURCE=.\SaveProjectDlg.cpp
# End Source File
# Begin Source File

SOURCE=.\SplitterEx.cpp
# End Source File
# Begin Source File

SOURCE=.\SpreadEx.cpp
# End Source File
# Begin Source File

SOURCE=.\spreadsheet.cpp
# End Source File
# Begin Source File

SOURCE=.\StartMenuDlg.cpp
# End Source File
# Begin Source File

SOURCE=.\StdAfx.cpp
# ADD CPP /Yc"stdafx.h"
# End Source File
# Begin Source File

SOURCE=.\SubMenu.cpp
# End Source File
# Begin Source File

SOURCE=.\SubMenuDlg.cpp
# End Source File
# Begin Source File

SOURCE=.\System.cpp
# End Source File
# Begin Source File

SOURCE=.\UserInfo.cpp
# End Source File
# Begin Source File

SOURCE=.\WorkScheduler.cpp
# End Source File
# Begin Source File

SOURCE=.\WorkScheduler.rc
# End Source File
# Begin Source File

SOURCE=.\WorkSchedulerDlg.cpp
# End Source File
# End Group
# Begin Group "Header Files"

# PROP Default_Filter "h;hpp;hxx;hm;inl"
# Begin Group "ManualEditHeader"

# PROP Default_Filter ""
# Begin Source File

SOURCE=.\ManualBunkatsuDlg.h
# End Source File
# Begin Source File

SOURCE=.\ManualBunkatsuDlg001.h
# End Source File
# Begin Source File

SOURCE=.\ManualDstrDlg.h
# End Source File
# Begin Source File

SOURCE=.\ManualDstrDlg001.h
# End Source File
# Begin Source File

SOURCE=.\ManualEditChildWnd.h
# End Source File
# Begin Source File

SOURCE=.\ManualEditChildWnd1.h
# End Source File
# Begin Source File

SOURCE=.\ManualEditDlg.h
# End Source File
# Begin Source File

SOURCE=.\ManualEditDlg001.h
# End Source File
# Begin Source File

SOURCE=.\ManualEditDocument.h
# End Source File
# Begin Source File

SOURCE=.\ManualEditFrameWnd.h
# End Source File
# Begin Source File

SOURCE=.\ManualEditSublineDlg.h
# End Source File
# Begin Source File

SOURCE=.\ManualEditSublineDlg001.h
# End Source File
# Begin Source File

SOURCE=.\ManualEditSublineInfo.h
# End Source File
# Begin Source File

SOURCE=.\ManualEditView.h
# End Source File
# Begin Source File

SOURCE=.\ManualEditView1.h
# End Source File
# Begin Source File

SOURCE=.\ManualEditView2.h
# End Source File
# Begin Source File

SOURCE=.\ManualEditView3.h
# End Source File
# Begin Source File

SOURCE=.\ManualEditWnd001.h
# End Source File
# Begin Source File

SOURCE=.\ManualEditWnd002.h
# End Source File
# Begin Source File

SOURCE=.\ManualMoveDlg.h
# End Source File
# Begin Source File

SOURCE=.\ManualMoveDlg001.h
# End Source File
# Begin Source File

SOURCE=.\ManualSchedulingDlg.h
# End Source File
# Begin Source File

SOURCE=.\ManualSchedulingDlg001.h
# End Source File
# Begin Source File

SOURCE=.\ManualViewTypeDlg.h
# End Source File
# Begin Source File

SOURCE=.\ManualViewTypeDlg001.h
# End Source File
# End Group
# Begin Group "ExcelFileHeader"

# PROP Default_Filter ""
# Begin Source File

SOURCE=.\ExcelBuhinhyoLink.h
# End Source File
# Begin Source File

SOURCE=.\ExcelBuhinKitting.h
# End Source File
# Begin Source File

SOURCE=.\ExcelDSTRSanshutsu.h
# End Source File
# Begin Source File

SOURCE=.\ExcelFile.h
# End Source File
# Begin Source File

SOURCE=.\ExcelKatashikiKiseiError.h
# End Source File
# Begin Source File

SOURCE=.\ExcelKatashikiKiseiMatrix.h
# End Source File
# Begin Source File

SOURCE=.\ExcelKinmuTaisei.h
# End Source File
# Begin Source File

SOURCE=.\ExcelKittingTaishoBuhin.h
# End Source File
# Begin Source File

SOURCE=.\ExcelKittingTaishogai.h
# End Source File
# Begin Source File

SOURCE=.\ExcelKittingTemplate.h
# End Source File
# Begin Source File

SOURCE=.\ExcelKyotsuKotei.h
# End Source File
# Begin Source File

SOURCE=.\ExcelLib.h
# End Source File
# Begin Source File

SOURCE=.\ExcelLineKiseiError.h
# End Source File
# Begin Source File

SOURCE=.\ExcelLineKiseiMatrix.h
# End Source File
# Begin Source File

SOURCE=.\ExcelSeisanDaisu.h
# End Source File
# Begin Source File

SOURCE=.\ExcelSeisanJoken.h
# End Source File
# Begin Source File

SOURCE=.\ExcelYosoSagyohyo.h
# End Source File
# Begin Source File

SOURCE=.\ExcelYosoSagyoTemplate.h
# End Source File
# End Group
# Begin Group "SetteiHeader"

# PROP Default_Filter ""
# Begin Source File

SOURCE=.\SetConditionsDlg.h
# End Source File
# Begin Source File

SOURCE=.\SetConditionsDlg001.h
# End Source File
# Begin Source File

SOURCE=.\SetKittingDlg.h
# End Source File
# Begin Source File

SOURCE=.\SetKittingDlg001.h
# End Source File
# Begin Source File

SOURCE=.\SetKyotsuKoteiDlg.h
# End Source File
# Begin Source File

SOURCE=.\SetKyotsuKoteiDlg001.h
# End Source File
# Begin Source File

SOURCE=.\SetSeisanJoken1Dlg.h
# End Source File
# Begin Source File

SOURCE=.\SetSeisanJoken1Dlg001.h
# End Source File
# Begin Source File

SOURCE=.\SetSeisanJoken2Dlg.h
# End Source File
# Begin Source File

SOURCE=.\SetSeisanJoken2Dlg001.h
# End Source File
# Begin Source File

SOURCE=.\SetSeisanJokenDlg.h
# End Source File
# End Group
# Begin Source File

SOURCE=.\AboutDlg.h
# End Source File
# Begin Source File

SOURCE=.\AboutDlg001.h
# End Source File
# Begin Source File

SOURCE=.\ActiveFrameWnd.h
# End Source File
# Begin Source File

SOURCE=.\ArrayEx.h
# End Source File
# Begin Source File

SOURCE=.\AutoSchedulingDlg.h
# End Source File
# Begin Source File

SOURCE=.\AutoSchedulingDlg001.h
# End Source File
# Begin Source File

SOURCE=.\CInPlaceList.h
# End Source File
# Begin Source File

SOURCE=.\ColorButton.h
# End Source File
# Begin Source File

SOURCE=.\Const.h
# End Source File
# Begin Source File

SOURCE=.\DataCheck.h
# End Source File
# Begin Source File

SOURCE=.\DataManager.h
# End Source File
# Begin Source File

SOURCE=.\DialogEx.h
# End Source File
# Begin Source File

SOURCE=.\ExcelExportGraphTemplate.h
# End Source File
# Begin Source File

SOURCE=.\FileExportDlg.h
# End Source File
# Begin Source File

SOURCE=.\FileExportDlg001.h
# End Source File
# Begin Source File

SOURCE=.\Global.h
# End Source File
# Begin Source File

SOURCE=.\HeaderCtrlEx.h
# End Source File
# Begin Source File

SOURCE=.\IniFile.h
# End Source File
# Begin Source File

SOURCE=.\InPlaceEdit.h
# End Source File
# Begin Source File

SOURCE=.\KatashikiKiseiMatrixDlg.h
# End Source File
# Begin Source File

SOURCE=.\KatashikiKiseiMatrixDlg001.h
# End Source File
# Begin Source File

SOURCE=.\ListCtrlEx.h
# End Source File
# Begin Source File

SOURCE=.\ListCtrlEx1.h
# End Source File
# Begin Source File

SOURCE=.\LoginDlg.h
# End Source File
# Begin Source File

SOURCE=.\ManualEditKishuBar.h
# End Source File
# Begin Source File

SOURCE=.\MenuDispCtrl.h
# End Source File
# Begin Source File

SOURCE=.\Message.h
# End Source File
# Begin Source File

SOURCE=.\MyCtrl.h
# End Source File
# Begin Source File

SOURCE=.\Resource.h
# End Source File
# Begin Source File

SOURCE=.\SaveProjectDlg.h
# End Source File
# Begin Source File

SOURCE=.\SaveProjectDlg001.h
# End Source File
# Begin Source File

SOURCE=.\SplitterEx.h
# End Source File
# Begin Source File

SOURCE=.\SpreadEx.h
# End Source File
# Begin Source File

SOURCE=.\spreadsheet.h
# End Source File
# Begin Source File

SOURCE=.\StartMenuDlg.h
# End Source File
# Begin Source File

SOURCE=.\StartMenuDlg001.h
# End Source File
# Begin Source File

SOURCE=.\StdAfx.h
# End Source File
# Begin Source File

SOURCE=.\SubMenu.h
# End Source File
# Begin Source File

SOURCE=.\SubMenuDlg.h
# End Source File
# Begin Source File

SOURCE=.\System.h
# End Source File
# Begin Source File

SOURCE=.\UserInfo.h
# End Source File
# Begin Source File

SOURCE=.\WorkScheduler.h
# End Source File
# Begin Source File

SOURCE=.\WorkSchedulerDlg.h
# End Source File
# Begin Source File

SOURCE=.\WorkSchedulerDlg001.h
# End Source File
# End Group
# Begin Group "Resource Files"

# PROP Default_Filter "ico;cur;bmp;dlg;rc2;rct;bin;rgs;gif;jpg;jpeg;jpe"
# Begin Source File

SOURCE=.\res\bitmap1.bmp
# End Source File
# Begin Source File

SOURCE=.\res\bitmap_drag.bmp
# End Source File
# Begin Source File

SOURCE=.\res\cursor1.cur
# End Source File
# Begin Source File

SOURCE=.\res\icon1.ico
# End Source File
# Begin Source File

SOURCE=.\res\icon_tri.ico
# End Source File
# Begin Source File

SOURCE=.\res\kyotsu_kotei.bmp
# End Source File
# Begin Source File

SOURCE=.\res\toolbar1.bmp
# End Source File
# Begin Source File

SOURCE=.\res\viewtype1.bmp
# End Source File
# Begin Source File

SOURCE=.\res\viewtype2.bmp
# End Source File
# Begin Source File

SOURCE=.\res\viewtype3.bmp
# End Source File
# Begin Source File

SOURCE=.\res\viewtype4.bmp
# End Source File
# Begin Source File

SOURCE=.\res\WorkScheduler.ico
# End Source File
# Begin Source File

SOURCE=.\res\WorkScheduler.rc2
# End Source File
# End Group
# Begin Source File

SOURCE=.\ReadMe.txt
# End Source File
# End Target
# End Project
# Section WorkScheduler : {857FBBF8-D0C6-44E6-AB18-D03165173D42}
# 	2:21:DefaultSinkHeaderFile:spreadsheet.h
# 	2:16:DefaultSinkClass:CSpreadSheet
# End Section
# Section WorkScheduler : {857FBBF3-D0C6-44E6-AB18-D03165173D42}
# 	2:5:Class:CSpreadSheet
# 	2:10:HeaderFile:spreadsheet.h
# 	2:8:ImplFile:spreadsheet.cpp
# End Section
