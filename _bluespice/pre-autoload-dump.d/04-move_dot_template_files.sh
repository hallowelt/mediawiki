#!/bin/sh
# POSIX-compatible way of finding -maxdepth 3
find . -name '*.php.template' | awk -F/ 'NF <= 4' | while IFS= read -r f
do
	cp "$f" "${f%.template}"
done
