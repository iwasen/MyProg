main.obj : main.c
	cl /c /AL /Zp /J main.c

prog1.obj : prog1.c
	cl /c /AL /Zp /J prog1.c

prog2.obj : prog2.c
	cl /c /AL /Zp /J prog2.c

prog3.obj : prog3.c
	cl /c /AL /Zp /J prog3.c

prog4.obj : prog4.c
	cl /c /AL /Zp /J prog4.c

prog5.obj : prog5.c
	cl /c /AL /Zp /J prog5.c

prog6.obj : prog6.c
	cl /c /AL /Zp /J prog6.c

prog7.obj : prog7.c
	cl /c /AL /Zp /J prog7.c

prog8.obj : prog8.c
	cl /c /AL /Zp /J prog8.c

prog9.obj : prog9.c
	cl /c /AL /Zp /J prog9.c

local.obj : local.c
	cl /c /AL /Zp /J local.c

cmf.obj : cmf.c
	cl /c /AL /Zp /J cmf.c

cms.obj : cms.c
	cl /c /AL /Zp /J cms.c

msg.obj : msg.c
	cl /c /AL /Zp /J msg.c

sub.obj : sub.c
	cl /c /AL /Zp /J sub.c

sg.obj : sg.c
	cl /c /AL /Zp /J sg.c

com.obj : com.c
	cl /c /AL /Zp /J com.c

console.obj : console.c
	cl /c /AL /Zp /J console.c

bios.obj : bios.c
	cl /c /AL /Zp /J bios.c

guide.obj : guide.c
	cl /c /AL /Zp /J guide.c

data.obj : data.c
	cl /c /AL /Zp /J data.c

pc_cns.exe : main.obj prog1.obj prog2.obj prog3.obj prog4.obj prog5.obj \
	prog6.obj prog7.obj prog8.obj prog9.obj \
	local.obj cmf.obj cms.obj msg.obj sub.obj sg.obj com.obj \
	console.obj bios.obj guide.obj data.obj
	link /exepack /st:8192 @pc_cns.lnk
