// InputDAT.h: CInputDAT クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_INPUTDAT_H__B7CEDDC9_E35F_41D5_A704_7975CE928922__INCLUDED_)
#define AFX_INPUTDAT_H__B7CEDDC9_E35F_41D5_A704_7975CE928922__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "InputDevice.h"

class CInputDAT : public CInputDevice  
{
public:
	CInputDAT();
	virtual ~CInputDAT();

	int KakuninMessage(Param *pParam);
	int CheckVolumeName(Param *pPparam);
	int InputFiles(Param *pPparam);
};

#endif // !defined(AFX_INPUTDAT_H__B7CEDDC9_E35F_41D5_A704_7975CE928922__INCLUDED_)
