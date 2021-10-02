#!/usr/bin/env bash
# See https://raw.githubusercontent.com/wp-cli/scaffold-command/master/templates/install-wp-tests.sh

WP_TESTS_DIR=${WP_TESTS_DIR-tests/vendor}

# Exit if any command fails.
set -ex

install_test_suite() {
	# set up testing suite if it doesn't yet exist
	if [ ! -d $WP_TESTS_DIR/wordpress-tests-lib ]; then
		git clone https://github.com/wp-phpunit/wp-phpunit.git $WP_TESTS_DIR/wordpress-tests-lib
		cd $WP_TESTS_DIR/wordpress-tests-lib
		git fetch --tags
		local LATEST_TEST_SUITE_TAG=$(git describe --tags `git rev-list --tags --max-count=1`)
		git checkout $LATEST_TEST_SUITE_TAG
	fi
}

install_wc() {
	if [ ! -d $WP_TESTS_DIR/woocommerce ]; then
		git clone https://github.com/woocommerce/woocommerce.git $WP_TESTS_DIR/woocommerce
		cd $WP_TESTS_DIR/woocommerce
		git fetch --tags
		local LATEST_WC_TAG=$(git describe --tags `git rev-list --tags --max-count=1`)
		git checkout $LATEST_WC_TAG
		composer install --optimize-autoloader
	fi
}

install_test_suite
install_wc
