#!/bin/csh

setenv ORACLE_SID BLCK

sqlplus anpn/melonpan@anpn.melonpan.net <<EOF
execute osusume_count_up
/
exit
EOF

