#include "windows.h"		/* ���ׂĂ�Windows�A�v���P�[�V�����ɕK�v */
#include <string.h>
#include "generic.h"		/* ���̃v���O�����ɕK�v�ȃw�b�_�[        */
#include "cap.h"

HANDLE hInst;			/* ���݂̃C���X�^���X                    */

/****************************************************************************

    �֐�: WinMain(HANDLE, HANDLE, LPSTR, int)

    �ړI: �������֐��̌Ăяo���ƃ��b�Z�[�W���[�v����

    �R�����g:

        Windows�́A���̊֐��̖��O���v���O�����̏����G���g���|�C���g�Ƃ��ĔF��
        ���܂��BWinMain�֐��́A�����v���O�����̕ʂ̃C���X�^���X�����s���łȂ�
        ��΃A�v���P�[�V�������������[�`�����Ăяo���܂��B�܂��A�˂ɃC���X�^
        ���X���������[�`�����Ăяo���܂��B���̌�A�ŏ�ʂ̐���\���ł���u���b
        �Z�[�W�̎擾�ƃf�B�X�p�b�`���s�����[�v�v�����s���āA�c��̏������s����
        ���B���̃��[�v��WM_QUIT���b�Z�[�W���󂯎�����Ƃ��ɏI�����܂��B���̂�
        ��WinMain�֐���PostQuitMessage()�̖߂�l��Ԃ��A�A�v���P�[�V�����̃C��
        �X�^���X���I�������܂��B

        �ʏ�A���b�Z�[�W���[�v�ɓ���O�Ɋ֐��̏����𒆎~���Ȃ���΂Ȃ�Ȃ��ꍇ
        �ɂ́ANULL��Ԃ��܂��B

****************************************************************************/

int PASCAL WinMain(hInstance, hPrevInstance, lpCmdLine, nCmdShow)
HANDLE hInstance;			     /* ���݂̃C���X�^���X        */
HANDLE hPrevInstance;			     /* �ȑO�̃C���X�^���X        */
LPSTR lpCmdLine;			     /* �R�}���h���C��            */
int nCmdShow;				     /* �\���̃^�C�v              */
                                             /* (�ʏ�܂��̓A�C�R��)      */
{
    MSG msg;				     /* ���b�Z�[�W                */

    if (!hPrevInstance)			 /* �ق��̃C���X�^���X�����s����? */
	if (!InitApplication(hInstance)) /* ���ʂ̏���������              */
	    return (FALSE);		 /* �������Ɏ��s�����ꍇ�͏I��    */

    /* �C���X�^���X���Ƃɏ��������������s */

    if (!InitInstance(hInstance, nCmdShow))
        return (FALSE);

    /* WM_QUIT���b�Z�[�W���󂯎��܂Ń��b�Z�[�W�̎擾�ƃf�B�X�p�b�`���s�� */

    while (GetMessage(&msg,	  /* ���b�Z�[�W�\����                       */
	    NULL,		  /* ���b�Z�[�W���󂯎��E�B���h�E�n���h�� */
	    NULL,		  /* ���ׂ郁�b�Z�[�W�̉����l               */
	    NULL))		  /* ���ׂ郁�b�Z�[�W�̏���l               */
	{
	TranslateMessage(&msg);	  /* ���z�L�[�R�[�h�̕ϊ�                   */
	DispatchMessage(&msg);	  /* ���b�Z�[�W���E�B���h�E�Ƀf�B�X�p�b�`   */
    }
    return (msg.wParam);	  /* PostQuitMessage�֐��̖߂�l��Ԃ�      */
}


/****************************************************************************

    �֐�: InitApplication(HANDLE)

    �ړI: �E�B���h�E�f�[�^�̏������ƃE�B���h�E�N���X�̓o�^

    �R�����g:

        ���̊֐��́A���̃A�v���P�[�V�����̃C���X�^���X���܂�1�����s����Ă�
        �Ȃ��Ƃ������A�A�v���P�[�V�����̏������������ɌĂяo����܂��B���̊֐�
        �����������������s����̂́A���s���̃C���X�^���X�̐��ɂ�����炸�ŏ���
        1�x�����ł��B

        �����ł́AWNDCLASS�\���̂ɕK�v�Ȓl��ݒ肵�AWindows��RegisterClass()��
        �����Ăяo�����Ƃɂ��E�B���h�E�N���X�����������܂��B���̃A�v���P�[
        �V�����̂��ׂẴC���X�^���X�͓����E�B���h�E�N���X���g�p���邽�߁A�E�B
        ���h�E�N���X�̏��������K�v�Ȃ͍̂ŏ��̃C���X�^���X������������Ƃ�����
        �ł��B

****************************************************************************/

BOOL InitApplication(hInstance)
HANDLE hInstance;			       /* ���݂̃C���X�^���X       */
{
    WNDCLASS  wc;

    /*
     * �E�B���h�E�N���X�\���̂ɁA���C���E�B���h�E��
     * ���e���L�q����p�����[�^��ݒ�
     */

    wc.style = NULL;                    /* �N���X�X�^�C��                   */
    wc.lpfnWndProc = MainWndProc;       /* ���̃N���X�̃E�B���h�E�ɑ����� */
                                        /* ���b�Z�[�W���擾����֐�         */
    wc.cbClsExtra = 0;                  /* �N���X���Ƃ̕⑫�f�[�^�Ȃ�       */
    wc.cbWndExtra = 0;                  /* �E�B���h�E���Ƃ̕⑫�f�[�^�Ȃ�   */
    wc.hInstance = hInstance;           /* �N���X�����L����A�v���P�[�V���� */
    wc.hIcon = LoadIcon(NULL, IDI_APPLICATION);
    wc.hCursor = LoadCursor(NULL, IDC_ARROW);
    wc.hbrBackground = GetStockObject(WHITE_BRUSH); 
    wc.lpszMenuName =  "GenericMenu";   /*
                                         * ���\�[�X�t�@�C��(.rc)�Ŏw�肵��
                                         * ���j���[��
                                         */
    wc.lpszClassName = "GenericWClass"; /* CreateWindow�Ŏg���閼�O     */

    /* �E�B���h�E�N���X��o�^���A�����܂��͎��s�������R�[�h��Ԃ� */
    return (RegisterClass(&wc));
}


/****************************************************************************

    �֐�: InitInstance(HANDLE, int)

    �ړI: �C���X�^���X�n���h���̕ۑ��ƃ��C���E�B���h�E�̍쐬

    �R�����g:

        ���̊֐��́A���̃A�v���P�[�V�����̂��ׂẴC���X�^���X�̏��������ɌĂ�
        �o����A�C���X�^���X�Ԃŋ��L�ł��Ȃ����������������s���܂��B

        �����ł́A�C���X�^���X�n���h�����X�^�e�B�b�N�ϐ��ɕۑ����āA���C��
        �v���O�����̃E�B���h�E���쐬���A�\�����܂��B

****************************************************************************/

BOOL InitInstance(hInstance, nCmdShow)
    HANDLE          hInstance;       /* ���݂̃C���X�^���X               */
    int             nCmdShow;        /* �ŏ���ShowWindow()�Ăяo���̈��� */
{
    HWND            hWnd;            /* ���C���E�B���h�E�̃n���h��       */

    /*
     * �C���X�^���X�n���h�����X�^�e�B�b�N�ϐ��ɕۑ�����B���̃n���h���́A
     * InitInstance�̎��s��ɃA�v���P�[�V��������Windows���Ăяo���Ƃ���
     * �p�ɂɎg����
     */

    hInst = hInstance;

    /* ���̃A�v���P�[�V�����C���X�^���X�̃��C���E�B���h�E���쐬 */

    hWnd = CreateWindow(
        "GenericWClass",               /* RegisterClass()�Ăяo�����Q��    */
        "Generic",                     /* �E�B���h�E�^�C�g���o�[�̃e�L�X�g */
        WS_OVERLAPPEDWINDOW,           /* �E�B���h�E�X�^�C��               */
        CW_USEDEFAULT,                 /* �f�t�H���g�̐����ʒu             */
        CW_USEDEFAULT,                 /* �f�t�H���g�̐����ʒu             */
        CW_USEDEFAULT,                 /* �f�t�H���g�̕�                   */
        CW_USEDEFAULT,                 /* �f�t�H���g�̍���                 */
        NULL,                          /*
                                        * �I�[�o�[���b�v�E�B���h�E��
                                        * �e�E�B���h�E�������Ȃ�
                                        */
        NULL,                          /* �E�B���h�E�N���X�̃��j���[���g�� */
        hInstance,                     /*
                                        * ���̃C���X�^���X��
                                        * ���̃E�B���h�E�����L
                                        */
        NULL                           /* �|�C���^�͕s�v                   */
    );

    /* �E�B���h�E���쐬�ł��Ȃ������Ƃ��́u���s�v������FALSE��Ԃ� */
    if (!hWnd)
        return (FALSE);

    /* �E�B���h�E��\�����A���̃N���C�A���g�̈���X�V����TRUE��Ԃ� */

    ShowWindow(hWnd, nCmdShow);  /* �E�B���h�E��\��              */
    UpdateWindow(hWnd);          /* WM_PAINT���b�Z�[�W�𑗂�      */
    return (TRUE);               /* PostQuitMessage�̖߂�l��Ԃ� */
}

/****************************************************************************

    �֐�: MainWndProc(HWND, UINT, WPARAM, LPARAM)

    �ړI: ���b�Z�[�W����

    ���b�Z�[�W:

        WM_COMMAND - �A�v���P�[�V�������j���[
                     ([�o�[�W�������]�_�C�A���O�{�b�N�X)
        WM_DESTROY - �E�B���h�E�̔j��

    �R�����g:

        IDM_ABOUT���b�Z�[�W���������邽�߁AMakeProcInstance()���Ăяo����
        About()�֐��̌��݂̃C���X�^���X�A�h���X���擾���܂��B���̌�A
        Dialogbox�֐����Ăяo���āAgeneric.rc�t�@�C���̏��ɏ]���ă_�C�A���O
        �{�b�N�X���쐬���AAbout()�֐��ɐ����n���܂��BAbout()�֐���������
        �������_�ŁA�C���X�^���X�A�h���X��������܂��B

****************************************************************************/

long CALLBACK __export MainWndProc(hWnd, message, wParam, lParam)
HWND hWnd;				  /* �E�B���h�E�n���h�� */
UINT message;				  /* ���b�Z�[�W�̃^�C�v */
WPARAM wParam;				  /* �ǉ����           */
LPARAM lParam;				  /* �ǉ����           */
{
    switch (message) {
        case WM_COMMAND:
            if (wParam == IDM_ABOUT){
	    	{
				CapPachinko(hWnd, (LPSTR)"www");

			}
		break;

	    } 
               return (DefWindowProc(hWnd, message, wParam, lParam));
        case WM_DESTROY:             /* ���b�Z�[�W: �E�B���h�E��j��  */
            PostQuitMessage(0);
            break;

        default:                     /* ���������b�Z�[�W�͂��̂܂ܓn�� */
            return (DefWindowProc(hWnd, message, wParam, lParam));
    }
    return (NULL);
}

