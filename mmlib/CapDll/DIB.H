/****************************************************************************
 *                                                                          *
 *  �t�@�C����: DIB.H                                                   *
 *                                                                          *
 *                                                                          *
 ****************************************************************************/

/* �w�肳�ꂽ�l��͈͓��Ɏ��߂�}�N�� */
#define BOUND(x,min,max) ((x) < (min) ? (min) : ((x) > (max) ? (max) : (x)))

/* 2�̒l����������}�N�� */
#define SWAP(x,y)   ((x)^=(y)^=(x)^=(y))

/* 2�̒l�̍ŏ��l���擾����}�N�� */
#define MIN(x,y) (((x) <= (y)) : x ? y)

/*
 * ���Ԃ̂����鏈���̏ꍇ�ɍ����v�J�[�\����\�����A
 * �����I����ɏ�������}�N��
 */
#define StartWait() hcurSave = SetCursor(LoadCursor(NULL,IDC_WAIT))
#define EndWait()   SetCursor(hcurSave)

#define MINBAND         50     /* ���̃v���O�����Ŏg����ŏ��̃o���h��  */
#define BANDINCREMENT   20     /* �œK�ȃo���h�������肷��Ƃ��Ɏg����A*/
                               /* �o���h���̍����l                        */

/*
 * DIB�����t���O(fFileOptions)�̃r�b�g�l�B[�t�@�C�����J��]�_�C�A���O
 * ��DIB�r�b�g�����W�I�{�^���̃R���g���[��ID�Ƃ��Ă��g����B
 */ 
#define F_1BPP            DLGOPEN_1BPP
#define F_4BPP            DLGOPEN_4BPP
#define F_8BPP            DLGOPEN_8BPP
#define F_24BPP     DLGOPEN_24BPP

/*
 * DIB�����t���O(fFileOptions)�̃r�b�g�l�B[�t�@�C�����J��]�_�C�A���O
 * ��DIB���k�^�C�v���W�I�{�^���̃R���g���[��ID�Ƃ��Ă��g����B
 */
#define F_RLE4            DLGOPEN_RLE4
#define F_RLE8            DLGOPEN_RLE8
#define F_RGB            DLGOPEN_RGB

/* _lseek�̃t���O*/
#define  SEEK_CUR 1
#define  SEEK_END 2
#define  SEEK_SET 0

/* SETDIBSCALING�v�����^�G�X�P�[�v�œn�����\���� */
typedef struct {
        short ScaleMode;
        short dx, dy;
} DIBPARAMS;

extern HWND hWndApp;              /* �A�v���P�[�V�����E�B���h�E�̃n���h��   */

#define MAXREAD  32768            /* �ǂݎ�菈�����̓ǂݎ��\�ȃo�C�g�� */

/* ���܂��܂ȃ��\�[�X�̃w�b�_�[�L�� */
#define BFT_ICON   0x4349   /* 'IC' */
#define BFT_BITMAP 0x4d42   /* 'BM' */
#define BFT_CURSOR 0x5450   /* 'PT' */

/* ���\�[�X��DIB���ǂ����𔻒f����}�N�� */
#define ISDIB(bft) ((bft) == BFT_BITMAP)

/* �w�肳�ꂽ�l���ł��߂�DWORD (unsigned long )���E�ɐ��񂳂���}�N�� */
#define ALIGNULONG(i)        ((i+3)/4*4)

/* �w�肳�ꂽ�l���ł��߂��o�C�g���E�ɐ��񂳂���}�N�� */
#define WIDTHBYTES(i)        ((i+31)/32*4)

#define PALVERSION      0x300
#define MAXPALETTE        256   /* �T�|�[�g����p���b�g�G���g���̍ő吔 */

/********** �ȉ��̒�`��DLGOPEN.C�Ŏg�p ************************/

/* DlgOpen�_�C�A���O�̃R���g���[��ID */
#define DLGOPEN_OPTION           0xF000
#define DLGOPEN_1BPP             0x0001
#define DLGOPEN_4BPP             0x0002
#define DLGOPEN_8BPP             0x0004
#define DLGOPEN_24BPP            0x0008
#define DLGOPEN_RLE4             0x0010
#define DLGOPEN_RLE8             0x0020
#define DLGOPEN_RGB              0x0040

#define DLGOPEN_OPTION8          0x0080

/*
 * �t���O:
 *     LOWORD�͕W���I��FileOpen()�̃t���O(OF_*)
 *     HIWORD�͈ȉ��̂����ꂩ
 */
#define OF_MUSTEXIST    0x00010000  /* ���[�U�[��[OK]���������ꍇ��         */
                                    /* �t�@�C�����K�����݂���               */
#define OF_NOSHOWSPEC   0x00020000  /* �ҏW�{�b�N�X�Ɍ������@��\�����Ȃ�   */
#define OF_SHOWSPEC     0x00000000  /* �ҏW�{�b�N�X�Ɍ������@��\������     */
#define OF_SAVE         0x00040000  /* [OK]�{�^�����u�ۑ��v���Ӗ�����       */
#define OF_OPEN         0x00080000  /* [OK]�{�^�����u�J���v���Ӗ�����       */
#define OF_NOOPTIONS    0x00100000  /* �I�v�V�������\���ɂ���             */

/*  DlgDirLst() �̑��� */
#define ATTRFILELIST    0x0000         /* �t�@�C���̂�               */
#define ATTRDIRLIST     0xC010         /* �f�B���N�g���ƃh���C�u�̂� */
#define CBEXTMAX        6              /* "\*.txt"�̃o�C�g��         */


#define IDF(id)  ((id) & ~DLGOPEN_OPTION)  /* �R���g���[��ID����t���O���擾 */
#define FID(f)   ((f)  |  DLGOPEN_OPTION)  /* �t���O����R���g���[��ID���擾 */

/***************** �O���[�o���ϐ� *************************/

extern char        achFileName[128]; /* �t�@�C���̃p�X��                    */
extern DWORD       dwOffset;         /* DIB�t�@�C���̌��݈ʒu���w���|�C���^ */
extern RECT        rcClip;           /* ���݂̃N���b�s���O�����`            */
extern BOOL        fPalColors;       /* ���݂�DIB�J���[�e�[�u����RGB�l�ł�  */
                                     /* �Ȃ��p���b�g�C���f�b�N�X�Ȃ��TRUE  */
extern BOOL        bDIBToDevice;     /* SetDIBitsToDevice()���g���ăf�[�^�� */
                                     /* �]��                                */
extern BOOL        bLegitDraw;       /* �`��\�ȃr�b�g�}�b�v�����邩?     */
extern WORD        wTransparent;     /* DC�̃��[�h�@�@�@�@�@                */
extern char        szAppName[];      /* �A�v���P�[�V�������@�@�@�@�@�@      */
extern HPALETTE    hpalCurrent;      /* ���݂̃p���b�g�����ʂ���n���h��    */
extern HANDLE      hdibCurrent;      /* ���݂̃�����DIB�����ʂ���n���h��   */
extern HBITMAP     hbmCurrent;       /* ���݂̃������r�b�g�}�b�v�����ʂ���  */
                                     /* �n���h��                            */
extern HANDLE      hbiCurrent;       /* ���݂̃r�b�g�}�b�v���\���̂�      */
                                     /* ���ʂ���n���h��                    */
extern DWORD       dwStyle;          /* �A�v���P�[�V�����E�C���h�E��        */
                                     /* �X�^�C���r�b�g                      */

/***************** �֐��̐錾 *******************/

DWORD PASCAL lread(int, VOID FAR *, DWORD);
DWORD PASCAL lwrite(int, VOID FAR *, DWORD);

/***********************************************************/
/* dib.c���W���[���Ŏg����֐��̐錾�@�@�@�@�@           */
/***********************************************************/

extern	HANDLE      OpenDIB (LPSTR);
extern	HANDLE		OpenMemDIB(HWND);
extern	BOOL        WriteDIB (LPSTR,HANDLE);
extern	WORD        GetPaletteSize (VOID FAR *);
extern	WORD		GetDibNumColorsByHandle(HANDLE);
extern	WORD        GetDibNumColors (VOID FAR *);
extern	HPALETTE    CreateDibPalette (HANDLE);
extern	HPALETTE    CreateBIPalette (LPBITMAPINFOHEADER);
extern	HANDLE      DibFromBitmap (HBITMAP, DWORD, WORD, HPALETTE);
extern	HBITMAP     BitmapFromDib (HANDLE, HPALETTE);
extern	BOOL        DibBlt (HDC, int, int, int, int, HANDLE, int, int, LONG);
extern	BOOL        StretchDibBlt (HDC, int, int, int, int, HANDLE, int, int, int, int, LONG);
extern	BOOL        DibInfo (HANDLE,LPBITMAPINFOHEADER);
extern	HANDLE      ReadDibBitmapInfo (int);
extern	BOOL        DrawBitmap (HDC, int, int, HBITMAP, DWORD);
