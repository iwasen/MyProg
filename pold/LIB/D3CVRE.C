/************************************************************
 *	FILE DESCRIPTION
 *		シェルの正規表現を特殊ライブラリの正規表現に
 *		変換する
 *	FILE	:	d3cvre.c
 *		:	d3cvre
 *	REMARKS	:	1988-05-20	M.MOTOKI
 *
 ************************************************************/

static	char	sccsid[]="@(#)d3cvre.c 1.2 88/06/12 15:03:03";

d3cvre(ibuf,obuf,mode)
char	*ibuf, *obuf;
int	mode;
{
	if (mode == 0)			/* 完全一致 */
		(*obuf++) = '^';
	if (*ibuf == '*') {
		strcpy(obuf, "[^\\.].*");
		ibuf++; obuf += 7;
	} else if (*ibuf == '?') {
		strcpy(obuf, "[^\\.]");
		ibuf++; obuf += 5;
	}
	while (*ibuf) {
		switch (*ibuf) {
		case	'?':	*(obuf++) = '.';
				break;
		case	'[':	*(obuf++) = *ibuf;
				if (*(ibuf+1) == '!') {	/* NOT exprresion */
					*(obuf++) = '^';
					ibuf++;
				}
				break;
		case	'*':	*(obuf++) = '.';	*(obuf++) = '*';
				break;
		case	'$':	*(obuf++) = '\\';	*(obuf++) = '$';
				break;
		case	'^':	*(obuf++) = '\\';	*(obuf++) = '^';
				break;
		case	'{':	*(obuf++) = '\\';	*(obuf++) = '{';
				break;
		case	'}':	*(obuf++) = '\\';	*(obuf++) = '}';
				break;
		case	'(':	*(obuf++) = '\\';	*(obuf++) = '(';
				break;
		case	')':	*(obuf++) = '\\';	*(obuf++) = ')';
				break;
		case	'.':	*(obuf++) = '\\';	*(obuf++) = '.';
				break;
		case	'+':	*(obuf++) = '\\';	*(obuf++) = '+';
				break;
		case	'\\':	*(obuf++) = *(ibuf++);
				if (*ibuf == 'n')	*(obuf++) = '\\';
				*(obuf++) = *ibuf;
				break;
		default	:	*(obuf++) = *ibuf;
		}			/* END SWITCH */
		ibuf++;
	}				/* END FOR */
	if (mode == 0)
		*(obuf++) = '$';
	*obuf = '\0';

	return(0);
}
