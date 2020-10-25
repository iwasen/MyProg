// InputCMT.h: CInputCMT クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_INPUTCMT_H__8C6D0510_5408_49A1_9D22_60A33519BFEC__INCLUDED_)
#define AFX_INPUTCMT_H__8C6D0510_5408_49A1_9D22_60A33519BFEC__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "InputDevice.h"
#include "MTDriver.h"

class CInputCMT : public CInputDevice  
{
public:
	CInputCMT();
	virtual ~CInputCMT();

	int KakuninMessage(Param *pParam);
	int CheckVolumeName(Param *pPparam);
	int InputFiles(Param *pPparam);
	void GetError(int &nErrorCode, CString &sErrorMsg);

protected:
	CMTDriver m_MTDriver;
	BOOL m_bOpen;

	int InputFilesWithLabel(Param *pParam, LPCTSTR pDirName);
	int InputFilesNonLabel(Param *pParam, LPCTSTR pDirName);
};

#endif // !defined(AFX_INPUTCMT_H__8C6D0510_5408_49A1_9D22_60A33519BFEC__INCLUDED_)
