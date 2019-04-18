Nova.booting((Vue) => {
    Vue.component('index-media-field', require('./field/IndexField'))
    Vue.component('detail-media-field', require('./field/DetailField'))
    Vue.component('form-media-field', require('./field/FormField'))
})
