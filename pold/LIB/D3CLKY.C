/***************************************************************	
	Clear Not Use Key-Value on "Lab"
	FUNC.NAME	: d3clky() 
	INPUT		: dsp		; pointer of d3lbgn()
	OUTPUT		: return code
	REMARKS		:	88.01.17	S.Takahashi
***************************************************************/
#include	"d3clky.h"
extern char	*malloc();
extern struct dsm *d3gcmp(), *d3gtra();

static char	sccsid[] = "%Z%%M% %I% %E% %U%";

d3clky (dsp , col)
DSET	*dsp;
int	col;
{
	struct dsm *labp, *axip, *axi_dp, *trap, *datp, *dimp, *datap;
	int	sbn_suu;
	int	*tbl;
	int	i;
	int	lmax;
	int	*lines;
	int	*data;
	int	tno;

	/* get Lab's seibun suu & move pointer to Lab */

	if ((labp = d3gcmp(dsp->ds_entry, "Lab")) == 0)
		return(DE_DS_FORMAT);
	if ((axip = d3gcmp(labp, "Axi2")) == 0)
		return(0);
	sbn_suu = axip->length;
	axi_dp = &axip->ptr.d[2];

	/* get Tran's fields number max */

	if (col == 0) {
		lmax = 0;
		for (i = 0; i < sbn_suu; i++) {
			if (strncmp (axi_dp[i].seibun , "Tra" , 3) == 0 ) {
				if (axi_dp[i].length > lmax)
					lmax = axi_dp[i].length;
			}
		}
	} else {
		if ((trap = d3gtra(axip, col)) == 0)
			return(0);
		lmax = trap->length;
	}

	if (lmax == 0)
		return(0);

	/* lines , data pointer get */

	if ((datp = d3gcmp(dsp->ds_entry, "Dat")) == 0)
		return(DE_DS_FORMAT);
	if ((dimp = d3gcmp(datp, "Dim")) == 0)
		return(0);
	if ((datap = d3gcmp(datp, "Data")) == 0)
		return(0);

	lines = dimp->ptr.i;
	data = datap->ptr.i;

	/* check table allocate on dynamic memory  */

	tbl = (int *)malloc(lmax * sizeof(int));
	if (tbl == NULL) 
		return (DE_MEM);

	/* compress memory */

	if (col == 0) {
		for (i = 0; i < sbn_suu; i++) {
			if (strncmp (axi_dp[i].seibun , "Tra" , 3) == 0) {
				/* Tra No get */
				tno = atoi(axi_dp[i].seibun + 3);
				/* renumber code  & compress key-value */
				clky10(&axi_dp[i], tno, lines, data, tbl);
			}
		}
	} else {
		clky10(trap, col, lines, data, tbl);
	}

	free(tbl);
	return(0);
}

static
clky10(trap, col, lines, data, tbl)
DMEM	*trap;
int	col;
int	*lines, *data, *tbl;
{
	int	i, not_use_num, code;
	int	*l_key;
	int	*i_key;
	char	**c_key;

	for (i = 0; i < trap->length; i++) 
		tbl[i] = NOT_USE;
	/* use check */
	for (i = 0; i < *lines; i++) {
		if (data[*lines*(col-1)+i] != PD_NA) 
			tbl[(data[*lines*(col-1)+i])-1] = USE;
	}
	/* memory update check */
	not_use_num = 0;
	for (i = 0; i < trap->length; i++) {
		if (tbl[i] == NOT_USE)  
			not_use_num++;
	}
	/* memory update */
	if (not_use_num != 0) {
		/* renumber code  & 
				compress key-value */
		switch (trap->type) {
		case DS_L : 
			l_key = trap->ptr.i; 
			break;
		case DS_R : 
			i_key = trap->ptr.i; 
			break;
		case DS_C : 
			c_key = trap->ptr.c; 
			break;
		}
		code = 0;
		for (i = 0; i < trap->length; i++) {
			if (tbl[i] == USE) {
				code++;
				tbl[i] = code;
				switch (trap->type) {
				case DS_L :
					l_key[code-1] = l_key[i];
					break;
				case DS_R :
					i_key[code-1] = i_key[i];
					break;
				case DS_C :
					c_key[code-1] = c_key[i];
					break;
				}
			}
		}
		/* length count down */
		trap->length -= not_use_num;
		/* replace renumber code on Data */
		for (i = 0; i < *lines; i++) {
			if (tbl[(data[*lines*(col-1)+i])-1] != NOT_USE) {
				data[*lines*(col-1)+i] = 
				    tbl[(data[*lines*(col-1)+i])-1];
			}
		}
	}
}
