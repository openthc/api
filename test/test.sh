#!/bin/bash
#
# OpenTHC Test Runner
#

set -o errexit
set -o nounset

f=$(readlink -f "$0")
d=$(dirname "$f")
dt=$(date)

cd "$d"

out_path="../webroot/test-output"
if [ ! -d "$out_path" ]
then
	mkdir "$out_path"
fi


#
#
../vendor/bin/phpunit "$@" 2>&1 | tee "$out_path/output.txt"
note=$(tail -n1 "$out_path/output.txt")

#
# Get Transform
if [ ! -f "report.xsl" ]
then
	wget -q https://openthc.com/pub/phpunit/report.xsl
fi

xsltproc \
	--nomkdir \
	--output "$out_path/output.html" \
	report.xsl \
	"$out_path/output.xml"

sed "s/%TEST_DATE%/$dt/g; s/%TEST_NOTE%/$note/g;" ./report.html > ../webroot/test-output/index.html
