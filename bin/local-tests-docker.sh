#!/usr/bin/env bash

set -e

REAL_BIN_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
REAL_PROJECT_DIR=$(dirname ${REAL_BIN_DIR})
BIN_DIR="/var/www/bin"
PROJECT_DIR="/var/www"
_VERSIONS="5.3 5.4 5.5 5.6 7.0 7.1"
VERSIONS=$(echo ${_VERSIONS} | tr " " "\n")

for version in ${VERSIONS}
do
    mkdir -p ${REAL_PROJECT_DIR}/build/php-versions/${version/./}
done

if [ -z $(docker images -q splitbrain/phpfarm:jessie) ]; then
    ${REAL_BIN_DIR}/install-local-env.sh
fi

container="docker run --rm -t -i -v ${REAL_PROJECT_DIR}:${PROJECT_DIR}:rw splitbrain/phpfarm:jessie"

function resetTextStyle()
{
    tput sgr0
}

function printHeader()
{
    tput setaf 0
    tput setab 2
    echo -n " # $1 "
    resetTextStyle
    echo ''
}

function printInfo()
{
    echo "";
    tput setaf 0
    tput setab 7
    echo -n " ### $1 "
    resetTextStyle
}

function runTestsFor()
{
    local version=$1
    local escapedVersion=${version/./}
    local versionDir="$PROJECT_DIR/build/php-versions/$escapedVersion"
    local realVersionDir="$REAL_PROJECT_DIR/build/php-versions/$escapedVersion"
    local phpCommand="php-$version"

    printHeader "PHP $version"
    ${container} ${phpCommand} --version

    if [ ! -f "$realVersionDir/autoload.php" ]; then
        cp composer.json ~composer.json

        echo 'Installing composer.phar...'
        ${container} bash -c "cd ${versionDir} && ${phpCommand} -n ${BIN_DIR}/install-composer.php"

        echo 'Updating dependencies...'
        local configComposer="config vendor-dir 'build/php-versions/$escapedVersion'"
        ${container} \
            bash -c \
            "cd ${PROJECT_DIR} && ${phpCommand} -n ${versionDir}/composer.phar ${configComposer}"
        ${container} ${phpCommand} -n "$versionDir/composer.phar" update --dev

        rm ${REAL_PROJECT_DIR}/composer.lock
        rm composer.json
        mv ~composer.json composer.json
    fi

    echo 'Executing tests...'
    local extensions="-d zend_extension=/phpfarm/inst/php-${version}/lib/xdebug.so"
    extensions=''
    ${container} ${phpCommand} -n ${extensions} \
        "build/php-versions/$escapedVersion/bin/phpunit" \
        --bootstrap "build/php-versions/$escapedVersion/autoload.php" \
        --coverage-html "build/logs/$escapedVersion"
    echo ""
}

function runTestsForAll
{
    for version in ${VERSIONS}
    do
        runTestsFor ${version}
    done
}

if [ "$1" == "-h" ]
then
    tput setaf 2
    echo "Usage:" $(basename $0)
    echo "    -h - this command"
    echo "    5.3 - run tests for PHP 5.3"
    echo "    without arguments - run tests for all supported php versions"
    echo -n "Supported php versions: 5.3, 5.4, 5.5, 5.6, 7.0, 7.1."
    resetTextStyle
    echo ""
    exit 0
fi

if [ -n "$1" ]
then
    v=$1
    printInfo "StackTrace                   "
    printInfo "Running tests for version $v"
    echo ""
    echo ""
    runTestsFor ${v}
else
    printInfo "StackTrace                    "
    printInfo "Running tests for all versions"
    echo ""
    echo ""
    runTestsForAll
fi
