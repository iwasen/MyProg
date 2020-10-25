#define	IMAF	154
short	radius(float);
short	radius2(short);
main()
{
	short 	x, r1, r2;

	for (x = 1; x < 128; x++) {
		r1 = radius((float)x);
		r2 = radius2(x * 100);
		if (r1 != r2)
			printf("%d\t%x\t%x\n", x, r1, r2);
	}
}

short	radius(float R)
{
	float	PI=3.1415926;
	short	ibuffer;
	short	g;
	float	buffer, RRADIUS;

	buffer = (R * PI) / (2 * IMAF);
	if (buffer < 1) {
		g = 0;
		while (buffer < 1) {
			g++;
			buffer *= 2;
		}
	} else 
		if (buffer >= 2) {
			g = 0;
			while (buffer >= 2) {
				g--;
				buffer /= 2;
			}
		} else
			g = 0;
	g++;
	ibuffer = (short)((buffer - 1) * 256 + 0.5);
	if (ibuffer == 256)
		ibuffer = 255;
	RRADIUS = ((0x8000 | ((g & 0x0007) << 12) |
		((ibuffer & 0x00ff) << 4))) & 0xfff0;
	return(RRADIUS);
}

short	radius2(short R)
{
	short	ibuffer;
	short	g;
	long	buffer;

	buffer = R * 6845L / 1024;
	g = 1;
	if (buffer < 0x10000) {
		while (buffer < 0x10000) {
			g++;
			buffer *= 2;
		}
	} else  {
		if (buffer >= 0x20000) {
			while (buffer >= 0x20000) {
				g--;
				buffer /= 2;
			}
		}
	}

	ibuffer = (short)((buffer - 0x10000 + 0x80) >> 8);
	if (ibuffer == 256)
		ibuffer = 255;

	return((short)(0x8000 | (g << 12) | (ibuffer << 4)));
}
