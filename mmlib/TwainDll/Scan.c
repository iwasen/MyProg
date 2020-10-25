/****************************************************************************
 |
 |   			TWAIN�Ή��@���Ű�摜��荞�݂c�k�k
 | 
 |			̧�ٖ� :		scan.c		
 |			�쐬�� :		m.hama
 |			���t�F			96/09/03
 |
 |
 ***************************************************************************/
#include <windows.h>         // Req. for twain.h type defs and ...
#include <commdlg.h>
#include <math.h>
#include <string.h>
#include <stdlib.h>

#include "twain.h"
#include "code.h"
#include "extern.h"
#include "data.h"
#include "scan.h"

static	VOID	InitData(HWND);

/****************************************************************************
 |
 |   		���Ű�摜��荞��
 | 
 |		int	FAR PASCAL _export ScanDib(HWND hWnd, LPSTR DibFileName);			
 |
 |			HWND 	hWnd;			�Ăь�����޳�����
 |			LPSTR	DibFileName;	DIB̧�ٖ�
 |
 ***************************************************************************/
int	APIENTRY ScanDib(HWND hWnd, LPSTR DibFileName)
{
	/* ���ع����ID�ҏW */
	InitData(hWnd);

	lstrcpy((LPSTR)Filename, DibFileName);

	EnableWindow(hWnd, FALSE);

	mode = 0;
  	TWAcquire(hWnd, TRUE);

	EnableWindow(hWnd, TRUE);
	SetFocus(hWnd);

	return(WriteDibFlag);
}

HANDLE	APIENTRY ScanBmp(HWND hWnd, IMAGE_INFO *pImageInfo)
{
	/* ���ع����ID�ҏW */
	InitData(hWnd);

	EnableWindow(hWnd, FALSE);

	mode = 1;
	hBitmap = NULL;

  	TWAcquire(hWnd, TRUE);

	EnableWindow(hWnd, TRUE);
	SetFocus(hWnd);

	if (hBitmap != NULL) {
		pImageInfo->XResolution = twImageInfo.XResolution.Whole;
		pImageInfo->YResolution = twImageInfo.YResolution.Whole;
		pImageInfo->ImageWidth = twImageInfo.ImageWidth;
		pImageInfo->ImageLength = twImageInfo.ImageLength;
		pImageInfo->BitsPerPixel = twImageInfo.BitsPerPixel;
	}

	return(hBitmap);
}

void APIENTRY ScanBmpContinue(HWND hWnd)
{
	/* ���ع����ID�ҏW */
	InitData(hWnd);

	EnableWindow(hWnd, FALSE);

	mode = 2;
	hBitmap = NULL;
  	TWAcquire(hWnd, TRUE);

	EnableWindow(hWnd, TRUE);
	//SetFocus(hWnd);
}

BOOL APIENTRY SelectTwainDriver(HWND hWnd)
{
	InitData(hWnd);

	if (!TWOpenDSM())
		return FALSE;

	TWSelectDS();
	TWCloseDSM();
	return TRUE;
}

BOOL APIENTRY GetDefaultDriver(HWND hWnd, TW_IDENTITY *dsID)
{
	BOOL rc;

	InitData(hWnd);

	if (!TWOpenDSM())
		return FALSE;

	rc = TWGetDefault(dsID);
	TWCloseDSM();

	return rc;
}

static	VOID	InitData(HWND hWnd)
{
    TW_IDENTITY AppIdentity;

	/* ���ع����ID�ҏW */
    AppIdentity.Id = 0; // init to 0, but Source Manager will assign real value
    AppIdentity.Version.MajorNum = 1;
    AppIdentity.Version.MinorNum = 5;
    AppIdentity.Version.Language = TWLG_USA;
    AppIdentity.Version.Country  = TWCY_USA;
    lstrcpy (AppIdentity.Version.Info,  "xxxx TWAIN Interface DLL 1.1  97/11/05");

    AppIdentity.Protocxxxajor =    TWON_PROTOCxxxAJOR;
    AppIdentity.Protocxxxinor =    TWON_PROTOCxxxINOR;
    AppIdentity.SupportedGroups =  DG_IMAGE | DG_CONTROL;
    lstrcpy (AppIdentity.Manufacturer,  "xxxx");
    lstrcpy (AppIdentity.ProductFamily, "M.HAMA/S.AIZAWA");
    lstrcpy (AppIdentity.ProductName,   "SCAN32.DLL");

    // pass app particulars to glue code
    TWInitialize (&AppIdentity, hWnd);
	SetMessageLevel(ML_NONE);

	dsID.Id = 0;
	dsID.ProductName[0] = 0;

	WriteDibFlag = FALSE;
}




