#include <stdio.h>

static	unsigned char	data[0xf000];
static	unsigned char	map[0xf000];

static	void	set_sum(char *);

main(int argc, char *argv[])
{
	FILE	*fp;
	char	buf[100], *bufp;
	int	len, tmp;
	unsigned long	address, last_address;
	unsigned int	check_sum;

	if (argc != 2) {
		fprintf(stderr, "usage: checksum S3file\n");
		exit(1);
	}

	if ((fp = fopen(argv[1], "r")) == NULL) {
		fprintf(stderr, "file open error [%s]\n", argv[1]);
		exit(1);
	}

	while (fgets(buf, sizeof(buf), fp) != NULL) {
		if (strncmp(buf, "S3", 2) != 0)
			continue;
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
	}
	fclose(fp);

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
