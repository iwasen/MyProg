#include <stdio.h>
#include <math.h>
// #define	PI	3.1415926536
#define PI   3.14159265358979323846
main()
{
	int	i, c;

	printf("short\tsin_tbl[] = {\n");
	c = 0;
	for (i = 0; i < 1800; i++) {
		printf("\t");
		printf("0x%04x,", (short)(sin(i * PI / 1800) * 0x4000 + 0.5));
		if (c == 4)
			printf("\n");
		c = (c + 1) % 5;
	}
	printf("};\n\n");

	printf("short\tatan_tbl[] = {\n");
	c = 0;
	for (i = 0; i <= 1024; i++) {
		printf("\t");
		printf("0x%04x,", (short)(atan(i / 1024.0) / PI * 2048.0 + 0.5) << 4);
		if (c == 4)
			printf("\n");
		c = (c + 1) % 5;
	}
	printf("};\n");
}
