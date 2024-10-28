#!/bin/bash
# Helper script to load the installer into the proper directory
# Copyright: 2021
# License: GPLv3

GREEN='\033[0;32m'
RED='\033[0;31m'
PURPLE='\033[0;35m'
NC='\033[0m'

BRANCH=$(git branch | cut -f 2 -d " ")
BRANCH=${BRANCH%%[[:space:]]}
BRANCH=${BRANCH##[[:space:]]}
BRANCH="${BRANCH//[$'\t\r\n ']}"

if [ "$BRANCH" == "4.1.x" ] || [ "$BRANCH" == "4.2.x" ]
then
	BRANCH="REL1_35-$BRANCH"
fi
if [ "$BRANCH" == "4.3.x" ] || [ "$BRANCH" == "4.4.x" ] || [ "$BRANCH" == "4.5.x" ]
then
	BRANCH="REL1_39-$BRANCH"
fi

printf "\n${PURPLE}Fetching installer: ${NC}"

if ! [ -d "mw-config/overrides/.git" ]
then
	rm -rf mw-config/overrides
	git clone -b $BRANCH --depth 1 https://gerrit.wikimedia.org/r/bluespice/mw-config/overrides mw-config/overrides
else
	git -C mw-config/overrides/ pull
fi

# Check if the git command was successful
if [ $? -ne 0 ]
then
	printf "${RED}[ FAILED ]${NC}\n"
	# Try again by pulling out the default branch
	# Read the value in the BLUESPICE-VERSION at the top of the repo
	# and use that as the default branch using cat
	DEFAULT_BRANCH=$(cat BLUESPICE-VERSION)
	# Replace the last character with and X
	# This is because the default branch is 4.2.x
	# and we want to get the REL1_35-4.2.x branch
	# so we replace the last character with an X
	# and then append the REL1_35- to the front
	# of the string
	RELDEFAULT_BRANCH="REL1_39-${DEFAULT_BRANCH%?}x"
	rm -rf mw-config/overrides
	git clone -b $RELDEFAULT_BRANCH --depth 1 https://gerrit.wikimedia.org/r/bluespice/mw-config/overrides mw-config/overrides
fi

if [ $? -ne 0 ]
then
	printf "${RED}[ EXCEPTION FAILED ]${NC}\n"
	exit 1
fi

printf "${GREEN}[ DONE ]${NC}\n"
