/********************************************************************
*
*     Title   : Check sum save area at user program end.
*
*     Name    : chksum.c
*
*     Entry   : Non
*
*     Linkage : Non
*
*     Exit    : Non
*
*     Version 00.00  1991.9.26 by Takeda
*
********************************************************************/

#pragma SECTION DATA=chksum

#include  "sg_cpu.h"

word   chksum  = 0xd44b;        /* Checksum data                  */

word   version = 0x0000;        /* program version 00 : 1991.9.30 */
