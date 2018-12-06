APPNAME=directory

SASS := $(shell command -v sassc 2> /dev/null)
MSGFMT := $(shell command -v msgfmt 2> /dev/null)
LANGUAGES := $(wildcard language/*/LC_MESSAGES)

VERSION := $(shell cat VERSION | tr -d "[:space:]")
COMMIT := $(shell git rev-parse --short HEAD)

default: compile package

deps:
ifndef SASS
	$(error "sassc is not installed")
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
	sassc -t compact -m public/css/screen.scss public/css/screen-${VERSION}.css

package: compile
	if [ ! -d build ]; then mkdir build; fi
	rsync -rl --exclude-from=buildignore --delete . build/$(APPNAME)
	cd build && tar czf $(APPNAME).tar.gz $(APPNAME)

docker: package
	docker build -t docker-repo.bloomington.in.gov/library/directory:${VERSION}-${COMMIT} -f docker/dockerfile build
	docker push docker-repo.bloomington.in.gov/library/directory:${VERSION}-${COMMIT}

$(LANGUAGES): deps
	cd $@ && msgfmt -cv *.po
