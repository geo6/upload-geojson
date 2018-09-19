const path = require('path');

module.exports = (env, options) => {
    return {
        entry: {
            'upload': './resources/javascript/upload.js',
            'view': './resources/javascript/view.js'
        },
        output: {
            filename: '[name].min.js',
            path: path.resolve(__dirname, 'public/js')
        },
        module: {
        },
        plugins: [
        ],
        devtool: options.mode === 'development' ? 'cheap-eval-source-map' : false
    };
};
