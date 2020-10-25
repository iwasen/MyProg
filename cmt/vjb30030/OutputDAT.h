// OutputDAT.h: COutputDAT クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_OUTPUTDAT_H__7B99318D_E252_41E4_8128_5B80897C3290__INCLUDED_)
#define AFX_OUTPUTDAT_H__7B99318D_E252_41E4_8128_5B80897C3290__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "OutputDevice.h"

class COutputDAT : public COutputDevice  
{
public:
	COutputDAT();
	virtual ~COutputDAT();

	int CheckVolumeName(Queue *pQueue);
	int OutputFiles(Queue *pQueue);
};

#endif // !defined(AFX_OUTPUTDAT_H__7B99318D_E252_41E4_8128_5B80897C3290__INCLUDED_)
