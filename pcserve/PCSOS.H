/************************************************************************
 *
 *			ÇoÇbÅ|ÇrÇdÇqÇuÇd
 *
 *		ñºèÃ		: ÇnÇrï ÉwÉbÉ_
 *		ÉtÉ@ÉCÉãñº	: pcsos.h
 *		çÏê¨é“		: s.aizawa
 *
 ************************************************************************/

#ifdef	OS_MSDOS
#define	ENTER_CRITICAL_SECTION(p1)
#define	LEAVE_CRITICAL_SECTION(p1)
#define	INIT_CRITICAL_SECTION(p1)
#define	DELETE_CRITICAL_SECTION(p1)
#define	CRITICALSECTION			SHORT
typedef	char _huge *HPSTR;
typedef	int _huge *HPINT;
#define	HALLOC(_size)	_halloc(_size,1)
#define	HFREE(_p)	_hfree(_p);
#define	HMEMSET(_p,_data,_size)	{LONG l;for(l=0;l<_size;l++) ((HPSTR)_p)[l]=_data;}
#define	HMEMCPY(_p1,_p2,_size)	{LONG l;for(l=0;l<_size;l++) ((HPSTR)_p1)[l]=((HPSTR)_p2)[l];}
#endif

#ifdef	OS_WINDOWS
#include <windows.h>
#define	ENTER_CRITICAL_SECTION(p1)
#define	LEAVE_CRITICAL_SECTION(p1)
#define	INIT_CRITICAL_SECTION(p1)
#define	DELETE_CRITICAL_SECTION(p1)
#define	CRITICALSECTION			SHORT
typedef	char _huge *HPSTR;
typedef	int _huge *HPINT;
#define	HALLOC(_size)	_halloc(_size,1)
#define	HFREE(_p)	_hfree(_p);
#define	HMEMSET(_p,_data,_size)	{LONG l;for(l=0;l<_size;l++) _p[l]=_data;}
#define	HMEMCPY(_p1,_p2,_size)	hmemcpy(_p1,_p2,_size)
#endif

#ifdef	OS_WNT
#include <windows.h>
#define	ENTER_CRITICAL_SECTION(p1)	{if(active)EnterCriticalSection(p1);}
#define	LEAVE_CRITICAL_SECTION(p1)	{if(active)LeaveCriticalSection(p1);}
#define	INIT_CRITICAL_SECTION(p1)	InitializeCriticalSection(p1)
#define	DELETE_CRITICAL_SECTION(p1)	DeleteCriticalSection(p1)
#define	CRITICALSECTION			CRITICAL_SECTION
typedef	char *HPSTR;
typedef	int *HPINT;
#define	HALLOC(_size)	malloc(_size)
#define	HFREE(_p)	free(_p);
#define	HMEMSET(_p,_data,_size)	memset(_p,_data,_size)
#define	HMEMCPY(_p1,_p2,_size)	memcpy(_p1,_p2,_size)
#endif
