// receive.h : ヘッダー ファイル
//

#include "video.h"

#ifndef __RECEIVE_H__
#define __RECEIVE_H__

/////////////////////////////////////////////////////////////////////////////
// CReceiveWnd フレーム

class CReceiveWnd : public CVideoWnd
{
	DECLARE_DYNCREATE(CReceiveWnd)
public:
	CReceiveWnd(PUSER pUser = NULL);

public:
	virtual ~CReceiveWnd();

	CStatusBar m_wndStatusBar;

	// メッセージ マップ関数の生成
	//{{AFX_MSG(CReceiveWnd)
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnMove(int x, int y);
	afx_msg void OnTimer(UINT nIDEvent);
	afx_msg void OnLButtonDown(UINT nFlags, CPoint point);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

public:
	void	ReceiveVideoData(char *, int);
	void	ChangeVideoWindowSize(int, int);

public:
	BOOL	m_bStill;
	int	m_no;
	class	CReceiveWnd *m_pChain;

private:
	int	m_quality;
	long	m_receiveBytes;
	long	m_receiveBytes2;
	int	m_frameCount;
	int	m_nBlockTmp;
	BOOL	m_bDraw;
	LPDINFO	m_lpDInfo;
};

/////////////////////////////////////////////////////////////////////////////

#endif // __RECEIVE_H__
