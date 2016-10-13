'use strict';

const webpack = require('webpack');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const OptimizeCssPlugin = require('optimize-css-assets-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const path = require('path');
const root = path.dirname(path.dirname(path.dirname(__dirname)));

module.exports = {
    entry: './app.js',
    output: {
        filename: 'js/albums.js',
        path: path.join(root,'./web'),
        publicPath: '/'
    },
    module: {
        loaders: [
            {
                test: /\.js?$/,
                exclude: /node_modules/,
                loader: 'babel',
                query: {
                    presets: ['es2015']
                }
            },
            {
                test: /\.jst$/,
                loader: 'underscore-template-loader'
            },
            {
                test: /\.css$/,
                exclude: /node_modules/,
                loader: ExtractTextPlugin.extract('style-loader', 'css-loader')
            },
            {
                test: /\.(png|jpg|jpeg|gif|svg)$/,
                loader: 'url',
                query: {
                    limit: 10000,
                    name: '[name].[ext]?[hash]'
                }
            }
        ]
    },
    plugins: [
        new ExtractTextPlugin('css/all.css', {
            allChunks: true
        }),
        new CopyWebpackPlugin([{
            from: './images/*',
            to: path.join(root,'./web/images/*')
        }]),
        new webpack.ProvidePlugin({
            $: 'jquery',
            _: 'underscore'
        }),
        new webpack.optimize.UglifyJsPlugin({
            compress: {
                warnings: false
            },
            output: {
                comments: false
            }
        }),
        new OptimizeCssPlugin({
            assetNameRegExp: /\.min\.css$/,
            cssProcessorOptions: { discardComments: { removeAll: true } }
        })
    ],
    resolve: {
        root: path.join(__dirname, '.')
    },
    resolveLoader: {
        root: path.join(__dirname, './node_modules')
    }
};
