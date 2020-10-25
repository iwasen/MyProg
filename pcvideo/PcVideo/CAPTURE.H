// capture.h : ヘッダー ファイル
//

#include "video.h"

#ifndef __CAPTURE_H__
#define __CAPTURE_H__

/////////////////////////////////////////////////////////////////////////////
// CCaptureWnd フレーム

class CCaptureWnd : public CVideoWnd
{
	DECLARE_DYNCREATE(CCaptureWnd)
public:
	CCaptureWnd();

protected:
	virtual ~CCaptureWnd();

	CStatusBar m_wndStatusBar;

	// メッセージ マップ関数の生成
	//{{AFX_MSG(CCaptureWnd)
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnTimer(UINT nIDEvent);
	afx_msg void OnMove(int x, int y);
	afx_msg void OnDestroy();
	afx_msg void OnClose();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	int m_nCaptureWidth;
	int m_nCaptureHeight;
	int m_nCaptureColor;
	long m_nCaptureSize;
	int m_nCaptureQuality;
	int m_nCaptureEdgeEnhance;
	int m_nCaptureCounter;
	DWORD m_nCaptureCompression;
	BOOL m_bCaptureSendInfo;
	int m_nBlockTmp;
	int m_nFrameCount;
	long m_nSendBytes;
	long m_nSendBytes2;
	LPCINFO	m_lpCInfo;
	BOOL m_bFullFrame;
	int m_cBuf[MAXSIZE_Y / BLOCK_PIXEL][MAXSIZE_X / BLOCK_PIXEL];
	int m_nCx, m_nCy;
	BOOL m_bCaptureData;
	UINT m_nCaptureTimer;
	BOOL m_bUnderBPS;
	BOOL m_bOverBPS;
	int m_nOverBPS;
	int m_nTimerAdjuxxxounter;
	int m_nDispWidth;
	int m_nDispHeight;
	LPSTR m_lpBuf;
	LPSTR m_lpWorkBuf;
	BITMAPINFOHEADER m_CaptureFormat;
	BOOL m_bCaptureRequest;
	HANDLE m_hDIB;

	void TerminateCapture();
	void SetCaptureTimer(BOOL);
	void TimerCaptureWindow();
	int Capture8(char *);
	int Capture16(char *);
	int Capture24(char *);
	void CaptureFrameRequest();
	void FreeVideoBuffer();

public:
	BOOL	m_bStopCapture;
	BOOL	SetCaptureMode(int, int, int);
	void	CaptureFrame(LPVIDEOHDR lpVHdr);
};

/////////////////////////////////////////////////////////////////////////////

#endif // __CAPTURE_H__
