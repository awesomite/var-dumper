#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

if [[ "$DEPENDENCIES" = 'low' ]]; then
  export PHP_ARGS=$(php -r "echo '-d error_reporting=', E_ALL ^ (E_DEPRECATED | E_USER_DEPRECATED);");
else
  export PHP_ARGS='';
fi

if php -r "var_export(get_loaded_extensions(true));" | grep --quiet -i xdebug; then
  if [ -f ${DIR}/../vendor/bin/coveralls ]; then
    php ${PHP_ARGS} ${DIR}/../vendor/bin/coveralls -v
  else
    php ${PHP_ARGS} ${DIR}/../vendor/bin/php-coveralls -v
  fi
else
  echo "Xdebug is not installed"
fi
