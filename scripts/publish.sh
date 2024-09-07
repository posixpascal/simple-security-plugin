#!/usr/bin/env bash

set -eaux

TAG_VERSION=$(git describe --tags --abbrev=0)
DATE=$(date +%Y-%m-%d\ %H:%I:%S)

PWD=$(pwd)
mkdir -p /tmp/simple-security-plugin/

cp -R src /tmp/simple-security-plugin/
(cd /tmp && zip -r /tmp/simple-security-plugin.zip simple-security-plugin/)
