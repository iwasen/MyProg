#include <stdio.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <errno.h>
#include <string.h>
#include "la_ws/include/d3libc.h"
#include "d3item.h"

static	int	m_no, num;

main(argc, argv)
int	argc;
char	*argv[];
{
	int	rtn, c_n, j, end;
	D_CELL	*c_p;
	

	d3init();
	m_no = d3mgno();

	if ((rtn = d3file(argv[1], m_no, &c_p, &c_n)) != 0) {
		printf("error in d3file: %d\n", rtn);
		exit(1);
	}

	for (j=0; j<c_n; j++)
		printf("%s\n",(c_p+j)->ptr);
	d3free(m_no);

	d3term();
	return(0);
}
