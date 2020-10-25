# Microsoft Developer Studio Project File - Name="PCSWIN32" - Package Owner=<4>
# Microsoft Developer Studio Generated Build File, Format Version 6.00
# ** 編集しないでください **

# TARGTYPE "Win32 (x86) Dynamic-Link Library" 0x0102

CFG=PCSWIN32 - Win32 Release
!MESSAGE これは有効なﾒｲｸﾌｧｲﾙではありません。 このﾌﾟﾛｼﾞｪｸﾄをﾋﾞﾙﾄﾞするためには NMAKE を使用してください。
!MESSAGE [ﾒｲｸﾌｧｲﾙのｴｸｽﾎﾟｰﾄ] ｺﾏﾝﾄﾞを使用して実行してください
!MESSAGE 
!MESSAGE NMAKE /f "pcswin32.mak".
!MESSAGE 
!MESSAGE NMAKE の実行時に構成を指定できます
!MESSAGE ｺﾏﾝﾄﾞ ﾗｲﾝ上でﾏｸﾛの設定を定義します。例:
!MESSAGE 
!MESSAGE NMAKE /f "pcswin32.mak" CFG="PCSWIN32 - Win32 Release"
!MESSAGE 
!MESSAGE 選択可能なﾋﾞﾙﾄﾞ ﾓｰﾄﾞ:
!MESSAGE 
!MESSAGE "PCSWIN32 - Win32 Release" ("Win32 (x86) Dynamic-Link Library" 用)
!MESSAGE "PCSWIN32 - Win32 Debug" ("Win32 (x86) Dynamic-Link Library" 用)
!MESSAGE 

# Begin Project
# PROP AllowPerConfigDependencies 0
# PROP Scc_ProjName ""
# PROP Scc_LocalPath ""
CPP=cl.exe
MTL=midl.exe
RSC=rc.exe

!IF  "$(CFG)" == "PCSWIN32 - Win32 Release"

# PROP BASE Use_MFC 0
# PROP BASE Use_Debug_Libraries 0
# PROP BASE Output_Dir "PCSWIN32___Win32_Release"
# PROP BASE Intermediate_Dir "PCSWIN32___Win32_Release"
# PROP BASE Target_Dir ""
# PROP Use_MFC 0
# PROP Use_Debug_Libraries 0
# PROP Output_Dir "Pcswin32_Release"
# PROP Intermediate_Dir "Pcswin32_Release"
# PROP Ignore_Export_Lib 0
# PROP Target_Dir ""
# ADD BASE CPP /nologo /G5 /Zp1 /MD /W3 /WX /GX /O2 /D "WIN32" /D "NDEBUG" /D "_WINDOWS" /D "OS_WNT" /J /FD /c
# ADD CPP /nologo /G5 /Zp1 /MD /W3 /WX /GX /O2 /D "WIN32" /D "NDEBUG" /D "_WINDOWS" /D "OS_WNT" /J /FD /c
# ADD BASE MTL /nologo /D "NDEBUG" /mktyplib203 /win32
# ADD MTL /nologo /D "NDEBUG" /mktyplib203 /win32
# ADD BASE RSC /l 0x411 /d "NDEBUG"
# ADD RSC /l 0x411 /d "NDEBUG"
BSC32=bscmake.exe
# ADD BASE BSC32 /nologo
# ADD BSC32 /nologo
LINK32=link.exe
# ADD BASE LINK32 kernel32.lib user32.lib advapi32.lib shell32.lib ole32.lib oleaut32.lib wsock32.lib /nologo /subsystem:windows /dll /machine:I386 /out:".\PCSWIN32.dll"
# ADD LINK32 kernel32.lib user32.lib advapi32.lib oleaut32.lib wsock32.lib /nologo /subsystem:windows /dll /machine:I386
# Begin Custom Build
TargetPath=.\Pcswin32_Release\pcswin32.dll
TargetName=pcswin32
InputPath=.\Pcswin32_Release\pcswin32.dll
SOURCE="$(InputPath)"

"c:\winnt\$(TargetName).dll" : $(SOURCE) "$(INTDIR)" "$(OUTDIR)"
	copy $(TargetPath) c:\winnt

# End Custom Build

!ELSEIF  "$(CFG)" == "PCSWIN32 - Win32 Debug"

# PROP BASE Use_MFC 0
# PROP BASE Use_Debug_Libraries 0
# PROP BASE Output_Dir "PCSWIN32___Win32_Debug"
# PROP BASE Intermediate_Dir "PCSWIN32___Win32_Debug"
# PROP BASE Ignore_Export_Lib 0
# PROP BASE Target_Dir ""
# PROP Use_MFC 0
# PROP Use_Debug_Libraries 0
# PROP Output_Dir "Pcswin32_Debug"
# PROP Intermediate_Dir "Pcswin32_Debug"
# PROP Ignore_Export_Lib 0
# PROP Target_Dir ""
# ADD BASE CPP /nologo /Zp1 /MDd /W3 /WX /GX /ZI /Od /D "WIN32" /D "DEBUG" /D "_WINDOWS" /D "OS_WNT" /J /FD /c
# ADD CPP /nologo /G5 /Zp1 /MDd /W3 /WX /GX /ZI /Od /D "WIN32" /D "DEBUG" /D "_WINDOWS" /D "OS_WNT" /J /FD /c
# ADD BASE MTL /nologo /D "DEBUG" /mktyplib203 /win32
# ADD MTL /nologo /D "DEBUG" /mktyplib203 /win32
# ADD BASE RSC /l 0x411 /d "DEBUG"
# ADD RSC /l 0x411 /d "DEBUG"
BSC32=bscmake.exe
# ADD BASE BSC32 /nologo
# ADD BSC32 /nologo /o"Pcswin32_Debug/pcswin32d.bsc"
LINK32=link.exe
# ADD BASE LINK32 kernel32.lib user32.lib advapi32.lib shell32.lib ole32.lib oleaut32.lib wsock32.lib /nologo /subsystem:windows /dll /map /debug /machine:I386 /out:".\PCSWIN3d.dll"
# ADD LINK32 kernel32.lib user32.lib advapi32.lib oleaut32.lib wsock32.lib /nologo /subsystem:windows /dll /incremental:yes /map /debug /machine:I386 /out:"Pcswin32_Debug/pcswin32d.dll"
# SUBTRACT LINK32 /force
# Begin Custom Build
TargetPath=.\Pcswin32_Debug\pcswin32d.dll
TargetName=pcswin32d
InputPath=.\Pcswin32_Debug\pcswin32d.dll
SOURCE="$(InputPath)"

"c:\windows\$(TargetName).dll" : $(SOURCE) "$(INTDIR)" "$(OUTDIR)"
	copy $(TargetPath) c:\windows

# End Custom Build

!ENDIF 

# Begin Target

# Name "PCSWIN32 - Win32 Release"
# Name "PCSWIN32 - Win32 Debug"
# Begin Group "Source Files"

# PROP Default_Filter "cpp;c;cxx;rc;def;r;odl;hpj;bat;for;f90"
# Begin Source File

SOURCE=.\BIN.C
# End Source File
# Begin Source File

SOURCE=.\CAD.C
# End Source File
# Begin Source File

SOURCE=.\CALNDR.C
# End Source File
# Begin Source File

SOURCE=.\CCF.C
# End Source File
# Begin Source File

SOURCE=.\CCL.C
# End Source File
# Begin Source File

SOURCE=.\CDB.C
# End Source File
# Begin Source File

SOURCE=.\CGW.C
# End Source File
# Begin Source File

SOURCE=.\cms.c
# End Source File
# Begin Source File

SOURCE=.\CPS.C
# End Source File
# Begin Source File

SOURCE=.\CRF.C
# End Source File
# Begin Source File

SOURCE=.\CSN.C
# End Source File
# Begin Source File

SOURCE=.\DB.C
# End Source File
# Begin Source File

SOURCE=.\DBF.C
# End Source File
# Begin Source File

SOURCE=.\DBSUB.C
# End Source File
# Begin Source File

SOURCE=.\FILTER.C
# End Source File
# Begin Source File

SOURCE=.\IDX.C
# End Source File
# Begin Source File

SOURCE=.\IOC.C
# End Source File
# Begin Source File

SOURCE=.\LoginDlg.c
# End Source File
# Begin Source File

SOURCE=.\PCSCOM.C
# End Source File
# Begin Source File

SOURCE=.\PCSWIN32.C
# End Source File
# Begin Source File

SOURCE=.\PCSWIN32.DEF
# End Source File
# Begin Source File

SOURCE=.\pcswin32.rc
# End Source File
# Begin Source File

SOURCE=.\RS232C.C
# End Source File
# Begin Source File

SOURCE=.\SEQNO.C
# End Source File
# Begin Source File

SOURCE=.\SORT.C
# End Source File
# End Group
# Begin Group "Header Files"

# PROP Default_Filter "h;hpp;hxx;hm;inl;fi;fd"
# Begin Source File

SOURCE=.\PCSCL.H
# End Source File
# Begin Source File

SOURCE=.\PCSCLNT.H
# End Source File
# Begin Source File

SOURCE=.\PCSCOM.H
# End Source File
# Begin Source File

SOURCE=.\PCSDB.H
# End Source File
# Begin Source File

SOURCE=.\Pcsdbsub.h
# End Source File
# Begin Source File

SOURCE=.\PCSFC.H
# End Source File
# Begin Source File

SOURCE=.\Pcsio.h
# End Source File
# Begin Source File

SOURCE=.\PCSOS.H
# End Source File
# Begin Source File

SOURCE=.\PCSSN.H
# End Source File
# Begin Source File

SOURCE=.\Pcssock.h
# End Source File
# Begin Source File

SOURCE=.\PCSSYS.H
# End Source File
# End Group
# Begin Group "Resource Files"

# PROP Default_Filter "ico;cur;bmp;dlg;rc2;rct;bin;cnt;rtf;gif;jpg;jpeg;jpe"
# End Group
# End Target
# End Project
