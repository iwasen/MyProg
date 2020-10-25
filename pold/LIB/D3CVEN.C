/*	環境変数の含まれたパス名を通常のパス名に変換する
 *
 *		$Y_PKHOME/swork/abc001 -> /users/la/polk/swork/abc001
 *
 */

#include	<stdio.h>

extern	char	*getenv(), *strcpy(), *strpbrk(), *strchr();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

d3cven(data)
char	*data;
{
	char	tmp[128];
	char	*d_1, *p_0, *p_1, *s2="$/", *env, save;
	int	n;

	if ((d_1 = strchr(data, '$')) == NULL)
		return(0);

	strcpy(tmp,data);
	p_0 = tmp;

	n = d_1 - data;
	if (n)			/* .... $Y_PKHOME .... */
		*d_1 = '\0';
	p_0 += n;		/* current position ---> '$' */
	for (;;) {
		p_1 = strpbrk(p_0+1, s2);
		if (p_1 != NULL) {
			save = *p_1; *p_1 = '\0';
			if ((env = getenv(p_0+1)) != NULL) {
				strcpy(d_1, env);
				d_1 += strlen(env);
			} else {
				strcpy(d_1, p_0);
				d_1 += strlen(p_0);
			}
			*p_1 = save;
			p_0 = p_1;

			if (*p_0 == '/') {
				p_1 = strchr(p_0+1, '$');	
				if (p_1 == NULL) {
					strcpy(d_1, p_0);
					break;
				} else {
					n = p_1 - p_0;
					strcpy(d_1, p_0, n);
					d_1 += n; *d_1 = '\0';
					p_0 = p_1;
				}
			}
		} else {
			if ((env = getenv(p_0+1)) != NULL)
				strcpy(d_1, env);
			else
				strcpy(d_1, p_0);
			break;
		}
	}

	return(0);
}
