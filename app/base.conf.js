import webpack from 'webpack';
const path = require('path');
const fs = require('fs');

var plugins = [
    new webpack.ProvidePlugin({
        $: "jquery",
        jQuery: "jquery"
    })
];


const config = {
    mode: 'development',
    entry: {
        main: './src/app.js'
    },
    output: {
        path: path.join(__dirname),
        filename: "./asset/script.js"
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                loader: 'babel-loader',
                options: {
                    babelrc: false,
                }
            },
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            {
                test: /\.css$/,
                use: ['style-loader', 'css-loader']
            },
            {
                test: /\.scss$/,
                use: [
                    "style-loader", // creates style nodes from JS strings
                    "css-loader", // translates CSS into CommonJS
                    "sass-loader" // compiles Sass to CSS, using Node Sass by default
                ]
            },
            {
                test: /\.styl$/,
                use: [
                    "stylus-loader", // creates style nodes from JS strings
                    "css-loader", // translates CSS into CommonJS
                ]
            },
            {
                test: /\.pug$/,
                loader: 'pug-plain-loader'
            },
            {
                test: /\.(ico|jpg|jpeg|png|gif|eot|otf|webp|svg|ttf|woff|woff2)(\?.*)?$/,
                loader: path.resolve(__dirname, 'node_modules', 'file-loader'),
                // loader: 'file-loader',
                query: {
                    name: '[hash:8].[ext]',
                },
            },
        ]
    },
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.esm.js'
        },
        extensions: ['*', '.js', '.vue', '.json']
    },
    plugins

};

export default config;