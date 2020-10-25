/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"
#include "bcbios.h"

#define	RSINTST		*(char *)0xf3bb
#define	BCINTST		*(char *)0xf3bc
#define	FUNC_TBL	(char *)0xf45a
#define	RSODEV		*(char *)0xf623

extern	char	key_int;
extern	char	on_com_flg[], on_key_flg[], on_brcd_flg;
extern	void	(*on_com_adr[])();
extern	void	(*on_key_adr[])();
extern	void	(*on_brcd_adr)();

#pragma	module	intrpt@

void b_intrpt()
{
	char	rtinf[9], com, key;

	if (RSINTST) {
		com = RSODEV;
		if (com != 0xff) {
			switch (on_com_flg[com]) {
			case 0:
				RSINTST = 0;
				break;
			case 1:
				RSINTST = 0;
				if (on_com_adr[com] != 0) {
					on_com_flg[com] = 2;
					(*on_com_adr[com])();
					on_com_flg[com] = 1;
				}
			}
		}
	}

	if (key_int) {
		key = key_int;
		switch (on_key_flg[key]) {
		case 0:
			key_int = 0;
			break;
		case 1:
			key_int = 0;
			if (on_key_adr[key] != 0) {
				on_key_flg[key] = 2;
				(*on_key_adr[key])();
				on_key_flg[key] = 1;
			}
		}
	}

	if (BCINTST) {
		switch (on_brcd_flg) {
		case 0:
			BCINTST = 0;
			break;
		case 1:
			BCINTST = 0;
			if (on_brcd_adr != 0) {
				on_brcd_flg = 2;
				(*on_brcd_adr)();
				on_brcd_flg = 1;
			}
		}
	}
}


#pragma	module	on_com@

void b_on_com(n, addr)
int	n;
void	(*addr)();
{
	if (n != 0 && n != 3)
		b_error(E_FC);
	on_com_adr[n] = addr;
}


#pragma	module	on_key@

void b_on_key(n, addr)
int	n;
void	(*addr)();
{
	if (n < 1 || n > 10)
		b_error(E_FC);
	on_key_adr[n] = addr;
}


#pragma	module	on_brcd@

void b_on_brcd(addr)
void	(*addr)();
{
	on_brcd_adr = addr;
}


#pragma	module	keyint@

static void key1()
{
	key_int = 1;
}

static void key2()
{
	key_int = 2;
}

static void key3()
{
	key_int = 3;
}

static void key4()
{
	key_int = 4;
}

static void key5()
{
	key_int = 5;
}

static void key6()
{
	key_int = 6;
}

static void key7()
{
	key_int = 7;
}

static void key8()
{
	key_int = 8;
}

static void key9()
{
	key_int = 9;
}

static void key10()
{
	key_int = 10;
}

void b_keyint()
{
	static struct {
		char	bank;
		void	(*addr)();
	} keyint_tbl[] = {
		{0, key1},
		{0, key2},
		{0, key3},
		{0, key4},
		{0, key5},
		{0, key6},
		{0, key7},
		{0, key8},
		{0, key9},
		{0, key10}
	};
	char	*p1, *p2, i;

	p1 = (char *)keyint_tbl;
	p2 = FUNC_TBL;
	for (i = 0; i < sizeof(keyint_tbl); i++)
		*p2++ = *p1++;
}


#pragma	module	comn@

void b_comn(n, st)
int	n, st;
{
	if (n != 0 && n != 3)
		b_error(E_FC);

	on_com_flg[n] = st;
}


#pragma	module	keyn@

void b_keyn(n, st)
int	n, st;
{
	if (n < 1 || n > 10)
		b_error(E_FC);

	on_key_flg[n]= st;
}


#pragma	module	brcd@

void b_brcd(st)
int	st;
{
	on_brcd_flg = st;
}
