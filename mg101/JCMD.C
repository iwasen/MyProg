/********************************************************
*							*
*		JCMD.C	( MEM_RW , TRACE , TREPT )	*
*							*
********************************************************/

#include	"adaputer.h"
#include	"adresp.h"

extern	void	sndrsp(), tsend();
extern	char	cmdbuf[], sdata[], rdata[];
extern	int	st;

/*	memory read/write routine	*/

mem_rw()
{
	union {
		int	addr;
		char	*ptr;
	} mem;
	char	*p, i, c, *sdp, pack(), unpack();

	p = &cmdbuf[1];
	switch (*p++) {
	case 'R':
	case 'W':
		break;
	default:
		sndrsp(R_CMDERR);
		return;
	}
	mem.addr = 0;
	for (i = 0; i != 4; i++) {
		if ((c = pack(*p++)) == 0xff) {
			sndrsp(R_PRMERR);
			return;
		}
		mem.addr *= 0x10;
		mem.addr += c;
	}

	sndrsp(R_NORMAL);

	if (cmdbuf[1] == 'R') {
		sdp = sdata;
		for (i = 0; i != 64; i++) {
			c = *mem.ptr++;
			*sdp++ = unpack(c >> 4);
			*sdp++ = unpack(c & 0x0f);
		}
		*sdp = 0;
		tsend(sdata,128);
	} else {
		while (1) {
			if ((c = pack(*p++)) == 0xff)
				break;
			if ((i = pack(*p++)) == 0xff)
				break;
			*mem.ptr++ = c * 0x10 + i;
		}
	}
}

/*	pack		*/

char
pack(c)
char	c;
{
	if (c >= '0' && c <= '9')
		return(c - '0');
	else if (c >= 'A' && c <= 'F')
		return(c - 'A' + 10);
	else if (c >= 'a' && c <= 'f')
		return(c - 'a' + 10);
	else
		return(0xff);
}

/*	unpack		*/

char
unpack(c)
char	c;
{
	if (c <= 9)
		return(c + '0');
	return(c - 10 + 'A');
}

/*	trace		*/

#define		T_BUF_SIZE	128

static	char	t_buf[T_BUF_SIZE];
static	char	front_ev;
static	int	t_buf_ptr;
static	int	t_buf_time;

trace(int ev)
{
char	x,y;
void	t_buf_set();

	x = st;
	y = ev;
	if	(!(y == 0x03 && front_ev == 0x03))
	{
		t_buf_set(x);
		t_buf_set(y);
	}
	front_ev = y;
}

void
t_buf_set(c)
char	c;
{
	t_buf[t_buf_ptr++] = c;
	if	(t_buf_ptr == T_BUF_SIZE)
	{
		t_buf_time++;
		t_buf_ptr = 0;
	}
}

/*	trace	report		*/

trept()
{
int	trept_ptr , i;

	trept_ptr = 0;
	if	(t_buf_time != 0)
	{
		for(i=t_buf_ptr ; i<T_BUF_SIZE ; i++)
		{
			rdata[trept_ptr++] = unpack(t_buf[i]);
		}
	}
	for(i=0 ; i<t_buf_ptr ; i++)
	{
		rdata[trept_ptr++] = unpack(t_buf[i]);
	}
	if	(t_buf_time == 0)
	{
		for( ; i<T_BUF_SIZE ; i++)
		{
			rdata[trept_ptr++] = '*';
		}
	}
	tsend(rdata,T_BUF_SIZE);
}
