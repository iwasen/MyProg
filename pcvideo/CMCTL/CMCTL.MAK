# Microsoft Visual C++ generated build script - Do not modify

PROJ = CMCTL
DEBUG = 0
PROGTYPE = 1
CALLER = test.exe
ARGS = 
DLLS = 
D_RCDEFINES = -d_DEBUG
R_RCDEFINES = -dNDEBUG
ORIGIN = MSVC
ORIGIN_VER = 1.00
PROJPATH = W:\AIZAWA\PCVIDEO\DLL\
USEMFC = 0
CC = cl
CPP = cl
CXX = cl
CCREATEPCHFLAG = 
CPPCREATEPCHFLAG = 
CUSEPCHFLAG = 
CPPUSEPCHFLAG = 
FIRSTC =             
FIRSTCPP = CMCTL.CPP   
RC = rc
CFLAGS_D_WDLL = /nologo /G2 /Zp1 /W3 /WX /Zi /ALw /Od /D "_DEBUG" /FR /GD /Fd"CMCTL.PDB" /J
CFLAGS_R_WDLL = /nologo /G3 /Zp1 /W3 /WX /ALw /O2 /D "NDEBUG" /FR /GD /J
LFLAGS_D_WDLL = /NOLOGO /NOD /NOE /PACKC:61440 /ALIGN:16 /ONERROR:NOEXE /CO /MAP:FULL
LFLAGS_R_WDLL = /NOLOGO /NOD /NOE /PACKC:61440 /ALIGN:16 /ONERROR:NOEXE /MAP:FULL
LIBS_D_WDLL = oldnames libw ldllcew 
LIBS_R_WDLL = oldnames libw ldllcew 
RCFLAGS = /nologo
RESFLAGS = /nologo
RUNFLAGS = 
DEFFILE = CMCTL.DEF
OBJS_EXT = 
LIBS_EXT = ..\VCC1.LIB A:\VFWDK\LIB\VFW.LIB 
!if "$(DEBUG)" == "1"
CFLAGS = $(CFLAGS_D_WDLL)
LFLAGS = $(LFLAGS_D_WDLL)
LIBS = $(LIBS_D_WDLL)
MAPFILE = nul
RCDEFINES = $(D_RCDEFINES)
!else
CFLAGS = $(CFLAGS_R_WDLL)
LFLAGS = $(LFLAGS_R_WDLL)
LIBS = $(LIBS_R_WDLL)
MAPFILE = nul
RCDEFINES = $(R_RCDEFINES)
!endif
!if [if exist MSVC.BND del MSVC.BND]
!endif
SBRS = CMCTL.SBR \
		LOCAL.SBR \
		WINMAIN.SBR \
		DATA.SBR \
		REMOTE.SBR


VCC1_DEP = 

VFW_DEP = 

CMCTL_DEP = w:\aizawa\pcvideo\dll\cmctldll.h \
	w:\aizawa\pcvideo\dll\cmctl.h


LOCAL_DEP = a:\vfwdk\inc\vfw.h \
	a:\vfwdk\inc\compman.h \
	a:\vfwdk\inc\compddk.h \
	a:\vfwdk\inc\drawdib.h \
	a:\vfwdk\inc\avifmt.h \
	a:\vfwdk\inc\mmreg.h \
	a:\vfwdk\inc\avifile.h \
	a:\vfwdk\inc\aviiface.h \
	a:\vfwdk\inc\mciwnd.h \
	a:\vfwdk\inc\msvideo.h \
	a:\vfwdk\inc\avicap.h \
	a:\vfwdk\inc\msacm.h \
	w:\aizawa\pcvideo\dll\cmctldll.h \
	w:\aizawa\pcvideo\dll\cmctl.h \
	w:\aizawa\pcvideo\vcc1.h


WINMAIN_DEP = w:\aizawa\pcvideo\dll\cmctldll.h \
	w:\aizawa\pcvideo\dll\cmctl.h


DATA_DEP = w:\aizawa\pcvideo\dll\cmctldll.h \
	w:\aizawa\pcvideo\dll\cmctl.h


REMOTE_DEP = a:\winsock\winsock.h \
	w:\aizawa\pcvideo\dll\cmctldll.h \
	w:\aizawa\pcvideo\dll\cmctl.h \
	w:\aizawa\pcvideo\common.h \
	w:\aizawa\pcvideo\cmcmd.h


all:	$(PROJ).DLL $(PROJ).BSC

CMCTL.OBJ:	CMCTL.CPP $(CMCTL_DEP)
	$(CPP) $(CFLAGS) $(CPPCREATEPCHFLAG) /c CMCTL.CPP

LOCAL.OBJ:	LOCAL.CPP $(LOCAL_DEP)
	$(CPP) $(CFLAGS) $(CPPUSEPCHFLAG) /c LOCAL.CPP

WINMAIN.OBJ:	WINMAIN.CPP $(WINMAIN_DEP)
	$(CPP) $(CFLAGS) $(CPPUSEPCHFLAG) /c WINMAIN.CPP

DATA.OBJ:	DATA.CPP $(DATA_DEP)
	$(CPP) $(CFLAGS) $(CPPUSEPCHFLAG) /c DATA.CPP

REMOTE.OBJ:	REMOTE.CPP $(REMOTE_DEP)
	$(CPP) $(CFLAGS) $(CPPUSEPCHFLAG) /c REMOTE.CPP


$(PROJ).DLL::	CMCTL.OBJ LOCAL.OBJ WINMAIN.OBJ DATA.OBJ REMOTE.OBJ $(OBJS_EXT) $(DEFFILE)
	echo >NUL @<<$(PROJ).CRF
CMCTL.OBJ +
LOCAL.OBJ +
WINMAIN.OBJ +
DATA.OBJ +
REMOTE.OBJ +
$(OBJS_EXT)
$(PROJ).DLL
$(MAPFILE)
c:\msvc\lib\+
c:\msvc\mfc\lib\+
..\VCC1.LIB+
A:\VFWDK\LIB\VFW.LIB+
$(LIBS)
$(DEFFILE);
<<
	link $(LFLAGS) @$(PROJ).CRF
	$(RC) $(RESFLAGS) $@
	implib /nowep $(PROJ).LIB $(PROJ).DLL


run: $(PROJ).DLL
	$(PROJ) $(RUNFLAGS)


$(PROJ).BSC: $(SBRS)
	bscmake @<<
/o$@ $(SBRS)
<<
