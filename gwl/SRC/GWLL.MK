gwll.dmy : gwll.lib

wlib_l.obj : wlib.c gwlib.h
	cl /c /AL /Zp /J /Oil /Fowlib_l wlib.c

clib_l.obj : clib.c gwlib.h
	cl /c /AL /Zp /J /Oil /Foclib_l clib.c

tlib_l.obj : tlib.c gwlib.h
	cl /c /AL /Zp /J /Fotlib_l tlib.c

glib_l.obj : glib.c gwlib.h
	cl /c /AL /Zp /J /Foglib_l glib.c

mlib_l.obj : mlib.c gwlib.h
	cl /c /AL /Zp /J /Fomlib_l mlib.c

dlib_l.obj : dlib.c gwlib.h
	cl /c /AL /Zp /J /Fodlib_l dlib.c

gwll.lib : wlib_l.obj clib_l.obj tlib_l.obj glib_l.obj mlib_l.obj dlib_l.obj
	command /c del gwll.lib
	lib gwll+wlib_l+clib_l+tlib_l+glib_l+mlib_l+dlib_l;
