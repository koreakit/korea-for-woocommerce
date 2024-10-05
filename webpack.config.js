// webpack.config.js
const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const path = require('path');

module.exports = {
    ...defaultConfig,
    entry: {
        checkout: './client/classic/checkout/index.js',
        settings: './client/settings/index.js',
    },
    output: {
        filename: '[name].js',
        path: path.resolve(process.cwd(), 'dist')
    }
};
