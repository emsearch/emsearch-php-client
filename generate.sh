#!/bin/bash

rm -Rf ./generated/;
cp "/var/www/emsearch/public/docs/Developer/v1/openapi.json" ./openapi.json;
./vendor/bin/openapi-php-client-generator generate ./openapi.json generated emsearch\\Api
