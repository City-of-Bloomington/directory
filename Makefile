APPNAME=directory

SASS := $(shell command -v pysassc 2> /dev/null)
MSGFMT := $(shell command -v msgfmt 2> /dev/null)

LANGUAGES := $(wildcard language/*/LC_MESSAGES)

default: compile package

deps:
ifndef SASS
	$(error "pysassc is not installed")
endif
ifndef MSGFMT
	$(error "msgfmt is not installed, please install gettext")
endif

clean:
	rm -Rf build
	mkdir build

	rm -Rf public/css/.sass-cache
	find language -name '*.mo' -delete

compile: deps $(LANGUAGES)
	pysassc -t compact -m public/css/screen.scss public/css/screen.css

package: compile
	if [ ! -d build ]; then mkdir build; fi
	rsync -rl --exclude-from=buildignore --delete . build/$(APPNAME)
	cd build && tar czf $(APPNAME).tar.gz $(APPNAME)

$(LANGUAGES): deps
	cd $@ && msgfmt -cv *.po
