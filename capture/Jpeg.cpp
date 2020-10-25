#include "stdafx.h"
#include <mmsystem.h>
#include <vfw.h>
#include "IniFile.h"
extern "C" {
#undef FAR
#define boolean BOOL
#include "jpeglib.h"
}

#define OUTPUT_BUF_SIZE  4096

typedef	struct	{
	WORD	blue:5,
			green:5,
			red:5;
} RGB16, *LPRGB16;

static SHORT WriteJpgData(FILE *, LPBITMAPINFO, LPBYTE, int, int);

// JPEG ファイルの書き込み
BOOL WriteJpegFile(HWND hWnd, LPVIDEOHDR lpVHdr)
{
	BYTE bmi[sizeof(BITMAPINFOHEADER) + sizeof(RGBQUAD) * 256];
	LPBITMAPINFO lpBmi = (LPBITMAPINFO)bmi;
	CString tempFile;
	FILE *fp;
	int	quality;
	int rc, n;

	capGetVideoFormat(hWnd, lpBmi, sizeof(bmi));

	switch (iniData.m_Quality) {
	case 0:		// 低画質
		quality = 53;
		break;
	case 1:		// 標準
		quality = 73;
		break;
	case 2:		// 高画質
		quality = 85;
		break;
	case 3:		// 最高画質
		quality = 95;
		break;
	default:	// 念のため
		quality = 75;
		break;
	}

	if (!iniData.m_OutputFile.IsEmpty()) {
		if ((n = iniData.m_OutputFile.ReverseFind('.')) != -1)
			tempFile.Format("%s.tmp", iniData.m_OutputFile.Left(n));
		else
			tempFile.Format("%s.tmp", iniData.m_OutputFile);

		if ((fp  = fopen((LPCTSTR)tempFile, "wb")) != NULL) {
			rc = WriteJpgData(fp, lpBmi, lpVHdr->lpData, quality, 1);
			fclose(fp);

//			::DeleteFile(iniData.m_OutputFile);
//			::MoveFile(tempFile, iniData.m_OutputFile);
			::CopyFile(tempFile, iniData.m_OutputFile, FALSE);
			::DeleteFile(tempFile);
		}
	}

	if (!iniData.m_OutputFile2.IsEmpty()) {
		if ((n = iniData.m_OutputFile2.ReverseFind('.')) != -1)
			tempFile.Format("%s.tmp", iniData.m_OutputFile2.Left(n));
		else
			tempFile.Format("%s.tmp", iniData.m_OutputFile2);

		if ((fp  = fopen((LPCTSTR)tempFile, "wb")) != NULL) {
			rc = WriteJpgData(fp, lpBmi, lpVHdr->lpData, quality, 2);
			fclose(fp);

//			::DeleteFile(iniData.m_OutputFile2);
//			::MoveFile(tempFile, iniData.m_OutputFile2);
			::CopyFile(tempFile, iniData.m_OutputFile2, FALSE);
			::DeleteFile(tempFile);
		}
	}

	return TRUE;
}

static SHORT WriteJpgData(FILE *fp, LPBITMAPINFO lpBmi, LPBYTE lpPixel, int quality, int step)
{
	LPBYTE inptr;
	LPBYTE	outptr;
	JSAMPARRAY buffer;	/* Output row buffer */
	struct jpeg_compress_struct cinfo;
	struct jpeg_error_mgr jerr;
	int row_stride;		/* physical row width in image buffer */
	UINT	i;
	LPRGBQUAD lpPalette;
	int	widthBytes;

	switch (lpBmi->bmiHeader.biBitCount) {
	case 8:
		widthBytes = ((int)lpBmi->bmiHeader.biWidth + 3) & ~3;
		lpPalette = (LPRGBQUAD)((LPSTR)lpBmi +
					(WORD)lpBmi->bmiHeader.biSize);
		break;
	case 16:
		widthBytes = ((int)lpBmi->bmiHeader.biWidth * 2 + 3) & ~3;
		lpPalette = NULL;
		break;
	case 24:
		widthBytes = ((int)lpBmi->bmiHeader.biWidth * 3 + 3) & ~3;
		lpPalette = NULL;
		break;
	default:
		return FALSE;
	}

	cinfo.err = jpeg_std_error(&jerr);

	jpeg_create_compress(&cinfo);

	jpeg_stdio_dest(&cinfo, fp);

	cinfo.image_width = (int)lpBmi->bmiHeader.biWidth / step;
	cinfo.image_height = (int)lpBmi->bmiHeader.biHeight / step;
	cinfo.input_components = 3;	/* # of color components per pixel */
	cinfo.in_color_space = JCS_RGB; 	/* colorspace of input image */

	jpeg_set_defaults(&cinfo);
	cinfo.dct_method = JDCT_IFAST;
	jpeg_set_quality(&cinfo, quality, TRUE);
	jpeg_start_compress(&cinfo, TRUE);

	row_stride = (cinfo.image_width * 3 + 3) & ~3;

	buffer = (*cinfo.mem->alloc_sarray)
		((j_common_ptr) &cinfo, JPOOL_IMAGE, row_stride, 1);

	while (cinfo.next_scanline < cinfo.image_height) {
		outptr = buffer[0];
		inptr = lpPixel + (cinfo.image_height * step
				- cinfo.next_scanline * step - 1)
				* (long)widthBytes;
		switch (lpBmi->bmiHeader.biBitCount) {
		case 8:
			for (i = 0; i < cinfo.image_width; i++) {
				*outptr++ = lpPalette[(UCHAR)*inptr].rgbRed;
				*outptr++ = lpPalette[(UCHAR)*inptr].rgbGreen;
				*outptr++ = lpPalette[(UCHAR)*inptr].rgbBlue;
				inptr += step;
			}
			break;
		case 16:
			for (i = 0; i < cinfo.image_width; i++) {
				*outptr++ = (BYTE)(((LPRGB16)inptr)->red << 3);
				*outptr++ = (BYTE)(((LPRGB16)inptr)->green << 3);
				*outptr++ = (BYTE)(((LPRGB16)inptr)->blue << 3);
				inptr += 2 * step;
			}
			break;
		case 24:
			for (i = 0; i < cinfo.image_width; i++) {
				*outptr++ = inptr[2];
				*outptr++ = inptr[1];
				*outptr++ = inptr[0];
				inptr += 3 * step;
			}
			break;
		}
		(void) jpeg_write_scanlines(&cinfo, buffer, 1);
	}

	jpeg_finish_compress(&cinfo);
	jpeg_destroy_compress(&cinfo);

	return 0;
}
