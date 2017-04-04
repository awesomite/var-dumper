#!/usr/bin/env bash

set -e

BIN_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
PROJECT_DIR=$(dirname ${BIN_DIR})

docker run -it --rm --name my-running-script -v "$PROJECT_DIR":/usr/src/myapp -w /usr/src/myapp diegomarangoni/hhvm:cli hhvm vendor/bin/phpunit ${@}
