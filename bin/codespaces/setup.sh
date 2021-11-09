#!/usr/bin/env sh

# Exit if any command fails.
set -e

echo
echo "Setting up environment for Github Codespaces..."
echo

echo "Updating config of the development & test environments..."
npx wp-env run cli "wp config set WP_HOME 'https://$CODESPACE_NAME-8888$RAILS_DEVELOPMENT_HOSTS'"
npx wp-env run cli "wp config set WP_SITEURL 'https://$CODESPACE_NAME-8888$RAILS_DEVELOPMENT_HOSTS'"
npx wp-env run cli "wp config set WP_TESTS_DOMAIN 'https://$CODESPACE_NAME-8889$RAILS_DEVELOPMENT_HOSTS'"
npx wp-env run tests-cli "wp config set WP_HOME 'https://$CODESPACE_NAME-8889$RAILS_DEVELOPMENT_HOSTS'"
npx wp-env run tests-cli "wp config set WP_SITEURL 'https://$CODESPACE_NAME-8889$RAILS_DEVELOPMENT_HOSTS'"
npx wp-env run tests-cli "wp config set WP_TESTS_DOMAIN 'https://$CODESPACE_NAME-8889$RAILS_DEVELOPMENT_HOSTS'"

echo "Updating the permalink structure"
npx wp-env run cli "wp option update permalink_structure '/%postname%/'"

echo "Creating customer account"
npx wp-env run cli "wp user create customer customer@korea-for-woocommerce.com --user_pass=password --role=customer"

echo "Adding basic WooCommerce settings..."
npx wp-env run cli "wp option set woocommerce_store_address '123 Maple Street'"
npx wp-env run cli "wp option set woocommerce_store_city 'Seoul'"
npx wp-env run cli "wp option set woocommerce_default_country 'KR'"
npx wp-env run cli "wp option set woocommerce_store_postcode '12345'"
npx wp-env run cli "wp option set woocommerce_currency 'KRW'"
npx wp-env run cli "wp option set woocommerce_currency_pos 'right'"
npx wp-env run cli "wp option set woocommerce_price_num_decimals '0'"
npx wp-env run cli "wp option set woocommerce_product_type 'both'"
npx wp-env run cli "wp option set woocommerce_allow_tracking 'no'"

echo "Importing WooCommerce shop pages..."
npx wp-env run cli "wp wc --user=admin tool run install_pages"

echo "Installing and activating the WordPress Importer plugin..."
npx wp-env run cli "wp plugin install wordpress-importer --activate"

echo "Importing some sample data..."
npx wp-env run cli "wp import wp-content/plugins/woocommerce/sample-data/sample_products.xml --authors=skip"

echo
echo "SUCCESS! You should now be able to access https://$CODESPACE_NAME-8888$RAILS_DEVELOPMENT_HOSTS/wp-admin/"
echo "Default login credentials are username: admin password: password"
