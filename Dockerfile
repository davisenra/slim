FROM dunglas/frankenphp

RUN install-php-extensions \
	intl \
    zip \
	opcache

COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

RUN \
	useradd -D ${USER}; \
	setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp; \
	chown -R ${USER}:${USER} /data/caddy && chown -R ${USER}:${USER} /config/caddy;

USER ${USER}