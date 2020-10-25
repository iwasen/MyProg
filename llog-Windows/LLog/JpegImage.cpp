// JpegImage.cpp : 実装ファイル
//

#include "stdafx.h"
#include "LLog.h"
#include "JpegImage.h"
#include "General.h"


// CJpegImage

IMPLEMENT_DYNAMIC(CJpegImage, CStatic)

CJpegImage::CJpegImage()
{
	m_bOriginal = FALSE;
	m_bPreloadOriginal = FALSE;
}


BEGIN_MESSAGE_MAP(CJpegImage, CStatic)
END_MESSAGE_MAP()


// CJpegImage メッセージ ハンドラ

void CJpegImage::DrawItem(LPDRAWITEMSTRUCT lpDrawItemStruct)
{
	if (!m_oImage.IsNull()) {
		int nWidth = m_oImage.GetWidth();
		int nHeight = m_oImage.GetHeight();

		CRect rectImage;
//		if (m_bOriginal) {
//		} else {
//			if (m_bFitting) {
//				rectImage = lpDrawItemStruct->rcItem;
//			} else {
				CSize sizeImage;
				if (nWidth <= lpDrawItemStruct->rcItem.right && nHeight <= lpDrawItemStruct->rcItem.bottom) {
					sizeImage.cx = nWidth;
					sizeImage.cy = nHeight;
				} else {
					double fWidthRatio = (double)lpDrawItemStruct->rcItem.right / nWidth;
					double fHeightRatio = (double)lpDrawItemStruct->rcItem.bottom / nHeight;
					double fMinRatio = min(fWidthRatio, fHeightRatio);
					sizeImage.cx = (int)(nWidth * fMinRatio);
					sizeImage.cy = (int)(nHeight * fMinRatio);
				}
				CPoint pointImage((lpDrawItemStruct->rcItem.right - sizeImage.cx) / 2, (lpDrawItemStruct->rcItem.bottom - sizeImage.cy) / 2);
				rectImage = CRect(pointImage, sizeImage);
//			}

			if (nWidth == rectImage.Width() && nHeight == rectImage.Height())
				m_oImage.BitBlt(lpDrawItemStruct->hDC, rectImage.left, rectImage.top);
			else {
				::SetStretchBltMode(lpDrawItemStruct->hDC, HALFTONE);
				m_oImage.StretchBlt(lpDrawItemStruct->hDC, rectImage);
			}
//		}

		::ExcludeClipRect(lpDrawItemStruct->hDC, rectImage.left, rectImage.top, rectImage.right, rectImage.bottom);
	}

	::FillRect(lpDrawItemStruct->hDC, &lpDrawItemStruct->rcItem, (HBRUSH)::GetStockObject(GRAY_BRUSH));
}

void CJpegImage::DispJpeg(LPCTSTR pFolderName, LPCTSTR pFileName, const CTime &oTime, BOOL bOriginal)
{
	Clear();

//	m_bFitting = bFitting;
	m_bOriginal = bOriginal;

	if (pFolderName == m_sPreloadFolderName && pFileName == m_sPreloadFileName && bOriginal == m_bPreloadOriginal) {
		m_oImage.Attach(m_oPreloadImage.Detach());
		m_sPreloadFolderName.Empty();
		m_sPreloadFileName.Empty();
	} else
		LoadJpeg(pFolderName, pFileName, oTime, bOriginal, m_oImage);

	UpdateWindow();
}

void CJpegImage::PreloadJpeg(LPCTSTR pFolderName, LPCTSTR pFileName, const CTime &oTime, BOOL bOriginal)
{
	m_sPreloadFolderName = pFolderName;
	m_sPreloadFileName = pFileName;
	m_bPreloadOriginal = bOriginal;

	if (!m_oPreloadImage.IsNull())
		m_oPreloadImage.Destroy();

	LoadJpeg(pFolderName, pFileName, oTime, bOriginal, m_oPreloadImage);
}

void CJpegImage::LoadJpeg(LPCTSTR pFolderName, LPCTSTR pFileName, const CTime &oTime, BOOL bOriginal, CImage &oImage)
{
	CString sPathName;

	if (bOriginal)
		sPathName = CGeneral::GetOriginalPhotoPath(oTime, pFolderName, pFileName);
	else
		sPathName = CGeneral::GetSmallPhotoPath(oTime, pFolderName, pFileName);

	oImage.Load(sPathName);
}

void CJpegImage::Clear()
{
	if (!m_oImage.IsNull())
		::DeleteObject(m_oImage.Detach());

	Invalidate();
}
