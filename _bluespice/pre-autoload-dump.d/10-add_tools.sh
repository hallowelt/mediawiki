#!/bin/bash
# Helper script to load useful tools
# Copyright: 2024
# License: GPLv3

targetdir="_bluespice/tools"

if [ ! -d "$targetdir" ]
then
	mkdir -p "$targetdir"
fi

wget -q -O "$targetdir/mediawiki-adm" "https://github.com/hallowelt/misc-mediawiki-adm/releases/latest/download/mediawiki-adm"
chmod +x "$targetdir/mediawiki-adm"

wget -q -O "$targetdir/parallel-runjobs-service" "https://github.com/hallowelt/misc-parallel-runjobs-service/releases/latest/download/parallel-runjobs-service"
chmod +x "$targetdir/parallel-runjobs-service"
