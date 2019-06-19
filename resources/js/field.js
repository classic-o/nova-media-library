Nova.booting((Vue) => {
    Vue.component('index-nml-field', require('./field/Index/'));
    Vue.component('detail-nml-field', require('./field/Detail/'));
    Vue.component('form-nml-field', require('./field/Form/'));
});
