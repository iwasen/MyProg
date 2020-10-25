#include <stdio.h>

main()
{
	char	pano[20], name[20];
	int	adrs, bit;

	for (;;) {
		if (scanf(" define%s  = %xh%d%s", pano, &adrs, &bit, name) == 0)
			break;
		printf("#define\tPA_%s\t%d\n", name, (adrs - 0x4140) * 8 + bit);
	}
}
