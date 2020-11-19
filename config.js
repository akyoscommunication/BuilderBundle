const path = require('path');

const rootPath = path.resolve(__dirname);

const config = {
    paths: {
        root: rootPath,
        assets: path.join(rootPath, 'assets'),
        dist: path.join(rootPath, 'Resources/public')
    },
    entry: {
        'builderjs': "./assets/scripts/builderjs.js",
        'buildercss': "./assets/styles/buildercss.scss",
        'visualEditorcss': "./assets/styles/layouts/_visual-editor.scss",
    },
    manifest: {},
    minify: (process.env.NODE_ENV === 'production')
};

module.exports = config;