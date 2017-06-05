
RM = rm -rfd
CHMOD = chmod
MKDIR = mkdir -p
VENDOR = vendor
COMPOSER = ./composer.phar
COMPOSER_OPTIONS ?= --no-interaction

# Local installed PHPStan while supporting PHP 5.
# PHPStan requires PHP 7.
PHPSTAN = ~/.composer/vendor/bin/phpstan


.PHONY: all
all: install test

.PHONY: install
install: $(VENDOR)

.PHONY: update
update: $(COMPOSER)
	$(COMPOSER) selfupdate
	$(COMPOSER) update

.PHONY: test
test:

.PHONY: test_phpstan
test_phpstan:
	$(PHPSTAN) analyse --level 5 --no-progress src

.PHONY: clean
clean:
	$(RM) composer.lock $(COMPOSER) $(VENDOR)

$(VENDOR): $(COMPOSER)
	$(COMPOSER) install $(COMPOSER_OPTIONS)

$(COMPOSER):
	curl -sS https://getcomposer.org/installer | php
	$(CHMOD) u=rwx,go=rx $(COMPOSER)

$(PHPCS): $(VENDOR)
