import Popup from '../IndexPopup/IndexPopup.vue'

let wheel = null;
let timeout = null;

export default {
  components: { Popup },
  props: {
    tool: { type: Boolean, default: false },
    field: { type: String, default: 'none' },
    gallery: { type: Boolean, default: false }
  },
  data() {
    return {
      bulk: {
        array: [],
        is: false
      },
      filter: {
        title: '',
        from: '',
        to: '',
        step: 0
      },
      old: {},
      data: {
        array: [],
        full: false
      },
      upload: {
        count: 0,
        done: 0,
        error: 0
      },
      popup: null,
      loading: false,
      types: '.'+Nova.config.nml_type.join(',.')
    }
  },
  methods: {

    clearData() {
      this.data = { array: [], full: false };
      this.filter.step = 0;
    },

    clearUpload(length = 0) {
      this.upload = {
        count: length,
        done: 0,
        error: 0
      }
    },

    doSearch() {
      if ( JSON.stringify(this.filter) === JSON.stringify(this.old) ) return;
      this.old = {...this.filter};
      clearTimeout(timeout);
      timeout = setTimeout(() => {
        this.clearData();
        this.get();
      }, 1000);
    },

    updateDate(value, target) {
      this.filter[target] = value;
      this.doSearch();
    },

    get() {
      this.data.full = true;
      Nova.request().post('/nova-vendor/nova-media-library/get', this.filter).then( r => {
        if ( Array.isArray(r.data) ) {
          this.data.array = this.data.array.concat(r.data);
        }
        this.data.full = r.data.length < 1;
      }).catch(() => {});
    },

    loader() {
      this.filter.step++;
      this.old.step++;
      this.get();
    },

    scroller() {
      if ( this.data.full || this.loading ) return;
      try {
        if ( (window.innerHeight + window.scrollY) >= document.body.offsetHeight ) {
          this.loader();
        }
      } catch (e) {
        console.error('Your browser does not support some features for automatic file downloads.');
      }
    },

    selectFiles(input) {
      if ( !input.target.files.length ) return;
      this.loading = true;
      this.clearUpload(input.target.files.length);

      for (let key in input.target.files) {
        if ( !isNaN(parseInt(key)) ) {
          this.uploadFile(input.target.files[key], key);
        }
      }

      document.getElementById('nml_upload').value = null;
    },

    uploadFile(file, i) {
      let config = { headers: { 'Content-Type': 'multipart/form-data' } };
      let data = new FormData();
      data.append('file', file);
      data.append('num', i);

      Nova.request().post('/nova-vendor/nova-media-library/upload', data, config).then(r => {
        if ( r.data.status ) {
          this.upload.done++;
        } else {
          let error = r.data.error ? r.data.error : 'No file has been uploaded.';
          this.$toasted.show(error, { type: 'error' });
        }
        this.uploadCheck();
      }).catch(() => {
        this.upload.error++;
        this.uploadCheck();
      });
    },

    uploadCheck() {
      this.upload.count -= 1;
      if ( this.upload.count < 1 ) {
        this.loading = false;
        this.$toasted.show('Uploaded files: '+ this.upload.done, { type: 'success' });
        if ( this.upload.error > 0 ) {
          this.$toasted.show('Uploaded files with error: '+ this.upload.error, { type: 'error' });
        }
        if ( this.upload.done > 0 ) {
          this.clearData();
          this.get();
        }
      }
    },

    deleteFiles(ids) {
      if ( !ids.length || !confirm('Delete selected?') ) return;
      this.loading = true;
      Nova.request().post('/nova-vendor/nova-media-library/delete', { ids: ids }).then(r => {
        if ( r.data.status ) {
          this.popup = null;
          this.bulk.array = [];
          this.clearData();
          this.get();
        } else {
          this.$toasted.show('No file has been deleted.', { type: 'error' });
        }
        this.loading = false;
      }).catch(() => {
        this.loading = false;
        this.$toasted.show('Something went wrong.', { type: 'error' });
      });
    },

    changeBulk() {
      if ( this.bulk.is ) this.bulk.array = [];
      this.bulk.is = !this.bulk.is;
    },

    bulkAll() {
      if ( this.bulk.array.length === this.data.array.length ) {
        this.bulk.array = [];
      } else {
        this.bulk.array = [];
        this.data.array.forEach(item => this.bulk.array.push(item.id))
      }
    },

    pushFiles() {
      console.log(this.field);
      let bulk = this.bulk.array, i = bulk.length, data = this.data.array, array = [];
      for (let a = 0; a < data.length; a++) {
        if ( bulk.includes(data[a].id) ) {
          array.push(data[a].image.url);
          i--;
          if ( i < 1 ) break;
        }
      }
      Nova.$emit('nml-select-files', [this.field, array]);
    },

    clickCard(item) {
      if ( this.bulk.is ) {
        if ( this.bulk.array.includes(item.id) ) {
          this.bulk.array = this.bulk.array.filter(id => id !== item.id).slice();
        } else {
          this.bulk.array.push(item.id);
        }
      } else {
        if ( !this.tool ) {
          Nova.$emit('nml-select-file', [this.field, item.image.url]);
          return;
        }
        this.popup = null;
        Nova.request().get('/nova-vendor/nova-media-library/single?id='+item.id).then(r => {
          if ( r.data.id ) {
            this.popup = r.data;
          } else {
            this.$toasted.show('Something went wrong, try again.', { type: 'error' });
          }
        }).catch(() => {});

      }
    }

  },

  created() {
    if ( 'onwheel' in document )      wheel = 'wheel';
    if ( 'onmousewheel' in document ) wheel = 'mousewheel';
    this.old = {...this.filter};
    this.get();

    if ( this.tool ) document.addEventListener(wheel, this.scroller);
  },

  beforeDestroy() {
    if ( this.tool ) document.removeEventListener(wheel, this.scroller);
  }
}
