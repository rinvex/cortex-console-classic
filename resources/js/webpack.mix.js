module.exports = {
    scanForCssSelectors: [
        path.join(__dirname, 'node_modules/jquery.terminal/**/*.js')
    ],
    whitelistPatterns: [],
    webpackPlugins: [],
    install: ['jquery.terminal'],
    copy: [],
    mix: {
        css: [
            {input: 'app/cortex/console/resources/sass/module.scss', output: 'public/css/cortex-console.css'},
        ],
        js: [
            {input: 'app/cortex/console/resources/js/module.js', output: 'public/js/cortex-console.js'},
        ],
    },
};
