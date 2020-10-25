FLAGS=/c /AC /J /Zp /O
CC=cl
HDR=rs232c.h yterm.h

YTERM : yterm.exe

main.obj : main.c $(HDR)
	$(CC) $(FLAGS) main.c

term.obj : term.c $(HDR)
	$(CC) $(FLAGS) term.c

file.obj : file.c $(HDR)
	$(CC) $(FLAGS) file.c

xmodem.obj : xmodem.c $(HDR)
	$(CC) $(FLAGS) xmodem.c

console.obj : console.c $(HDR)
	$(CC) $(FLAGS) console.c

bios.obj : bios.c $(HDR)
	$(CC) $(FLAGS) bios.c

data.obj : data.c $(HDR)
	$(CC) $(FLAGS) data.c

yterm.exe : main.obj term.obj file.obj xmodem.obj console.obj bios.obj data.obj rs232c_c.lib
	link /st:4096 main+term+file+xmodem+console+bios+data,yterm,,rs232c_c+graphics;
