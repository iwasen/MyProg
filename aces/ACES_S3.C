#include <stdio.h>

static	unsigned char	data[0xf000];
static	unsigned char	map[0xf000];

static	void	set_sum(char *);

main()
{
	char	buf[100], buf2[100], *bufp;
	int	len, tmp;
	unsigned long	address, last_address;
	unsigned int	check_sum;

	while (gets(buf) != NULL) {
		if (strncmp(buf, "S0", 2) == 0)
			strcpy(buf2, buf);
		else if (strncmp(buf, "S3", 2) == 0) {
			puts(buf2);
			strcpy(buf2, buf);

			sscanf(buf+2, "%2x%8lx", &len, &address);
			address -= 0xff080000;
			len -= 5;
			if (address < sizeof(map)) {
				for (bufp = buf + 12; len--; bufp += 2, address++) {
					map[address] = 1;
					sscanf(bufp, "%2x", &tmp);
					data[address] = tmp;
				}
			} else
				fprintf(stderr, "%s\n", buf);
		} else if (strncmp(buf, "S7", 2) == 0) {
			strcpy(buf2, buf);
			break;
		}
	}

	last_address = address - 2;
	for (address = 0; address < last_address; address++) {
		if (map[address] == 0) {
			sprintf(buf, "S306%08lX00", address + 0xff080000);
			set_sum(buf + 2);
			printf("%s\n", buf);
		}
	}
	for (address = 0, check_sum = 0; address < last_address; address += 2)
		check_sum += ((unsigned short)data[address] << 8) + data[address+1];
	sprintf(buf, "S307%08lX%04X", last_address + 0xff080000, check_sum);
	set_sum(buf + 2);
	printf("%s\n", buf);
	puts(buf2);
}

static	void	set_sum(char *p)
{
	unsigned char	sum;
	int	tmp;

	for (sum = 0xff; *p != '\0'; p += 2) {
		sscanf(p, "%2x", &tmp);
		sum -= tmp;
	}
	sprintf(p, "%02X", sum);
}
