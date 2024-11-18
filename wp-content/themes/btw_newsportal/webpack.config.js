const path = require('path');
const TerserPlugin = require('terser-webpack-plugin');

module.exports = {
  entry: {
    main: [
        './assets/js/front-end/main-scripts/btw-ua-parser.js',
        './assets/js/front-end/main-scripts/utilities.js',
        './assets/js/front-end/main-scripts/lazy-images/lazysizes.min.js',
        './assets/js/front-end/main-scripts/lazy-images/optimize.js',
        './assets/js/front-end/main-scripts/lazy-images/lazyloadEmbed.js',

        './assets/js/front-end/keen-slider.js',
        './assets/js/front-end/keen-slider-lightbox.js',
        './assets/js/front-end/keen-slider-navigation.js',
    ],
  },
  output: {
    path: path.resolve(__dirname, 'assets/js/front-end/production'),
    filename: '[name].bundle.js',
  },
    optimization: {
        minimize: true,
        minimizer: [
        new TerserPlugin({
            extractComments: false,
            terserOptions: {
                compress: {
                    drop_console: ['log', 'info']
                }
            }
        }),
        ],
    },
  module: {
    rules: [
      {
        test: /\.m?js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
        },
      },
    ],
  },
};