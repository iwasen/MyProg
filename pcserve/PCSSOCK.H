/************************************************************************
 *
 *			�o�b�|�r�d�q�u�d
 *
 *		����		: �\�P�b�g���C�u����
 *		�t�@�C����	: pcssock.h
 *		�쐬��		: s.aizawa
 *
 ************************************************************************/

#ifdef	OS_MSDOS
//#include <d:\socket\include\sys\types.h>
//#include <d:\socket\include\sys\time.h>
//#include <d:\socket\include\sys\errno.h>
//#include <d:\socket\include\sys\socket.h>
//#include <d:\socket\include\sys\ioctl.h>
//#include <d:\socket\include\sys\proto.h>
//#define	closesocket	soclose
#endif

#ifdef	OS_WINDOWS
#include "winsock.h"
#endif

#ifdef	OS_WNT
#include <winsock.h>
#endif
