var Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}



Encore
    .setOutputPath('public/assets/')
    .setPublicPath('/assets')

    .addEntry('app', './assets/js/app.js')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
    .enableSassLoader()
    .copyFiles([
        {from: './assets/img', context: './assets'},
        {from: './node_modules/@coreui/icons/svg', context: './node_modules/@coreui/icons'},
        {from: './node_modules/@coreui/icons/sprites', context: './node_modules/@coreui/icons'},
        {from: './node_modules/@coreui/icons/fonts', context: './node_modules/@coreui/icons'}
    ])
    .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
