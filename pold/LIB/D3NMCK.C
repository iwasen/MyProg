#include	<string.h>
#include	"la_ws/include/d3nmty.h"

#define	GRP_NUM		1
#define	SYS_NUM		1
#define	USR_NUM		2
#define	LAF_NUM		2

static	char	*group_prefix[GRP_NUM] = "GRP.";
static	char	*index_prefix[SYS_NUM] = "IND.";
static	char	*uindx_prefix[USR_NUM] = {"USR.", "EP."};
static	char	*pdfile_ident[LAF_NUM] = {".B", ".T"};

/************************************************	
	La Data-Set Type Check by Specified Name
*************************************************/
d3nmck(ds_name)
char	*ds_name;
{
	int	i, j;

	if ((i=nmck00(ds_name, group_prefix, GRP_NUM)) >= 0)
		j=GRP_IDX;	/* Specified Name is GRP_IDX */
	else if ((i=nmck00(ds_name, index_prefix, SYS_NUM)) >= 0)
		j=SYS_IDX;	/* Specified Name is SYS_IDX */
	else if ((i=nmck00(ds_name, uindx_prefix, USR_NUM)) >= 0)
		j=USR_IDX;	/* Specified Name is USR_IDX */
	else if ((i=nmck00(ds_name, pdfile_ident, LAF_NUM)) >= 0)
		j=PD_FILE;	/* Specified Name is PD_FILE */
	else {
		i=0;
		j=PD_DSET;	/* Specified Name is LA_DATASET */
	}
	return((j<<8) + i);
}
static
nmck00(name, table, num)
char	*name, *table[];	
int	num;
{
	int	k;

	for (k=0; k<num; k++) {
		if (strncmp(name, table[k], strlen(table[k]))==0) break;
	}
	if (k < num)
		return(k);	/* found */
	else
		return(-1);	/* not found */
}
