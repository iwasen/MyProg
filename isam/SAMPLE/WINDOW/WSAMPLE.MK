wsample.dmy : wsample.exe

wsample.obj : wsample.c
	cl /c /AL /Zp /J /O wsample.c

wsample.exe : wsample.obj isaml.lib gwll.lib
	link /st:8192 wsample,,,isaml gwll;
