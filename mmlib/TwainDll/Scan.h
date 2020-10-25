/****************************************************************************
 |
 |   			TWAIN�Ή��@���Ű�摜��荞�݂c�k�k
 | 
 |			̧�ٖ� :		scan.h		
 |			�쐬�� :		m.hama
 |			���t�F			96/09/03
 |
 |
 ***************************************************************************/

#define WM_SCANBMP	(WM_USER + 1999)

typedef struct {
	int XResolution;
	int YResolution;
	int	ImageWidth;
	int	ImageLength;
	int	BitsPerPixel;
} IMAGE_INFO;

extern	int	APIENTRY ScanDib(HWND, LPSTR);
extern	HANDLE	APIENTRY ScanBmp(HWND hWnd, IMAGE_INFO *pImageInfo);
extern	void APIENTRY ScanBmpContinue(HWND hWnd);
extern	BOOL APIENTRY SelectTwainDriver(HWND hWnd);
extern	BOOL APIENTRY GetDefaultDriver(HWND hWnd, TW_IDENTITY *dsID);
