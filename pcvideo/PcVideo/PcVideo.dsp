# Microsoft Developer Studio Project File - Name="PcVideo" - Package Owner=<4>
# Microsoft Developer Studio Generated Build File, Format Version 6.00
# ** 編集しないでください **

# TARGTYPE "Win32 (x86) Application" 0x0101

CFG=PcVideo - Win32 Debug_Eng
!MESSAGE これは有効なﾒｲｸﾌｧｲﾙではありません。 このﾌﾟﾛｼﾞｪｸﾄをﾋﾞﾙﾄﾞするためには NMAKE を使用してください。
!MESSAGE [ﾒｲｸﾌｧｲﾙのｴｸｽﾎﾟｰﾄ] ｺﾏﾝﾄﾞを使用して実行してください
!MESSAGE 
!MESSAGE NMAKE /f "PcVideo.mak".
!MESSAGE 
!MESSAGE NMAKE の実行時に構成を指定できます
!MESSAGE ｺﾏﾝﾄﾞ ﾗｲﾝ上でﾏｸﾛの設定を定義します。例:
!MESSAGE 
!MESSAGE NMAKE /f "PcVideo.mak" CFG="PcVideo - Win32 Debug_Eng"
!MESSAGE 
!MESSAGE 選択可能なﾋﾞﾙﾄﾞ ﾓｰﾄﾞ:
!MESSAGE 
!MESSAGE "PcVideo - Win32 Release_Jpn" ("Win32 (x86) Application" 用)
!MESSAGE "PcVideo - Win32 Release_Eng" ("Win32 (x86) Application" 用)
!MESSAGE "PcVideo - Win32 Debug_Jpn" ("Win32 (x86) Application" 用)
!MESSAGE "PcVideo - Win32 Debug_Eng" ("Win32 (x86) Application" 用)
!MESSAGE 

# Begin Project
# PROP AllowPerConfigDependencies 0
# PROP Scc_ProjName ""
# PROP Scc_LocalPath ""
CPP=cl.exe
MTL=midl.exe
RSC=rc.exe

!IF  "$(CFG)" == "PcVideo - Win32 Release_Jpn"

# PROP BASE Use_MFC 6
# PROP BASE Use_Debug_Libraries 0
# PROP BASE Output_Dir "PcVideo___Win32_Release_Jpn"
# PROP BASE Intermediate_Dir "PcVideo___Win32_Release_Jpn"
# PROP BASE Ignore_Export_Lib 0
# PROP BASE Target_Dir ""
# PROP Use_MFC 6
# PROP Use_Debug_Libraries 0
# PROP Output_Dir "Release_Jpn"
# PROP Intermediate_Dir "Release_Jpn"
# PROP Ignore_Export_Lib 0
# PROP Target_Dir ""
# ADD BASE CPP /nologo /Zp1 /MD /W3 /GX /O2 /I "..\Common" /D "WIN32" /D "NDEBUG" /D "_WINDOWS" /D "_AFXDLL" /D "_MBCS" /Yu"stdafx.h" /J /FD /c
# ADD CPP /nologo /Zp1 /MD /W3 /GX /O2 /I "..\Common" /D "WIN32" /D "NDEBUG" /D "_WINDOWS" /D "_AFXDLL" /D "_MBCS" /Yu"stdafx.h" /J /FD /c
# ADD BASE MTL /nologo /D "NDEBUG" /mktyplib203 /win32
# ADD MTL /nologo /D "NDEBUG" /mktyplib203 /win32
# ADD BASE RSC /l 0x411 /d "NDEBUG" /d "_AFXDLL"
# ADD RSC /l 0x411 /d "NDEBUG" /d "_AFXDLL" /d "AFX_RESOURCE_DLL" /d "AFX_TARG_NEU" /d "AFX_TARG_JPN"
BSC32=bscmake.exe
# ADD BASE BSC32 /nologo
# ADD BSC32 /nologo
LINK32=link.exe
# ADD BASE LINK32 wsock32.lib winmm.lib vfw32.lib /nologo /subsystem:windows /machine:I386
# ADD LINK32 wsock32.lib winmm.lib vfw32.lib License.lib /nologo /subsystem:windows /machine:I386 /out:"Release_Jpn/PcVideoJ.exe"

!ELSEIF  "$(CFG)" == "PcVideo - Win32 Release_Eng"

# PROP BASE Use_MFC 6
# PROP BASE Use_Debug_Libraries 0
# PROP BASE Output_Dir "PcVideo___Win32_Release_Eng"
# PROP BASE Intermediate_Dir "PcVideo___Win32_Release_Eng"
# PROP BASE Ignore_Export_Lib 0
# PROP BASE Target_Dir ""
# PROP Use_MFC 6
# PROP Use_Debug_Libraries 0
# PROP Output_Dir "Release_Eng"
# PROP Intermediate_Dir "Release_Eng"
# PROP Ignore_Export_Lib 0
# PROP Target_Dir ""
# ADD BASE CPP /nologo /Zp1 /MD /W3 /GX /O2 /I "..\Common" /D "WIN32" /D "NDEBUG" /D "_WINDOWS" /D "_AFXDLL" /D "_MBCS" /Yu"stdafx.h" /J /FD /c
# ADD CPP /nologo /Zp1 /MD /W3 /GX /O2 /I "..\Common" /D "WIN32" /D "NDEBUG" /D "_WINDOWS" /D "_AFXDLL" /D "_MBCS" /Yu"stdafx.h" /J /FD /c
# ADD BASE MTL /nologo /D "NDEBUG" /mktyplib203 /win32
# ADD MTL /nologo /D "NDEBUG" /mktyplib203 /win32
# ADD BASE RSC /l 0x411 /d "NDEBUG" /d "_AFXDLL"
# ADD RSC /l 0x409 /d "NDEBUG" /d "_AFXDLL" /d "AFX_RESOURCE_DLL" /d "AFX_TARG_NEU" /d "AFX_TARG_ENU"
BSC32=bscmake.exe
# ADD BASE BSC32 /nologo
# ADD BSC32 /nologo
LINK32=link.exe
# ADD BASE LINK32 wsock32.lib winmm.lib vfw32.lib /nologo /subsystem:windows /machine:I386
# ADD LINK32 wsock32.lib winmm.lib vfw32.lib License.lib /nologo /subsystem:windows /machine:I386 /out:"Release_Eng/PcVideoE.exe"

!ELSEIF  "$(CFG)" == "PcVideo - Win32 Debug_Jpn"

# PROP BASE Use_MFC 6
# PROP BASE Use_Debug_Libraries 1
# PROP BASE Output_Dir "PcVideo___Win32_Debug_Jpn"
# PROP BASE Intermediate_Dir "PcVideo___Win32_Debug_Jpn"
# PROP BASE Ignore_Export_Lib 0
# PROP BASE Target_Dir ""
# PROP Use_MFC 6
# PROP Use_Debug_Libraries 1
# PROP Output_Dir "Debug_Jpn"
# PROP Intermediate_Dir "Debug_Jpn"
# PROP Ignore_Export_Lib 0
# PROP Target_Dir ""
# ADD BASE CPP /nologo /Zp1 /MDd /W3 /Gm /GX /ZI /Od /I "..\Common" /D "WIN32" /D "_DEBUG" /D "_WINDOWS" /D "_AFXDLL" /D "_MBCS" /Yu"stdafx.h" /J /FD /GZ /c
# ADD CPP /nologo /Zp1 /MDd /W3 /Gm /GX /ZI /Od /I "..\Common" /D "WIN32" /D "_DEBUG" /D "_WINDOWS" /D "_AFXDLL" /D "_MBCS" /Yu"stdafx.h" /J /FD /GZ /c
# ADD BASE MTL /nologo /D "_DEBUG" /mktyplib203 /win32
# ADD MTL /nologo /D "_DEBUG" /mktyplib203 /win32
# ADD BASE RSC /l 0x411 /d "_DEBUG" /d "_AFXDLL"
# ADD RSC /l 0x411 /d "_DEBUG" /d "_AFXDLL" /d "AFX_RESOURCE_DLL" /d "AFX_TARG_NEU" /d "AFX_TARG_JPN"
BSC32=bscmake.exe
# ADD BASE BSC32 /nologo
# ADD BSC32 /nologo
LINK32=link.exe
# ADD BASE LINK32 wsock32.lib winmm.lib vfw32.lib /nologo /subsystem:windows /debug /machine:I386 /pdbtype:sept
# ADD LINK32 wsock32.lib winmm.lib vfw32.lib Licensed.lib /nologo /subsystem:windows /debug /machine:I386 /out:"Debug_Jpn/PcVideoJ.exe" /pdbtype:sept

!ELSEIF  "$(CFG)" == "PcVideo - Win32 Debug_Eng"

# PROP BASE Use_MFC 6
# PROP BASE Use_Debug_Libraries 1
# PROP BASE Output_Dir "PcVideo___Win32_Debug_Eng"
# PROP BASE Intermediate_Dir "PcVideo___Win32_Debug_Eng"
# PROP BASE Ignore_Export_Lib 0
# PROP BASE Target_Dir ""
# PROP Use_MFC 6
# PROP Use_Debug_Libraries 1
# PROP Output_Dir "Debug_Eng"
# PROP Intermediate_Dir "Debug_Eng"
# PROP Ignore_Export_Lib 0
# PROP Target_Dir ""
# ADD BASE CPP /nologo /Zp1 /MDd /W3 /Gm /GX /ZI /Od /I "..\Common" /D "WIN32" /D "_DEBUG" /D "_WINDOWS" /D "_AFXDLL" /D "_MBCS" /Yu"stdafx.h" /J /FD /GZ /c
# ADD CPP /nologo /Zp1 /MDd /W3 /Gm /GX /ZI /Od /I "..\Common" /D "WIN32" /D "_DEBUG" /D "_WINDOWS" /D "_AFXDLL" /D "_MBCS" /Yu"stdafx.h" /J /FD /GZ /c
# ADD BASE MTL /nologo /D "_DEBUG" /mktyplib203 /win32
# ADD MTL /nologo /D "_DEBUG" /mktyplib203 /win32
# ADD BASE RSC /l 0x411 /d "_DEBUG" /d "_AFXDLL"
# ADD RSC /l 0x409 /d "_DEBUG" /d "_AFXDLL" /d "AFX_RESOURCE_DLL" /d "AFX_TARG_NEU" /d "AFX_TARG_ENU"
BSC32=bscmake.exe
# ADD BASE BSC32 /nologo
# ADD BSC32 /nologo
LINK32=link.exe
# ADD BASE LINK32 wsock32.lib winmm.lib vfw32.lib /nologo /subsystem:windows /debug /machine:I386 /pdbtype:sept
# ADD LINK32 wsock32.lib winmm.lib vfw32.lib Licensed.lib /nologo /subsystem:windows /debug /machine:I386 /out:"Debug_Eng/PcVideoE.exe" /pdbtype:sept

!ENDIF 

# Begin Target

# Name "PcVideo - Win32 Release_Jpn"
# Name "PcVideo - Win32 Release_Eng"
# Name "PcVideo - Win32 Debug_Jpn"
# Name "PcVideo - Win32 Debug_Eng"
# Begin Group "Source Files"

# PROP Default_Filter "cpp;c;cxx;rc;def;r;odl;idl;hpj;bat"
# Begin Source File

SOURCE=.\CALL.CPP
# End Source File
# Begin Source File

SOURCE=.\CAMERA.CPP
# End Source File
# Begin Source File

SOURCE=.\CAPTURE.CPP
# End Source File
# Begin Source File

SOURCE=.\CDAUDIO.CPP
# End Source File
# Begin Source File

SOURCE=.\CDVIDEO.CPP
# End Source File
# Begin Source File

SOURCE=.\CHAT.CPP
# End Source File
# Begin Source File

SOURCE=.\CMCMD.CPP
# End Source File
# Begin Source File

SOURCE=.\ConnectDlg.cpp
# End Source File
# Begin Source File

SOURCE=.\CSUB.CPP
# End Source File
# Begin Source File

SOURCE=.\DATA.CPP
# End Source File
# Begin Source File

SOURCE=.\Global.cpp
# End Source File
# Begin Source File

SOURCE=.\LBView.cpp
# End Source File
# Begin Source File

SOURCE=.\LICENSE.CPP
# End Source File
# Begin Source File

SOURCE=.\LoginDlg.cpp
# End Source File
# Begin Source File

SOURCE=.\MainFrm.cpp
# End Source File
# Begin Source File

SOURCE=.\Mixer.cpp
# End Source File
# Begin Source File

SOURCE=.\PcVideo.cpp
# End Source File
# Begin Source File

SOURCE=.\PcVideo.rc
# End Source File
# Begin Source File

SOURCE=.\RECEIVE.CPP
# End Source File
# Begin Source File

SOURCE=.\REMOTE.CPP
# End Source File
# Begin Source File

SOURCE=.\SETTING.CPP
# End Source File
# Begin Source File

SOURCE=.\SOCKET.CPP
# End Source File
# Begin Source File

SOURCE=.\StdAfx.cpp
# ADD BASE CPP /Yc"stdafx.h"
# ADD CPP /Yc"stdafx.h"
# End Source File
# Begin Source File

SOURCE=.\USER.CPP
# End Source File
# Begin Source File

SOURCE=.\VIDEO.CPP
# End Source File
# Begin Source File

SOURCE=.\Voice.cpp
# End Source File
# End Group
# Begin Group "Header Files"

# PROP Default_Filter "h;hpp;hxx;hm;inl"
# Begin Source File

SOURCE=.\CALL.H
# End Source File
# Begin Source File

SOURCE=.\CAMERA.H
# End Source File
# Begin Source File

SOURCE=.\CAPTURE.H
# End Source File
# Begin Source File

SOURCE=.\CHAT.H
# End Source File
# Begin Source File

SOURCE=.\CMCMD.H
# End Source File
# Begin Source File

SOURCE=..\Common\COMMON.H
# End Source File
# Begin Source File

SOURCE=.\ConnectDlg.h
# End Source File
# Begin Source File

SOURCE=.\LBView.h
# End Source File
# Begin Source File

SOURCE=.\LICENSE.H
# End Source File
# Begin Source File

SOURCE=.\LoginDlg.h
# End Source File
# Begin Source File

SOURCE=.\MainFrm.h
# End Source File
# Begin Source File

SOURCE=.\Mixer.h
# End Source File
# Begin Source File

SOURCE=.\PCVCOMM.H
# End Source File
# Begin Source File

SOURCE=.\PcVideo.h
# End Source File
# Begin Source File

SOURCE=.\RECEIVE.H
# End Source File
# Begin Source File

SOURCE=.\REMOTE.H
# End Source File
# Begin Source File

SOURCE=.\Resource.h
# End Source File
# Begin Source File

SOURCE=.\SETTING.H
# End Source File
# Begin Source File

SOURCE=.\StdAfx.h
# End Source File
# Begin Source File

SOURCE=.\VCC1.H
# End Source File
# Begin Source File

SOURCE=.\VIDEO.H
# End Source File
# Begin Source File

SOURCE=.\VOICE.H
# End Source File
# End Group
# Begin Group "Resource Files"

# PROP Default_Filter "ico;cur;bmp;dlg;rc2;rct;bin;rgs;gif;jpg;jpeg;jpe"
# Begin Source File

SOURCE=.\res\CHAT.BMP
# End Source File
# Begin Source File

SOURCE=.\res\CHAT2.BMP
# End Source File
# Begin Source File

SOURCE=.\res\DLL.BMP
# End Source File
# Begin Source File

SOURCE=.\res\DLL_MASK.BMP
# End Source File
# Begin Source File

SOURCE=.\res\DOWND.BMP
# End Source File
# Begin Source File

SOURCE=.\res\DOWNU.BMP
# End Source File
# Begin Source File

SOURCE=.\res\DOWNX.BMP
# End Source File
# Begin Source File

SOURCE=.\res\FARD.BMP
# End Source File
# Begin Source File

SOURCE=.\res\FARU.BMP
# End Source File
# Begin Source File

SOURCE=.\res\FARX.BMP
# End Source File
# Begin Source File

SOURCE=.\res\HANDCLOS.CUR
# End Source File
# Begin Source File

SOURCE=.\res\HANDOPEN.CUR
# End Source File
# Begin Source File

SOURCE=.\res\HMOVE.CUR
# End Source File
# Begin Source File

SOURCE=.\res\HOMED.BMP
# End Source File
# Begin Source File

SOURCE=.\res\HOMEU.BMP
# End Source File
# Begin Source File

SOURCE=.\res\HOMEX.BMP
# End Source File
# Begin Source File

SOURCE=.\res\ico00001.ico
# End Source File
# Begin Source File

SOURCE=.\res\icon1.ico
# End Source File
# Begin Source File

SOURCE=.\ICON6.ICO
# End Source File
# Begin Source File

SOURCE=.\res\idr_main.ico
# End Source File
# Begin Source File

SOURCE=.\res\LEFTD.BMP
# End Source File
# Begin Source File

SOURCE=.\res\LEFTU.BMP
# End Source File
# Begin Source File

SOURCE=.\res\LEFTX.BMP
# End Source File
# Begin Source File

SOURCE=.\res\mainfram.bmp
# End Source File
# Begin Source File

SOURCE=.\res\micropho.ico
# End Source File
# Begin Source File

SOURCE=.\MOVE.CUR
# End Source File
# Begin Source File

SOURCE=.\res\NEARD.BMP
# End Source File
# Begin Source File

SOURCE=.\res\NEARU.BMP
# End Source File
# Begin Source File

SOURCE=.\res\NEARX.BMP
# End Source File
# Begin Source File

SOURCE=.\res\NONE.BMP
# End Source File
# Begin Source File

SOURCE=.\res\NONE2.BMP
# End Source File
# Begin Source File

SOURCE=.\PCVIDEO.ICO
# End Source File
# Begin Source File

SOURCE=.\res\PcVideo.ico
# End Source File
# Begin Source File

SOURCE=.\res\PcVideo.rc2
# End Source File
# Begin Source File

SOURCE=.\res\PcVideoDoc.ico
# End Source File
# Begin Source File

SOURCE=.\PHONE04.ICO
# End Source File
# Begin Source File

SOURCE=.\PHONE07.ICO
# End Source File
# Begin Source File

SOURCE=.\PHONE08.ICO
# End Source File
# Begin Source File

SOURCE=.\res\REFLECT.BMP
# End Source File
# Begin Source File

SOURCE=.\res\REFLECT2.BMP
# End Source File
# Begin Source File

SOURCE=.\res\RIGHTD.BMP
# End Source File
# Begin Source File

SOURCE=.\res\RIGHTU.BMP
# End Source File
# Begin Source File

SOURCE=.\res\RIGHTX.BMP
# End Source File
# Begin Source File

SOURCE=.\res\speaker.ico
# End Source File
# Begin Source File

SOURCE=.\res\speaker_.ico
# End Source File
# Begin Source File

SOURCE=.\SPK2.ICO
# End Source File
# Begin Source File

SOURCE=.\res\TELED.BMP
# End Source File
# Begin Source File

SOURCE=.\res\TELEU.BMP
# End Source File
# Begin Source File

SOURCE=.\res\TELEX.BMP
# End Source File
# Begin Source File

SOURCE=.\res\Toolbar.bmp
# End Source File
# Begin Source File

SOURCE=.\res\TOOLBAR1.BMP
# End Source File
# Begin Source File

SOURCE=.\res\UPD.BMP
# End Source File
# Begin Source File

SOURCE=.\res\UPU.BMP
# End Source File
# Begin Source File

SOURCE=.\res\UPX.BMP
# End Source File
# Begin Source File

SOURCE=.\res\VIDEO.BMP
# End Source File
# Begin Source File

SOURCE=.\res\VIDEO2.BMP
# End Source File
# Begin Source File

SOURCE=.\res\VMOVE.CUR
# End Source File
# Begin Source File

SOURCE=.\res\VOICE.BMP
# End Source File
# Begin Source File

SOURCE=.\res\VOICE2.BMP
# End Source File
# Begin Source File

SOURCE=.\res\WIDED.BMP
# End Source File
# Begin Source File

SOURCE=.\res\WIDEU.BMP
# End Source File
# Begin Source File

SOURCE=.\res\WIDEX.BMP
# End Source File
# End Group
# Begin Source File

SOURCE=.\ReadMe.txt
# End Source File
# Begin Source File

SOURCE=..\VCC1_32\Release\VCC1_32.lib
# End Source File
# End Target
# End Project
