import Cropper from 'cropperjs';
import Toasted from 'toastedjs';
let toasted = new Toasted({
  theme: 'nova',
  position: 'bottom-right',
  duration: 6000,
})

export default {
  data() {
    return {
      img: null,
      crop: null,
      info: { rotate: 0 },
    }
  },
  methods: {
    rotate() {
      this.crop.rotateTo(parseInt(this.info.rotate || 0));
    },
    save(over) {
      this.info.over = over;
      this.info.id = this.$parent.$parent.item.id;
      this.$parent.$parent.loading = true;
      Nova.request().post('/nova-vendor/nova-media-library/crop', this.info).then(() => {
        toasted.show(this.__('Image cropped successfully'), { type: 'success' });
        this.$parent.$parent.clearData();
        this.$parent.$parent.get();
        this.$parent.$parent.item = null;
      }).catch(e => {
        this.$parent.$parent.loading = false;
        window.nmlToastHook(e);
      });
    }
  },
  mounted() {
    document.body.classList.add('overflow-hidden');
    let el = this;
    el.img = document.getElementById('cropper-img');
    el.crop = new Cropper(el.img, {
      autoCrop: false,
      checkCrossOrigin: false,
      guides: false,
      //toggleDragModeOnDblclick: false,
      viewMode: 1,
      //zoomable: false,
      crop(e) {
        el.info = {
          x: e.detail.x,
          y: e.detail.y,
          width: parseInt(e.detail.width),
          height: parseInt(e.detail.height),
          rotate: e.detail.rotate
        };
      },
    });
  },
  beforeDestroy() {
    document.body.classList.remove('overflow-hidden');
  }
}
