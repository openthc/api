# OpenTHC Common API Interfaces

The world of marijuana regulatory compliance is getting complex and large.
There are numerous vendors, each with a common goal but a unique API.

An important first step for bringing the industry together is to start talking in with common terms, a second is a common interface.

The OpenTHC API Specification aims to describe one part of this standard.

The OpenTHC Enforcement Engine and the OpenTHC Enforcement Engine Proxy both implement this interface.

It's our hope that others in the cannabis/marijuna industry could adopt, in whole or in part, some of the concepts and designs we've published.


# API

The OpenTHC API Module provides a method for reading/writing to an OpenTHC Compatible Data Store.

This API was heavily influenced by the [Open API Initiative](https://openapis.org/).

## Documentation

The documentation has been createded using a combination of [Swagger](https://swagger.io) and asciidoc.
Is stored in ./doc/

	make docs

## Testing

Run the unit tests in ./test

	make test
