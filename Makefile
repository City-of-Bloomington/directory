SHELL := /bin/bash
APPNAME := directory

SASS := $(shell command -v sassc 2> /dev/null)
MSGFMT := $(shell command -v msgfmt 2> /dev/null)
LANGUAGES := $(wildcard language/*/LC_MESSAGES)
JAVASCRIPT := $(shell find public -name '*.js' ! -name '*-*.js')

VERSION := $(shell cat VERSION | tr -d "[:space:]")
COMMIT := $(shell git rev-parse --short HEAD)

default: clean compile test package

deps:
ifndef SASS
	$(error "sassc is not installed")
endif
ifndef MSGFMT
	$(error "gettext is not installed")
endif

clean:
	rm -Rf build/${APPNAME}*
	for f in $(shell find public/css   -name '*-*.css'   ); do rm $$f; done
	for f in $(shell find data/Themes  -name '*-*.css'   ); do rm $$f; done

compile: deps $(LANGUAGES)
	cd public/css                 && sassc -t compact -m screen.scss screen-${VERSION}.css
#	cd data/Themes/COB/public/css && sassc -t compact -m screen.scss screen-${VERSION}.css
	for f in ${JAVASCRIPT}; do cp $$f $${f%.js}-${VERSION}.js; done

test:
	vendor/phpunit/phpunit/phpunit -c src/Test/Unit.xml

package:
	[[ -d build ]] || mkdir build
	rsync -rl --exclude-from=buildignore . build/${APPNAME}
	cd build && tar czf ${APPNAME}-${VERSION}.tar.gz ${APPNAME}

$(LANGUAGES): deps
	cd $@ && msgfmt -cv *.po
