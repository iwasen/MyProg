# Microsoft Developer Studio Project File - Name="pcserve" - Package Owner=<4>
# Microsoft Developer Studio Generated Build File, Format Version 6.00
# ** 編集しないでください **

# TARGTYPE "Win32 (x86) Console Application" 0x0103

CFG=pcserve - Win32 Release
!MESSAGE これは有効なﾒｲｸﾌｧｲﾙではありません。 このﾌﾟﾛｼﾞｪｸﾄをﾋﾞﾙﾄﾞするためには NMAKE を使用してください。
!MESSAGE [ﾒｲｸﾌｧｲﾙのｴｸｽﾎﾟｰﾄ] ｺﾏﾝﾄﾞを使用して実行してください
!MESSAGE 
!MESSAGE NMAKE /f "pcserve.mak".
!MESSAGE 
!MESSAGE NMAKE の実行時に構成を指定できます
!MESSAGE ｺﾏﾝﾄﾞ ﾗｲﾝ上でﾏｸﾛの設定を定義します。例:
!MESSAGE 
!MESSAGE NMAKE /f "pcserve.mak" CFG="pcserve - Win32 Release"
!MESSAGE 
!MESSAGE 選択可能なﾋﾞﾙﾄﾞ ﾓｰﾄﾞ:
!MESSAGE 
!MESSAGE "pcserve - Win32 Release" ("Win32 (x86) Console Application" 用)
!MESSAGE "pcserve - Win32 Debug" ("Win32 (x86) Console Application" 用)
!MESSAGE 

# Begin Project
# PROP AllowPerConfigDependencies 0
# PROP Scc_ProjName ""
# PROP Scc_LocalPath ""
CPP=cl.exe
RSC=rc.exe

!IF  "$(CFG)" == "pcserve - Win32 Release"

# PROP BASE Use_MFC 0
# PROP BASE Use_Debug_Libraries 0
# PROP BASE Output_Dir ".\Release"
# PROP BASE Intermediate_Dir ".\Release"
# PROP BASE Target_Dir ""
# PROP Use_MFC 0
# PROP Use_Debug_Libraries 0
# PROP Output_Dir "Pcserve_Release"
# PROP Intermediate_Dir "Pcserve_Release"
# PROP Ignore_Export_Lib 0
# PROP Target_Dir ""
# ADD BASE CPP /nologo /W3 /GX /O2 /D "WIN32" /D "NDEBUG" /D "_CONSOLE" /YX /c
# ADD CPP /nologo /G6 /Zp1 /MT /W3 /WX /GX /O2 /D "WIN32" /D "NDEBUG" /D "_CONSOLE" /D "OS_WNT" /D "ALLOC_COUNT" /D "_SERVER" /YX /J /FD /c
# ADD BASE RSC /l 0x411 /d "NDEBUG"
# ADD RSC /l 0x411 /d "NDEBUG"
BSC32=bscmake.exe
# ADD BASE BSC32 /nologo
# ADD BSC32 /nologo
LINK32=link.exe
# ADD BASE LINK32 kernel32.lib user32.lib gdi32.lib winspool.lib comdlg32.lib advapi32.lib shell32.lib ole32.lib oleaut32.lib uuid.lib odbc32.lib odbccp32.lib /nologo /subsystem:console /machine:I386
# ADD LINK32 kernel32.lib advapi32.lib wsock32.lib netapi32.lib pcscheck.lib /nologo /subsystem:console /map /machine:I386 /out:"Pcserve_Release/pcserve.wnt"

!ELSEIF  "$(CFG)" == "pcserve - Win32 Debug"

# PROP BASE Use_MFC 0
# PROP BASE Use_Debug_Libraries 1
# PROP BASE Output_Dir ".\Debug"
# PROP BASE Intermediate_Dir ".\Debug"
# PROP BASE Target_Dir ""
# PROP Use_MFC 0
# PROP Use_Debug_Libraries 1
# PROP Output_Dir ".\Pcserve_Debug"
# PROP Intermediate_Dir ".\Pcserve_Debug"
# PROP Ignore_Export_Lib 0
# PROP Target_Dir ""
# ADD BASE CPP /nologo /W3 /Gm /GX /Zi /Od /D "WIN32" /D "_DEBUG" /D "_CONSOLE" /YX /c
# ADD CPP /nologo /G6 /Zp1 /MTd /W3 /WX /Gm /GX /ZI /Od /D "WIN32" /D "_DEBUG" /D "_CONSOLE" /D "OS_WNT" /D "ALLOC_COUNT" /D "_SERVER" /YX /J /FD /c
# ADD BASE RSC /l 0x411 /d "_DEBUG"
# ADD RSC /l 0x411 /d "_DEBUG"
BSC32=bscmake.exe
# ADD BASE BSC32 /nologo
# ADD BSC32 /nologo
LINK32=link.exe
# ADD BASE LINK32 kernel32.lib user32.lib gdi32.lib winspool.lib comdlg32.lib advapi32.lib shell32.lib ole32.lib oleaut32.lib uuid.lib odbc32.lib odbccp32.lib /nologo /subsystem:console /debug /machine:I386
# ADD LINK32 kernel32.lib advapi32.lib wsock32.lib netapi32.lib pcscheck.lib /nologo /subsystem:console /debug /machine:I386 /out:"Pcserve_Debug/pcserve.wnt"
# SUBTRACT LINK32 /map /nodefaultlib
# Begin Custom Build
InputPath=.\Pcserve_Debug\pcserve.wnt
SOURCE="$(InputPath)"

"c:\service\pcserve\pcserve.wnt" : $(SOURCE) "$(INTDIR)" "$(OUTDIR)"
	copy Pcserve_Debug.\pcserve.wnt c:\service\pcserve\pcserve.wnt

# End Custom Build

!ENDIF 

# Begin Target

# Name "pcserve - Win32 Release"
# Name "pcserve - Win32 Debug"
# Begin Group "Source Files"

# PROP Default_Filter "cpp;c;cxx;rc;def;r;odl;hpj;bat;for;f90"
# Begin Source File

SOURCE=.\BIN.C
# End Source File
# Begin Source File

SOURCE=.\CALNDR.C
# End Source File
# Begin Source File

SOURCE=.\CMD.C
# End Source File
# Begin Source File

SOURCE=.\CS_WNT.C
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

SOURCE=.\DUPLEX.C
# End Source File
# Begin Source File

SOURCE=.\FILTER.C
# End Source File
# Begin Source File

SOURCE=.\IDX.C
# End Source File
# Begin Source File

SOURCE=.\MULTI.C
# End Source File
# Begin Source File

SOURCE=.\NETWNT.C
# End Source File
# Begin Source File

SOURCE=.\PCSCOM.C
# End Source File
# Begin Source File

SOURCE=.\PCSERVE.C
# End Source File
# Begin Source File

SOURCE=.\PCSSUB.C
# End Source File
# Begin Source File

SOURCE=.\SAD.C
# End Source File
# Begin Source File

SOURCE=.\SCFWNT.C
# End Source File
# Begin Source File

SOURCE=.\SCL.C
# End Source File
# Begin Source File

SOURCE=.\SDB.C
# End Source File
# Begin Source File

SOURCE=.\SENDMAIL.C
# End Source File
# Begin Source File

SOURCE=.\SEQNO.C
# End Source File
# Begin Source File

SOURCE=.\SFile.c
# End Source File
# Begin Source File

SOURCE=.\SFUNC.C
# End Source File
# Begin Source File

SOURCE=.\SGW.C
# End Source File
# Begin Source File

SOURCE=.\SIO.C
# End Source File
# Begin Source File

SOURCE=.\SMS.C
# End Source File
# Begin Source File

SOURCE=.\SORT.C
# End Source File
# Begin Source File

SOURCE=.\SPS.C
# End Source File
# Begin Source File

SOURCE=.\SRFWNT.C
# End Source File
# Begin Source File

SOURCE=.\SSN.C
# End Source File
# End Group
# Begin Group "Header Files"

# PROP Default_Filter "h;hpp;hxx;hm;inl;fi;fd"
# Begin Source File

SOURCE=.\PCSCL.H
# End Source File
# Begin Source File

SOURCE=.\PCSCOM.H
# End Source File
# Begin Source File

SOURCE=.\PCSDB.H
# End Source File
# Begin Source File

SOURCE=.\PCSDBSUB.H
# End Source File
# Begin Source File

SOURCE=.\PCSERVE.H
# End Source File
# Begin Source File

SOURCE=.\PCSFC.H
# End Source File
# Begin Source File

SOURCE=.\PCSIO.H
# End Source File
# Begin Source File

SOURCE=.\PCSOS.H
# End Source File
# Begin Source File

SOURCE=.\PCSSN.H
# End Source File
# Begin Source File

SOURCE=.\PCSSOCK.H
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
