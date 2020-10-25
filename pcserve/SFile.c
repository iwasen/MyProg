/************************************************************************
 *
 *			ÇoÇbÅ|ÇrÇdÇqÇuÇd
 *
 *		ñºèÃ		: ÉtÉ@ÉCÉãÉIÅ[ÉvÉìèàóù
 *		ÉtÉ@ÉCÉãñº	: SFile.c
 *		çÏê¨é“		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "pcserve.h"

SHORT OpenUserDBF(PDB *pDB)
{
	SHORT err;
	SHORT indexNo;

	if ((err = DBOpen(FN_USER_DBF, pDB, PERMISSION_ALL)) != 0)
		return err;

	if ((err = DBIndex(*pDB, FN_USER_NDX, &indexNo)) != 0) {
		DBClose(*pDB);
		return err;
	}

	return 0;
}

SHORT OpenUserGrpDBF(PDB *pDB)
{
	SHORT err;
	SHORT indexNo;

	if ((err = DBOpen(FN_USRGRP_DBF, pDB, PERMISSION_ALL)) != 0)
		return err;

	if ((err = DBIndex(*pDB, FN_USRGRP1_NDX, &indexNo)) != 0) {
		DBClose(*pDB);
		return err;
	}

	return 0;
}

SHORT OpenTermDBF(PDB *pDB)
{
	SHORT err;
	SHORT indexNo;

	if ((err = DBOpen(FN_TERM_DBF, pDB, PERMISSION_ALL)) != 0)
		return err;

	if ((err = DBIndex(*pDB, FN_TERM_NDX, &indexNo)) != 0) {
		DBClose(*pDB);
		return err;
	}

	return 0;
}

SHORT OpenTermGrpDBF(PDB *pDB)
{
	SHORT err;
	SHORT indexNo;

	if ((err = DBOpen(FN_TRMGRP_DBF, pDB, PERMISSION_ALL)) != 0)
		return err;

	if ((err = DBIndex(*pDB, FN_TRMGRP1_NDX, &indexNo)) != 0) {
		DBClose(*pDB);
		return err;
	}

	return 0;
}

SHORT OpenAccessDBF(PDB *pDB)
{
	SHORT err;
	SHORT indexNo;

	if ((err = DBOpen(FN_ACCESS_DBF, pDB, PERMISSION_ALL)) != 0) {
		return err;
	}

	if ((err = DBIndex(*pDB, FN_ACCESS_NDX, &indexNo)) != 0) {
		DBClose(*pDB);
		return err;
	}

	return 0;
}
