msgcnv.exe : msgcnv.c pc_cns.h
	cl /Zp /AC msgcnv.c /link /EXEPACK
