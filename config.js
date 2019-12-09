const path = require('path');

const rootPath = path.resolve(__dirname);

const config = {
    paths: {
        root: rootPath,
        assets: path.join(rootPath, 'assets'),
        dist: path.join(rootPath, 'Resources/public')
    },
    entry: [
        "./assets/scripts/main.js",
        "./assets/styles/main.scss"
    ],
    manifest: {},
    minify: (process.env.NODE_ENV === 'production')
};

module.exports = config;