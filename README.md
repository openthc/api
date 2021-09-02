# OpenTHC Common API Interface

Provides a common reference and API end-point specification for implementing Cannabis Data Systems.

The world of cannabis regulatory compliance is complex and large.
There are hundreds of vendors, each with a common goal but without an API, partial API, or have implemented a unique flavor.
The goal of this interface is to provide a basis for data interoperability.

It's our hope that others in the cannabis industry could adopt, in whole or in part, some of the concepts and designs published here.
Open Issues, submit PRs etc, etc and we can build the future we all want to see.


## A Common Protocol

An important first step for bringing the industry together is to start talking in with common terms, a second is a common interface.

The OpenTHC API Specification aims to define core parts of this data model and suggested interfaces.
The API and [data-models](./openapi/components/schema) are defined in the [openapi](./openapi) directory.
There are definitions of base-data (ie: [lab-metric-type](./etc/lab-metric), [product-type](./etc/product-type) in [etc](./etc).
Both are defined with YAML.

Some scripts for processing that YAML into other flavours (JSON, CSV, SQL) are in [bin](./bin).
But, YAML is so easy, one can quickly process with their preferred tools.


## API

The OpenTHC API Module provides a method for reading/writing to a compatible system.
A set of standard base data models for objects in the cannabis industry.

This API was heavily influenced by the [Open API Initiative](https://openapis.org/)
and the [Data Transfer Project](https://opensource.googleblog.com/2018/07/introducing-data-transfer-project.html)

* Use [International System of Units](https://en.wikipedia.org/wiki/International_System_of_Units) for weight and volume values.
* Use [ISO-8601](https://en.wikipedia.org/wiki/ISO_8601) for date and time values.

We provide some base data examples and JSON schema.


## Documentation

The documentation has been created using a combination of [Asciidoc](http://asciidoc.org) and [OpenAPI](https://swagger.io).

Asciidoc is in [doc](./doc) and the OpenAPI sources are in [openapi](./openapi)

	./make.sh docs


## JSON Schema

A JSON Schema is published for all the objects in this system, they are located in ./json-schema
These files are constructed from the YAML documentation in ./swagger
Sample objects are provided in ./json-sample

	./make.sh json-schema


## Core Data

There is a bunch of "core" data for this industry, Company, Company_Type, License, License_Type, Product_Type, Variety and Laboratory Metrics.
Included in the ./etc directory are pre-populated yaml files describing these common objects.
Usage of common, unique identifiers for data-fields we all care about will improve interoperablity.


### System Data

In the Core data there is a subset of *System* data, the Company, Contact, License, License_Type and Product_Type and Lab_Metric records.


### License Configuration Data

These objects are defined and specific to each Company or License in the system.
Includes: Product, Variety, Section, Vehicle (although, Section and Vehicle only exist for backwards compatibility)


### License Operational Data

* Crop / Plant
* Inventory Lot
* Lab Sample / Lab Result


## Testing

Run the unit tests in ./test

	./make.sh test

You could also use [Prism](https://github.com/stoplightio/prism) for running a mock interface.


## Dependencies

This thing depends on Asciidoc/Asciidoctor (Python, Ruby) and some build scripts (JS, PHP, Ruby).
Just review the `make.sh` script for the latest information.


## See Also

 * https://openapis.org/
 * https://asciidoctor.org/
 * https://redoc.ly/redoc/
 * https://jsonschema.net/#/
 * https://www.jsonschemavalidator.net/
 * https://github.com/epoberezkin/ajv
 * https://github.com/zircote/swagger-php
 * https://github.com/jensoleg/swagger-ui
 * https://github.com/E96/swagger2slate
 * [How to Design the APIs of Tomorrow](https://news.ycombinator.com/item?id=24332418)
 * [Ask HN: What is your go-to example for a good REST API?](https://news.ycombinator.com/item?id=11971491)
 * [Ask HN: What are good reads for designing APIs?](https://news.ycombinator.com/item?id=12262586)
 * [Microsoft REST API Guidelines](https://news.ycombinator.com/item?id=12122828)
