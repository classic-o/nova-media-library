Nova.booting((Vue, router) => {
    router.addRoutes([
        {
            name: 'nova-media-library',
            path: '/media-library',
            component: require('./tool/Index'),
        },
    ])
})
