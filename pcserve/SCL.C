/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: サーバ側カレンダー処理
 *		ファイル名	: scl.c
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "pcserve.h"

/*=======================================================================
 |
 |		PSCLGetDate() 関数実行処理
 |
 |	VOID	ExecPSCLGetDate(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
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
 |		PSCLGetCalendar() 関数実行処理
 |
 |	VOID	ExecPSCLGetCalendar(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
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
