#!/bin/bash

rm -Rf ./generated/;
rm -Rf ./tests/generated/;
cp "/var/www/emsearch/public/docs/Developer/v1/openapi.json" ./openapi.json;
./vendor/bin/openapi-php-client-generator generate ./openapi.json generated Emsearch\\Api tests/generated Emsearch\\Api\\Tests
