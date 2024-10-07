const webpack = require('webpack')
const path = require('path')

module.exports = {
    mode: 'development',

    entry: {
        index: './src/index',
    },

    output: {
        path: path.resolve(__dirname, './dist'),
        filename: '[name].js',
        publicPath: 'http://localhost:3000/assets/'
    },

    module: {
        rules: [
            {
                test: /\.(js|jsx)$/,
                include: path.appSrc,
                loader: require.resolve('babel-loader'),
            }
        ]
    },

    devServer: {
        host: 'localhost',
        port: 3000,
        historyApiFallback: true,
        hot: true,
        headers: {
            'Access-Control-Allow-Origin': '*'
        }
    }
}
