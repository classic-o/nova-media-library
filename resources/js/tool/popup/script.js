import { copy } from 'v-copy';

export default {
  directives: { copy },
  data() {
    return {
      folder: null
    }
  },
  computed: {
    folders() {
      return this.getFolders(this.$parent.config.folders, '/', ['/']);
    }
  },
  methods: {
    getFolders(obj, path, array) {
      for (let i in obj) {
        array.push(path+i+'/');
        if ( 'object' === typeof obj[i] )
          array = this.getFolders(obj[i], path+i+'/', array);
      }
      return array;
    },
    onPrivate(e) {
      this.$set(this.$parent.item, 'private', e.target.checked)
    },
    update() {
      let cp = this.$parent.config.can_private;
      this.$parent.loading = true;
      let data = { id: this.$parent.item.id, title: this.$parent.item.title, folder: this.folder };
      if ( cp ) data.private = Boolean(this.$parent.item.private);

      Nova.request().post('/nova-vendor/nova-media-library/update', data).then(r => {
        this.$toasted.show(this.__('Successfully updated'), { type: 'success' });
        this.$parent.loading = false;
        this.$parent.item = null;
        if ( this.folder || cp ) {
          this.$parent.clearData();
          this.$parent.get();
          this.folder = null;
        } else {
          let index = this.$parent.items.array.findIndex(x => x.id === r.data.id);
          if ( index > -1 && r.data.id ) {
            r.data.url += '?'+Date.now();
            this.$set(this.$parent.items.array, index, r.data);
          }
        }
      }).catch(e => {
        this.$parent.loading = false;
        window.nmlToastHook(e);
      });
    },
    onCopy() {
      this.$toasted.show(this.__('URL has been copied'), { type: 'success' });
    }
  },

  mounted() {
    document.body.classList.add('overflow-hidden');
  },
  beforeDestroy() {
    document.body.classList.remove('overflow-hidden');
  }
}
