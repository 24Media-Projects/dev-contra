const path = require('path');
const TerserPlugin = require('terser-webpack-plugin');

module.exports = {
  entry: {
    postsFromApi: [
        './assets/js/front-end/postsFromApi.js',
        './assets/js/front-end/parselyPosts.js',
    ],
    scripts: [
        './assets/js/front-end/scripts.js',
        './assets/js/front-end/scripts.vanilla.js',
    ],
    infinitePosts: './assets/js/front-end/infinitePosts.js',
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