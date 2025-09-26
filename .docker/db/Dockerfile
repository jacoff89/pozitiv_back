FROM mysql:8.4

ARG UID
ARG GID

RUN groupadd -g ${GID} customgroup && \
    useradd -u ${UID} -g customgroup -m customuser && \
    chown -R customuser:customgroup /var/lib/mysql

USER customuser
