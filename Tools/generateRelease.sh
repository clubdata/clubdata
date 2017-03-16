#!/bin/bash

VERSION="$1";
RELEASEDIR="Releases/$1";

if [ -z "VERSION" ]; then
	echo "Aufruf: $0 <Version>";
	exit 1;
fi

rm -rf "$RELEASEDIR";
mkdir -p "$RELEASEDIR";

rsync -avz --exclude '*~' --exclude Releases --exclude '*.tpl.php' --exclude '*.tmp' --exclude '.*' --exclude '*.log' . "$RELEASEDIR"

rm "$RELEASEDIR"/include/configuration*.php

(cd "Releases"; tar cvzf "$VERSION.tar.gz" $VERSION;)

