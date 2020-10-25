#!/bin/csh

psql -h $MELONPAN_DB -U postgres melonpan <<EOF
select mailladdr_comp();
\q
EOF
