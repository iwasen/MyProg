gwlc.dmy : gwlc.lib

wlib_c.obj : wlib.c gwlib.h
	cl /c /AC /Zp /J /Oil /Fowlib_c wlib.c

clib_c.obj : clib.c gwlib.h
	cl /c /AC /Zp /J /Oil /Foclib_c clib.c

tlib_c.obj : tlib.c gwlib.h
	cl /c /AC /Zp /J /Fotlib_c tlib.c

glib_c.obj : glib.c gwlib.h
	cl /c /AC /Zp /J /Foglib_c glib.c

mlib_c.obj : mlib.c gwlib.h
	cl /c /AC /Zp /J /Fomlib_c mlib.c

dlib_c.obj : dlib.c gwlib.h
	cl /c /AC /Zp /J /Fodlib_c dlib.c

gwlc.lib : wlib_c.obj clib_c.obj tlib_c.obj glib_c.obj mlib_c.obj dlib_c.obj
	command /c del gwlc.lib
	lib gwlc+wlib_c+clib_c+tlib_c+glib_c+mlib_c+dlib_c;
