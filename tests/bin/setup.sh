#!/usr/bin/env sh

# Exit if any command fails.
set -e

echo
echo "Setting up tests environment..."
echo

echo "Updating the permalink structure"
npx wp-env run tests-cli "wp rewrite structure '/%postname%/'"

echo "Activating WooCommerce..."
npx wp-env run tests-cli "wp plugin activate woocommerce"

echo "Creating customer account"
npx wp-env run tests-cli "wp user create customer customer@woocommercecoree2etestsuite.com --user_pass=password --role=customer"

echo "Adding basic WooCommerce settings..."
npx wp-env run tests-cli "wp option set woocommerce_store_address '123 Maple Street'"
npx wp-env run tests-cli "wp option set woocommerce_store_city 'Seoul'"
npx wp-env run tests-cli "wp option set woocommerce_default_country 'KR'"
npx wp-env run tests-cli "wp option set woocommerce_store_postcode '12345'"
npx wp-env run tests-cli "wp option set woocommerce_currency 'KRW'"
npx wp-env run tests-cli "wp option set woocommerce_currency_pos 'right'"
npx wp-env run tests-cli "wp option set woocommerce_price_num_decimals '0'"
npx wp-env run tests-cli "wp option set woocommerce_product_type 'both'"
npx wp-env run tests-cli "wp option set woocommerce_allow_tracking 'no'"

echo "Importing WooCommerce shop pages..."
npx wp-env run tests-cli "wp wc --user=admin tool run install_pages"

echo "Activating the WordPress Importer plugin..."
npx wp-env run tests-cli "wp plugin activate wordpress-importer"

echo "Importing some sample data..."
npx wp-env run tests-cli "wp import wp-content/plugins/woocommerce/sample-data/sample_products.xml --authors=skip"

echo "Activating Korea for WooCommerce plugin..."
npx wp-env run tests-cli "wp plugin activate korea-for-woocommerce"

echo
echo "SUCCESS! You should now be able to access http://localhost:8889/wp-admin/"
echo "Default login credentials are username: admin password: password"
