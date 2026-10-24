#!/bin/bash

# Make docker
docker build . -f docker/Dockerfile --tag openthc/api
