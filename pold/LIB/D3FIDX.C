/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3fidx.c
 *		ROUTINE		: 索引ファイル名取り出し
 *		REVISION	:
 *		REMARKS		: 88.01.18
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

extern struct dsm *d3gcmp(), *d3gtra();

static	char	sccsid[]="@(#)d3fidx.c 1.4 88/06/12 14:51:01";

/*	FUNCTION DESCRIPTION
 *
 *			索引ファイル名取り出し
 *
 *		FUNC.NAME	: d3fidx()
 *		INPUT		: dsp = data set pointer
 *				: ds_name = data set name
 *		OUTPUT		: index_name = 索引ファイル名
 *				: return value = error code
 */

d3fidx(dsp, ds_name, index_name)
DSET *dsp;
char	*ds_name, *index_name;
{
	struct dsm *labp, *datp, *axip, *labsp, *tra1p, *tra3p, *dimp, *datap;
	int	i, rn, ds_no, index_no, *dp, prefix, index;

	if ((labp = d3gcmp(dsp->ds_entry, "Lab")) == NULL)
		return(DE_DS_FORMAT);

	if ((datp = d3gcmp(dsp->ds_entry, "Dat")) == NULL)
		return(DE_DS_FORMAT);

	if ((axip = d3gcmp(labp, "Axi2")) == NULL)
		return(DE_DS_FORMAT);

	if ((labsp = d3gcmp(axip, "Labs")) == NULL)
		return(DE_DS_FORMAT);

	prefix = index = 0;
	for (i = 0; i < labsp->length; i++) {
		if (strcmp(labsp->ptr.c[i], GRP_PREFIX) == 0)
			prefix = i + 1;
		else if (strcmp(labsp->ptr.c[i], GRP_INDEX) == 0)
			index = i + 1;
	}
	if (prefix == 0 || index == 0)
		return(DE_DS_FORMAT);
	
	if ((tra1p = d3gtra(axip, prefix)) == NULL)
		return(DE_DS_FORMAT);

	if ((tra3p = d3gtra(axip, index)) == NULL)
		return(DE_DS_FORMAT);

	if ((dimp = d3gcmp(datp, "Dim")) == NULL)
		return(DE_DS_FORMAT);

	if ((datap = d3gcmp(datp, "Data")) == NULL)
		return(DE_DS_FORMAT);

	rn = dimp->ptr.i[0];
	dp = datap->ptr.i;

	for (i = 0; i < tra1p->length; i++) {
		if (strncmp(ds_name, tra1p->ptr.c[i], strlen(tra1p->ptr.c[i])) == 0)
			break;
	}

	if (i == tra1p->length)
		return(DE_NONE_GRP);

	ds_no = i + 1;

	for (i = 0; i < rn; i++) {
		if (ds_no == dp[i + rn * (prefix-1)])
			break;
	}
	if (i == rn)
		return(DE_NONE_GRP);

	index_no = dp[i + rn * (index-1)];

	strcpy(index_name, tra3p->ptr.c[index_no - 1]);

	return(0);
}

