#include <stdio.h>
#include <dos.h>

void	t_getfont(int code, unsigned char *font)
{
	union REGS	reg;
	void	far *fptr;

	reg.h.ah = 0x14;
	reg.x.dx = code;
	fptr = font;
	reg.x.cx = FP_OFF(fptr);
	reg.x.bx = FP_SEG(fptr);

	int86(0x18, &reg, &reg);
}

main(int argc, char *argv[])
{
	unsigned char	font[2+2*16];
	int	i;
	int	code;

	if (argc != 2)
		exit(1);

	sscanf(argv[1], "%x", &code);
	t_getfont(code, font);
	for (i = 0; i < 34; i++)
		printf("0x%02x, ", font[i]);
}
