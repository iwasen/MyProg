cmdcnv.exe : cmdcnv.c pc_cns.h
	cl /Zp /AC cmdcnv.c /link /EXEPACK
