/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"
#include "bcbios.h"

extern	int	sscanf(char *, char *, ...);

#pragma	module	read@

void b_read(p)
char	*p;
{
	union	{
		char	**pp;
		int	**ip;
		long	**lp;
		float	**fp;
		double	**dp;
		STRTBL	**sp;
	} ap;
	STRTBL	*s;
	char	i, len;
	char	getdata(void);
	int	getnum(void);

	ap.pp = &p+1;
	while (*p) {
		len = getdata();
		switch (*p++) {
		case 'c':
		case 'i':
			**ap.ip++ = getnum();
			break;
		case 'l':
			if (b_work[0] == '&')
				**ap.lp++ = getnum();
			else
				sscanf(b_work,"%ld",*ap.lp++);
			break;
		case 'f':
			if (b_work[0] == '&')
				**ap.fp++ = getnum();
			else
				sscanf(b_work,"%g",*ap.fp++);
			break;
		case 'd':
			if (b_work[0] == '&')
				**ap.dp++ = getnum();
			else
				sscanf(b_work,"%lg",*ap.dp++);
			break;
		case 's':
			s = *ap.sp++;
			s->s_len = len;
			for (i = 0; i < len; i++)
				s->s_str[i] = b_work[i];
		}
	}
}

static char getdata()
{
	char	c, *dp, len, flag;

	len = flag = 0;
	dp = b_work;

	if (*data_ptr == 0xff)
		b_error(E_OD);

	if (*data_ptr == '"') {
		flag = 1;
		data_ptr++;
	}

	while (1) {
		switch (c = *data_ptr++) {
		case 0:
			goto getdata10;
		case '"':
			flag = 0;
			break;
		case ',':
			if (flag == 0)
				goto getdata10;
		default:
			*dp++ = c;
			len++;
		}
	}
getdata10:
	*dp = 0;
	return(len);
}

static int getnum()
{
	char	*dp, *s;
	static	int	data;

	dp = b_work;
	if (*dp == '&') {
		switch (*(++dp)) {
		case 'H':
		case 'h':
			dp++;
			s = "%x";
			break;
		case 'O':
		case 'o':
			dp++;
		default:
			s = "%o";
		}
	} else
		s = "%d";

	sscanf(dp,s,&data);
	return(data);
}
