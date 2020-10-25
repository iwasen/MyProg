isaml.dmy : isaml.lib

db_l.obj : db.c isam.h
	cl /c /AL /Zp /J /O /Fodb_l db.c

dbf_l.obj : dbf.c isam.h
	cl /c /AL /Zp /J /O /Fodbf_l dbf.c

idx_l.obj : idx.c isam.h
	cl /c /AL /Zp /J /O /Foidx_l idx.c

sort_l.obj : sort.c isam.h
	cl /c /AL /Zp /J /O /Fosort_l sort.c

func_l.obj : func.c isam.h
	cl /c /AL /Zp /J /O /Fofunc_l func.c

csub_l.obj : csub.c isam.h
	cl /c /AL /Zp /J /O /Focsub_l csub.c

data_l.obj : data.c isam.h
	cl /c /AL /Zp /J /O /Fodata_l data.c

isaml.lib : db_l.obj dbf_l.obj idx_l.obj sort_l.obj \
	   func_l.obj csub_l.obj data_l.obj
	command /c del isaml.lib
	lib isaml+db_l+dbf_l+idx_l+sort_l+func_l+csub_l+data_l;
