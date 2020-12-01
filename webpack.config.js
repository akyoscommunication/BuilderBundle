'use strict'; // eslint-disable-line

const webpack = require("webpack");
const merge = require('webpack-merge');
const ExtractTextPlugin = require("extract-text-webpack-plugin");
const CleanWebpackPlugin = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const FriendlyErrorsWebpackPlugin = require('friendly-errors-webpack-plugin');
const UglifyJsPlugin = require("uglifyjs-webpack-plugin");
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
// const BabelTransformClassProperty = require('babel-loader');
// const ManifestPlugin = require('webpack-manifest-plugin');

const config = require('./config');

let webpackConfig = {
    context: config.paths.root,
    entry: config.entry,
    output: {
        path: config.paths.dist,
        filename: "[name].js"
    },
    stats: {
        hash: false,
        version: false,
        timings: false,
        children: false,
        errors: false,
        errorDetails: false,
        warnings: false,
        chunks: false,
        modules: false,
        reasons: false,
        source: false,
        publicPath: false,
    },
    watchOptions: {
        poll: true
    },
    module: {
        rules: [
            {
                enforce: 'pre',
                test: /\.(js|s?[ca]ss)$/,
                include: config.paths.assets,
                loader: 'import-glob',
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: "babel-loader"
            },
            {
                test: /\.css$/,
                include: config.paths.assets,
                use: ExtractTextPlugin.extract({
                    fallback: 'style',
                    use: [
                        { loader: 'css' },
                        {
                            loader: 'postcss', options: {
                                config: { path: __dirname, ctx: config }
                            },
                        },
                    ],
                }),
            },
            {
                test: /\.scss$/,
                include: config.paths.assets,
                use: ExtractTextPlugin.extract({
                    fallback: 'style',
                    use: [
                        { loader: 'css', options: { minimize: config.minify } },
                        {
                            loader: 'postcss', options: {
                                config: { path: __dirname, ctx: config }
                            },
                        },
                        { loader: 'resolve-url' },
                        { loader: 'sass' },
                    ],
                })
            },
            {
                test: /\.woff(2)?(\?[a-z0-9]+)?$/,
                use: [
                    {
                        loader: "url-loader?limit=10000&mimetype=application/font-woff",
                        options: {
                            name: '[hash].[ext]'
                        }
                    }
                ]
            },
            {
                test: /\.(ttf|eot|svg)(\?[a-z0-9]+)?$/,
                use: [
                    {
                        loader: "file",
                        options: {
                            name: '[hash].[ext]'
                        }
                    }
                ]
            }
        ]
    },
    resolve: {
        modules: [
            config.paths.assets,
            'node_modules',
        ],
        enforceExtension: false,
    },
    resolveLoader: {
        moduleExtensions: ['-loader'],
    },
    plugins: [
        new CleanWebpackPlugin(config.paths.dist),
        // new CopyWebpackPlugin([{from: 'assets/images', to: 'images' }]),
        // new CopyWebpackPlugin([{from: 'assets/fonts', to: 'fonts' }]),
        new ExtractTextPlugin({
            filename: '[name].css'
        }),
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery',
            Popper: 'popper.js',
        }),
        new FriendlyErrorsWebpackPlugin(),
        // new ManifestPlugin()
    ]
};

if (process.env.NODE_ENV === 'production') {
    let prod = {
        plugins: [
            new UglifyJsPlugin({
                cache: true,
                parallel: true,
                sourceMap: false
            }),
            new ExtractTextPlugin("main.css"),
            new OptimizeCssAssetsPlugin()
        ]
    };
    webpackConfig = merge(webpackConfig, prod);
}

module.exports = webpackConfig;
