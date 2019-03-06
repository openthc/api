#!/bin/bash
#
# Run the Test Cases
#

f=$(readlink -f "$0")
d=$(dirname "$f")

cd "$d"


# set -o errexit # phpnit errexits
# set -o nounset

../vendor/bin/phpunit 

xsltproc ./style.xsl ../webroot/reports/phpunit.xml > ../webroot/reports/phpunit.html
