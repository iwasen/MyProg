/******************************************************************************
 *                                                                            *
 *  ���W���[��: DIB.C                                                         *
 *                                                                            *
 *  ����:       DIB���������郋�[�`��                                         *
 *                                                                            *
 *  �֐�:                                                                     *
 *                                                                            *
 *  OpenDIB()           - DIB�t�@�C�����J���ă�����DIB���쐬���܂��B          *
 *  WriteDIB()          - �O���[�o���n���h����CF_DIB�`���Ńt�@�C���ɏ�������  *
 *                        �܂��B                                              *
 *  DibInfo()           - CF_DIB�`���̃������u���b�N�Ɋ֘A�t�����Ă���      *
 *                        ���u���b�N���擾���܂��B                          *
 *  CreateBIPalette()   - BITMAPINFO�\���̂��w���|�C���^����GDI�p���b�g��     *
 *                        �쐬���܂��B                                        *
 *  CreateDibPalette()  - BITMAPINFO�\���̂̃n���h������GDI�p���b�g���쐬     *
 *                        ���܂��B                                            *
 *  ReadDibBitmapInfo() - DIB�`���̃t�@�C����ǂݎ��A����BITMAPINFO��       *
 *                        �O���[�o���n���h����Ԃ��܂��B                      *
 *  GetPaletteSize()       - �w�肳�ꂽDIB�̃p���b�g�̃o�C�g�����v�Z���܂��B     *
 *  GetDibNumColors()      - DIB�̐F���𔻒f���܂��B                             *
 *  BitmapFromDib()     - CF_DIB�`���̃u���b�N�����ʂ���O���[�o���n���h��    *
 *                        ����ADDB���쐬���܂��B                             *
 *  DibFromBitmap()     - �n���ꂽDDB�Ɠ������e��DIB���쐬���܂��B            *
 *  DrawBitmap()        - DC���̎w�肳�ꂽ�ʒu�Ƀr�b�g�}�b�v��`�悵�܂��B    *
 *  DibBlt()            - SetDIBitsToDevice()���g���āACIF_DIB�`���Ńr�b�g    *
 *                        �}�b�v��`�悵�܂��B                                *
 *  StretchDibBlt()     - StretchDIBits()���g���āACIF_DIB�`���Ńr�b�g�}�b�v  *
 *                        ��`�悵�܂��B                                      *
 *  lread()             - 64K�o�C�g�ȏ��ǂݏo�����߂̃v���C�x�[�g���[�`��   *
 *  lwrite()            - 64K�o�C�g�ȏ���������ނ��߂̃v���C�x�[�g���[�`��   *
 *                                                                            *
 ******************************************************************************/

#include <windows.h>
#include <stdlib.h>
#include <string.h>
#include "dib.h"

static   HCURSOR hcurSave;

/****************************************************************************
 *                                                                          *
 *  �֐�:   OpenDIB(LPSTR szFile)                                           *
 *                                                                          *
 *  �ړI:   DIB�t�@�C�����J���ă�����DIB���쐬���܂��B�܂��ABITMAPINFO�A    *
 *          �p���b�g�f�[�^�A�r�b�g���܂ރ������n���h�����쐬���܂��B        *
 *                                                                          *
 *  �߂�l: DIB�����ʂ���n���h��                                           *
 *                                                                          *
 ****************************************************************************/
HANDLE OpenDIB (szFile)
LPSTR szFile;
{
    unsigned            fh;
    BITMAPINFOHEADER    bi;
    LPBITMAPINFOHEADER  lpbi;
    DWORD               dwLen = 0;
    DWORD               dwBits;
    HANDLE              hdib;
    HANDLE              h;
    OFSTRUCT            of;

    /* �t�@�C�����J����DIB����ǂݎ�� */
    fh = OpenFile(szFile, &of, OF_READ);
    if (fh == -1)
        return NULL;

    hdib = ReadDibBitmapInfo(fh);
    if (!hdib)
        return NULL;
    DibInfo(hdib,&bi);

    /* DIB�̕ێ��ɕK�v�ȃ������ʂ��v�Z */
    dwBits = bi.biSizeImage;
    dwLen  = bi.biSize + (DWORD)GetPaletteSize (&bi) + dwBits;

    /* DIB��ێ�����r�b�g�}�b�v���o�b�t�@�T�C�Y�𑝂₷ */
    h = GlobalReAlloc(hdib, dwLen, GHND);
    if (!h){
        GlobalFree(hdib);
        hdib = NULL;
    }
    else
        hdib = h;

    /* �r�b�g��ǂݎ�� */
    if (hdib){

        lpbi = (VOID FAR *)GlobalLock(hdib);
        lread(fh, (LPSTR)lpbi + (WORD)lpbi->biSize + GetPaletteSize(lpbi), dwBits);
        GlobalUnlock(hdib);
    }
    _lclose(fh);

    return hdib;
}

/****************************************************************************
 *                                                                          *
 *  �֐�:   OpenMemDIB(VOID)                                           *
 *                                                                          *
 *  �ړI:   �N���b�v�{�[�h��DIB���烁����DIB���쐬���܂��B�܂��ABITMAPINFO�A    *
 *          �p���b�g�f�[�^�A�r�b�g���܂ރ������n���h�����쐬���܂��B        *
 *                                                                          *
 *  �߂�l: DIB�����ʂ���n���h��                                           *
 *                                                                          *
 ****************************************************************************/
HANDLE OpenMemDIB (HWND hWnd)
{
    LPBITMAPINFOHEADER  lpbi;
    DWORD	i, dwLen = 0;
	HANDLE	h = NULL, hbi = NULL;
	LPSTR	p;

    /* �t�@�C�����J����DIB����ǂݎ�� */
	if (! OpenClipboard(hWnd))
        return NULL;

	if ((h = GetClipboardData(CF_DIB)) == NULL)
		goto end;

	dwLen = (int)GlobalSize(h);
	if ((hbi = GlobalAlloc(GHND, dwLen)) == NULL)
		goto end;

	p = (LPSTR)GlobalLock(hbi);
	lpbi = (LPBITMAPINFOHEADER)GlobalLock(h);

	for (i = 0; i < dwLen;i++)
		*p++ = *((LPSTR)lpbi + i);

	GlobalUnlock(h);
	GlobalUnlock(hbi);

end:
	EmptyClipboard();
	CloseClipboard();
	return(hbi);
}

/****************************************************************************
 *                                                                          *
 *  �֐�:   WriteDIB(LPSTR szFile,HANDLE hdib)                              *
 *                                                                          *
 *  �ړI:   �O���[�o���n���h����DF_DIB�`���Ńt�@�C���ɏ������݂܂��B        *
 *                                                                          *
 *  �߂�l: TRUE  - ����ɏI�������ꍇ                                      *
 *          FALSE - ����ȊO�̏ꍇ                                          *
 *                                                                          *
 ****************************************************************************/
BOOL WriteDIB (szFile, hdib)
LPSTR szFile;
HANDLE hdib;
{
    BITMAPFILEHEADER    hdr;
    LPBITMAPINFOHEADER  lpbi;
    int                 fh;
    OFSTRUCT            of;

    if (!hdib)
        return FALSE;

    fh = OpenFile (szFile, &of, OF_CREATE|OF_READWRITE);
    if (fh == -1)
        return FALSE;

    lpbi = (VOID FAR *)GlobalLock (hdib);

    /* �t�@�C���w�b�_�[�̊e�����o��ݒ� */
    hdr.bfType          = BFT_BITMAP;
    hdr.bfSize          = (int)GlobalSize (hdib) + sizeof (BITMAPFILEHEADER);
    hdr.bfReserved1     = 0;
    hdr.bfReserved2     = 0;
    hdr.bfOffBits       = (DWORD)sizeof(BITMAPFILEHEADER) + lpbi->biSize +
                          GetPaletteSize(lpbi);

    /* �t�@�C���w�b�_�[���������� */
    _lwrite (fh, (LPSTR)&hdr, sizeof (BITMAPFILEHEADER));

    /* DIB�w�b�_�[�ƃr�b�g���������� */
    lwrite (fh, (LPSTR)lpbi, (int)GlobalSize(hdib));

    GlobalUnlock (hdib);
    _lclose (fh);
    return TRUE;
}

/****************************************************************************
 *                                                                          *
 *  �֐�:   DibInfo(HANDLE hbi,LPBITMAPINFOHEADER lpbi)                     *
 *                                                                          *
 *  �ړI:   CF_DIB�`���̃������u���b�N�Ɋ֘A�t�����Ă���DIB����         *
 *          �擾���܂��B                                                    *
 *                                                                          *
 *  �߂�l: TRUE  - ����ɏI�������ꍇ                                      *
 *          FALSE - ����ȊO�̏ꍇ                                          *
 *                                                                          *
 ****************************************************************************/
BOOL DibInfo (hbi, lpbi)
HANDLE hbi;
LPBITMAPINFOHEADER lpbi;
{
    if (hbi){
        *lpbi = *(LPBITMAPINFOHEADER)GlobalLock (hbi);

        /* �f�t�H���g�̃����o�ݒ� */
        if (lpbi->biSize != sizeof (BITMAPCOREHEADER)){
            if (lpbi->biSizeImage == 0L)
                lpbi->biSizeImage =
                    WIDTHBYTES(lpbi->biWidth*lpbi->biBitCount) * lpbi->biHeight;

            if (lpbi->biClrUsed == 0L)
                lpbi->biClrUsed = GetDibNumColors (lpbi);
        }
        GlobalUnlock (hbi);
        return TRUE;
    }
    return FALSE;
}

/****************************************************************************
 *                                                                          *
 *  �֐�:   CreateBIPalette(LPBITMAPINFOHEADER lpbi)                        *
 *                                                                          *
 *  �ړI:   BITMAPINFO�\���̂��w���|�C���^���w�肷��ƁA���̃J���[�e�[�u��  *
 *          ����GDI�p���b�g�I�u�W�F�N�g���쐬���܂��B                       *
 *                                                                          *
 *  �߂�l: �p���b�g�����ʂ���n���h��                                      *
 *                                                                          *
 ****************************************************************************/
HPALETTE CreateBIPalette (lpbi)
LPBITMAPINFOHEADER lpbi;
{
    LOGPALETTE          *pPal;
    HPALETTE            hpal = NULL;
    WORD                nNumColors;
    BYTE                red;
    BYTE                green;
    BYTE                blue;
    WORD                i;
    RGBQUAD        FAR *pRgb;

    if (!lpbi)
        return NULL;

    if (lpbi->biSize != sizeof(BITMAPINFOHEADER))
        return NULL;

    /* �J���[�e�[�u�����w���|�C���^�ƐF�����擾 */
    pRgb = (RGBQUAD FAR *)((LPSTR)lpbi + (WORD)lpbi->biSize);
    nNumColors = GetDibNumColors(lpbi);

    if (nNumColors){
        /* �_���p���b�g�\���̂����蓖�Ă� */
        pPal = (LOGPALETTE*)LocalAlloc(LPTR,sizeof(LOGPALETTE) + nNumColors * sizeof(PALETTEENTRY));
        if (!pPal)
            return NULL;

        pPal->palNumEntries = nNumColors;
        pPal->palVersion    = PALVERSION;

        /*
         * DIB�J���[�e�[�u������p���b�g�G���g����ݒ肵�A
         * �_���J���[�p���b�g���쐬����
         */
        for (i = 0; i < nNumColors; i++){
            pPal->palPalEntry[i].peRed   = pRgb[i].rgbRed;
            pPal->palPalEntry[i].peGreen = pRgb[i].rgbGreen;
            pPal->palPalEntry[i].peBlue  = pRgb[i].rgbBlue;
            pPal->palPalEntry[i].peFlags = (BYTE)0;
        }
        hpal = CreatePalette(pPal);
        LocalFree((HANDLE)pPal);
    }
    else if (lpbi->biBitCount == 24){
        /* 
         * 24�r�b�g��DIB�ɂ̓J���[�e�[�u���G���g�����Ȃ����߁A
         * �ő�l(256)��ݒ肷��
         */
        nNumColors = MAXPALETTE;
        pPal = (LOGPALETTE*)LocalAlloc(LPTR,sizeof(LOGPALETTE) + nNumColors * sizeof(PALETTEENTRY));
        if (!pPal)
            return NULL;

        pPal->palNumEntries = nNumColors;
        pPal->palVersion    = PALVERSION;

        red = green = blue = 0;

        /* 
         * 256 (= 8*8*4)�ʂ��RGB�̑g�ݍ��킹�𐶐�����
         * �p���b�g�ɐݒ肷��
         */
        for (i = 0; i < pPal->palNumEntries; i++){
            pPal->palPalEntry[i].peRed   = red;
            pPal->palPalEntry[i].peGreen = green;
            pPal->palPalEntry[i].peBlue  = blue;
            pPal->palPalEntry[i].peFlags = (BYTE)0;

            if (!(red += 32))
                if (!(green += 32))
                    blue += 64;
        }
        hpal = CreatePalette(pPal);
        LocalFree((HANDLE)pPal);
    }
    return hpal;
}

/****************************************************************************
 *                                                                          *
 *  �֐�:   CreateDibPalette(HANDLE hbi)                                    *
 *                                                                          *
 *  �ړI:   BITMAPINFO�\���̂��w���O���[�o���n���h�����w�肷��ƁA����      *
 *          �J���[�e�[�u������GDI�p���b�g�I�u�W�F�N�g���쐬���܂��B         *
 *          (BITMAPINFOHEADER�`����DIB��p)                                 *
 *                                                                          *
 *  �߂�l: �p���b�g�����ʂ���n���h��                                      *
 *                                                                          *
 ****************************************************************************/
HPALETTE CreateDibPalette (hbi)
HANDLE hbi;
{
    HPALETTE hpal;

    if (!hbi)
        return NULL;
    hpal = CreateBIPalette((LPBITMAPINFOHEADER)GlobalLock(hbi));
    GlobalUnlock(hbi);
    return hpal;
}

/****************************************************************************
 *                                                                          *
 *  �֐�:   ReadDibBitmapInfo(int fh)                                       *
 *                                                                          *
 *  �ړI:   DIB�`���̃t�@�C����ǂݎ��A����BITMAPINFO�̃O���[�o���n��     *
 *          �h����Ԃ��܂��B���̊֐��͈ȑO��(BITMAPCOREHEADER)�`���ƐV��    *
 *          ��BITMAPINFOHEADER)�`���̗����������ł��܂����A�Ԃ��̂͂�     *
 *          �ɐV����BITMAPINFO�ł��B                                        *
 *                                                                          *
 *  �߂�l: �t�@�C������DIB��BITMAPINFO�����ʂ���n���h��                   *
 *                                                                          *
 ****************************************************************************/
HANDLE ReadDibBitmapInfo (fh)
int fh;
{
    DWORD     off;
    HANDLE    hbi = NULL;
    int       size;
    int       i;
    WORD      nNumColors;

    RGBQUAD FAR       *pRgb;
    BITMAPINFOHEADER   bi;
    BITMAPCOREHEADER   bc;
    LPBITMAPINFOHEADER lpbi;
    BITMAPFILEHEADER   bf;
    DWORD              dwWidth = 0;
    DWORD              dwHeight = 0;
    WORD               wPlanes, wBitCount;

    if (fh == -1)
        return NULL;

    /* �t�@�C���|�C���^�����Z�b�g���A�t�@�C���w�b�_�[��ǂݎ�� */
    off = _llseek(fh, 0L, SEEK_CUR);
    if (sizeof (bf) != _lread (fh, (LPSTR)&bf, sizeof (bf)))
        return FALSE;

    /* RC�w�b�_�[�����邩���ׂ� */
    if (!ISDIB (bf.bfType)) {
        bf.bfOffBits = 0L;
        _llseek (fh, off, SEEK_SET);
    }
    if (sizeof (bi) != _lread (fh, (LPSTR)&bi, sizeof(bi)))
        return FALSE;

    nNumColors = GetDibNumColors (&bi);

    /* 
     * ���u���b�N�̓��e(BITMAPINFO�܂���BITMAPCORE)���`�F�b�N���A
     * ����ɏ]���ď����擾����BBITMAPCOREHEADER�Ȃ�΁A����
     * BITMAPINFOHEADER�`���̃u���b�N�ɓ]������B
     */
    switch (size = (int)bi.biSize){
        case sizeof (BITMAPINFOHEADER):
            break;

        case sizeof (BITMAPCOREHEADER):

            bc = *(BITMAPCOREHEADER*)&bi;

            dwWidth   = (DWORD)bc.bcWidth;
            dwHeight  = (DWORD)bc.bcHeight;
            wPlanes   = bc.bcPlanes;
            wBitCount = bc.bcBitCount;

            bi.biSize               = sizeof(BITMAPINFOHEADER);
            bi.biWidth              = dwWidth;
            bi.biHeight             = dwHeight;
            bi.biPlanes             = wPlanes;
            bi.biBitCount           = wBitCount;

            bi.biCompression        = BI_RGB;
            bi.biSizeImage          = 0;
            bi.biXPelsPerMeter      = 0;
            bi.biYPelsPerMeter      = 0;
            bi.biClrUsed            = nNumColors;
            bi.biClrImportant       = nNumColors;

            _llseek (fh, (LONG)sizeof (BITMAPCOREHEADER) - sizeof (BITMAPINFOHEADER), SEEK_CUR);
            break;

        default:
            /* DIB�ł͂Ȃ� */
            return NULL;
    }

    /* 0�Ȃ�΃f�t�H���g�l��ݒ� */
    if (bi.biSizeImage == 0){
        bi.biSizeImage = WIDTHBYTES ((DWORD)bi.biWidth * bi.biBitCount)
                         * bi.biHeight;
    }
    if (bi.biClrUsed == 0)
        bi.biClrUsed = GetDibNumColors(&bi);

    /* BITMAPINFO�\���̂ƃJ���[�e�[�u�������蓖�Ă� */
    hbi = GlobalAlloc (GHND, (LONG)bi.biSize + nNumColors * sizeof(RGBQUAD));
    if (!hbi)
        return NULL;
    lpbi = (VOID FAR *)GlobalLock (hbi);
    *lpbi = bi;

    /* �J���[�e�[�u�����w���|�C���^���擾 */
    pRgb = (RGBQUAD FAR *)((LPSTR)lpbi + bi.biSize);
    if (nNumColors){
        if (size == sizeof(BITMAPCOREHEADER)){
            /*
             * �Â��J���[�e�[�u��(3�o�C�g��RGBTRIPLE)��V�����J���[�e�[
             * �u��(4�o�C�g��RGBQUAD)�ɕϊ�
             */
            _lread (fh, (LPSTR)pRgb, nNumColors * sizeof(RGBTRIPLE));

            for (i = nNumColors - 1; i >= 0; i--){
                RGBQUAD rgb;

                rgb.rgbRed      = ((RGBTRIPLE FAR *)pRgb)[i].rgbtRed;
                rgb.rgbBlue     = ((RGBTRIPLE FAR *)pRgb)[i].rgbtBlue;
                rgb.rgbGreen    = ((RGBTRIPLE FAR *)pRgb)[i].rgbtGreen;
                rgb.rgbReserved = (BYTE)0;

                pRgb[i] = rgb;
            }
        }
        else
            _lread(fh,(LPSTR)pRgb,nNumColors * sizeof(RGBQUAD));
    }

    if (bf.bfOffBits != 0L)
        _llseek(fh,off + bf.bfOffBits,SEEK_SET);

    GlobalUnlock(hbi);
    return hbi;
}

/****************************************************************************
 *                                                                          *
 *  �֐�:   GetPaletteSize(VOID FAR * pv)                                      *
 *                                                                          *
 *  �ړI:   �p���b�g�̃o�C�g�����v�Z���܂��B���u���b�N��BITMAPCOREHEADER  *
 *          �^�Ȃ�΁A�F����3�{���p���b�g�T�C�Y�ɂȂ�܂��B����ȊO�̏ꍇ   *
 *          �́A�F����4�{���p���b�g�T�C�Y�ɂȂ�܂��B                       *
 *                                                                          *
 *  �߂�l: �p���b�g�̃o�C�g��                                              *
 *                                                                          *
 ****************************************************************************/
WORD GetPaletteSize (pv)
VOID FAR * pv;
{
    LPBITMAPINFOHEADER lpbi;
    WORD               NumColors;

    lpbi      = (LPBITMAPINFOHEADER)pv;
    NumColors = GetDibNumColors(lpbi);

    if (lpbi->biSize == sizeof(BITMAPCOREHEADER))
        return NumColors * sizeof(RGBTRIPLE);
    else
        return NumColors * sizeof(RGBQUAD);
}

/****************************************************************************
 *                                                                          *
 *  �֐�:   GetDibNumColorsByHandle(HANDLE h)                               *
 *                                                                          *
 *  �ړI:   ���u���b�N��BitCount�����o���Q�Ƃ��āADIB�̐F���𔻒f���܂��B *
 *                                                                          *
 *  �߂�l: DIB�̐F��                                                       *
 *                                                                          *
 ****************************************************************************/
WORD GetDibNumColorsByHandle(HANDLE h)
{
    LPBITMAPINFOHEADER  lpbi;
	WORD	n;


    lpbi = (LPBITMAPINFOHEADER)GlobalLock(h);
	n = GetDibNumColors((LPVOID)lpbi);
	GlobalUnlock(h);
	return(n);
}

/****************************************************************************
 *                                                                          *
 *  �֐�:   GetDibNumColors(VOID FAR * pv)                                     *
 *                                                                          *
 *  �ړI:   ���u���b�N��BitCount�����o���Q�Ƃ��āADIB�̐F���𔻒f���܂��B *
 *                                                                          *
 *  �߂�l: DIB�̐F��                                                       *
 *                                                                          *
 ****************************************************************************/
WORD GetDibNumColors (pv)
VOID FAR * pv;
{
    int                 bits;
    LPBITMAPINFOHEADER  lpbi;
    LPBITMAPCOREHEADER  lpbc;

    lpbi = ((LPBITMAPINFOHEADER)pv);
    lpbc = ((LPBITMAPCOREHEADER)pv);

    /*
     *  BITMAPINFO�`���w�b�_�[�̏ꍇ�A�p���b�g�̃T�C�Y��BITMAPCORE�`����
     *  �w�b�_�[��biClrUsed�������Ă���B�p���b�g�̃T�C�Y�́A�s�N�Z��������
     *  �̃r�b�g���ɂ��قȂ�B
     */
    if (lpbi->biSize != sizeof(BITMAPCOREHEADER)){
        if (lpbi->biClrUsed != 0)
            return (WORD)lpbi->biClrUsed;
        bits = lpbi->biBitCount;
    }
    else
        bits = lpbc->bcBitCount;

    switch (bits){
        case 1:                return 2;
        case 4:
                return 16;
        case 8:
                return 256;
        default:
                /* 24�r�b�gDIB�ɂ̓J���[�e�[�u���͂Ȃ� */
                return 0;
    }
}
/****************************************************************************
 *                                                                          *
 *  �֐�:   DibFromBitmap()                                                 *
 *                                                                          *
 *  �ړI:   �w�肳�ꂽDDB(�f�o�C�X�Ɉˑ�����r�b�g�}�b�v)�Ɠ������e��DIB    *
 *          ���܂ރO���[�o���������u���b�N���쐬���܂��B                    *
 *                                                                          *
 *  �߂�l: DIB�����ʂ���n���h��                                           *
 *                                                                          *
 ****************************************************************************/
HANDLE DibFromBitmap (HBITMAP hbm, DWORD biStyle, WORD biBits, HPALETTE hpal)
{
    BITMAP               bm;
    BITMAPINFOHEADER     bi;
    BITMAPINFOHEADER FAR *lpbi;
    DWORD                dwLen;
    HANDLE               hdib;
    HANDLE               h;
    HDC                  hdc;

    if (!hbm)
        return NULL;

    if (hpal == NULL)
        hpal = GetStockObject(DEFAULT_PALETTE);

    GetObject(hbm,sizeof(bm),(LPSTR)&bm);

    if (biBits == 0)
        biBits =  bm.bmPlanes * bm.bmBitsPixel;

    bi.biSize               = sizeof(BITMAPINFOHEADER);
    bi.biWidth              = bm.bmWidth;
    bi.biHeight             = bm.bmHeight;
    bi.biPlanes             = 1;
    bi.biBitCount           = biBits;
    bi.biCompression        = biStyle;
    bi.biSizeImage          = 0;
    bi.biXPelsPerMeter      = 0;
    bi.biYPelsPerMeter      = 0;
    bi.biClrUsed            = 0;
    bi.biClrImportant       = 0;

    dwLen  = bi.biSize + GetPaletteSize(&bi);

    hdc = GetDC(NULL);
    hpal = SelectPalette(hdc,hpal,FALSE);
         RealizePalette(hdc);

    hdib = GlobalAlloc(GHND,dwLen);

    if (!hdib){
        SelectPalette(hdc,hpal,FALSE);
        ReleaseDC(NULL,hdc);
        return NULL;
    }

    lpbi = (VOID FAR *)GlobalLock(hdib);

    *lpbi = bi;

    /* 
     *  lpBits�p�����[�^��NULL���w�肵��GetDIBits���Ăяo���A
     *  biSizeImage�����o���v�Z������
     */
    GetDIBits(hdc, hbm, 0, (WORD)bi.biHeight,
        NULL, (LPBITMAPINFO)lpbi, DIB_RGB_COLORS);

    bi = *lpbi;
    GlobalUnlock(hdib);

    /* �h���C�o��biSizeImage�����o��ݒ肵�Ȃ������ꍇ�͂����ŕ₤ */
    if (bi.biSizeImage == 0){
        bi.biSizeImage = WIDTHBYTES((DWORD)bm.bmWidth * biBits) * bm.bmHeight;

        if (biStyle != BI_RGB)
            bi.biSizeImage = (bi.biSizeImage * 3) / 2;
    }

    /* �r�b�g�S�̂�ێ�����̂ɏ\���ȑ傫���̃o�b�t�@���Ċ��蓖�Ă��� */
    dwLen = bi.biSize + GetPaletteSize(&bi) + bi.biSizeImage;
    if (h = GlobalReAlloc(hdib,dwLen,0))
        hdib = h;
    else{
        GlobalFree(hdib);
        hdib = NULL;

        SelectPalette(hdc,hpal,FALSE);
        ReleaseDC(NULL,hdc);
        return hdib;
    }
    /*
     * lpBits�p�����[�^��NULL�ȊO�̒l��ݒ肵��GetDIBits���Ăяo���A
     * ���ۂɃr�b�g�f�[�^���擾����
     */
    lpbi = (VOID FAR *)GlobalLock(hdib);

    if (GetDIBits( hdc,
                   hbm,
                   0,
                   (WORD)bi.biHeight,
                   (LPSTR)lpbi + (WORD)lpbi->biSize + GetPaletteSize(lpbi),
                   (LPBITMAPINFO)lpbi, DIB_RGB_COLORS) == 0){
         GlobalUnlock(hdib);
         hdib = NULL;
         SelectPalette(hdc,hpal,FALSE);
         ReleaseDC(NULL,hdc);
         return NULL;
    }

    bi = *lpbi;
    GlobalUnlock(hdib);

    SelectPalette(hdc,hpal,FALSE);
    ReleaseDC(NULL,hdc);
    return hdib;
}

/****************************************************************************
 *                                                                          *
 *  �֐�:   BitmapFromDib(HANDLE hdib, HPALETTE hpal)                       *
 *                                                                          *
 *  �ړI:   CF_DIB�`���̃������u���b�N�̃O���[�o���n���h�����w�肷��ƁA    *
 *          DDB (�f�o�C�X�Ɉˑ����Ȃ��r�b�g�}�b�v)���쐬���܂��B            *
 *                                                                          *
 *  �߂�l: DDB�����ʂ���n���h��                                           *
 *                                                                          *
 ****************************************************************************/
HBITMAP BitmapFromDib (HANDLE hdib, HPALETTE hpal)
{
    LPBITMAPINFOHEADER  lpbi;
    HPALETTE            hpalT;
    HDC                 hdc;
    HBITMAP             hbm;

    StartWait();

    if (!hdib)
        return NULL;

    lpbi = (VOID FAR *)GlobalLock(hdib);

    if (!lpbi)
        return NULL;

    hdc = GetDC(NULL);

    if (hpal){
        hpalT = SelectPalette(hdc,hpal,FALSE);
        RealizePalette(hdc);     // GDI�̃o�O????
    }

    hbm = CreateDIBitmap(hdc,
                (LPBITMAPINFOHEADER)lpbi,
                (LONG)CBM_INIT,
                (LPSTR)lpbi + lpbi->biSize + GetPaletteSize(lpbi),
                (LPBITMAPINFO)lpbi,
                DIB_RGB_COLORS );

    if (hpal)
        SelectPalette(hdc,hpalT,FALSE);

    ReleaseDC(NULL,hdc);
    GlobalUnlock(hdib);

    EndWait();

    return hbm;
}
/****************************************************************************
 *                                                                          *
 *  �֐�:   DrawBitmap(HDC hdc, int x, int y, HBITMAP hbm, DWORD rop)       *
 *                                                                          *
 *  �ړI:   DC��hdc���̎w�肳�ꂽ�ʒu�Ƀr�b�g�}�b�vhbm��`�悵�܂��B        *
 *                                                                          *
 *  �߂�l: BitBlt()�̖߂�l                                                *
 *                                                                          *
 ****************************************************************************/
BOOL DrawBitmap (hdc, x, y, hbm, rop)
HDC        hdc;
int        x, y;
HBITMAP    hbm;
DWORD      rop;
{
    HDC       hdcBits;
    BITMAP    bm;
    BOOL      f;

    if (!hdc || !hbm)
        return FALSE;

    hdcBits = CreateCompatibleDC(hdc);
    GetObject(hbm,sizeof(BITMAP),(LPSTR)&bm);
    SelectObject(hdcBits,hbm);
    f = BitBlt(hdc,0,0,bm.bmWidth,bm.bmHeight,hdcBits,0,0,rop);
    DeleteDC(hdcBits);

    return f;
}
/****************************************************************************
 *                                                                          *
 *  �֐�:  DibBlt( HDC hdc,                                                 *
 *                 int x0, int y0,                                          *
 *                 int dx, int dy,                                          *
 *                 HANDLE hdib,                                             *
 *                 int x1, int y1,                                          *
 *                 LONG rop)                                                *
 *                                                                          *
 *  �ړI:   SetDIBitsToDevice���g���āACF_DIB�`���̃r�b�g�}�b�v��`��       *
 *          ���܂��BBitBlt�Ɠ����p�����[�^�����܂��B                      *
 *                                                                          *
 *  �߂�l: TRUE  - ����ɏI�������ꍇ                                      *
 *          FALSE - ����ȊO�̏ꍇ                                          *
 *                                                                          *
 ****************************************************************************/
BOOL DibBlt (hdc, x0, y0, dx, dy, hdib, x1, y1, rop)
HDC        hdc;
int        x0, y0, dx, dy;
HANDLE     hdib;
int        x1, y1;
LONG       rop;
{
    LPBITMAPINFOHEADER   lpbi;
    LPSTR                pBuf;

    if (!hdib)
        return PatBlt(hdc,x0,y0,dx,dy,rop);

    lpbi = (VOID FAR *)GlobalLock(hdib);

    if (!lpbi)
        return FALSE;

    pBuf = (LPSTR)lpbi + (WORD)lpbi->biSize + GetPaletteSize(lpbi);
    SetDIBitsToDevice (hdc, x0, y0, dx, dy,
                       x1,y1,
                       x1,
                       dy,
                       pBuf, (LPBITMAPINFO)lpbi,
                       DIB_RGB_COLORS );

    GlobalUnlock(hdib);
    return TRUE;
}
/****************************************************************************
 *                                                                          *
 *  �֐�:   StretchDibBlt( HDC hdc,                                         *
 *                         int x, int y,                                    *
 *                         int dx, int dy,                                  *
 *                         HANDLE hdib,                                     *
 *                         int x0, int y0,                                  *
 *                         int dx0, int dy0,                                *
 *                         LONG rop)                                        *
 *                                                                          *
 *  �ړI:   StrechDIBits()���g���āACF_DIB�`���̃r�b�g�}�b�v��`�悵�܂��B  *
 *          StretchBlt()�Ɠ����p�����[�^�����܂��B                        *
 *                                                                          *
 *  �߂�l: TRUE  - ����ɏI�������ꍇ                                      *
 *          FALSE - ����ȊO�̏ꍇ                                          *
 *                                                                          *
 ****************************************************************************/
BOOL StretchDibBlt (hdc, x, y, dx, dy, hdib, x0, y0, dx0, dy0, rop)
HDC hdc;
int x, y;
int dx, dy;
HANDLE hdib;
int x0, y0;
int dx0, dy0;
LONG rop;

{
    LPBITMAPINFOHEADER lpbi;
    LPSTR        pBuf;
    BOOL         f;

    if (!hdib)
        return PatBlt(hdc,x,y,dx,dy,rop);

    lpbi = (VOID FAR *)GlobalLock(hdib);

    if (!lpbi)
        return FALSE;

    pBuf = (LPSTR)lpbi + (WORD)lpbi->biSize + GetPaletteSize(lpbi);

    f = StretchDIBits ( hdc,
                        x, y,
                        dx, dy,
                        x0, y0,
                        dx0, dy0,
                        pBuf, (LPBITMAPINFO)lpbi,
                        DIB_RGB_COLORS,
                        rop);

    GlobalUnlock(hdib);
    return f;
}

 /********** 64K�o�C�g�ȏ�̓ǂݏ������s���v���C�x�[�g���[�`�� ************/
/****************************************************************************
 *                                                                          *
 *  �֐�:   lread(int fh, VOID FAR *pv, DWORD ul)                           *
 *                                                                          *
 *  �ړI:   �f�[�^�����ׂēǂݎ��܂�32K�o�C�g���f�[�^��ǂݎ��܂��B   *
 *                                                                          *
 *  �߂�l: 0                  - ����ɓǂݎ��Ȃ������ꍇ                 *
 *          �ǂݎ�����o�C�g�� - ����ȊO�̏ꍇ                             *
 *                                                                          *
 ****************************************************************************/
DWORD PASCAL lread (fh, pv, ul)
int           fh;
VOID far      *pv;
DWORD         ul;
{
    DWORD     ulT = ul;
    BYTE	*hp = pv;

    while (ul > (DWORD)MAXREAD) {
        if (_lread(fh, (LPSTR)hp, (WORD)MAXREAD) != MAXREAD)
                return 0;
        ul -= MAXREAD;
        hp += MAXREAD;
    }
    if (_lread(fh, (LPSTR)hp, (WORD)ul) != (WORD)ul)
        return 0;
    return ulT;
}

/****************************************************************************
 *                                                                          *
 *  �֐�:   lwrite(int fh, VOID FAR *pv, DWORD ul)                          *
 *                                                                          *
 *  �ړI:   �f�[�^�����ׂď������ނ܂�32K�o�C�g���f�[�^���������݂܂��B   *
 *                                                                          *
 *  �߂�l: 0                  - ����ɏ������߂Ȃ������ꍇ                 *
 *          �������񂾃o�C�g�� - ����ȊO�̏ꍇ                             *
 *                                                                          *
 ****************************************************************************/
DWORD PASCAL lwrite (fh, pv, ul)
int          fh;
VOID FAR     *pv;
DWORD        ul;
{
    DWORD     ulT = ul;
    BYTE	*hp = pv;

    while (ul > MAXREAD) {
        if (_lwrite(fh, (LPSTR)hp, (WORD)MAXREAD) != MAXREAD)
                return 0;
        ul -= MAXREAD;
        hp += MAXREAD;
    }
    if (_lwrite(fh, (LPSTR)hp, (WORD)ul) != (WORD)ul)
        return 0;
    return ulT;
}