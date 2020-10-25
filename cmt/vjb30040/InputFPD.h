// InputFPD.h: CInputFPD クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_INPUTFPD_H__6C317565_07D9_4382_9BD3_815C346E34C0__INCLUDED_)
#define AFX_INPUTFPD_H__6C317565_07D9_4382_9BD3_815C346E34C0__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "InputDevice.h"

class CInputFPD : public CInputDevice  
{
public:
	CInputFPD();
	virtual ~CInputFPD();

	int KakuninMessage(Param *pParam);
	int CheckVolumeName(Param *pPparam);
	int InputFiles(Param *pPparam);
};

#endif // !defined(AFX_INPUTFPD_H__6C317565_07D9_4382_9BD3_815C346E34C0__INCLUDED_)
