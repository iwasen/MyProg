csample.dmy : csample.exe

csample.obj : csample.c
	cl /c /AL /Zp /J /O csample.c

csample.exe : csample.obj isaml.lib
	link /st:8192 csample,,,isaml;
