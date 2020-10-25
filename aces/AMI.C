#include <stdio.h>
#include <stdlib.h>
#include <string.h>

static	int	input_type();
static	int	input_value();
static	short	calc_dp();
static	short	radius();
static	int	ask_continue();

main()
{
	char	buf[80];
	double	image_field;
	short	dc;
	int	type;
	short	value;
	short	dp;

	printf("image field=");
	gets(buf);
	image_field = atof(buf);
	dc = (short)(image_field * 100 * 1024 / 0x2000);

	for (;;) {
		if (input_type(&type) == 0)
			break;

		if (input_value(image_field, type, &value) == 0)
			break;

		dp = calc_dp(dc, type, value);

		printf("encoding of dynamic parameter=%04x\n", dp);
		if (ask_continue() == 0)
			break;
		printf("\n");
	}
	printf("/***** tool program is over *****/\n");
}

static	int	input_type(type)
int	*type;
{
	char	buf[80];

        printf("l:LENGTH , ARC LENGTH\n");
        printf("p:POINT\n");
        printf("a:ANGLE\n");
        printf("d:DATA\n");
        printf("r:REDIUS\n");
        printf("f:ALPHANUMERIC CHARACTER\n");

	for (;;) {
		printf("dynamic parameter type=");
		gets(buf);
		switch (buf[0]) {
		case 'l':
		case 'p':
		case 'a':
		case 'd':
		case 'r':
		case 'f':
			*type = buf[0];
			return(1);
		default:
			printf("invalid dynamic parameter type !!\n");
			if (ask_continue() == 0)
				return(0);
			break;
		}
	}
}

static	int	input_value(image_field, type, value)
double	image_field;
int	type;
short	*value;
{
	struct	{
		char	*str;
		short	address;
	} font[] = {
		{"4 ", 0x4604},
		{"40", 0x4FFC},
		{"4.", 0x5024},
		{"4+", 0x503C},
		{"4-", 0x504C},
		{"4/", 0x4664},
		{"41", 0x5058},
		{"4%", 0x4680},
		{"4_", 0x46A8},
		{"4(", 0x46B0},
		{"4)", 0x46C4},
		{"4*", 0x46D4},
		{"4=", 0x46FC},
		{"42", 0x5068},
		{"4>", 0x4730},
		{"4<", 0x4740},
		{"4&", 0x4750},
		{"4:", 0x4770},
		{"43", 0x508C},
		{"44", 0x5200},
		{"45", 0x526C},
		{"46", 0x5290},
		{"47", 0x52C4},
		{"48", 0x52D4},
		{"49", 0x5314},
		{"4a", 0x48B0},
		{"4b", 0x48C8},
		{"4c", 0x48F4},
		{"4d", 0x491C},
		{"4e", 0x4938},
		{"4f", 0x4950},
		{"4g", 0x4960},
		{"4h", 0x4990},
		{"4i", 0x49A0},
		{"4j", 0x49B4},
		{"4k", 0x49D0},
		{"4l", 0x49E0},
		{"4m", 0x49EC},
		{"4n", 0x49F8},
		{"4o", 0x4A04},
		{"4p", 0x4A2C},
		{"4q", 0x4A48},
		{"4r", 0x4A74},
		{"4s", 0x4A90},
		{"4t", 0x4AC4},
		{"4u", 0x4AD4},
		{"4v", 0x4AF0},
		{"4w", 0x4B00},
		{"4x", 0x4B10},
		{"4y", 0x4B28},
		{"4z", 0x4B40},
		{"630", 0x50C8},
		{"631", 0x50E0},
		{"632", 0x5100},
		{"633", 0x511C},
		{"634", 0x5140},
		{"640", 0x5210},
		{"641", 0x5238},
		{"642", 0x5248},
		{"643", 0x5258},

		{"7 ", 0x53A8},
		{"70", 0x53AC},
		{"7.", 0x53D4},
		{"7+", 0x53F0},
		{"7-", 0x5400},
		{"7/", 0x540C},
		{"71", 0x5418},
		{"7%", 0x542C},
		{"7_", 0x5454},
		{"7(", 0x545C},
		{"7)", 0x5470},
		{"7*", 0x5480},
		{"7=", 0x54A8},
		{"72", 0x54B8},
		{"7>", 0x54E0},
		{"7<", 0x54F0},
		{"7&", 0x5500},
		{"7:", 0x5520},
		{"73", 0x553C},
		{"74", 0x56AC},
		{"75", 0x5724},
		{"76", 0x5750},
		{"77", 0x5784},
		{"78", 0x5794},
		{"79", 0x57D4},
		{"7a", 0x580C},
		{"7b", 0x5824},
		{"7c", 0x5850},
		{"7d", 0x5878},
		{"7e", 0x5894},
		{"7f", 0x58AC},
		{"7g", 0x58BC},
		{"7h", 0x58EC},
		{"7i", 0x58FC},
		{"7j", 0x5914},
		{"7k", 0x5930},
		{"7l", 0x5940},
		{"7m", 0x594C},
		{"7n", 0x5958},
		{"7o", 0x5968},
		{"7p", 0x5990},
		{"7q", 0x59AC},
		{"7r", 0x59DC},
		{"7s", 0x5A00},
		{"7t", 0x5A34},
		{"7u", 0x5A44},
		{"7v", 0x5A60},
		{"7w", 0x5A74},
		{"7x", 0x5A88},
		{"7y", 0x5AA0},
		{"7z", 0x5AB8},
		{"730", 0x5574},
		{"731", 0x558C},
		{"732", 0x55B0},
		{"733", 0x55C8},
		{"734", 0x55E8},
		{"740", 0x56BC},
		{"741", 0x56E4},
		{"742", 0x56F4},
		{"743", 0x570C},

		{"8 ", 0x5AD0},
		{"80", 0x5AD4},
		{"8.", 0x5AFC},
		{"8+", 0x5B14},
		{"8-", 0x5B24},
		{"8/", 0x5B30},
		{"81", 0x5B4C},
		{"8%", 0x5B60},
		{"8_", 0x5B88},
		{"8(", 0x5B90},
		{"8)", 0x5BA4},
		{"8*", 0x5BB4},
		{"8=", 0x5BE0},
		{"82", 0x5BF0},
		{"8>", 0x5C14},
		{"8<", 0x5C28},
		{"8&", 0x5C38},
		{"8:", 0x5C58},
		{"83", 0x5C74},
		{"84", 0x5DF0},
		{"85", 0x5E60},
		{"86", 0x5E84},
		{"87", 0x5EB8},
		{"88", 0x5EC8},
		{"89", 0x5F08},
		{"8a", 0x5F44},
		{"8b", 0x5F5C},
		{"8c", 0x5F88},
		{"8d", 0x5FB0},
		{"8e", 0x5FCC},
		{"8f", 0x5FE4},
		{"8g", 0x5FF8},
		{"8h", 0x6028},
		{"8i", 0x603C},
		{"8j", 0x6050},
		{"8k", 0x606C},
		{"8l", 0x6084},
		{"8m", 0x6094},
		{"8n", 0x60A4},
		{"8o", 0x60B8},
		{"8p", 0x60E0},
		{"8q", 0x60FC},
		{"8r", 0x6128},
		{"8s", 0x6148},
		{"8t", 0x617C},
		{"8u", 0x618C},
		{"8v", 0x61A8},
		{"8w", 0x61BC},
		{"8x", 0x61D4},
		{"8y", 0x61F0},
		{"8z", 0x6208},
		{"830", 0x5CAC},
		{"831", 0x5CC4},
		{"832", 0x5CE8},
		{"833", 0x5D08},
		{"834", 0x5D2C},
		{"840", 0x5E00},
		{"841", 0x5E28},
		{"842", 0x5E38},
		{"843", 0x5E48},
		{NULL, 0},
	};
	char	buf[80];
	double	fval;
	int	error, i;

	for (;;) {
		error = 0;
		printf("dynamic parameter value=");
		gets(buf);
		switch (type) {
		case 'l':
			fval = atof(buf);
			if (fval < 0 || fval > 4 * image_field)
				error = 1;
			else
				*value = (short)(fval * 100);
			break;
		case 'p':
			fval = atof(buf);
			if (fval < -2 * image_field || fval > 2 * image_field)
				error = 1;
			else
				*value = (short)(fval * 100);
			break;
		case 'a':
			fval = atof(buf);
			if (fval < 0 || fval > 360)
				error = 1;
			else
				*value = (short)(fval * 10);
			break;
		case 'd':
			fval = atof(buf);
			if (fval < -32768 || fval > 32767)
				error = 1;
			else
				*value = (short)fval;
			break;
		case 'r':
			fval = atof(buf);
			*value = (short)(fval * 100);
			break;
		case 'f':
			for (i = 0; font[i].str != NULL; i++) {
				if (strcmp(buf, font[i].str) == 0)
					break;
			}
			if (font[i].str == NULL)
				error = 1;
			else
				*value = font[i].address;
			break;
		}
		if (error != 0) {
			printf("invalid data !!\n");
			if (ask_continue() == 0)
				return(0);
		} else
			break;
	}
	return(1);
}

static	short	calc_dp(dc, type, value)
short	dc;
int	type;
short	value;
{
	short	dp;

	switch (type) {
	case 'l':
	case 'p':
		dp = (short)(value * 1024L / dc + 1) & 0xfffe;
		break;
	case 'a':
		dp = (short)(value * 0x8000L / 1800) & 0xfff0;
		break;
	case 'd':
		dp = value;
		break;
	case 'r':
		dp = radius(value);
		break;
	case 'f':
		dp = value;
		break;
	}
	return(dp);
}

static	short	radius(short r)
{
	register short	ibuffer, g;
	register long	buffer;

	buffer = r * 6845L / 1024;
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

static	int	ask_continue()
{
	char	buf[80];

	for (;;) {
		printf("continue? (y/n)");
		gets(buf);
		if (strcmp(buf, "y") == 0)
			return(1);
		else if (strcmp(buf, "n") == 0)
			return(0);
	}
}
