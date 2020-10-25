isamc.dmy : isamc.lib

db_c.obj : db.c isam.h
	cl /c /AC /Zp /J /O /Fodb_c db.c

dbf_c.obj : dbf.c isam.h
	cl /c /AC /Zp /J /O /Fodbf_c dbf.c

idx_c.obj : idx.c isam.h
	cl /c /AC /Zp /J /O /Foidx_c idx.c

sort_c.obj : sort.c isam.h
	cl /c /AC /Zp /J /O /Fosort_c sort.c

func_c.obj : func.c isam.h
	cl /c /AC /Zp /J /O /Fofunc_c func.c

csub_c.obj : csub.c isam.h
	cl /c /AC /Zp /J /O /Focsub_c csub.c

data_c.obj : data.c isam.h
	cl /c /AC /Zp /J /O /Fodata_c data.c

isamc.lib : db_c.obj dbf_c.obj idx_c.obj sort_c.obj \
	   func_c.obj csub_c.obj data_c.obj
	command /c del isamc.lib
	lib isamc+db_c+dbf_c+idx_c+sort_c+func_c+csub_c+data_c;
