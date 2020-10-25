#include "stdafx.h"
#include "MMView.h"

char g_SectionSettings[] = "Settings";
char g_SectionMainWindow[] = "MainWindow";
char g_SectionDetails[] = "Details";
CString g_StatusInfo;
CString g_PageInfo;
CImageList g_ImageList;
CImageList g_ImageList2;

void FormatDate(CString &strIn, CString &strOut)
{
	LPCTSTR pDate = strIn;

	strOut.Format("%.4s/%.2s/%.2s %.2s:%.2s",
			pDate, pDate + 4, pDate + 6,
			pDate + 8, pDate + 10);
}

HANDLE CopyHandle(HANDLE h)
{
	BYTE *lpCopy;
	BYTE *lp;
	HANDLE     hCopy;
	DWORD      dwLen;

	if (h == NULL)
		return NULL;

	dwLen = (DWORD)::GlobalSize((HGLOBAL) h);

	if ((hCopy = (HANDLE) ::GlobalAlloc (GHND, dwLen)) != NULL)
	{
		lpCopy = (BYTE *) ::GlobalLock((HGLOBAL) hCopy);
		lp     = (BYTE *) ::GlobalLock((HGLOBAL) h);

		while (dwLen--)
			*lpCopy++ = *lp++;

		::GlobalUnlock((HGLOBAL) hCopy);
		::GlobalUnlock((HGLOBAL) h);
	}

	return hCopy;
}

void PrintPicture(CDC *pDC, HDIB hDIB)
{
	short nSrcWidth, nSrcHeight, nColor;
	int nDstWidth, nDstHeight;

	if (hDIB == NULL)
		return;

	MMPictureGetInfo(hDIB, &nSrcWidth, &nSrcHeight, &nColor);

	int nDevWidth = pDC->GetDeviceCaps(HORZRES);
	int nDevHeight = pDC->GetDeviceCaps(VERTRES);

	nDstWidth = nDevWidth;
	nDstHeight = nSrcHeight * nDevWidth / nSrcWidth;
	if (nDstHeight > nDevHeight) {
		nDstWidth = nSrcWidth * nDevHeight / nSrcHeight;
		nDstHeight = nDevHeight;
	}

	MMPictureDraw(hDIB, pDC->m_hDC, 0, 0, nDstWidth, nDstHeight, 0, 0, nSrcWidth, nSrcHeight);
}

HDIB MakeSmallPicture(HDIB hDIB1, int width, int height)
{
	HDIB hDIB2;
	short width1, height1, color;

	if (MMPictureGetInfo(hDIB1, &width1, &height1, &color) != 0)
		return NULL;

	if (height1 * width / width1 < height)
		height = height1 * width / width1;
	else
		width = width1 * height / height1;

	if (MMPictureCopy(hDIB1, &hDIB2, width, height) != 0)
		return NULL;

	return hDIB2;
}

int CompareTitle(CString &str1, CString &str2)
{
	int n1, n2;
	int n;
	static char num[] = "0123456789";

	n1 = str1.FindOneOf(num);
	n2 = str2.FindOneOf(num);

	if (n1 != -1 && n1 == n2) {
		n = str1.Left(n1).CompareNoCase(str2.Left(n2));
		if (n == 0)
			n = atoi(str1.Mid(n1)) - atoi(str2.Mid(n2));
		return n;
	} else
		return str1.CompareNoCase(str2);
}

CString &GetString(UINT nID, int nIndex)
{
	static CString str[2];

	str[nIndex].LoadString(nID);
	return str[nIndex];
}
