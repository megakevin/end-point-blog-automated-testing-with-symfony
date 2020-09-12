# Build the image with (replace USER accordingly):
#   docker build --build-arg USER=kevin --build-arg UID=$(id -u) --build-arg GID=$(id -g) -t weather-app .
#
# Run a container based on the image:
#   docker run -d --network host -v ${PWD}:/workspaces/weather-app weather-app
#
# Connect to the container:
#   docker exec -it CONTAINER_NAME bash

FROM ubuntu

ARG USER=docker
ARG UID=1000
ARG GID=1000

RUN apt-get update && apt-get install -y software-properties-common wget curl sqlite3

RUN add-apt-repository ppa:ondrej/php
RUN apt-get update && apt-get install -y php composer php-xdebug php-sqlite3 php-xml php-curl

# Configuring Xdebug
RUN echo "xdebug.remote_enable=on" >> /etc/php/7.4/mods-available/xdebug.ini
RUN echo "xdebug.remote_autostart=on" >> /etc/php/7.4/mods-available/xdebug.ini

# Installing Symfony
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# Set up a non root user with sudo access
RUN groupadd --gid $GID $USER \
    && useradd -s /bin/bash --uid $UID --gid $GID -m $USER \
    # Add sudo support for the non-root user
    && apt-get install -y sudo \
    && echo $USER ALL=\(root\) NOPASSWD:ALL > /etc/sudoers.d/$USER\
    && chmod 0440 /etc/sudoers.d/$USER

# Use the non root user to log in as into the container
USER ${UID}:${GID}

WORKDIR /workspaces/symfony-demo

CMD ["sleep", "infinity"]
