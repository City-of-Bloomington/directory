#!/bin/bash
DIR=`pwd`
BUILD=$DIR/build
DIST=$DIR/dist

if [ ! -d $BUILD ]
	then mkdir $BUILD
fi

if [ ! -d $DIST ]
	then mkdir $DIST
fi

# The PHP code does not need to actually build anything.
# Just copy all the files into the build
rsync -rlv --exclude-from=$DIR/buildignore --delete $DIR/ $BUILD/

# Compile the SCSS
cd $DIR/public/css
./build_css.sh
cd $DIR

# Create a distribution tarball of the build
tar czvf $DIST/directory.tar.gz --transform=s/build/directory/ $BUILD
