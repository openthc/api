#!/bin/bash
#
# OpenTHC API Makefile
#
# (c) 2018 OpenTHC, Inc.
# This file is part of OpenTHC API released under MIT License
#
# SPDX-License-Identifier: MIT
#

set -o errexit
set -o errtrace
set -o nounset
set -o pipefail

BIN_SELF=$(readlink -f "$0")
APP_ROOT=$(dirname "$BIN_SELF")

CMD=${1:-help}

DONE_MAKE_CODE_OPENAPI=""
DONE_MAKE_CODE_OPENAPI_PHP=""
DONE_MAKE_DOCS=""
DONE_MAKE_DOCS_DOXYGEN=""
DONE_MAKE_DOCS_ASCIIDOC=""
DONE_MAKE_DOCS_OPENAPI=""

function _make_code_openapi()
{
	if [ -z "$DONE_MAKE_CODE_OPENAPI" ]
	then
		_make_docs_openapi
		_make_code_openapi_php

		# Go
		rm -fr ./webroot/sdk/go
		rm -fr ./webroot/sdk/go.zip
		mkdir ./webroot/sdk/go
		java -jar swagger-codegen-cli.jar \
			generate \
			--lang go \
			--input-spec ./webroot/openapi.yaml \
			--output ./webroot/sdk/go \
			>/dev/null \
			|| true
		#cd ./webroot/sdk && zip -r go.zip ./go/

		# JavaScript
		rm -fr ./webroot/sdk/javascript
		mkdir ./webroot/sdk/javascript
		java -jar swagger-codegen-cli.jar \
			generate \
			--lang javascript \
			--input-spec ./webroot/openapi.yaml \
			--output ./webroot/sdk/javascript \
			>/dev/null \
			|| true


		# Python
		rm -fr ./webroot/sdk/python
		mkdir ./webroot/sdk/python
		java -jar swagger-codegen-cli.jar \
			generate \
			--lang python \
			--input-spec ./webroot/openapi.yaml \
			--output ./webroot/sdk/python \
			>/dev/null \
			|| true
		zip -r ./webroot/sdk/python.zip ./webroot/sdk/python/

	fi
	DONE_MAKE_CODE_OPENAPI="done"
}

function _make_code_openapi_php()
{
	if [ -z "$DONE_MAKE_CODE_OPENAPI_PHP" ]
	then

		rm -fr ./webroot/sdk/php
		rm -fr ./webroot/sdk/php.zip

		java -jar swagger-codegen-cli.jar \
			generate \
			--verbose \
			--lang php \
			--api-package "FOO" \
			--input-spec ./webroot/openapi.yaml \
			--output ./webroot/sdk/php \
			>/dev/null \
			|| true

		zip -r ./webroot/sdk/php.zip ./webroot/sdk/php/ \
			>/dev/null \
			|| true

	fi
	DONE_MAKE_CODE_OPENAPI_PHP="done"
}


function _make_docs()
{
	if [ -z "$DONE_MAKE_DOCS" ]
	then
		_make_docs_asciidoc
		_make_docs_doxygen
		_make_docs_openapi
		_make_docs_openapi_html
		_make_docs_redoc
	fi
	DONE_MAKE_DOCS="done"
}

function _make_docs_asciidoc()
{
	if [ -z "$DONE_MAKE_DOCS_ASCIIDOC" ]
	then

		# mkdir -p ./webroot/doc/asciidoctor
		# mkdir -p ./webroot/doc/revealjs

		asciidoctor \
			--verbose \
			--backend=html5 \
			--require=asciidoctor-diagram \
			--section-numbers \
			--out-file=./webroot/doc/asciidoctor/index.html \
			./doc/index.ad

		asciidoctor \
			--verbose \
			--backend=pdf \
			--require=asciidoctor-diagram \
			--require=asciidoctor-pdf \
			--section-numbers \
			--out-file=./webroot/doc/openthc.pdf \
			./doc/index.ad

		asciidoctor \
			--backend=revealjs \
			--require=asciidoctor-diagram \
			--require=asciidoctor-revealjs \
			--out-file=./webroot/doc/revealjs/index.html \
			./doc/index.ad

	fi
	DONE_MAKE_DOCS_ASCIIDOC="done"
}


function _make_docs_openapi()
{
	if [ -z "$DONE_MAKE_DOCS_OPENAPI" ]
	then

		if [ ! -d ./webroot/openapi-ui/ ]
		then
			git clone https://github.com/swagger-api/swagger-ui.git ./webroot/openapi-ui/
		else
			pushd ./webroot/openapi-ui/
			git pull
			popd
		fi

		php ./bin/build-openapi.php > ./webroot/openapi.yaml

		# sed -i 's/url: "https:\/\/petstore.swagger.io\/v2\/swagger.json"/url: "\/openapi.yaml"/' webroot/openapi-ui/dist/swagger-initializer.js

	fi

	DONE_MAKE_DOCS_OPENAPI="done"

}

function _make_docs_doxygen()
{
	if [ -z "$DONE_MAKE_DOCS_DOXYGEN" ]
	then
		doxygen etc/Doxyfile
	fi
	DONE_MAKE_DOCS_DOXYGEN="done"
}

#
# HTML Stuff from OpenAPI
#
function _make_docs_openapi_html()
{
	rm -fr ./webroot/doc/openapi-html
	mkdir -p ./webroot/doc/openapi-html
	java -jar swagger-codegen-cli.jar \
		generate \
		--lang html \
		--input-spec ./webroot/openapi.yaml \
		--output ./webroot/doc/openapi-html || true

	# HTML-v2 SDK?
	rm -fr ./webroot/doc/openapi-html-v2
	mkdir -p ./webroot/doc/openapi-html-v2
	java -jar swagger-codegen-cli.jar \
		generate \
		--lang html2 \
		--input-spec ./webroot/openapi.yaml \
		--output ./webroot/doc/openapi-html-v2 || true

}

#
#
# Stopped Working Recently
function _make_docs_redoc()
{
	echo "SKIPPING REDOC" 1>&2
	# mkdir -p ./webroot/doc/redoc
	# ./node_modules/.bin/redoc-cli build ./webroot/openapi.yaml
	# mv ./redoc-static.html ./webroot/doc/redoc/index.html
}

#
# Action
case "$CMD" in

	#
	# All the things
	"install")

		composer install --no-ansi --no-dev --no-progress --quiet --classmap-authoritative

		npm install --quiet >/dev/null

		_make_docs
		_make_code_openapi

		. vendor/openthc/common/lib/lib.sh

		copy_bootstrap
		copy_fontawesome
		copy_jquery

		outpath="webroot/vendor/highlight.js"
		mkdir -p "$outpath/"
		cp node_modules/@highlightjs/cdn-assets/styles/atom-one-dark.min.css "$outpath/"
		cp node_modules/@highlightjs/cdn-assets/highlight.min.js "$outpath/"

		# curl https://home > index.html

		;;

	"system-install")

		# apt-get -qqy install doxygen graphviz libyaml-dev default-jre php-dev php-yaml python-pip ruby

		# gem install asciidoctor
		# gem install asciidoctor-diagram asciidoctor-revealjs coderay pygments.rb
		# gem install asciidoctor-pdf --pre

		wget https://repo1.maven.org/maven2/io/swagger/codegen/v3/swagger-codegen-cli/3.0.43/swagger-codegen-cli-3.0.43.jar \
			-O swagger-codegen-cli.jar

		;;

	#
	# Generate openapi/swagger Docs with https://github.com/swagger-api/swagger-codegen
	"code-openapi")
		_make_code_openapi
		;;
	#
	# Update the PHP SDK
	"code-openapi-php")
		_make_code_openapi_php
		_make_docs_openapi
		;;

	#
	# Build a bunch of docs
	"docs")
		_make_docs
		;;
	#
	# Generate asciidoc formats
	"docs-asciidoc")
		_make_docs_asciidoc
		;;
	#
	# Generate Doxygent stuff
	"docs-doxygen")
		_make_docs_doxygen
		;;
	#
	# Build the OpenAPI docs (/doc/openapi-ui/dist)
	"docs-openapi")
		_make_docs_openapi
		_make_docs_openapi_html
		;;
	#
	# Build API Reference with ReDoc
	"docs-redoc")
		_make_docs_openapi
		_make_docs_redoc
		;;

	"help"|*)
		echo
		echo "You must supply a make command"
		echo

		# works a bit better
		# grep --null-data --only-matching --perl-regexp '\s*#\n\s*#.*\n\s*["\w\-]+.*\n' "$0" \
		# 	| awk '{ printf "  \033[0;49;32m%-20s\033[0m%s\n", $0, gensub(/^# /, "", 1, x) };'

		grep --null-data --only-matching --perl-regexp '\s*#\n\s*#.*\n\s*["\w\-]+.*\n' "$0" \
			| awk '/\s*"\w+/ { printf "  \033[0;49;32m%-20s\033[0m  %s\n", $0, gensub(/^\s*# /, "", 1, x) }; { x=$$0 }' \
			| sort

		# parses make file
		# grep --null-data --only-matching --perl-regexp "#\n#.*\n[\w\-]+(:|\)).*\n" "$0" \
			# | awk '/[a-zA-Z0-9_-]+:/ { printf "  \033[0;49;32m%-20s\033[0m%s\n", $$1, gensub(/^# /, "", 1, x) }; { x=$$0 }'
		# 	| sort
		echo
		;;
esac
