/*********************************************************************
 *	FILE DESCRIPTION
 *
 *		FILE NAME	: d3ccmp.c
 *		ROUTINE		: 
 *		REVISION	:
 *		REMARKS		: 88.02.05	M.MOTOKI
 *		REMARKS		: 88.04.30	M.MOTOKI
 *			  d3ccmp() -> マクロ化 (d3lmac.h へ移した)
 *			  関数名を変更した。d3ccmm() -> d3ccmp()
 ********************************************************************/

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"
#include	"la_ws/include/d3lmac.h"

static	char	sccsid[]="@(#)d3ccmp.c 1.3 88/06/12 14:47:44";

/*	FUNCTION DESCRIPTION
 *
 *		FUNC.NAME	: d3ccmp()
 *		INPUT		: dsm = data set memory pointer
 *				: label = component name
 *		OUTPUT		: return value = error code
 */

d3ccmp(mp,label)
DMEM	*mp;
char	*label;
{
	int	err;
	PD_DENT	box;

	err = d3gent(mp, label, &box);
	if (err != 0)
		return(err);
	PD_MODE(box.ent_entp)   = DS_N;
	PD_LENGTH(box.ent_entp) = 0;
	PD_VALUE(box.ent_entp)  = NULL;

	return(0);
}
