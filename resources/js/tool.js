import { VueClipboard } from '@soerenmartius/vue3-clipboard';
import Toasted from 'toastedjs'
let toasted = new Toasted({
  theme: 'nova',
  position: 'bottom-right',
  duration: 6000,
})
Nova.booting((Vue) => {
  Vue.use(VueClipboard);
  Vue.component("index-media-library-field", require("./field/Index/").default);
  Vue.component("detail-media-library-field", require("./field/Detail/").default);
  Vue.component("form-media-library-field", require("./field/Form/").default);
  Nova.inertia("NovaMediaLibrary", require("./tool/").default);
  
  

  window.nmlToastHook = (e) => {
    if (422 === e.response.status && e.response.data.message)
      Vue.prototype.toasted.show(e.response.data.message, { type: "error" });
  };
});

if ("object" === typeof Nova.config.novaMediaLibrary) {
  if (Nova.config.novaMediaLibrary.store === "folders") {
    Nova.request()
      .get("/nova-vendor/nova-media-library/folders")
      .then((r) => {
        Object.assign(Nova.config.novaMediaLibrary, { folders: r.data });
      });
  }
  if ("object" === typeof Nova.config.novaMediaLibrary.lang) {
    Object.assign(Nova.config.translations, Nova.config.novaMediaLibrary.lang);
  }
}

//alert(window.Nova.config.novaMediaLibrary);
//document.querySelector('meta[name="viewport"]').setAttribute('content', 'width=device-width, initial-scale=1.0, user-scalable=yes');
