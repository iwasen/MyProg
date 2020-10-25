// OutputDevice.h: COutputDevice クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_OUTPUTDEVICE_H__5FB1EC1B_794D_453F_951B_5697B2F3B2EB__INCLUDED_)
#define AFX_OUTPUTDEVICE_H__5FB1EC1B_794D_453F_951B_5697B2F3B2EB__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

class COutputDevice  
{
public:
	COutputDevice();
	virtual ~COutputDevice();

	int CheckDstFile(Queue *pQueue);
	virtual int CheckVolumeName(Queue *pQueue) = 0;
	virtual int OutputFiles(Queue *pQueue) = 0;
	virtual void GetError(int &nErrorCode, CString &sErrorMsg);

protected:
	CString m_sSrcDir;

	BOOL GetVolumeName(LPCTSTR pPath, CString &sVolumeName);
};

#endif // !defined(AFX_OUTPUTDEVICE_H__5FB1EC1B_794D_453F_951B_5697B2F3B2EB__INCLUDED_)
