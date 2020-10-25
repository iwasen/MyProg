#pragma once

#include "atlimage.h"

// CJpegImage

class CJpegImage : public CStatic
{
	DECLARE_DYNAMIC(CJpegImage)

public:
	CJpegImage();

	void DispJpeg(LPCTSTR pFolderName, LPCTSTR pFileName, const CTime &oTime, BOOL bOriginal);
	void PreloadJpeg(LPCTSTR pFolderName, LPCTSTR pFileName, const CTime &oTime, BOOL bOriginal);
	void Clear();

protected:
	CImage m_oImage;
	CImage m_oPreloadImage;
	CString m_sPreloadFolderName;
	CString m_sPreloadFileName;
	BOOL m_bPreloadOriginal;
	BOOL m_bOriginal;

	void LoadJpeg(LPCTSTR pFolderName, LPCTSTR pFileName, const CTime &oTime, BOOL bOriginal, CImage &oImage);

	DECLARE_MESSAGE_MAP()
	virtual void DrawItem(LPDRAWITEMSTRUCT /*lpDrawItemStruct*/);
};


