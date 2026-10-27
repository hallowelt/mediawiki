#!/bin/sh
# Helper script to load useful tools
# Copyright: 2024
# License: GPLv3

targetdir="_bluespice/tools"

if [ ! -d "$targetdir" ]
then
	mkdir -p "$targetdir"
fi

# macOS ships curl but not wget; Alpine ships (BusyBox) wget but not curl
download() {
	if command -v wget >/dev/null 2>&1; then
		wget -q -O "$1" "$2"
	elif command -v curl >/dev/null 2>&1; then
		curl -fsSL -o "$1" "$2"
	else
		printf "Neither wget nor curl available\n" >&2
		exit 1
	fi
}

download "$targetdir/mediawiki-adm" "https://github.com/hallowelt/misc-mediawiki-adm/releases/latest/download/mediawiki-adm"
chmod +x "$targetdir/mediawiki-adm"

download "$targetdir/parallel-runjobs-service" "https://github.com/hallowelt/misc-parallel-runjobs-service/releases/latest/download/parallel-runjobs-service"
chmod +x "$targetdir/parallel-runjobs-service"
