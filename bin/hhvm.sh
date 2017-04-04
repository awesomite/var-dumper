#!/usr/bin/env bash

docker run --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp diegomarangoni/hhvm:cli hhvm ${@}
