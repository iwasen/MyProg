/*	FILE DESCRIPTION

		FILE NAME	: d3exec.c
		ROUTINE		: コマンド起動
		REVISION	:
		REMARKS		:	88.04.22	S.Aizawa
*/

#include	<stdio.h>

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3exec.c
		ROUTINE		: メイン処理
		INPUT		: argv = parameter
		OUTPUT		: return value = error code
*/

d3exec(argv)
char	*argv;
{
	int	status, err;

	if ((err = fork()) == 0) {
		if (execvp(argv, &argv) == -1)
			_exit(1);
	} else {
		if (err == -1)
			return(0x100);
		if (wait(&status) == -1)
			status = 0x100;
		return(status);
	}
}

