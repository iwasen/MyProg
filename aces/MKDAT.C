#include <stdio.h>

short	buf[44][32];

main()
{
	FILE	*fp;
	int	i;
	short	*bufp;

	if ((fp = fopen("SS.DAT", "rb")) == NULL)
		return(1);

	fread(buf, 44*32, 2, fp);

	fclose(fp);

	bufp = (short *)&buf[0][2];
	for (i = 0; i < 38; i++) {
		*bufp++ = 0xe000;
		*bufp++ = (i * 0x200 - 0x2000) | 1;
		*bufp++ = 0x4142;
		*bufp++ = 0x4344;
	}

	bufp = (short *)&buf[29][2];
	for (i = 0; i < 38; i++) {
		*bufp++ = 1;
		*bufp++ = (i * 0x200 - 0x2000) | 2;
		*bufp++ = 0x4142;
		*bufp++ = 0x4344;
	}

	bufp = (short *)&buf[14][10];
	for (i = 0; i < 15; i++) {
		*bufp++ = 0x2002;
		*bufp++ = (i * 0x200 - 0x1000) | 3;
		*bufp++ = 0x4142;
		*bufp++ = 0x4344;
	}

	bufp = (short *)&buf[21][0];
	for (i = 0; i < 64; i++) {
		*bufp++ = 0x40 * i;
		*bufp++ = 0x40 * i | 4;
	}

	if ((fp = fopen("SS.DAT", "wb")) == NULL)
		return(1);

	fwrite(buf, 44*32, 2, fp);

	fclose(fp);
}
