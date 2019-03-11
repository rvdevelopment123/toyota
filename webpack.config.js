const ExtractTextPlugin = require('extract-text-webpack-plugin');
const CommonsChunkPlugin = require('webpack/lib/optimize/CommonsChunkPlugin');
const DefinePlugin = require('webpack/lib/DefinePlugin');
const ManifestPlugin = require('webpack-manifest-plugin');
const ExtendedAPIPlugin = require('webpack/lib/ExtendedAPIPlugin');
const ProvidePlugin = require('webpack/lib/ProvidePlugin');
const NormalModuleReplacementPlugin = require('webpack/lib/NormalModuleReplacementPlugin');

if (process.env.BUILD_DEV) {
    var outputPath = './public/build/';
    var publicOutputPath = 'http://inventory.dev:8080/build/';
} else {
    var outputPath = __dirname + '/public/build/';
    var publicOutputPath = '/build/';
}

var plugins = [
    new ExtractTextPlugin(process.env.BUILD_DEV ? '[name].css' : '[name].[hash].css'),
    new CommonsChunkPlugin({
        name: 'vendor',
        filename: process.env.BUILD_DEV ? 'vendor.js' : 'vendor.[hash].js',
        minChunks: 0
    }),
    new DefinePlugin({
        __DEV__: JSON.stringify(JSON.parse(process.env.BUILD_DEV || 'false'))
    }),
    new ManifestPlugin({
        fileName: 'rev-manifest.json',
    }),
    new ProvidePlugin({
        $: "jquery",
        jQuery: "jquery",
        jquery: "jquery",
        "window.jQuery": "jquery'",
        "window.$": "jquery",
        Tether: "tether"
    })
];

if(!process.env.BUILD_DEV) {
    plugins.push(new ExtendedAPIPlugin());
}

module.exports = {
    entry: {
        app: "./resources/assets/js/app.js",
        base: './resources/assets/sass/app.scss',
        vendor: [
            'jquery',
            'jquery-migrate',
            'bootstrap-select',
            'eonasdan-bootstrap-datetimepicker/src/js/bootstrap-datetimepicker'
        ]
    },
    output: {
        filename: process.env.BUILD_DEV ? '[name].js' : '[name].[chunkHash].js',
        path: outputPath,
        publicPath: publicOutputPath,
    },
    module: {
        rules: [
          {
            test: /\.js$/,
            use: 'eslint-loader',
            enforce: "pre",
            exclude: [/node_modules/, /libs/]
          },
          { 
            test: /\.js$/, 
            use: ['babel-loader', 'eslint-loader'], 
            exclude: [/node_modules/, /libs/] 
          },
          { 
            test: require.resolve('jquery'), 
            loader: 'expose-loader?$!expose-loader?jQuery' 
          },
          { test: /\.s?css$/, loader: ExtractTextPlugin.extract(['css-loader', 'resolve-url-loader', 'sass-loader?sourceMap']) },
          { test: /\.(gif|jpe|jpg|png)$/, use: 'url-loader?limit=10000' },
          { test: /\.(eot|ttf|svg|woff(2)?)(\?.*$|$)/, use: 'file-loader' }
        ]
    },
    plugins: plugins,
    resolve: {
        extensions: ['.js', '.vue', '.json']
    },
    devtool: '#source-map',
    performance: {
      hints: process.env.NODE_ENV === 'production' ? "warning" : false
    },
    devServer: {
        contentBase: 'public',
        host: '0.0.0.0',
        disableHostCheck: true,
        headers: { "Access-Control-Allow-Origin": "*" },
        stats: {
            chunks: false
        }
    }
}
