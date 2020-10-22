#
# OpenTHC API Makefile
#

#
# Help, the default target
help:
	@echo
	@echo "You must supply a make command"
	@echo
	@grep --null-data --only-matching --perl-regexp "#\n#.*\n[\w\-]+:.*\n" $(MAKEFILE_LIST) \
		| awk '/[a-zA-Z0-9_-]+:/ { printf "  \033[0;49;32m%-20s\033[0m%s\n", $$1, gensub(/^# /, "", 1, x) }; { x=$$0 }' \
		| sort
	@echo


#
# Install necessary stuff
install:

	apt-get -qy install doxygen graphviz libyaml-dev default-jre php-dev php-yaml ruby

	gem install asciidoctor
	gem install asciidoctor-diagram asciidoctor-revealjs coderay pygments.rb
	gem install asciidoctor-pdf --pre


#
# All the things
all: code-openapi docs


#
# Build a bunch of docs
docs: docs-asciidoc docs-doxygen docs-openapi docs-redoc


#
# Generate asciidoc formats
docs-asciidoc:

	mkdir -p ./webroot/doc

	asciidoctor \
		--verbose \
		--backend=html5 \
		--require=asciidoctor-diagram \
		--section-numbers \
		--out-file=./webroot/doc/index.html \
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
		--out-file=./webroot/doc/slides.html \
		./doc/index.ad


#
# Generate Doxygent stuff
docs-doxygen:

	doxygen etc/Doxyfile


#
# Build the OpenAPI docs (/doc/openapi-ui/dist)
docs-openapi:

	# git clone https://github.com/swagger-api/swagger-ui.git ./webroot/openapi-ui/
	# cd ./webroot/openapi-ui/ && git pull || true

	php ./bin/build-openapi.php > ./webroot/openapi.yaml

#	rm -fr ./webroot/doc/openapi-html
#	java -jar swagger-codegen-cli.jar \
#		generate \
#		--input-spec ./webroot/openapi.yaml \
#		--lang html \
#		--output ./webroot/doc/openapi-html || true
#
#	#
#	rm -fr ./webroot/doc/openapi-html2
#	java -jar swagger-codegen-cli.jar \
#		generate \
#		--input-spec ./webroot/openapi.yaml \
#		--lang html2 \
#		--output ./webroot/doc/openapi-html2 || true


#
# Build API Reference with ReDoc
docs-redoc: docs-openapi
	./node_modules/.bin/redoc-cli bundle ./webroot/openapi.yaml
	mkdir -p ./webroot/redoc
	mv ./redoc-static.html ./webroot/redoc/index.html


#
# Generate JSON Schema Files from openapi/swagger
code-json-schema: docs-openapi

	# Building Schemas
	# The files in this repo are constructed from the components in the API project.
	rm -f ./json-schema/openthc/*json

	python \
		/opt/openapi2jsonschema/openapi2jsonschema/command.py \
		--output ./json-schema/openthc/ \
		--stand-alone \
		webroot/openapi.yaml

	# file:/opt/api.openthc.org/webroot/openapi.yaml
	# cd ./webroot/json-schema ; ls *json > index.txt


#
# https://github.com/swagger-api/swagger-codegen
# Generate openapi/swagger Docs
code-openapi: code-openapi-php docs-openapi

	# Bash
	rm -fr ./webroot/sdk/bash
	java -jar swagger-codegen-cli.jar \
		generate \
		--input-spec ./doc/openapi.yaml \
		--lang bash \
		--output ./webroot/sdk/bash || true

	# JavaScript
	rm -fr ./webroot/sdk/javascript
	java -jar swagger-codegen-cli.jar \
		generate \
		--input-spec ./doc/openapi.yaml \
		--lang javascript \
		--output ./webroot/sdk/javascript || true

	# Python
	rm -fr ./webroot/sdk/python
	java -jar swagger-codegen-cli.jar \
		generate \
		--input-spec ./doc/openapi.yaml \
		--lang python \
		--output ./webroot/sdk/python || true
	#zip -r ./webroot/sdk/python.zip ./webroot/sdk/python/

	# https://generator.swagger.io/#!/Admin/get_account
	# https://online.swagger.io/validator/debug?url=https://api.openthc.org/swagger.yaml


#
#
code-openapi-go:
	rm -fr ./webroot/sdk/go
	rm -fr ./webroot/sdk/go.zip
	#java -jar swagger-codegen-cli.jar \
	#	generate \
	#	--input-spec ./doc/openapi.yaml \
	#	--lang go \
	#	--output ./webroot/sdk/go || true
    #
	#cd ./webroot/sdk && zip -r go.zip ./go/


#
# Update the PHP SDK
code-openapi-php: docs-openapi

	rm -fr ./webroot/sdk/php
	rm -fr ./webroot/sdk/php.zip

	java -jar swagger-codegen-cli.jar \
		generate \
		--input-spec ./doc/openapi.yaml \
		--lang php \
		--output ./webroot/sdk/php || true

	zip -r ./webroot/sdk/php.zip ./webroot/sdk/php/

test: phpunit

#
# Execute Unit Tests
phpunit:
	./test/test.sh
	# ./vendor/bin/phpunit --bootstrap vendor/autoload.php --configuration test/phpunit.xml test/
