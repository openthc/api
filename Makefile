#
# OpenTHC API Makefile
#
# (c) 2018 OpenTHC, Inc.
# This file is part of OpenTHC API released under MIT License
#
# SPDX-License-Identifier: MIT
#

SHELL = /bin/bash
.PHONY: help

export APP_ROOT := $(realpath $(@D) )

#
# Help, the default target
help:
	@echo
	@echo "You must supply a make command, try 'make install'"
	@echo
	@grep -ozP "#\n#.*\n[\w\-]+:" $(MAKEFILE_LIST) \
		| awk '/[a-zA-Z0-9_-]+:/ { printf "  \033[0;49;32m%-20s\033[0m%s\n", $$1, gensub(/^# /, "", 1, x) }; { x=$$0 }'
	@echo

#
# Generate Client Libraries
codegen:

	# Bash
	# rm -fr ./webroot/sdk/bash
	# java -jar swagger-codegen-cli.jar \
	# 	generate \
	# 	--input-spec ./webroot/openapi.yaml \
	# 	--lang bash \
	# 	--output ./webroot/sdk/bash \
	# 	>/dev/null \
	# 	|| true


	# Go
	rm -fr ./webroot/sdk/go
	rm -fr ./webroot/sdk/go.zip
	mkdir ./webroot/sdk/go
	java -jar swagger-codegen-cli.jar \
		generate \
		--input-spec ./webroot/openapi.yaml \
		--lang go \
		--output ./webroot/sdk/go \
		>/dev/null \
		|| true
	#cd ./webroot/sdk && zip -r go.zip ./go/


	# JavaScript
	rm -fr ./webroot/sdk/javascript
	mkdir ./webroot/sdk/javascript
	java -jar swagger-codegen-cli.jar \
		generate \
		--input-spec ./webroot/openapi.yaml \
		--lang javascript \
		--output ./webroot/sdk/javascript \
		>/dev/null \
		|| true


	# PHP
	rm -fr ./webroot/sdk/php
	rm -fr ./webroot/sdk/php.zip

	java -jar swagger-codegen-cli.jar \
		generate \
		--input-spec ./webroot/openapi.yaml \
		--lang php \
		--output ./webroot/sdk/php \
		>/dev/null \
		|| true

	zip -r ./webroot/sdk/php.zip ./webroot/sdk/php/ \
		>/dev/null


	# Python
	rm -fr ./webroot/sdk/python
	mkdir ./webroot/sdk/python
	java -jar swagger-codegen-cli.jar \
		generate \
		--input-spec ./webroot/openapi.yaml \
		--lang python \
		--output ./webroot/sdk/python \
		>/dev/null \
		|| true
	zip -r ./webroot/sdk/python.zip ./webroot/sdk/python/



#
# PHP Composer
composer:
	composer update --no-dev -a


#
# Create Documentation
docs: docs-asciidoc docs-doxygen docs-openapi docs-openapi-html docs-redoc
		# _make_docs_asciidoc
		# _make_docs_doxygen
		# _make_docs_openapi
		# _make_docs_openapi_html
		# _make_docs_redoc

#
# Create Asciidoctor output
docs-asciidoc:
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


#
# Create Doxygen output
docs-doxygen:
	doxygen etc/Doxyfile


#
# Create OpenAPI Docs
docs-openapi:

	# if [ ! -d ./webroot/openapi-ui/ ]
	# then
	# 	git clone https://github.com/swagger-api/swagger-ui.git ./webroot/openapi-ui/
	# else
	# 	pushd ./webroot/openapi-ui/
	# 	git pull
	# 	popd
	# fi

	php ./bin/build-openapi.php > ./webroot/openapi.yaml


#
# Create OpenAPI based HTML output
docs-openapi-html:
	rm -fr ./webroot/doc/openapi-html
	java -jar swagger-codegen-cli.jar \
		generate \
		--input-spec ./webroot/openapi.yaml \
		--lang html \
		--output ./webroot/doc/openapi-html || true

	# HTML-v2 SDK?
	rm -fr ./webroot/doc/openapi-html-v2
	java -jar swagger-codegen-cli.jar \
		generate \
		--input-spec ./webroot/openapi.yaml \
		--lang html2 \
		--output ./webroot/doc/openapi-html-v2 || true


#
# Create redoc output
docs-redoc: npm
	mkdir -p ./webroot/doc/redoc
	./node_modules/.bin/redoc-cli bundle ./webroot/openapi.yaml
	mv ./redoc-static.html ./webroot/doc/redoc/index.html


#
# NPM Update
npm:
	npm update

#
# Install Live Environment
install: composer npm

	apt-get -qy install doxygen graphviz libyaml-dev default-jre php-dev php-yaml ruby

	gem install asciidoctor
	gem install asciidoctor-diagram asciidoctor-revealjs coderay pygments.rb
	gem install asciidoctor-pdf --pre

	wget https://repo1.maven.org/maven2/io/swagger/codegen/v3/swagger-codegen-cli/3.0.27/swagger-codegen-cli-3.0.27.jar \
		-O swagger-codegen-cli.jar

	cp node_modules/jquery/dist/jquery.min.js webroot/js/jquery.js

	cp node_modules/jquery-ui/dist/themes/base/jquery-ui.min.css webroot/css/jquery-ui.css
	cp node_modules/jquery-ui/dist/jquery-ui.js webroot/js/jquery-ui.js

	cp node_modules/bootstrap/dist/css/bootstrap.min.css webroot/css/bootstrap.css
	cp node_modules/bootstrap/dist/js/bootstrap.bundle.min.js webroot/js/bootstrap.js


#
# Update All (media, docs, )
update: update-docs update-media

#
# Update Documentation
update-docs:

	asciidoctor \
		--verbose \
		--backend=html5 \
		--require=asciidoctor-diagram \
		--section-numbers \
		--attribute allow-uri-read \
		--out-file=./webroot/man/index.html \
		./content/man/_.asciidoc

	asciidoctor \
		--verbose \
		--backend=pdf \
		--require=asciidoctor-diagram \
		--require=asciidoctor-pdf \
		--section-numbers \
		--attribute allow-uri-read \
		--out-file=./webroot/man/openthc-manual.pdf \
		./content/man/_.asciidoc

	# asciidoctor \
	# 	--backend=revealjs \
	# 	--require=asciidoctor-diagram \
	# 	--require=asciidoctor-revealjs \
	# 	--out-file=./webroot/doc/slides.html \
	# 	./content/man/_.asciidoc
