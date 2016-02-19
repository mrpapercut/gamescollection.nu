var webpack = require('webpack');
var ExtractTextPlugin = require('extract-text-webpack-plugin');

var extractSass = new ExtractTextPlugin('main.css');
var DEV = process.env.NODE_ENV === 'dev';

var PLUGINS = [extractSass];

if (!DEV) PLUGINS.push(new webpack.optimize.UglifyJsPlugin({minimize: true}));

module.exports = {
	entry: './src/js/app.js',
	output: {
		path: __dirname + '/public/',
		filename: 'bundle.js'
	},
	devtool: DEV ? 'cheap-module-eval-source-map' : false,
	module: {
		loaders: [{
			test: /\.js$/,
			loader: 'babel-loader',
			exclude: /node_modules/,
			query: {
				presets: ['es2015', 'react']
			}
		}, {
			test: /\.scss$/,
			loader: extractSass.extract(['css', 'sass'])
		}, {
			test: /\.(ttf|eot|svg|woff2?)(\?[a-z0-9]+)?$/,
			loader: 'file-loader'
		}]
	},
	plugins: PLUGINS
}