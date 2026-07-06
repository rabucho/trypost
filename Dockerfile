FROM ghcr.io/trypost/trypost:latest

# Copy custom entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Install netcat for health checks
RUN apk add --no-cache netcat-openbsd

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
