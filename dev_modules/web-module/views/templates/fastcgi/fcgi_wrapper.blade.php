#!/bin/sh
PHPRC={{ $EnvIni }}
export PHPRC
export PHP_FCGI_MAX_REQUESTS={{ $maxRequest }}
export PHP_FCGI_CHILDREN={{ $children }}
exec {{ $cgiExec }} -d  \
    -d disable_classes={{ $disabledClasses ?? '' }} \
    -d disable_functions={{ $disabledFunctions ?? '' }} \
    -d magic_quotes_gpc=off \
    -d open_basedir=
