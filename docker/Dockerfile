#
# OpenTHC API Docker Image
#

FROM openthc/web:debian-13

WORKDIR /opt/openthc/api

#
# Copy configs
COPY ./docker/Caddyfile /etc/caddy/Caddyfile
COPY ./docker/init.sh /init.sh
RUN chmod +x /init.sh

RUN mkdir -p /opt/openthc/api/var

# Permissions
RUN chown -R openthc:openthc   /opt/openthc/api
RUN chown -R www-data:www-data /opt/openthc/api/var

#
# Application Install
# Copy everything in
COPY ./ /opt/openthc/api/

# Change permissions before building so scripts can write if needed
RUN chown -R openthc:www-data /opt/openthc/api/var
RUN chmod +x /opt/openthc/api/make.sh

# Run the build script
RUN cd /opt/openthc/api \
    && rm -f ./composer.lock \
    && ./make.sh system-install \
    && ./make.sh install

#
# Volumes
VOLUME ["/opt/openthc/api/var"]

#
# Environment
ENV OPENTHC_SERVER_NAME="api"

EXPOSE 80

ENTRYPOINT [ "/init.sh" ]
