/************************************************************************
 *
 *			ＰＣ－ＶＩＤＥＯ
 *
 *		名称		: ビデオキャプチャ処理
 *		ファイル名	: capture.cpp
 *		作成者		: s.aizawa
 *
 ************************************************************************/

// capture.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "resource.h"
#include "pcvcomm.h"
#include "mainfrm.h"
#include "capture.h"
#include <uuids.h>

#define	IDT_CAPTURE	2000
#define	IDT_1SEC	2001
#define	IDC_PUSHBUTTON	2002

#define	SENDLEVEL	500
#define	DATASIZE	512

#define	DCMONO8(_a,_b)	abs((*_a) - (*_b))
#define	DCR16(_a,_b)	(abs((_a)->red - (_b)->red) << 3)
#define	DCG16(_a,_b)	(abs((_a)->green - (_b)->green) << 3)
#define	DCB16(_a,_b)	(abs((_a)->blue - (_b)->blue) << 3)
#define	DCR24(_a,_b)	abs((_a)->red - (_b)->red)
#define	DCG24(_a,_b)	abs((_a)->green - (_b)->green)
#define	DCB24(_a,_b)	abs((_a)->blue - (_b)->blue)

HWND hCaptureWnd;

static void CaptureFrameRequest();
LRESULT PASCAL ErrorCallbackProc(HWND hWnd, int nErrID, LPSTR lpErrorText);
LRESULT PASCAL FrameCallbackProc(HWND hWnd, LPVIDEOHDR lpVHdr);

#ifdef _DEBUG
#undef THIS_FILE
static char BASED_CODE THIS_FILE[] = __FILE__;
#endif

void	OpenCaptureWindow()
{
	if (pCaptureWnd == NULL) {
		pCaptureWnd = new CCaptureWnd();
		SendConxxxtAll();
	}
}

void	CloseCaptureWindow()
{
	if (pCaptureWnd != NULL)
		pCaptureWnd->DestroyWindow();
}

/////////////////////////////////////////////////////////////////////////////
// CCaptureWnd

IMPLEMENT_DYNCREATE(CCaptureWnd, CVideoWnd)

CCaptureWnd::CCaptureWnd()
{
	m_nCaptureWidth = 0;
	m_nCaptureHeight = 0;
	m_nCaptureColor = 0;
	m_nCaptureSize = 0;
	m_nCaptureQuality = 0;
	m_nCaptureEdgeEnhance = 0;
	m_nCaptureCounter = 0;
	m_nCaptureCompression = 0;
	m_bCaptureSendInfo = FALSE;
	m_nBlockTmp = 0;
	m_nFrameCount = 0;
	m_nSendBytes = 0;
	m_nSendBytes2 = 0;
	m_lpCInfo = NULL;
	m_bFullFrame = FALSE;
	memset(m_cBuf, 0, sizeof(m_cBuf));
	m_nCx = 0;
	m_nCy = 0;
	m_bCaptureData = FALSE;
	m_nCaptureTimer = 0;
	m_bUnderBPS = FALSE;
	m_bOverBPS = FALSE;
	m_nOverBPS = 0;
	m_nTimerAdjuxxxounter = 0;
	m_nDispWidth = 0;
	m_nDispHeight = 0;
	m_lpVideoBuf = NULL;
	m_lpWorkBuf = NULL;
	m_bCaptureRequest = FALSE;
	m_hDIB = NULL;
	m_bDispInfo = iniData.settings.bSendInfo;
	m_bStopCapture = FALSE;

	if (SetCaptureMode(videoSize[iniData.settings.movieSize].width,
			videoSize[iniData.settings.movieSize].height,
			iniData.settings.videoColor)) {

		CreateVideoWindow(iniData.settings.userName,
				iniData.videoWindow.left[0], iniData.videoWindow.top[0],
				iniData.mainWindow.bSendVideo);
	}
}

CCaptureWnd::~CCaptureWnd()
{
}


BEGIN_MESSAGE_MAP(CCaptureWnd, CVideoWnd)
	//{{AFX_MSG_MAP(CCaptureWnd)
	ON_WM_CREATE()
	ON_WM_TIMER()
	ON_WM_MOVE()
	ON_WM_DESTROY()
	ON_WM_CLOSE()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CCaptureWnd メッセージ ハンドラ


int CCaptureWnd::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CVideoWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	// キャプチャウィンドウハンドルにビューウィンドウを関連づける
	capSetUserData(hCaptureWnd, (DWORD)this);

	if (iniData.settings.maxBPS != 0)
		SetCaptureTimer(TRUE);

	SetTimer(IDT_1SEC, 1000, NULL);

	if (iniData.settings.bFrontKeep)
		SetWindowPos(&wndTopMost, 0, 0, 0, 0, SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);

	if ((m_lpCInfo = AllocVideoCInfo()) == NULL)
		return FALSE;

	m_nCaptureQuality = iniData.settings.videoQuality;
	m_bFullFrame = iniData.settings.bFullFrame;
	m_nCaptureEdgeEnhance = iniData.settings.videoEdgeEnhance;
	SetVideoCompressQuality(m_lpCInfo, m_nCaptureQuality, m_nCaptureEdgeEnhance);

	return 0;
}

void CCaptureWnd::OnTimer(UINT nIDEvent)
{
	switch (nIDEvent) {
	case IDT_CAPTURE:
		TimerCaptureWindow();
		break;
	case IDT_1SEC:
		m_nBPS = (int)(m_nSendBytes * 8 / 100);
		m_nSendBytes = 0;
		m_nFPS = m_nFrameCount;
		m_nFrameCount = 0;
		if (++m_nTimerAdjuxxxounter == 5)
			SetCaptureTimer(FALSE);
		break;
	}

	CFrameWnd::OnTimer(nIDEvent);
}

void CCaptureWnd::OnMove(int x, int y)
{
	CFrameWnd::OnMove(x, y);

	CRect	rect;
	if (!IsIconic() && !IsZoomed()) {
		GetWindowRect(rect);
		iniData.videoWindow.left[0] = max(rect.left, 0);
		iniData.videoWindow.top[0] = max(rect.top, 0);
	}
}

void CCaptureWnd::OnClose() 
{
	ShowWindow(SW_HIDE);
	iniData.mainWindow.bSendVideo = FALSE;
}

void CCaptureWnd::OnDestroy()
{
	CFrameWnd::OnDestroy();

	KillTimer(IDT_CAPTURE);
	KillTimer(IDT_1SEC);
	TerminateCapture();

	pCaptureWnd = NULL;
	SendConxxxtAll();
}

BOOL	InitializeCapture()
{
    if ((hCaptureWnd = capCreateCaptureWindow ("", WS_CHILD, 0, 0, 0, 0, AfxGetMainWnd()->m_hWnd, 0)) == NULL)
		return FALSE;

	// ドライバに接続
	if (nCaptureDriver == -1) {
		for (int i = 0; i < 3; i++) {
			if (!capDriverConxxxt(hCaptureWnd, i)) {
				::DestroyWindow(hCaptureWnd);
				return FALSE;
			} else
				break;
		}
	} else {
		if (!capDriverConxxxt(hCaptureWnd, nCaptureDriver)) {
			::DestroyWindow(hCaptureWnd);
			return FALSE;
		}
	}

	// コールバック関数の設定
	capSetCallbackOnError(hCaptureWnd, ErrorCallbackProc);
	capSetCallbackOnFrame(hCaptureWnd, FrameCallbackProc);

	InitializeCamera();

	if (!InitVideoCompress())
		return FALSE;

	return TRUE;
}

void	CCaptureWnd::TerminateCapture()
{
	if (hCaptureWnd != NULL) {
		capDriverDisconxxxt(hCaptureWnd);
		::DestroyWindow(hCaptureWnd);
		hCaptureWnd = NULL;
	}

	if (m_lpCInfo != NULL) {
		delete m_lpCInfo;
		m_lpCInfo = NULL;
	}

	FreeVideoBuffer();

	if (m_lpWorkBuf != NULL) {
		delete [] m_lpWorkBuf;
		m_lpWorkBuf = NULL;
	}
}

BOOL	CCaptureWnd::SetCaptureMode(int width, int height, int color)
{
	char	msgBuf[100];
	static	BOOL	bSetCapturePalette;
	static DWORD compression[3] = {0x32595559, 0x32315659, 0x39555659};
	int i;

	m_nCaptureWidth = width;
	m_nCaptureHeight = height;
	m_nCaptureColor = color;
	m_nCaptureSize = (width * height) * (m_nCaptureColor + 1);
	m_nCaptureCounter = 0;
	m_bCaptureSendInfo = iniData.settings.bSendInfo;

	if (m_nCaptureColor == COLOR_8 && !bSetCapturePalette) {
		capPaletteOpen(hCaptureWnd, "PCVIDEO.PAL");
		bSetCapturePalette = TRUE;
	}

	memset(&m_CaptureFormat, 0, sizeof(m_CaptureFormat));
	m_CaptureFormat.biSize = sizeof(BITMAPINFOHEADER);
	capGetVideoFormat(hCaptureWnd, &m_CaptureFormat, sizeof(m_CaptureFormat));
	m_CaptureFormat.biWidth = m_nCaptureWidth;
	m_CaptureFormat.biHeight = m_nCaptureHeight;
	m_CaptureFormat.biSizeImage = m_nCaptureSize;
	m_CaptureFormat.biPlanes = 1;
	m_CaptureFormat.biBitCount = colorTable[m_nCaptureColor];
	m_CaptureFormat.biCompression = 0;

	if (!capSetVideoFormat(hCaptureWnd, &m_CaptureFormat, sizeof(m_CaptureFormat))) {
		for (i = 0; i < 3; i++) {
			m_CaptureFormat.biCompression = compression[i];
			if (capSetVideoFormat(hCaptureWnd, &m_CaptureFormat, sizeof(m_CaptureFormat)))
				break;
		}
		if (i == 3) {	
			AfxMessageBox(IDS_IMAGE_SIZE);
			capGetVideoFormat(hCaptureWnd, &m_CaptureFormat, sizeof(m_CaptureFormat));
			m_nCaptureWidth = m_CaptureFormat.biWidth;
			m_nCaptureHeight = m_CaptureFormat.biHeight;
			switch (m_CaptureFormat.biBitCount) {
			case 8:
				m_nCaptureColor = 0;
				break;
			case 16:
				m_nCaptureColor = 1;
				break;
			default:
				m_nCaptureColor = 2;
				break;
			}
			m_nCaptureSize = m_CaptureFormat.biSizeImage;
		}
	}

	m_nCaptureCompression = m_CaptureFormat.biCompression;

	FreeVideoBuffer();

	if (m_nCaptureCompression == 0) {
		m_lpVideoBuf = new char[m_nCaptureSize];
		if (m_lpVideoBuf == NULL) {
			wsprintf(msgBuf, "GlobalLock error");
			MessageBox(msgBuf, title);
			return FALSE;
		}
	}

	if (m_lpWorkBuf != NULL)
		delete [] m_lpWorkBuf;

	m_lpWorkBuf = new char[m_nCaptureSize];
	if (m_lpWorkBuf == NULL) {
		wsprintf(msgBuf, "GlobalLock error");
		MessageBox(msgBuf, title);
		return FALSE;
	}

//	DispInfo(bCaptureSendInfo);
	SetVideoSize(m_nCaptureWidth, m_nCaptureHeight, m_nCaptureColor);

	return TRUE;
}

void	CCaptureWnd::SetCaptureTimer(BOOL bInit)
{
	UINT	nTimer;

	m_nTimerAdjuxxxounter = 0;
	nTimer = m_nCaptureTimer;

	if (bInit) {
		if (iniData.settings.maxBPS != 0) {
			m_nCaptureTimer = iniData.settings.bMaxSpeed ? 1 : 2000 / iniData.settings.maxBPS;
		}
	} else if (!iniData.settings.bMaxSpeed) {
		if (m_bUnderBPS) {
			if (m_nCaptureTimer > 30)
				m_nCaptureTimer -= m_nCaptureTimer / 10;
		} else if (m_bOverBPS)
			m_nCaptureTimer += m_nCaptureTimer / 10 + 1;
	}

	m_bUnderBPS = TRUE;
	m_bOverBPS = TRUE;

	if (m_nCaptureTimer != nTimer) {
		KillTimer(IDT_CAPTURE);
		SetTimer(IDT_CAPTURE, m_nCaptureTimer, NULL);
	}
}


void	CCaptureWnd::TimerCaptureWindow()
{
	PSEND	pSend;
	PUSER	pUser;
	int	len;
	char	sendBuf[1024];
	BOOL	bVideoSendFlag;

	if (!iniData.settings.bMaxSpeed) {
		if ((int)(m_nSendBytes * 8 / 1000) + 3 >=
						iniData.settings.maxBPS) {
			m_nOverBPS++;
			return;
		}
	}

	if (m_bCaptureRequest)
		return;

	if (!m_bCaptureData) {
		if (bCaptureStill && m_bFullFrame) {
			m_nTimerAdjuxxxounter = 0;
			return;
		}

		if (bChangeCaptureMode) {
			bChangeCaptureMode = FALSE;
			memset(m_cBuf, SENDLEVEL, sizeof(m_cBuf));

			SetCaptureTimer(TRUE);

			if (bCaptureStill) {
				SetCaptureMode(videoSize[iniData.settings.stillSize].width,
						videoSize[iniData.settings.stillSize].height,
						iniData.settings.videoColor);
				m_nFPS = m_nBPS = 0;
			} else {
				SetCaptureMode(videoSize[iniData.settings.movieSize].width,
						videoSize[iniData.settings.movieSize].height,
						iniData.settings.videoColor);
			}

			if (m_nCaptureQuality !=
					iniData.settings.videoQuality ||
					m_nCaptureEdgeEnhance !=
					iniData.settings.videoEdgeEnhance) {
				m_nCaptureQuality = iniData.settings.videoQuality;
				m_nCaptureEdgeEnhance = iniData.settings.videoEdgeEnhance;
				SetVideoCompressQuality(m_lpCInfo, m_nCaptureQuality, m_nCaptureEdgeEnhance);
			}
		}

		if (bNewVideoUser) {
			m_bFullFrame = TRUE;
			bNewVideoUser = FALSE;
		} else {
			if (bCaptureStill)
				m_bFullFrame = TRUE;
			else
				m_bFullFrame = iniData.settings.bFullFrame;
		}

		CaptureFrameRequest();
		return;
	}

	bVideoSendFlag = FALSE;
	if (IsWindowVisible()) {
		for (pSend = pTopSend; pSend != NULL; pSend = pSend->pChain) {
			for (pUser = pSend->pUser; pUser != NULL; pUser = pUser->pSendUser) {
				if (pUser->bSendVideo) {
					pSend->bVideoSendFlag = TRUE;
					bVideoSendFlag = TRUE;
					break;
				}
			}
		}
	}

	if (!bVideoSendFlag) {
		m_bCaptureData = FALSE;
		return;
	}

	switch (m_nCaptureColor) {
	case COLOR_8:
		len = Capture8(sendBuf);
		break;
	case COLOR_16:
		len = Capture16(sendBuf);
		break;
	case COLOR_24:
		len = Capture24(sendBuf);
		break;
	}

	for (pSend = pTopSend; pSend != NULL; pSend = pSend->pChain) {
		if (pSend->bVideoSendFlag) {
			SendData(&pSend->sock, sendBuf, len);
			pSend->bVideoSendFlag = FALSE;
		}
	}

	m_nSendBytes += len;
	m_nSendBytes2 += len - sizeof(HEADER);
}

void	CCaptureWnd::CaptureFrame(LPVIDEOHDR lpVHdr)
{
	m_bCaptureRequest = FALSE;

	if (m_bStopCapture)
		return;

	if (m_nCaptureCompression == 0)
		memcpy(m_lpVideoBuf, lpVHdr->lpData, lpVHdr->dwBytesUsed);
	else {
		if (m_hDIB != NULL) {
			::GlobalUnlock(m_hDIB);
			::GlobalFree(m_hDIB);
		}
		BITMAPINFOHEADER bmi;
		bmi = m_CaptureFormat;
		bmi.biCompression = 0;
		m_hDIB = ICImageDecompress(NULL, 0, (LPBITMAPINFO)&m_CaptureFormat, lpVHdr->lpData, (LPBITMAPINFO)&bmi);
		if (m_hDIB != NULL)
			m_lpVideoBuf = (LPSTR)::GlobalLock(m_hDIB) + sizeof(BITMAPINFOHEADER);
	}

	m_nCaptureCounter++;

	m_bCaptureData = TRUE;
	m_nCx = m_nCy = 0;

	if (m_nOverBPS == 0)
		m_bOverBPS = FALSE;
	else
		m_bUnderBPS = FALSE;
	m_nOverBPS = 0;

	DrawVideoWindow();

	m_nFrameCount++;
	m_nBlock = m_nBlockTmp;
	m_nBlockTmp = 0;
	m_nCompressRate = (int)(m_nSendBytes2 * 1000 / m_nCaptureSize);
	m_nSendBytes2 = 0;
}

int	CCaptureWnd::Capture8(char *sendBuf)
{
	LPMONO8	bufp1, bufp2;
	int	*cBufp;
	char	dataBuf[1024];
	char	posBuf[512], *posBufp;
	int	x, y, pos, pos2, len;
	long	offset;
	int	n;
	char	*sendBufp;
	int	blockWidth, blockHeight;
	int	block;
	static	int	qtblNo;
	static	int	resetVal;

	blockWidth = m_nCaptureWidth / BLOCK_PIXEL;
	blockHeight = m_nCaptureHeight / BLOCK_PIXEL;

	if (m_nCx == 0 && m_nCy == 0) {
		if (m_bFullFrame) {
			qtblNo = 0;
			resetVal = 0;
		} else {
			bufp1 = (LPMONO8)m_lpVideoBuf;
			bufp2 = (LPMONO8)m_lpWorkBuf;
			for (y = 0; y < m_nCaptureHeight; y += 2) {
				for (x = 0; x < m_nCaptureWidth; x += 2) {
					n = DCMONO8(bufp1, bufp2) * 3;

					cBufp = &m_cBuf[y / BLOCK_PIXEL][x / BLOCK_PIXEL];
					*cBufp += n * n / 80;

					if (*cBufp < 0)
						*cBufp = 32767;

					bufp1 += 2;
					bufp2 += 2;
				}
				if (y & 2) {
					bufp1 += m_nCaptureWidth - 1;
					bufp2 += m_nCaptureWidth - 1;
				} else {
					bufp1 += m_nCaptureWidth + 1;
					bufp2 += m_nCaptureWidth + 1;
				}
			}

			block = 0;
			for (y = 0; y < blockHeight; y++) {
				for (x = 0; x < blockWidth; x++) {
					if (m_cBuf[y][x] >= SENDLEVEL)
						block++;
				}
			}
			n = blockWidth * blockHeight;
			if (block > n / 4) {
				if (!iniData.settings.bCameraChange ||
						block < (int)(n * 9U / 10U)) {
					if (block > n / 2)
						qtblNo = 2;
					else
						qtblNo = 1;
					resetVal = SENDLEVEL / 2;
				} else {
					qtblNo = 0;
					resetVal = 0;
				}
			} else {
				qtblNo = 0;
				resetVal = 0;
			}
		}
	}

	bufp1 = (LPMONO8)m_lpVideoBuf;
	bufp2 = (LPMONO8)m_lpWorkBuf;

	((HEADER *)sendBuf)->flag = FLAG_VIDEO;
	((HEADER *)sendBuf)->id = localIPAddress;

	sendBufp = sendBuf + sizeof(HEADER);
	switch (qtblNo) {
	case 1:
		*sendBufp++ = (char)(VIDEO_MONO | VIDEO_COMPXY | VIDEO_LOQUALITY1);
		break;
	case 2:
		*sendBufp++ = (char)(VIDEO_MONO | VIDEO_COMPXY | VIDEO_LOQUALITY2);
		break;
	default:
		*sendBufp++ = (char)(VIDEO_MONO | VIDEO_COMPXY);
		break;
	}
	*sendBufp++ = (char)blockWidth;
	*sendBufp++ = (char)blockHeight;
	*sendBufp++ = (char)m_nCaptureQuality;

	block = 0;
	posBufp = posBuf;
	pos2 = -1;
	len = 0;
	StartVideoCompress(m_lpCInfo, dataBuf, m_lpVideoBuf, m_nCaptureWidth, m_nCaptureColor, qtblNo);

	for ( ; m_nCy < blockHeight; m_nCy++) {
		for ( ; m_nCx < blockWidth; m_nCx++) {
			if (m_cBuf[m_nCy][m_nCx] >= SENDLEVEL || m_bFullFrame) {
				if (len + block > DATASIZE || block == 255)
					goto send;

				x = m_nCx * BLOCK_PIXEL;
				y = m_nCy * BLOCK_PIXEL;
				len = DoVideoCompress(m_lpCInfo, x, y);

				pos = m_nCy * blockWidth + m_nCx;
				if ((n = pos - pos2) < 256)
					*posBufp++ = n;
				else {
					*posBufp++ = 0;
					*((short *)posBufp) = n;
					posBufp += 2;
				}
				pos2 = pos;

				block++;

				offset = ((long)y * m_nCaptureWidth + x);
				for (y = 0; y < BLOCK_PIXEL; y++) {
					memcpy(&bufp2[offset], &bufp1[offset], BLOCK_PIXEL);
					offset += m_nCaptureWidth;
				}

				m_cBuf[m_nCy][m_nCx] = resetVal;
			}
		}
		m_nCx = 0;
	}

	m_bCaptureData = FALSE;
	sendBuf[sizeof(HEADER)] |= VIDEO_DRAW;
send:
	*sendBufp++ = block;

	len = CompressRL(posBuf, sendBufp + 1, posBufp - posBuf);
	*sendBufp++ = (char)len;
	sendBufp += len;

	len = FinishVideoCompress(m_lpCInfo);
	memcpy(sendBufp, dataBuf, len);
	sendBufp += len;

	m_nBlockTmp += block;
	return(sendBufp - sendBuf);
}

int	CCaptureWnd::Capture16(char *sendBuf)
{
	LPRGB16	bufp1, bufp2;
	int	*cBufp;
	char	dataBuf[1024];
	char	posBuf[512], *posBufp;
	int	x, y, pos, pos2, len;
	long	offset;
	int	n;
	char	*sendBufp;
	int	blockWidth, blockHeight;
	int	block;
	static	int	qtblNo;
	static	int	resetVal;

	blockWidth = m_nCaptureWidth / BLOCK_PIXEL;
	blockHeight = m_nCaptureHeight / BLOCK_PIXEL;

	if (m_nCx == 0 && m_nCy == 0) {
		if (m_bFullFrame) {
			qtblNo = 0;
			resetVal = 0;
		} else {
			bufp1 = (LPRGB16)m_lpVideoBuf;
			bufp2 = (LPRGB16)m_lpWorkBuf;
			for (y = 0; y < m_nCaptureHeight; y += 2) {
				for (x = 0; x < m_nCaptureWidth; x += 2) {
					n = DCR16(bufp1, bufp2) +
							DCG16(bufp1, bufp2) +
							DCB16(bufp1, bufp2);

					cBufp = &m_cBuf[y / BLOCK_PIXEL][x / BLOCK_PIXEL];
					*cBufp += n * n / 80;

					if (*cBufp < 0)
						*cBufp = 32767;

					bufp1 += 2;
					bufp2 += 2;
				}
				if (y & 2) {
					bufp1 += m_nCaptureWidth - 1;
					bufp2 += m_nCaptureWidth - 1;
				} else {
					bufp1 += m_nCaptureWidth + 1;
					bufp2 += m_nCaptureWidth + 1;
				}
			}

			block = 0;
			for (y = 0; y < blockHeight; y++) {
				for (x = 0; x < blockWidth; x++) {
					if (m_cBuf[y][x] >= SENDLEVEL)
						block++;
				}
			}
			n = blockWidth * blockHeight;
			if (block > n / 4) {
				if (!iniData.settings.bCameraChange ||
						block < (int)(n * 9U / 10U)) {
					if (block > n / 2)
						qtblNo = 2;
					else
						qtblNo = 1;
					resetVal = SENDLEVEL / 2;
				} else {
					qtblNo = 0;
					resetVal = 0;
				}
			} else {
				qtblNo = 0;
				resetVal = 0;
			}
		}
	}

	bufp1 = (LPRGB16)m_lpVideoBuf;
	bufp2 = (LPRGB16)m_lpWorkBuf;

	((HEADER *)sendBuf)->flag = FLAG_VIDEO;
	((HEADER *)sendBuf)->id = localIPAddress;

	sendBufp = sendBuf + sizeof(HEADER);
	switch (qtblNo) {
	case 1:
		*sendBufp++ = (char)(VIDEO_FULLCOLOR | VIDEO_COMPXY | VIDEO_LOQUALITY1);
		break;
	case 2:
		*sendBufp++ = (char)(VIDEO_FULLCOLOR | VIDEO_COMPXY | VIDEO_LOQUALITY2);
		break;
	default:
		*sendBufp++ = (char)(VIDEO_FULLCOLOR | VIDEO_COMPXY);
		break;
	}
	*sendBufp++ = (char)blockWidth;
	*sendBufp++ = (char)blockHeight;
	*sendBufp++ = (char)m_nCaptureQuality;

	block = 0;
	posBufp = posBuf;
	pos2 = -1;
	len = 0;
	StartVideoCompress(m_lpCInfo, dataBuf, m_lpVideoBuf, m_nCaptureWidth, m_nCaptureColor, qtblNo);

	for ( ; m_nCy < blockHeight; m_nCy++) {
		for ( ; m_nCx < blockWidth; m_nCx++) {
			if (m_cBuf[m_nCy][m_nCx] >= SENDLEVEL || m_bFullFrame) {
				if (len + block > DATASIZE || block == 255)
					goto send;

				x = m_nCx * BLOCK_PIXEL;
				y = m_nCy * BLOCK_PIXEL;
				len = DoVideoCompress(m_lpCInfo, x, y);

				pos = m_nCy * blockWidth + m_nCx;
				if ((n = pos - pos2) < 256)
					*posBufp++ = n;
				else {
					*posBufp++ = 0;
					*((short *)posBufp) = n;
					posBufp += 2;
				}
				pos2 = pos;

				block++;

				offset = ((long)y * m_nCaptureWidth + x);
				for (y = 0; y < BLOCK_PIXEL; y++) {
					memcpy(&bufp2[offset], &bufp1[offset], BLOCK_PIXEL * 2);
					offset += m_nCaptureWidth;
				}

				m_cBuf[m_nCy][m_nCx] = resetVal;
			}
		}
		m_nCx = 0;
	}

	m_bCaptureData = FALSE;
	sendBuf[sizeof(HEADER)] |= VIDEO_DRAW;
send:
	*sendBufp++ = block;

	len = CompressRL(posBuf, sendBufp + 1, posBufp - posBuf);
	*sendBufp++ = (char)len;
	sendBufp += len;

	len = FinishVideoCompress(m_lpCInfo);
	memcpy(sendBufp, dataBuf, len);
	sendBufp += len;

	m_nBlockTmp += block;
	return(sendBufp - sendBuf);
}

int	CCaptureWnd::Capture24(char *sendBuf)
{
	LPRGB24	bufp1, bufp2;
	int	*cBufp;
	char	dataBuf[1024];
	char	posBuf[512], *posBufp;
	int	x, y, pos, pos2, len;
	long	offset;
	int	n;
	char	*sendBufp;
	int	blockWidth, blockHeight;
	int	block;
	static	int	qtblNo;
	static	int	resetVal;

	blockWidth = m_nCaptureWidth / BLOCK_PIXEL;
	blockHeight = m_nCaptureHeight / BLOCK_PIXEL;

	if (m_nCx == 0 && m_nCy == 0) {
		if (m_bFullFrame) {
			qtblNo = 0;
			resetVal = 0;
		} else {
			bufp1 = (LPRGB24)m_lpVideoBuf;
			bufp2 = (LPRGB24)m_lpWorkBuf;
			for (y = 0; y < m_nCaptureHeight; y += 2) {
				for (x = 0; x < m_nCaptureWidth; x += 2) {
					n = DCR24(bufp1, bufp2) +
							DCG24(bufp1, bufp2) +
							DCB24(bufp1, bufp2);

					cBufp = &m_cBuf[y / BLOCK_PIXEL][x / BLOCK_PIXEL];
					*cBufp += n * n / 80;

					if (*cBufp < 0)
						*cBufp = 32767;

					bufp1 += 2;
					bufp2 += 2;
				}
				if (y & 2) {
					bufp1 += m_nCaptureWidth - 1;
					bufp2 += m_nCaptureWidth - 1;
				} else {
					bufp1 += m_nCaptureWidth + 1;
					bufp2 += m_nCaptureWidth + 1;
				}
			}

			block = 0;
			for (y = 0; y < blockHeight; y++) {
				for (x = 0; x < blockWidth; x++) {
					if (m_cBuf[y][x] >= SENDLEVEL)
						block++;
				}
			}
			n = blockWidth * blockHeight;
			if (block > n / 4) {
				if (!iniData.settings.bCameraChange ||
						block < (int)(n * 9U / 10U)) {
					if (block > n / 2)
						qtblNo = 2;
					else
						qtblNo = 1;
					resetVal = SENDLEVEL / 2;
				} else {
					qtblNo = 0;
					resetVal = 0;
				}
			} else {
				qtblNo = 0;
				resetVal = 0;
			}
		}
	}

	bufp1 = (LPRGB24)m_lpVideoBuf;
	bufp2 = (LPRGB24)m_lpWorkBuf;

	((HEADER *)sendBuf)->flag = FLAG_VIDEO;
	((HEADER *)sendBuf)->id = localIPAddress;

	sendBufp = sendBuf + sizeof(HEADER);
	switch (qtblNo) {
	case 1:
		*sendBufp++ = (char)
			(VIDEO_FULLCOLOR | VIDEO_COMPXY | VIDEO_LOQUALITY1);
		break;
	case 2:
		*sendBufp++ = (char)
			(VIDEO_FULLCOLOR | VIDEO_COMPXY | VIDEO_LOQUALITY2);
		break;
	default:
		*sendBufp++ = (char)(VIDEO_FULLCOLOR | VIDEO_COMPXY);
		break;
	}
	*sendBufp++ = (char)blockWidth;
	*sendBufp++ = (char)blockHeight;
	*sendBufp++ = (char)m_nCaptureQuality;

	block = 0;
	posBufp = posBuf;
	pos2 = -1;
	len = 0;
	StartVideoCompress(m_lpCInfo, dataBuf, m_lpVideoBuf, m_nCaptureWidth, m_nCaptureColor, qtblNo);
	for ( ; m_nCy < blockHeight; m_nCy++) {
		for ( ; m_nCx < blockWidth; m_nCx++) {
			if (m_cBuf[m_nCy][m_nCx] >= SENDLEVEL || m_bFullFrame) {
				if (len + block > DATASIZE || block == 255)
					goto send;

				x = m_nCx * BLOCK_PIXEL;
				y = m_nCy * BLOCK_PIXEL;
				len = DoVideoCompress(m_lpCInfo, x, y);

				pos = m_nCy * blockWidth + m_nCx;
				if ((n = pos - pos2) < 256)
					*posBufp++ = n;
				else {
					*posBufp++ = 0;
					*((short *)posBufp) = n;
					posBufp += 2;
				}
				pos2 = pos;

				block++;

				offset = ((long)y * m_nCaptureWidth + x);
				for (y = 0; y < BLOCK_PIXEL; y++) {
					memcpy(&bufp2[offset], &bufp1[offset], BLOCK_PIXEL * 3);
					offset += m_nCaptureWidth;
				}

				m_cBuf[m_nCy][m_nCx] = resetVal;
			}
		}
		m_nCx = 0;
	}

	m_bCaptureData = FALSE;
	sendBuf[sizeof(HEADER)] |= VIDEO_DRAW;
send:
	*sendBufp++ = block;

	len = CompressRL(posBuf, sendBufp + 1, posBufp - posBuf);
	*sendBufp++ = (char)len;
	sendBufp += len;

	len = FinishVideoCompress(m_lpCInfo);
	memcpy(sendBufp, dataBuf, len);
	sendBufp += len;

	m_nBlockTmp += block;
	return(sendBufp - sendBuf);
}

#define	ERROR_CM_NOCAPTURE	1016
#define	ERROR_CM_MEMORY		1017
static	BOOL	AllocCaptureBuf(PUSER, long);

void	ReceiveCaptureCommand(PUSER pUser, PREMOTE_CAPTURE_CMD pCaptureCommand)
{
/*
	PCAPTUREBUF pCaptureBuf;
	int	width, height;
	long	size;
	REMOTE_CAPTURE_RSP	captureResponse;

	captureResponse.remote.header.flag = FLAG_REMOTE;
	captureResponse.remote.header.IPAddress = localIPAddress;

	captureResponse.remote.type = REMOTE_CAPTURE_RESPONSE;
	captureResponse.remote.remoteIPAddress = pUser->sock.IPAddress;

	if (pCaptureWnd == NULL) {
		captureResponse.errorCode = ERROR_CM_NOCAPTURE;
		captureResponse.dataSize = 0;
	} else {
		width = videoSize[pCaptureCommand->size].width;
		height = videoSize[pCaptureCommand->size].height;
		size = (long)width * (long)height *
					(long)(pCaptureCommand->color + 1);

		if (AllocCaptureBuf(pUser, size)) {
			pCaptureBuf = pUser->pCaptureBuf;
			pCaptureWnd->SetCaptureMode(
				videoSize[pCaptureCommand->size].width,
				videoSize[pCaptureCommand->size].height,
				pCaptureCommand->color);

			if (captureCounter != 0 && captureCounter != pCaptureBuf->captureCounter) {
				memcpy(pCaptureBuf->lpVideoHdr->lpData, lpVideoHdr->lpData, size);
				pCaptureBuf->captureCounter = captureCounter;
			} else
//				CaptureFrame(hVideo, pCaptureBuf->lpVideoHdr);

			captureResponse.errorCode = 0;
			captureResponse.dataSize = size;
		} else {
			captureResponse.errorCode = ERROR_CM_MEMORY;
			captureResponse.dataSize = 0;
		}
	}

	if (SendData(&pUser->sock, &captureResponse,
				sizeof(captureResponse)) !=
				sizeof(captureResponse))
		return;

	if (captureResponse.dataSize != 0) {
		pCaptureBuf->sendSize = captureResponse.dataSize;
		pCaptureBuf->lpSendBuf = (LPSTR)pCaptureBuf->lpVideoHdr->lpData;

		SendCaptureData(pUser);
	}
*/
}

static	BOOL	AllocCaptureBuf(PUSER pUser, long size)
{
/*
	PCAPTUREBUF pCaptureBuf;

	if (pUser->pCaptureBuf == NULL) {
		if ((pUser->pCaptureBuf = new CAPTUREBUF) == NULL)
			return FALSE;

		memset(pUser->pCaptureBuf, 0, sizeof(CAPTUREBUF));
	}

	pCaptureBuf = pUser->pCaptureBuf;

	if (pCaptureBuf->lpVideoHdr == NULL) {
		pCaptureBuf->hVideoHdr = GlobalAlloc(GHND | GMEM_SHARE, sizeof(VIDEOHDR));
		if (pCaptureBuf->hVideoHdr == NULL)
			return FALSE;

		pCaptureBuf->lpVideoHdr = (LPVIDEOHDR)GlobalLock(pCaptureBuf->hVideoHdr);
	}

	if (size != pCaptureBuf->size) {
		if (pCaptureBuf->hData != NULL) {
			GlobalUnlock(pCaptureBuf->hData);
			GlobalFree(pCaptureBuf->hData);
		}

		pCaptureBuf->hData = GlobalAlloc(GMEM_MOVEABLE | GMEM_SHARE, size);
		if (pCaptureBuf->hData == NULL)
			return FALSE;

		pCaptureBuf->lpVideoHdr->lpData = (LPBYTE)GlobalLock(pCaptureBuf->hData);
		pCaptureBuf->lpVideoHdr->dwBufferLength = size;
	}
*/
	return TRUE;
}

void CCaptureWnd::CaptureFrameRequest()
{
	m_bCaptureRequest = TRUE;
	capGrabFrame(hCaptureWnd);	// １フレーム取り込み
}

// エラーコールバック関数
LRESULT PASCAL ErrorCallbackProc(HWND hWnd, int nErrID, LPSTR lpErrorText)
{
	return (LRESULT)TRUE;
}

// フレームコールバック関数
LRESULT PASCAL FrameCallbackProc(HWND hWnd, LPVIDEOHDR lpVHdr)
{
	CCaptureWnd *pWnd = (CCaptureWnd *)capGetUserData(hWnd);

	if (pWnd != NULL)
		pWnd->CaptureFrame(lpVHdr);

	return (LRESULT)TRUE;
}

void CCaptureWnd::FreeVideoBuffer()
{
	if (m_hDIB == NULL) {
		if(m_lpVideoBuf != NULL) {
			delete [] m_lpVideoBuf;
			m_lpVideoBuf = NULL;
		}
	} else {
		::GlobalUnlock(m_hDIB);
		::GlobalFree(m_hDIB);
		m_hDIB = NULL;
		m_lpVideoBuf = NULL;
	}
}
