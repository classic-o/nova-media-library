import Toasted from 'toastedjs'
let toasted = new Toasted({
  theme: 'nova',
  position: 'bottom-right',
  duration: 6000,
})
export default {
 
  emits: ['changebulk'],
  
  data() {
    return {
      files: [],
      upload: {},
      
    }
  },
  
  methods: {
    clearUpload(length = 0) {
      this.upload = { total: length, done: 0 }
    },
    selectFiles(input) {
      if ( !input.target.files.length ) return;
      this.$parent.loading = true;
      this.clearUpload(input.target.files.length);

      this.files = Object.assign({}, input.target.files);
      this.uploadFile(0);

      document.getElementById('nml_upload').value = null;
    },
    uploadFile(i) {
      let file = this.files[i];
      if ( !file ) return this.uploadCheck();

      let config = { headers: { 'Content-Type': 'multipart/form-data' } };
      let data = new FormData();
      data.append('file', file);
      data.append('folder', this.$parent.filter.folder);

      Nova.request().post('/nova-vendor/nova-media-library/upload', data, config).then(r => {
        this.upload.done++;
        toasted.show(this.upload.done +' / '+ this.upload.total, { type: 'info', duration: 500 });
        this.uploadFile(i+1);
        if ( r.data.message ) toasted.show(r.data.message, { type: 'success' });
      }).catch(e => {
        this.uploadFile(i+1);
        window.nmlToastHook(e);
      });
    },
    uploadCheck() {
      this.$parent.loading = false;
      toasted.show(this.__('Uploaded') +': '+ this.upload.done +'/'+ this.upload.total, { type: 'success' });
      this.$parent.clearData();
      this.$parent.get();
    },

    changeBulk() {
      //this.$emit('changebulk');
      this.$parent.bulk['ids'] = {};
      this.$parent.bulk.enable = !this.$parent.bulk.enable;
      
      
    },

    bulkAll() {
      if ( this.$parent.bulkLen() === this.$parent.items.array.length ) {
        //this.$set(this.$parent.bulk, 'ids', {});
        this.$parent.bulk['ids'] = {};
      } else {
        this.$parent.items.array.forEach(item => {
          //this.$set(this.$parent.bulk.ids, item.id, item);
          this.$parent.bulk.ids[item.id] = item;
        });
      }
    },

    pushFiles() {
      let data = this.$parent.bulk.ids, array = [];
      for (let key in data)
        array.push(data[key]);

      Nova.$emit(`nmlSelectFiles[${this.$parent.field}]`, array);
    }
  }
}
