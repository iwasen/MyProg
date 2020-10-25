#include <stdio.h>
#include <signal.h>
#include <jcurses.h>

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3sgnl()
		ROUTINE		: 中断処理
		INPUT		: none
		OUTPUT		: none
*/

d3sgnl()
{
	int	sgnl10();

	signal(SIGHUP, sgnl10);
	signal(SIGINT, sgnl10);
	signal(SIGQUIT, sgnl10);
	signal(SIGILL, sgnl10);
	signal(SIGTRAP, sgnl10);
	signal(SIGIOT, sgnl10);
	signal(SIGEMT, sgnl10);
	signal(SIGFPE, sgnl10);
	signal(SIGBUS, sgnl10);
	signal(SIGSEGV, sgnl10);
	signal(SIGSYS, sgnl10);
}

static sgnl10()
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

	/* カーシズ終了処理 */

	clear();
	refresh();
	slk_clear();
	endwin();
	resetterm();

	/* すべてのデータセットをクローズする */

	d3qend();

	/* 自プロセスをグループリーダーとする */

	setpgrp();

	/* SIGCLD を無視する */

	signal(SIGCLD, SIG_IGN);

	/* 自プロセスを終了し、子プロセスにSEGHUPを送る */

	exit(0);
}

