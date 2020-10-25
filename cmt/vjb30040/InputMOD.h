// InputMOD.h: CInputMOD クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_INPUTMOD_H__78C88867_DC9B_4FB6_A403_3558970223A4__INCLUDED_)
#define AFX_INPUTMOD_H__78C88867_DC9B_4FB6_A403_3558970223A4__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "InputDevice.h"

class CInputMOD : public CInputDevice  
{
public:
	CInputMOD();
	virtual ~CInputMOD();

	int KakuninMessage(Param *pParam);
	int CheckVolumeName(Param *pPparam);
	int InputFiles(Param *pPparam);
};

#endif // !defined(AFX_INPUTMOD_H__78C88867_DC9B_4FB6_A403_3558970223A4__INCLUDED_)
