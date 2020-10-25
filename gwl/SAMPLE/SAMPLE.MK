sample.dmy : sample.exe

sample.obj : sample.c
	cl /c /AC /Zp /J sample.c

sample.exe : sample.obj gwlc.lib
	link /st:8192 sample,,,gwlc;
