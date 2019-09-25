# OpenTHC Common API Interface

Provides a common reference and API end-point specification for implementing Cannabis Data Systems.

The world of cannabis regulatory compliance is complex and large.
There are hundreds of vendors, each with a common goal but without an API, or have implemnted a unique flavor.
The goal of this interface is to provide a basis for data interoperability.

An important first step for bringing the industry together is to start talking in with common terms, a second is a common interface.

The OpenTHC API Specification aims to describe one part of this standard.

The OpenTHC CRE, Bunk, Data, Dump, Menu, P2P, Pipe, QA and other services implement these interfaces.

It's our hope that others in the cannabis industry could adopt, in whole or in part, some of the concepts and designs we've published.


## API

The OpenTHC API Module provides a method for reading/writing to an OpenTHC Compatible system.
A set of standard base data models for objects in the Cannabis Industry.

This API was heavily influenced by the [Open API Initiative](https://openapis.org/)
and the [Data Transfer Project](https://opensource.googleblog.com/2018/07/introducing-data-transfer-project.html)

* Use [International System of Units](https://en.wikipedia.org/wiki/International_System_of_Units) for weight and volume values.
* Use [ISO-8601](https://en.wikipedia.org/wiki/ISO_8601) for date and time values.

We provide some base data examples and JSON schema.


## Documentation

The documentation has been createded using a combination of [Asciidoc](http://asciidoc.org) and [OpenAPI](https://swagger.io).

Asciidoc is in [./doc] and the OpenAPI sources are in [./openapi]

	make docs

We've been using Asciidoctor over Asciidoc for generating the outputs.
Our Makefile supports both, but one, or the other may be missing features (such as PlantUML).


## JSON Schema

A JSON Schema is published for all the objects in this system, they are located in ./json-schema
These files are constructed from the YAML documentation in ./swagger
Sample objects are provided in ./json-sample

	make json-schema


## Core Data

There is a bunch of "core" data for this industry, Company, Company_Type, License, License_Type, Product_Type, Variety and Laboratory Metrics.
Included in the ./etc directory are pre-populated yaml files describing these common objects.
Usage of common, unique identifiers for data-fields we all care about will improve interoperablity.


## Testing

Run the unit tests in ./test

	make test

You could also use [Prism](https://github.com/stoplightio/prism) for running a mock interface.


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
