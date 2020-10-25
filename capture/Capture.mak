# Microsoft Developer Studio Generated NMAKE File, Format Version 4.20
# ** 編集しないでください **

# TARGTYPE "Win32 (x86) Application" 0x0101

!IF "$(CFG)" == ""
CFG=Capture - Win32 Debug
!MESSAGE 構成が指定されていません。ﾃﾞﾌｫﾙﾄの Capture - Win32 Debug を設定します。
!ENDIF 

!IF "$(CFG)" != "Capture - Win32 Release" && "$(CFG)" !=\
 "Capture - Win32 Debug"
!MESSAGE 指定された ﾋﾞﾙﾄﾞ ﾓｰﾄﾞ "$(CFG)" は正しくありません。
!MESSAGE ｺﾏﾝﾄﾞ ﾗｲﾝ上でﾏｸﾛ 'CFG' を定義することによって
!MESSAGE NMAKE 実行時にﾋﾞﾙﾄﾞ ﾓｰﾄﾞを指定できます。例えば:
!MESSAGE 
!MESSAGE NMAKE /f "Capture.mak" CFG="Capture - Win32 Debug"
!MESSAGE 
!MESSAGE 選択可能なﾋﾞﾙﾄﾞ ﾓｰﾄﾞ:
!MESSAGE 
!MESSAGE "Capture - Win32 Release" ("Win32 (x86) Application" 用)
!MESSAGE "Capture - Win32 Debug" ("Win32 (x86) Application" 用)
!MESSAGE 
!ERROR 無効な構成が指定されています。
!ENDIF 

!IF "$(OS)" == "Windows_NT"
NULL=
!ELSE 
NULL=nul
!ENDIF 
################################################################################
# Begin Project
# PROP Target_Last_Scanned "Capture - Win32 Debug"
CPP=cl.exe
RSC=rc.exe
MTL=mktyplib.exe

!IF  "$(CFG)" == "Capture - Win32 Release"

# PROP BASE Use_MFC 5
# PROP BASE Use_Debug_Libraries 0
# PROP BASE Output_Dir "Release"
# PROP BASE Intermediate_Dir "Release"
# PROP BASE Target_Dir ""
# PROP Use_MFC 5
# PROP Use_Debug_Libraries 0
# PROP Output_Dir "Release"
# PROP Intermediate_Dir "Release"
# PROP Target_Dir ""
OUTDIR=.\Release
INTDIR=.\Release

ALL : "$(OUTDIR)\Capture.exe"

CLEAN : 
	-@erase "$(INTDIR)\CaptDoc.obj"
	-@erase "$(INTDIR)\Capture.obj"
	-@erase "$(INTDIR)\Capture.pch"
	-@erase "$(INTDIR)\Capture.res"
	-@erase "$(INTDIR)\CaptView.obj"
	-@erase "$(INTDIR)\Inifile.obj"
	-@erase "$(INTDIR)\jpeg.obj"
	-@erase "$(INTDIR)\MainFrm.obj"
	-@erase "$(INTDIR)\SetFile.obj"
	-@erase "$(INTDIR)\StdAfx.obj"
	-@erase "$(OUTDIR)\Capture.exe"

"$(OUTDIR)" :
    if not exist "$(OUTDIR)/$(NULL)" mkdir "$(OUTDIR)"

# ADD BASE CPP /nologo /MT /W3 /GX /O2 /D "WIN32" /D "NDEBUG" /D "_WINDOWS" /D "_MBCS" /Yu"stdafx.h" /c
# ADD CPP /nologo /G5 /MT /W3 /GX /O2 /D "WIN32" /D "NDEBUG" /D "_WINDOWS" /D "_MBCS" /Yu"stdafx.h" /c
CPP_PROJ=/nologo /G5 /MT /W3 /GX /O2 /D "WIN32" /D "NDEBUG" /D "_WINDOWS" /D\
 "_MBCS" /Fp"$(INTDIR)/Capture.pch" /Yu"stdafx.h" /Fo"$(INTDIR)/" /c 
CPP_OBJS=.\Release/
CPP_SBRS=.\.
# ADD BASE MTL /nologo /D "NDEBUG" /win32
# ADD MTL /nologo /D "NDEBUG" /win32
MTL_PROJ=/nologo /D "NDEBUG" /win32 
# ADD BASE RSC /l 0x411 /d "NDEBUG"
# ADD RSC /l 0x411 /d "NDEBUG"
RSC_PROJ=/l 0x411 /fo"$(INTDIR)/Capture.res" /d "NDEBUG" 
BSC32=bscmake.exe
# ADD BASE BSC32 /nologo
# ADD BSC32 /nologo
BSC32_FLAGS=/nologo /o"$(OUTDIR)/Capture.bsc" 
BSC32_SBRS= \
	
LINK32=link.exe
# ADD BASE LINK32 /nologo /subsystem:windows /machine:I386
# ADD LINK32 vfw32.lib jpeg32.lib /nologo /subsystem:windows /machine:I386
LINK32_FLAGS=vfw32.lib jpeg32.lib /nologo /subsystem:windows /incremental:no\
 /pdb:"$(OUTDIR)/Capture.pdb" /machine:I386 /out:"$(OUTDIR)/Capture.exe" 
LINK32_OBJS= \
	"$(INTDIR)\CaptDoc.obj" \
	"$(INTDIR)\Capture.obj" \
	"$(INTDIR)\Capture.res" \
	"$(INTDIR)\CaptView.obj" \
	"$(INTDIR)\Inifile.obj" \
	"$(INTDIR)\jpeg.obj" \
	"$(INTDIR)\MainFrm.obj" \
	"$(INTDIR)\SetFile.obj" \
	"$(INTDIR)\StdAfx.obj"

"$(OUTDIR)\Capture.exe" : "$(OUTDIR)" $(DEF_FILE) $(LINK32_OBJS)
    $(LINK32) @<<
  $(LINK32_FLAGS) $(LINK32_OBJS)
<<

!ELSEIF  "$(CFG)" == "Capture - Win32 Debug"

# PROP BASE Use_MFC 5
# PROP BASE Use_Debug_Libraries 1
# PROP BASE Output_Dir "Debug"
# PROP BASE Intermediate_Dir "Debug"
# PROP BASE Target_Dir ""
# PROP Use_MFC 5
# PROP Use_Debug_Libraries 1
# PROP Output_Dir "Debug"
# PROP Intermediate_Dir "Debug"
# PROP Target_Dir ""
OUTDIR=.\Debug
INTDIR=.\Debug

ALL : "$(OUTDIR)\Capture.exe"

CLEAN : 
	-@erase "$(INTDIR)\CaptDoc.obj"
	-@erase "$(INTDIR)\Capture.obj"
	-@erase "$(INTDIR)\Capture.pch"
	-@erase "$(INTDIR)\Capture.res"
	-@erase "$(INTDIR)\CaptView.obj"
	-@erase "$(INTDIR)\Inifile.obj"
	-@erase "$(INTDIR)\jpeg.obj"
	-@erase "$(INTDIR)\MainFrm.obj"
	-@erase "$(INTDIR)\SetFile.obj"
	-@erase "$(INTDIR)\StdAfx.obj"
	-@erase "$(INTDIR)\vc40.idb"
	-@erase "$(INTDIR)\vc40.pdb"
	-@erase "$(OUTDIR)\Capture.exe"
	-@erase "$(OUTDIR)\Capture.ilk"
	-@erase "$(OUTDIR)\Capture.pdb"

"$(OUTDIR)" :
    if not exist "$(OUTDIR)/$(NULL)" mkdir "$(OUTDIR)"

# ADD BASE CPP /nologo /MTd /W3 /Gm /GX /Zi /Od /D "WIN32" /D "_DEBUG" /D "_WINDOWS" /D "_MBCS" /Yu"stdafx.h" /c
# ADD CPP /nologo /MTd /W3 /Gm /GX /Zi /Od /D "WIN32" /D "_DEBUG" /D "_WINDOWS" /D "_MBCS" /Yu"stdafx.h" /c
CPP_PROJ=/nologo /MTd /W3 /Gm /GX /Zi /Od /D "WIN32" /D "_DEBUG" /D "_WINDOWS"\
 /D "_MBCS" /Fp"$(INTDIR)/Capture.pch" /Yu"stdafx.h" /Fo"$(INTDIR)/"\
 /Fd"$(INTDIR)/" /c 
CPP_OBJS=.\Debug/
CPP_SBRS=.\.
# ADD BASE MTL /nologo /D "_DEBUG" /win32
# ADD MTL /nologo /D "_DEBUG" /win32
MTL_PROJ=/nologo /D "_DEBUG" /win32 
# ADD BASE RSC /l 0x411 /d "_DEBUG"
# ADD RSC /l 0x411 /d "_DEBUG"
RSC_PROJ=/l 0x411 /fo"$(INTDIR)/Capture.res" /d "_DEBUG" 
BSC32=bscmake.exe
# ADD BASE BSC32 /nologo
# ADD BSC32 /nologo
BSC32_FLAGS=/nologo /o"$(OUTDIR)/Capture.bsc" 
BSC32_SBRS= \
	
LINK32=link.exe
# ADD BASE LINK32 /nologo /subsystem:windows /debug /machine:I386
# ADD LINK32 vfw32.lib jpeg32d.lib winmm.lib /nologo /subsystem:windows /debug /machine:I386
LINK32_FLAGS=vfw32.lib jpeg32d.lib winmm.lib /nologo /subsystem:windows\
 /incremental:yes /pdb:"$(OUTDIR)/Capture.pdb" /debug /machine:I386\
 /out:"$(OUTDIR)/Capture.exe" 
LINK32_OBJS= \
	"$(INTDIR)\CaptDoc.obj" \
	"$(INTDIR)\Capture.obj" \
	"$(INTDIR)\Capture.res" \
	"$(INTDIR)\CaptView.obj" \
	"$(INTDIR)\Inifile.obj" \
	"$(INTDIR)\jpeg.obj" \
	"$(INTDIR)\MainFrm.obj" \
	"$(INTDIR)\SetFile.obj" \
	"$(INTDIR)\StdAfx.obj"

"$(OUTDIR)\Capture.exe" : "$(OUTDIR)" $(DEF_FILE) $(LINK32_OBJS)
    $(LINK32) @<<
  $(LINK32_FLAGS) $(LINK32_OBJS)
<<

!ENDIF 

.c{$(CPP_OBJS)}.obj:
   $(CPP) $(CPP_PROJ) $<  

.cpp{$(CPP_OBJS)}.obj:
   $(CPP) $(CPP_PROJ) $<  

.cxx{$(CPP_OBJS)}.obj:
   $(CPP) $(CPP_PROJ) $<  

.c{$(CPP_SBRS)}.sbr:
   $(CPP) $(CPP_PROJ) $<  

.cpp{$(CPP_SBRS)}.sbr:
   $(CPP) $(CPP_PROJ) $<  

.cxx{$(CPP_SBRS)}.sbr:
   $(CPP) $(CPP_PROJ) $<  

################################################################################
# Begin Target

# Name "Capture - Win32 Release"
# Name "Capture - Win32 Debug"

!IF  "$(CFG)" == "Capture - Win32 Release"

!ELSEIF  "$(CFG)" == "Capture - Win32 Debug"

!ENDIF 

################################################################################
# Begin Source File

SOURCE=.\ReadMe.txt

!IF  "$(CFG)" == "Capture - Win32 Release"

!ELSEIF  "$(CFG)" == "Capture - Win32 Debug"

!ENDIF 

# End Source File
################################################################################
# Begin Source File

SOURCE=.\Capture.cpp
DEP_CPP_CAPTU=\
	".\CaptDoc.h"\
	".\Capture.h"\
	".\CaptView.h"\
	".\inifile.h"\
	".\MainFrm.h"\
	".\StdAfx.h"\
	

"$(INTDIR)\Capture.obj" : $(SOURCE) $(DEP_CPP_CAPTU) "$(INTDIR)"\
 "$(INTDIR)\Capture.pch"


# End Source File
################################################################################
# Begin Source File

SOURCE=.\StdAfx.cpp
DEP_CPP_STDAF=\
	".\StdAfx.h"\
	

!IF  "$(CFG)" == "Capture - Win32 Release"

# ADD CPP /Yc"stdafx.h"

BuildCmds= \
	$(CPP) /nologo /G5 /MT /W3 /GX /O2 /D "WIN32" /D "NDEBUG" /D "_WINDOWS" /D\
 "_MBCS" /Fp"$(INTDIR)/Capture.pch" /Yc"stdafx.h" /Fo"$(INTDIR)/" /c $(SOURCE) \
	

"$(INTDIR)\StdAfx.obj" : $(SOURCE) $(DEP_CPP_STDAF) "$(INTDIR)"
   $(BuildCmds)

"$(INTDIR)\Capture.pch" : $(SOURCE) $(DEP_CPP_STDAF) "$(INTDIR)"
   $(BuildCmds)

!ELSEIF  "$(CFG)" == "Capture - Win32 Debug"

# ADD CPP /Yc"stdafx.h"

BuildCmds= \
	$(CPP) /nologo /MTd /W3 /Gm /GX /Zi /Od /D "WIN32" /D "_DEBUG" /D "_WINDOWS"\
 /D "_MBCS" /Fp"$(INTDIR)/Capture.pch" /Yc"stdafx.h" /Fo"$(INTDIR)/"\
 /Fd"$(INTDIR)/" /c $(SOURCE) \
	

"$(INTDIR)\StdAfx.obj" : $(SOURCE) $(DEP_CPP_STDAF) "$(INTDIR)"
   $(BuildCmds)

"$(INTDIR)\Capture.pch" : $(SOURCE) $(DEP_CPP_STDAF) "$(INTDIR)"
   $(BuildCmds)

!ENDIF 

# End Source File
################################################################################
# Begin Source File

SOURCE=.\MainFrm.cpp
DEP_CPP_MAINF=\
	".\Capture.h"\
	".\MainFrm.h"\
	".\StdAfx.h"\
	

"$(INTDIR)\MainFrm.obj" : $(SOURCE) $(DEP_CPP_MAINF) "$(INTDIR)"\
 "$(INTDIR)\Capture.pch"


# End Source File
################################################################################
# Begin Source File

SOURCE=.\CaptDoc.cpp
DEP_CPP_CAPTD=\
	".\CaptDoc.h"\
	".\Capture.h"\
	".\inifile.h"\
	".\StdAfx.h"\
	

"$(INTDIR)\CaptDoc.obj" : $(SOURCE) $(DEP_CPP_CAPTD) "$(INTDIR)"\
 "$(INTDIR)\Capture.pch"


# End Source File
################################################################################
# Begin Source File

SOURCE=.\CaptView.cpp
DEP_CPP_CAPTV=\
	".\CaptDoc.h"\
	".\Capture.h"\
	".\CaptView.h"\
	".\inifile.h"\
	".\SetFile.h"\
	".\StdAfx.h"\
	

"$(INTDIR)\CaptView.obj" : $(SOURCE) $(DEP_CPP_CAPTV) "$(INTDIR)"\
 "$(INTDIR)\Capture.pch"


# End Source File
################################################################################
# Begin Source File

SOURCE=.\Capture.rc
DEP_RSC_CAPTUR=\
	".\res\CaptDoc.ico"\
	".\res\Capture.ico"\
	".\res\Capture.rc2"\
	".\res\Toolbar.bmp"\
	

"$(INTDIR)\Capture.res" : $(SOURCE) $(DEP_RSC_CAPTUR) "$(INTDIR)"
   $(RSC) $(RSC_PROJ) $(SOURCE)


# End Source File
################################################################################
# Begin Source File

SOURCE=.\SetFile.cpp
DEP_CPP_SETFI=\
	".\Capture.h"\
	".\inifile.h"\
	".\SetFile.h"\
	".\StdAfx.h"\
	

"$(INTDIR)\SetFile.obj" : $(SOURCE) $(DEP_CPP_SETFI) "$(INTDIR)"\
 "$(INTDIR)\Capture.pch"


# End Source File
################################################################################
# Begin Source File

SOURCE=.\Inifile.cpp
DEP_CPP_INIFI=\
	".\inifile.h"\
	".\StdAfx.h"\
	

"$(INTDIR)\Inifile.obj" : $(SOURCE) $(DEP_CPP_INIFI) "$(INTDIR)"\
 "$(INTDIR)\Capture.pch"


# End Source File
################################################################################
# Begin Source File

SOURCE=.\jpeg.cpp

!IF  "$(CFG)" == "Capture - Win32 Release"

DEP_CPP_JPEG_=\
	".\inifile.h"\
	".\Jconfig.h"\
	".\jmorecfg.h"\
	".\jpeglib.h"\
	".\StdAfx.h"\
	
NODEP_CPP_JPEG_=\
	".\jerror.h"\
	".\jpegint.h"\
	

"$(INTDIR)\jpeg.obj" : $(SOURCE) $(DEP_CPP_JPEG_) "$(INTDIR)"\
 "$(INTDIR)\Capture.pch"


!ELSEIF  "$(CFG)" == "Capture - Win32 Debug"

DEP_CPP_JPEG_=\
	".\inifile.h"\
	".\Jconfig.h"\
	".\jmorecfg.h"\
	".\jpeglib.h"\
	".\StdAfx.h"\
	
NODEP_CPP_JPEG_=\
	".\jerror.h"\
	".\jpegint.h"\
	

"$(INTDIR)\jpeg.obj" : $(SOURCE) $(DEP_CPP_JPEG_) "$(INTDIR)"\
 "$(INTDIR)\Capture.pch"


!ENDIF 

# End Source File
# End Target
# End Project
################################################################################
