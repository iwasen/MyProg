// IACCWnd.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "Sas.h"
#include "IACCWnd.h"

/////////////////////////////////////////////////////////////////////////////
// CIACCWnd

CIACCWnd::CIACCWnd()
{
	m_bInitialize = FALSE;
}


BEGIN_MESSAGE_MAP(CIACCWnd, CWnd)
	ON_WM_PAINT()
	ON_WM_ERASEBKGND()
END_MESSAGE_MAP()


/////////////////////////////////////////////////////////////////////////////
// CIACCWnd ���b�Z�[�W �n���h��

void CIACCWnd::OnPaint()
{
	CPaintDC dc(this); // �`��p�̃f�o�C�X �R���e�L�X�g

	CBitmap *pBitmapOrg = m_dcMem.SelectObject(&m_bitmapMem);
	dc.BitBlt(0, 0, m_nWidth, m_nHeight, &m_dcMem, 0, 0, SRCCOPY);
	m_dcMem.SelectObject(pBitmapOrg);
}

BOOL CIACCWnd::OnEraseBkgnd(CDC* /*pDC*/)
{
	return TRUE;
}

void CIACCWnd::Initialize(int nFontSize, LPCTSTR pTitle, int nRemark)
{
	RECT rect;

	if (m_bInitialize)
		return;

	m_bInitialize = TRUE;

	GetClientRect(&rect);

	m_nWidth = rect.right;
	m_nHeight = rect.bottom;

	CDC *pDC = GetDC();
	m_dcMem.CreateCompatibleDC(pDC);
	m_bitmapMem.CreateCompatibleBitmap(pDC, m_nWidth, m_nHeight);
	ReleaseDC(pDC);

	m_penBlack.CreatePen(PS_SOLID, 0, RGB(0, 0, 0));
	m_penGray.CreatePen(PS_SOLID, 0, RGB(128, 128, 128));
	m_penLightGray.CreatePen(PS_SOLID, 0, RGB(192, 192, 192));
	m_penData.CreatePen(PS_SOLID, 0, RGB(255, 0, 0));
	m_penIACC.CreatePen(PS_DOT, 0, RGB(255, 0, 255));
	m_penTIACC.CreatePen(PS_DOT, 0, RGB(0, 0, 255));
	m_penWIACC.CreatePen(PS_DOT, 0, RGB(0, 255, 0));

	m_brushWhite.CreateSolidBrush(RGB(255, 255, 255));

	CString str;
	LOGFONT font;
	memset(&font, 0, sizeof(font));
	font.lfHeight = nFontSize;
	font.lfCharSet = DEFAULT_CHARSET;
	str.LoadString(IDS_DEFAULTFONT);
	strcpy_s(font.lfFaceName, str);
	m_Font.CreatePointFontIndirect(&font, &m_dcMem);
	font.lfEscapement = 900;
	m_Font2.CreatePointFontIndirect(&font, &m_dcMem);

	m_dcMem.SetBkMode(TRANSPARENT);
	m_dcMem.SetTextColor(RGB(64, 64, 64));

	CFont *pFontOrg = m_dcMem.SelectObject(&m_Font);
	TEXTMETRIC tm;
	m_dcMem.GetTextMetrics(&tm);
	m_dcMem.SelectObject(pFontOrg);

	m_nScaleLeft = tm.tmAveCharWidth * 4 + tm.tmHeight + 5;
	m_nScaleTop = 16;
	m_nScaleRight = m_nWidth - 16;
	m_nScaleBottom = m_nHeight - ((tm.tmHeight + 1) * 2 + 1);
	m_nScaleWidth = m_nScaleRight - m_nScaleLeft;
	m_nScaleHeight = m_nScaleBottom - m_nScaleTop;
	m_nScaleVCenter = m_nScaleTop + m_nScaleHeight / 2;
	m_nScaleHCenter = m_nScaleLeft + m_nScaleWidth / 2;

	m_Rgn.CreateRectRgn(m_nScaleLeft, m_nScaleTop, m_nScaleRight, m_nScaleBottom);

	if (pTitle != NULL) {
		CString str;
		REMARK_INFO remark;
		remark.nRemark = nRemark;
		remark.remarks[0].pen = &m_penData;
		remark.remarks[0].text = pTitle;
		remark.remarks[1].pen = &m_penIACC;
		remark.remarks[1].text = "IACC";
		remark.remarks[2].pen = &m_penTIACC;
		str.LoadString(IDS_TIACC);
		remark.remarks[2].text = str;
		remark.remarks[3].pen = &m_penWIACC;
		remark.remarks[3].text = "W_IACC";

		m_wndRemark.Create(this);
		m_wndRemark.DispRemarks(&remark);
	}
}

void CIACCWnd::SetBitmap()
{
	CRect rect;
	int i;
	int x, y;
	CString text;
	CSize size;

	CBitmap *pBitmapOrg = m_dcMem.SelectObject(&m_bitmapMem);
	CPen *pPenOrg = m_dcMem.SelectObject(&m_penBlack);
	CFont *pFontOrg = m_dcMem.SelectObject(&m_Font);

	rect.SetRect(0, 0, m_nWidth, m_nHeight);
	m_dcMem.FillRect(rect, &m_brushWhite);

	for (i = -10; i <= 10; i++) {
		y = m_nScaleVCenter - i * m_nScaleHeight / 20;
		if (i == -10 || i == 0 || i == 10)
			m_dcMem.SelectObject(&m_penBlack);
		else if (i == -5 || i == 5)
			m_dcMem.SelectObject(&m_penGray);
		else
			m_dcMem.SelectObject(&m_penLightGray);

		m_dcMem.MoveTo(m_nScaleLeft, y);
		m_dcMem.LineTo(m_nScaleRight, y);

		if (i % 2 == 0) {
			text.Format("%g", (double)i / 10);
			size = m_dcMem.GetOutputTextExtent(text);
			m_dcMem.TextOut(m_nScaleLeft - size.cx - 2, y - size.cy / 2, text);
		}
	}

	for (i = -10; i <= 10; i++) {
		x = m_nScaleHCenter + i * m_nScaleWidth / 20;
		if (i == -10 || i == 0 || i == 10)
			m_dcMem.SelectObject(&m_penBlack);
		else if (i == -5 || i == 5)
			m_dcMem.SelectObject(&m_penGray);
		else
			m_dcMem.SelectObject(&m_penLightGray);

		m_dcMem.MoveTo(x, m_nScaleTop + 1);
		m_dcMem.LineTo(x, m_nScaleBottom);

		if (i % 5 == 0) {
			text.Format("%g", (double)i / 10);
			size = m_dcMem.GetOutputTextExtent(text);
			m_dcMem.TextOut(x - size.cx / 2, m_nScaleBottom + 1, text);
		}
	}

	text.LoadString(IDS_TAU);
	text += " [ms]";
	size = m_dcMem.GetOutputTextExtent(text);
	m_dcMem.TextOut(m_nScaleLeft + (m_nScaleWidth - size.cx) / 2, m_nHeight - (size.cy + 1), text);

	m_dcMem.SelectObject(&m_Font2);
//	text.LoadString(IDS_CCF2);
	text = "CCF";
	size = m_dcMem.GetOutputTextExtent(text);
	m_dcMem.TextOut(1, m_nScaleTop + (m_nScaleHeight + size.cx) / 2, text);

	m_dcMem.SelectObject(pBitmapOrg);
	m_dcMem.SelectObject(pPenOrg);
	m_dcMem.SelectObject(pFontOrg);
}
void CIACCWnd::DispGraph(const double *pData, int nData, double fRate, double IACC, double TIACC, double WIACC1, double WIACC2)
{
	int i;
	int x, y;

	SetBitmap();

	if (pData == NULL) {
		Invalidate(FALSE);
		UpdateWindow();
		return;
	}

	m_dcMem.SelectClipRgn(&m_Rgn);
	CBitmap *pBitmapOrg = m_dcMem.SelectObject(&m_bitmapMem);
	CPen *pPenOrg = m_dcMem.SelectObject(&m_penIACC);

	y = (int)(m_nScaleVCenter - IACC * (m_nScaleHeight / 2) + 0.5);
	m_dcMem.MoveTo(m_nScaleLeft, y);
	m_dcMem.LineTo(m_nScaleRight, y);

	m_dcMem.SelectObject(&m_penTIACC);
	x = (int)(m_nScaleHCenter + TIACC * (m_nScaleWidth / 2) + 0.5);
	m_dcMem.MoveTo(x, m_nScaleTop);
	m_dcMem.LineTo(x, m_nScaleBottom);

	m_dcMem.SelectObject(&m_penWIACC);
	x = (int)(m_nScaleHCenter + (TIACC - WIACC1) * (m_nScaleWidth / 2) + 0.5);
	m_dcMem.MoveTo(x, m_nScaleTop);
	m_dcMem.LineTo(x, m_nScaleBottom);
	x = (int)(m_nScaleHCenter + (TIACC + WIACC2) * (m_nScaleWidth / 2) + 0.5);
	m_dcMem.MoveTo(x, m_nScaleTop);
	m_dcMem.LineTo(x, m_nScaleBottom);

	m_dcMem.SelectObject(&m_penData);

	for (i = 0; i < nData; i++) {
		x = (int)(m_nScaleHCenter + (i - nData / 2) * 1000 * (m_nScaleWidth / 2) / fRate);
		y = (int)(m_nScaleVCenter - pData[i] * m_nScaleHeight / 2 + 0.5);

		if (i == 0)
			m_dcMem.MoveTo(x, y);
		else
			m_dcMem.LineTo(x, y);
	}

	m_dcMem.SelectObject(pBitmapOrg);
	m_dcMem.SelectObject(pPenOrg);

	m_dcMem.SelectClipRgn(NULL);

	Invalidate(FALSE);
	UpdateWindow();
}
