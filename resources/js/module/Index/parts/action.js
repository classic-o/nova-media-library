export default {
  data() {
    return {
      list: {},
      upload: {}
    }
  },
  methods: {
    clearUpload(length = 0) {
      this.upload = {
        total: length,
        done: 0
      }
    },

    selectFiles(input) {
      if ( !input.target.files.length ) return;
      this.$parent.loading = true;
      this.clearUpload(input.target.files.length);

      this.list = Object.assign({}, input.target.files);
      this.uploadFile(0);

      document.getElementById('nml_upload').value = null;
    },

    uploadFile(i) {
      let file = this.list[i];
      if ( !file ) return this.uploadCheck();

      let config = { headers: { 'Content-Type': 'multipart/form-data' } };
      let data = new FormData();
      data.append('file', file);

      Nova.request().post('/nova-vendor/nova-media-library/upload', data, config).then(r => {
        this.upload.done++;
        this.$toasted.show(this.upload.done +' / '+ this.upload.total, { type: 'info', duration: 500 });
        this.uploadFile(i+1);
        if ( r.data.message ) {
          this.$toasted.show(r.data.message, { type: 'success' });
        }
      }).catch(e => {
        this.uploadFile(i+1);
        this.$toasted.show(e.response.data.message || this.__('nml_unknown_error'), { type: 'error' });
      });
    },

    uploadCheck() {
      this.$parent.loading = false;
      this.$toasted.show(this.__("nml_uploaded_files") +': '+ this.upload.done +'/'+ this.upload.total, { type: 'success' });
      this.$parent.clearData();
      this.$parent.get();
    },

    changeBulk() {
      if ( this.$parent.bulk.is ) this.$parent.bulk.array = [];
      this.$parent.bulk.is = !this.$parent.bulk.is;
    },

    bulkAll() {
      if ( this.$parent.bulk.array.length === this.$parent.items.array.length ) {
        this.$parent.bulk.array = [];
      } else {
        this.$parent.bulk.array = [];
        this.$parent.items.array.forEach(item => this.$parent.bulk.array.push(item.id))
      }
    },

    pushFiles() {
      let bulk = this.$parent.bulk.array, i = bulk.length, data = this.$parent.items.array, array = [];
      for (let a = 0; a < data.length; a++) {
        if ( bulk.includes(data[a].id) ) {
          array.push(data[a].url);
          i--;
          if ( i < 1 ) break;
        }
      }
      Nova.$emit('nml-select-files', [this.$parent.field, array]);
    }
  }
}
