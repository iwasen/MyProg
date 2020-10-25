
/************************************************************
 *	FUNCTION	DISCRIPT
 *	COMMENT	:	成分のパス名を各成分名に分解する
 *	FILE	:	d3cmnm.c
 *		:	d3cmnm()
 *	REMARKS	:	1988-04-27	M.MOTOKI
 *
 ************************************************************/
#include <stdio.h>
#include <string.h>
#include "la_ws/include/d3libc.h"

#define	MAX_DEPTH	10
#define	MAX_BUFLN	256

extern	char	*strpbrk();

static	char	sccsid[]="@(#)d3cmnm.c 1.2 88/06/12 15:02:22";

d3cmnm(path_name, nitems, comp_name)
char	*path_name, ***comp_name;
int	*nitems;
{
	static	char	*p[MAX_DEPTH + 1];
	static	char	buf[MAX_BUFLN];
	static	char	s2[]="\\$";
	char	*p1, *p2, *setup=NULL;
	int	cnt=0, boff=0, n;

	p1 = path_name;
	while ( *p1 && (cnt < MAX_DEPTH)) {
		p2 = strpbrk(p1, s2);
		if (*p2 == '\\') {	/* とりあえずバッファへコピーする */
			n = p2 - p1 + 2;
			if (boff + n >= MAX_BUFLN -1)
				return(DE_IF_MEMOVR);
			if (setup == NULL)
				setup = &buf[boff];
			strncpy(&buf[boff], p1, n);
			boff += n;
			p1 += n;
		} else if (*p2 == '$') {
			n = p2 - p1;
			if (boff + n >= MAX_BUFLN -1)
				return(DE_IF_MEMOVR);
			if (setup == NULL)
				p[cnt] = &buf[boff];
			else {
				p[cnt] = setup;
				setup = NULL;
			}
			strncpy(&buf[boff], p1, n);
			boff += n;
			buf[boff++] = '\0';
			p1 = ++p2; cnt++;
		} else {
			n = strlen(p1) + 1;
			if (boff + n >= MAX_BUFLN -1)
				return(DE_IF_MEMOVR);
			if (setup == NULL)
				p[cnt] = &buf[boff];
			else {
				p[cnt] = setup;
				setup = NULL;
			}
			strcpy(&buf[boff], p1);
			cnt++;		/* 個数カウントアップ */
			break;
		}
	}
	*nitems = cnt;
	*comp_name = p;
	if (cnt > MAX_DEPTH)
		return(DE_PRM);
	return(0);
}
