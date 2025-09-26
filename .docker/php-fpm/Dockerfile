FROM phpdockerio/php:8.4-fpm
WORKDIR '/app'

ARG DEBIAN_FRONTEND=noninteractive
ARG USERNAME=customuser
ARG UID
ARG GID

# Install FPM
RUN apt-get update \
    && apt-get -y --no-install-recommends install php-zip unzip php8.4-mysql php-sqlite3 php8.4-bcmath php8.4-xml php8.4-gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN cd /tmp/ \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Удаляем любого пользователя с тем же UID и группу с тем же GID (если есть),
# затем создаём требуемую группу/пользователя
RUN set -eux; \
    EXISTING_USER="$(awk -F: -v uid=\"${UID}\" '$3==uid{print $1}' /etc/passwd || true)"; \
    if [ -n \"$EXISTING_USER\" ]; then \
      echo \"Removing existing user with UID ${UID}: $EXISTING_USER\"; \
      if command -v deluser >/dev/null 2>&1; then deluser --remove-home \"$EXISTING_USER\" || true; else userdel -r \"$EXISTING_USER\" || true; fi; \
    fi; \
    EXISTING_GROUP="$(awk -F: -v gid=\"${GID}\" '$3==gid{print $1}' /etc/group || true)"; \
    if [ -n \"$EXISTING_GROUP\" ]; then \
      echo \"Removing existing group with GID ${GID}: $EXISTING_GROUP\"; \
      if command -v delgroup >/dev/null 2>&1; then delgroup \"$EXISTING_GROUP\" || true; else groupdel \"$EXISTING_GROUP\" || true; fi; \
    fi; \
# Создаём группу и пользователя \
    groupadd -g ${GID} ${USERNAME} || true; \
    useradd -m -u ${UID} -g ${GID} -s /bin/bash ${USERNAME} || true

# Создаём pid-файл и каталог сокета, даем права
RUN mkdir -p /run/php \
    && touch /run/php-fpm.pid \
    && chown -R ${UID}:${GID} /run/php /run/php-fpm.pid


# Переключаемся на нового пользователя
USER ${USERNAME}

# Запуск php-fpm
CMD ["/usr/sbin/php-fpm8.4", "-O"]
