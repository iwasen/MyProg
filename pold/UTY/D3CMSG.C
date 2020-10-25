#include <stdio.h>
#include <signal.h>
#include <jcurses.h>

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3cmsg()
		ROUTINE		: コマンド中断処理
		INPUT		: none
		OUTPUT		: none
*/

d3cmsg()
{
	int	cmsg10();

	signal(SIGHUP, cmsg10);
	signal(SIGINT, cmsg10);
	signal(SIGQUIT, cmsg10);
	signal(SIGILL, cmsg10);
	signal(SIGTRAP, cmsg10);
	signal(SIGIOT, cmsg10);
	signal(SIGEMT, cmsg10);
	signal(SIGFPE, cmsg10);
	signal(SIGBUS, cmsg10);
	signal(SIGSEGV, cmsg10);
	signal(SIGSYS, cmsg10);
}

static cmsg10()
{
	/* シグナル処理中のシグナルを無視する */

	signal(SIGHUP, SIG_IGN);
	signal(SIGINT, SIG_IGN);
	signal(SIGQUIT, SIG_IGN);
	signal(SIGILL, SIG_IGN);
	signal(SIGTRAP, SIG_IGN);
	signal(SIGIOT, SIG_IGN);
	signal(SIGEMT, SIG_IGN);
	signal(SIGFPE, SIG_IGN);
	signal(SIGBUS, SIG_IGN);
	signal(SIGSEGV, SIG_IGN);
	signal(SIGSYS, SIG_IGN);

	/* すべてのデータセットをクローズする */

	d3qend();

	/* 自プロセスをグループリーダーとする */

	setpgrp();

	/* SIGCLD を無視する */

	signal(SIGCLD, SIG_IGN);

	/* 自プロセスを終了し、子プロセスにSEGHUPを送る */

	exit(0);
}

