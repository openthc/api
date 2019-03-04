# OpenTHC Common API Interfaces

Provides a common reference and API end-point specification for implementing Cannabis Data Systems.

The world of cannabis regulatory compliance is getting complex and large.
There are numerous vendors, each with a common goal but a unique API.

An important first step for bringing the industry together is to start talking in with common terms, a second is a common interface.

The OpenTHC API Specification aims to describe one part of this standard.

The OpenTHC CRE, Bunk, Data, Dump, Menu, P2P, Pipe, QA and other services implement these interfaces.

It's our hope that others in the cannabis industry could adopt, in whole or in part, some of the concepts and designs we've published.


## API

The OpenTHC API Module provides a method for reading/writing to an OpenTHC Compatible Data Store.

This API was heavily influenced by the [Open API Initiative](https://openapis.org/)
and the [Data Transfer Project](https://opensource.googleblog.com/2018/07/introducing-data-transfer-project.html)


## Documentation

The documentation has been createded using a combination of [Asciidoc](http://asciidoc.org) and [Swagger](https://swagger.io).

Asciidoc is in [./doc], Swagger sources are in [./swagger]

	make docs

We've been using Asciidoctor over Asciidoc for generating the outputs.
Our Makefile supports both, but one, or the other may be missing features (such as PlantUML).


## JSON Schema

A JSON Schema is published for all the objects in this system, they are located in ./json-schema
These files are constructed from the YAML documentation in ./swagger
Sample objects are provided in ./json-sample

	make json-schema


## Testing

Run the unit tests in ./test

	make test


## Dependencies

This thing depends on Asciidoc/Asciidoctor (Python, Ruby) and some build scripts (JS, PHP, Ruby)

	apt-get install ruby-bundler ruby-dev
	bundle install --path vendor/bundle


## See Also

 * https://www.jsonschemavalidator.net/
 * https://jsonschema.net/#/
 * https://github.com/epoberezkin/ajv
 * https://github.com/zircote/swagger-php
 * https://github.com/jensoleg/swagger-ui
 * https://github.com/E96/swagger2slate


## Asciidoc

 * http://laurent-laville.org/asciidoc/bootstrap/
