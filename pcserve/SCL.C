/************************************************************************
 *
 *			�o�b�|�r�d�q�u�d
 *
 *		����		: �T�[�o���J�����_�[����
 *		�t�@�C����	: scl.c
 *		�쐬��		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "pcserve.h"

/*=======================================================================
 |
 |		PSCLGetDate() �֐����s����
 |
 |	VOID	ExecPSCLGetDate(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSCLGetDate(PSCB pSCB)
{
	struct	PRM_CL_GETDATE	*pParam;
	SHORT	err;

	pParam = (struct PRM_CL_GETDATE *)pSCB->command.param;
	pParam->calendarID[sizeof(pParam->calendarID) - 1] = '\0';

	err = CLGetDate(pParam->calendarID, &pParam->date, pParam->days,
						(PCLDATE)pSCB->lpData);

	pSCB->response.status = err;
	pSCB->response.dataLength = (err == 0) ? sizeof(CLDATE) : 0;
}

/*=======================================================================
 |
 |		PSCLGetCalendar() �֐����s����
 |
 |	VOID	ExecPSCLGetCalendar(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSCLGetCalendar(PSCB pSCB)
{
	struct	PRM_CL_GETCALENDAR	*pParam;
	SHORT	err;

	pParam = (struct PRM_CL_GETCALENDAR *)pSCB->command.param;
	pParam->calendarID[sizeof(pParam->calendarID) - 1] = '\0';

	err = CLGetCalendar(pParam->calendarID, &pParam->date, pSCB->lpData);

	pSCB->response.status = err;
	pSCB->response.dataLength = (err == 0) ? 31 : 0;
}