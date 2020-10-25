// OutputMOD.h: COutputMOD クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_OUTPUTMOD_H__F05D28AD_5FDC_453F_91AF_01E0ACB51AE2__INCLUDED_)
#define AFX_OUTPUTMOD_H__F05D28AD_5FDC_453F_91AF_01E0ACB51AE2__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "OutputDevice.h"

class COutputMOD : public COutputDevice  
{
public:
	COutputMOD();
	virtual ~COutputMOD();

	int CheckVolumeName(Queue *pQueue);
	int OutputFiles(Queue *pQueue);
};

#endif // !defined(AFX_OUTPUTMOD_H__F05D28AD_5FDC_453F_91AF_01E0ACB51AE2__INCLUDED_)
