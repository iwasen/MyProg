/************************************************************************
 *
 *			ＰＣ－ＶＩＤＥＯ
 *
 *		名称		: データ受信処理
 *		ファイル名	: receive.cpp
 *		作成者		: s.aizawa
 *
 ************************************************************************/

// receive.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "resource.h"
#include "pcvcomm.h"
#include "receive.h"

#ifdef _DEBUG
#undef THIS_FILE
static char BASED_CODE THIS_FILE[] = __FILE__;
#endif

#define	IDT_2SEC	3001

static PVIDEO	pTopVideo;

PVIDEO	CreateVideo(PUSER pUser)
{
	return new CReceiveWnd(pUser);
}

VOID	DeleteVideo(PVIDEO pVideo)
{
	if (pVideo != NULL) {
		if (pVideo->m_hWnd)
			pVideo->DestroyWindow();
		else
			delete pVideo;
	}
}

/////////////////////////////////////////////////////////////////////////////
// CReceiveWnd

IMPLEMENT_DYNCREATE(CReceiveWnd, CVideoWnd)

CReceiveWnd::CReceiveWnd(PUSER pUser)
{
	PVIDEO	pVideo, *ppVideo;
	int	n;

	for (n = 0; ; n++) {
		for (pVideo = pTopVideo; pVideo != NULL; pVideo = pVideo->m_pChain) {
			if (pVideo->m_no == n)
				break;
		}
		if (pVideo == NULL)
			break;
	}

	m_lpDInfo = AllocVideoDInfo();

	for (ppVideo = &pTopVideo; *ppVideo != NULL; ppVideo = &(*ppVideo)->m_pChain)
		;
	*ppVideo = this;

	m_bResize = iniData.settings.bResize;
	m_bFixedAspect = iniData.settings.bFixedAspect;
	m_bFixedSize = iniData.settings.bFixedSize;
	m_bDispInfo = iniData.settings.bDispInfo;

	m_no = n;
	m_quality = 0;
	m_bStill = FALSE;
	m_receiveBytes = 0;
	m_receiveBytes2 = 0;
	m_frameCount = 0;
	m_nBlockTmp = 0;
	m_bDraw = FALSE;
	m_pUser = pUser;
	m_pChain = NULL;
}

CReceiveWnd::~CReceiveWnd()
{
	PVIDEO	*ppVideo;

	m_pUser->pVideo = NULL;

	for (ppVideo = &pTopVideo; *ppVideo != 0; ppVideo = &(*ppVideo)->m_pChain) {
		if ((*ppVideo) == this) {
			*ppVideo = (*ppVideo)->m_pChain;
			break;
		}
	}

	if (m_lpVideoBuf)
		delete [] m_lpVideoBuf;

	if (m_lpDInfo != NULL)
		delete m_lpDInfo;
}


BEGIN_MESSAGE_MAP(CReceiveWnd, CVideoWnd)
	//{{AFX_MSG_MAP(CReceiveWnd)
	ON_WM_CREATE()
	ON_WM_MOVE()
	ON_WM_TIMER()
	ON_WM_ACTIVATE()
	ON_WM_LBUTTONDOWN()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CReceiveWnd メッセージ ハンドラ


int CReceiveWnd::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CVideoWnd::OnCreate(lpCreateStruct) == -1)
		return -1;
	
	if (iniData.settings.bFrontKeep)
		SetWindowPos(&wndTopMost, 0, 0, 0, 0, SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);

	if (m_bDispInfo)
		SetTimer(IDT_2SEC, 2000, NULL);

	return 0;
}

void CReceiveWnd::OnMove(int x, int y)
{
	CFrameWnd::OnMove(x, y);

	CRect	rect;
	
	if (!IsIconic() && !IsZoomed()) {
		GetWindowRect(rect);
		iniData.videoWindow.left[m_no+1] = max(rect.left, 0);
		iniData.videoWindow.top[m_no+1] = max(rect.top, 0);
	}
}

void CReceiveWnd::OnTimer(UINT nIDEvent)
{
	switch (nIDEvent) {
	case IDT_2SEC:	
		m_nBPS = (int)(m_receiveBytes * 8 / 200);
		m_nFPS = m_frameCount / 2;
		m_receiveBytes = 0;
		m_frameCount &= 1;
		break;
	}
	
	CFrameWnd::OnTimer(nIDEvent);
}

void CReceiveWnd::OnLButtonDown(UINT nFlags, CPoint point) 
{
	pMainListWnd->SetCurSel(m_pUser->listIndex);
	
	CVideoWnd::OnLButtonDown(nFlags, point);
}

void	CReceiveWnd::ReceiveVideoData(char *p, int len)
{
	int	width, height, color;
	int	videoMode;
	int	block, quality;
	int	x, y, n, col, pos;
	int	qtblNo;
	char	posBuf[512];

	if (m_bStill)
		return;

	p += sizeof(HEADER);
	videoMode = *p++;
	width = *p++ * BLOCK_PIXEL;
	height = *p++ * BLOCK_PIXEL;
	if (videoMode & VIDEO_MONO)
		color = COLOR_8;
	else if (videoMode & VIDEO_FULLCOLOR)
		color = COLOR_24;
	else
		return;

	if (width != m_nWidth || height != m_nHeight)
		ChangeVideoWindowSize(width, height);

	m_receiveBytes += len;
	m_receiveBytes2 += len - sizeof(HEADER);

	len -= sizeof(HEADER) + 3;

	quality = *p++;
	block = *p++;
	m_nBlockTmp += block;

	if (quality != m_quality) {
		SetVideoDecompressQuality(m_lpDInfo, quality);
		m_quality = quality;
	}

	if (videoMode & VIDEO_LOQUALITY1)
		qtblNo = 1;
	else if (videoMode & VIDEO_LOQUALITY2)
		qtblNo = 2;
	else
		qtblNo = 0;

	if (videoMode & VIDEO_COMPXY) {
		col = width / BLOCK_PIXEL;
		n = *p++;
		DecompressRL(p, posBuf, n);
		StartVideoDecompress(m_lpDInfo, p + n, m_lpVideoBuf, m_nWidth, color, qtblNo);
		p = posBuf;
		pos = -1;
		while (block > 0) {
			if ((n = *p++) == 0) {
				n = *((short *)p);
				p += 2;
			}
			pos += n;
			x = (pos % col) * BLOCK_PIXEL;
			y = (pos / col) * BLOCK_PIXEL;
			DoVideoDecompress(m_lpDInfo, x, y);
			block--;
		}
	} else {
		StartVideoDecompress(m_lpDInfo, p + block * 2, m_lpVideoBuf, m_nWidth, color, qtblNo);
		while (block > 0) {
			x = *p++ * BLOCK_PIXEL;
			y = *p++ * BLOCK_PIXEL;
			DoVideoDecompress(m_lpDInfo, x, y);
			block--;
		}
	}

	if ((videoMode & VIDEO_DRAW) && m_hWnd) {
		m_frameCount++;
		m_nBlock = m_nBlockTmp;
		m_nBlockTmp = 0;
		m_nCompressRate = (int)
			(m_receiveBytes2 * 1000 /
			((long)m_nWidth * (long)m_nHeight *
			(long)(color + 1)));
		m_receiveBytes2 = 0;
		if (m_bDraw) {
			DrawVideoWindow();
		} else
			m_bDraw = TRUE;
	}
}

void	CReceiveWnd::ChangeVideoWindowSize(int width, int height)
{
	if (m_lpVideoBuf != NULL)
		delete [] m_lpVideoBuf;

	m_lpVideoBuf = new char[width * height * 3];

	memset(m_lpVideoBuf, 150, width * height * 3);

	SetVideoSize(width, height, COLOR_24);
	if (!m_hWnd) {
		CreateVideoWindow(m_pUser->userName,
				iniData.videoWindow.left[m_no+1],
				iniData.videoWindow.top[m_no+1]);
	}
}
