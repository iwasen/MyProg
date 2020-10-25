// InputDevice.h: CInputDevice クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_INPUTDEVICE_H__1D24EFE6_F951_484E_BEEA_7CA74C1ABF7F__INCLUDED_)
#define AFX_INPUTDEVICE_H__1D24EFE6_F951_484E_BEEA_7CA74C1ABF7F__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

class CInputDevice  
{
public:
	CInputDevice();
	virtual ~CInputDevice();

	virtual int KakuninMessage(Param *pParam) = 0;
	virtual int CheckVolumeName(Param *pParam) = 0;
	virtual int InputFiles(Param *pParam) = 0;
	virtual void GetError(int &nErrorCode, CString &sErrorMsg);
	void SetUpdateWindow(CWnd *pWnd);

protected:
	CWnd *m_pUpdateWnd;

	BOOL GetVolumeName(LPCTSTR pPath, CString &sVolumeName);
};

#endif // !defined(AFX_INPUTDEVICE_H__1D24EFE6_F951_484E_BEEA_7CA74C1ABF7F__INCLUDED_)
