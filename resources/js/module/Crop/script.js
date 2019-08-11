import Cropper from 'cropperjs';

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
      this.crop.rotateTo(parseInt(this.info.rotate) || 0);
    },
    save(over) {
      this.info.over = over;
      this.info.id = this.$parent.popup.id;
      this.$parent.loading = true;
      Nova.request().post('/nova-vendor/nova-media-library/crop', this.info).then(() => {
        this.$toasted.show(this.__('nml_cropped_successfully'), { type: 'success' });
        this.$parent.clearData();
        this.$parent.get();
        this.$parent.popup = null;
      }).catch(e => {
        this.$parent.loading = false;
        this.$toasted.show(e.response.data.message || this.__('nml_unknown_error'), { type: 'error' });
      });
    }
  },
  mounted() {
    document.body.classList.add('overflow-hidden');
    let el = this;
    this.img = document.getElementById('cropper-img');
    this.crop = new Cropper(this.img, {
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
    window.crop = this.crop;
  },
  beforeDestroy() {
    document.body.classList.remove('overflow-hidden');
  }
}
