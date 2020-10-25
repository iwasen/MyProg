/*	FILE DESCRIPTION

		FILE NAME	: d3fitm.c
		ROUTINE		: çÄñ⁄àÍóóÉeÅ[ÉuÉãçÏê¨
		REVISION	:
		REMARKS		:	88.04.19	S.Aizawa
*/

#include	<jcurses.h>
#include	"d3item.h"
#include	"la_ws/include/d2lerr.h"
#include	"la_ws/include/d3libc.h"

extern	char	*d3allc();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3fitm.c
		INPUT		: file_name = file name
				: mgno      = memory group No.
		OUTPUT		: ptr       = item table pointer
				: n         = number of item
				: return value = error code
*/

d3fitm(file_name, mgno, ptr, n_item)
char	*file_name;
int	mgno;
D_CELL	**ptr;
int	*n_item;
{
	FILE	*fp;
	int	err;

	if ((fp = fopen(file_name, "r")) == 0)
		return(E_TF_A);

	err = d3fit2(fp, mgno, ptr, n_item);

	fclose(fp);

	return(err);
}


/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3fit2.c
		INPUT		: fp	    = file pointer
				: mgno      = memory group No.
		OUTPUT		: ptr       = item table pointer
				: n         = number of item
				: return value = error code
*/

d3fit2(fp, mgno, ptr, n_item)
FILE	*fp;
int	mgno;
D_CELL	**ptr;
int	*n_item;
{
	int	i, len;
	D_CELL	*p;
	char	s[128];

	*n_item = 0;
	while (fgets(s, 128, fp) != 0)
		(*n_item)++;

	rewind(fp);

	if (*n_item == 0)
		return(0);

	if ((*ptr = (D_CELL *)d3allc(mgno, sizeof(D_CELL) * *n_item)) == 0)
		return(E_M);

	p = *ptr;
	for (i = 0; i < *n_item; i++) {
		p->flag = p->result = 0;
		if (fgets(s, 128, fp) == 0) {
			return(E_TF_A);
		}
		len = strlen(s) - 1;
		if ((p->ptr = d3allc(mgno, len+1)) == 0) {
			return(E_M);
		}
		strncpy(p->ptr, s, len);
		p->ptr[len] = '\0';
		p++;
	}
	return(0);
}

