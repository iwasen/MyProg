// OutputFPD.h: COutputFPD クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_OUTPUTFPD_H__D3BF92DA_D9C2_42F8_AFF8_6EA648CDDD9F__INCLUDED_)
#define AFX_OUTPUTFPD_H__D3BF92DA_D9C2_42F8_AFF8_6EA648CDDD9F__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "OutputDevice.h"

class COutputFPD : public COutputDevice  
{
public:
	COutputFPD();
	virtual ~COutputFPD();

	int CheckVolumeName(Queue *pQueue);
	int OutputFiles(Queue *pQueue);
};

#endif // !defined(AFX_OUTPUTFPD_H__D3BF92DA_D9C2_42F8_AFF8_6EA648CDDD9F__INCLUDED_)
