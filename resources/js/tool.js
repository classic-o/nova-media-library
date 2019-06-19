Nova.booting((Vue, router, store) => {
    router.addRoutes([
        {
            name: 'media-library',
            path: '/media-library',
            component: require('./tool/'),
        },
    ])
});

if ( 'object' === typeof Nova.config.nml_lang ) {
    Object.assign(Nova.config.translations, Nova.config.nml_lang)
}
