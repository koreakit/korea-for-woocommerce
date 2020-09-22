#!/usr/bin/env sh

# Exit if any command fails.
set -e

echo
echo "Setting up environment..."
echo

echo "Updating WordPress to the latest version..."
wp-env run cli "wp core update --quiet"

echo "Updating the WordPress database..."
wp-env run cli "wp core update-db --quiet"

echo "Updating the permalink structure"
wp-env run cli "wp option update permalink_structure '/%postname%'"

echo "Installing and activating WooCommerce..."
wp-env run cli "wp plugin install woocommerce --activate"

echo "Creating customer account"
wp-env run cli "wp user create customer customer@woocommercecoree2etestsuite.com --user_pass=password --role=customer"

echo "Adding basic WooCommerce settings..."
wp-env run cli "wp option set woocommerce_store_address '123 Maple Street'"
wp-env run cli "wp option set woocommerce_store_city 'Seoul'"
wp-env run cli "wp option set woocommerce_default_country 'KR'"
wp-env run cli "wp option set woocommerce_store_postcode '12345'"
wp-env run cli "wp option set woocommerce_currency 'KRW'"
wp-env run cli "wp option set woocommerce_product_type 'both'"
wp-env run cli "wp option set woocommerce_allow_tracking 'no'"

echo "Importing WooCommerce shop pages..."
wp-env run cli "wp wc --user=admin tool run install_pages"

echo "Installing and activating the WordPress Importer plugin..."
wp-env run cli "wp plugin install wordpress-importer --activate"

echo "Importing some sample data..."
wp-env run cli "wp import wp-content/plugins/woocommerce/sample-data/sample_products.xml --authors=skip"

echo "Activating Korea for WooCommerce plugin..."
wp-env run cli "wp plugin activate korea-for-woocommerce"

echo
echo "SUCCESS! You should now be able to access http://localhost:8888/wp-admin/"
echo "Default login credentials are username: admin password: password"
