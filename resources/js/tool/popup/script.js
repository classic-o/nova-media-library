
import Toasted from 'toastedjs'
let toasted = new Toasted({
  theme: 'nova',
  position: 'bottom-right',
  duration: 6000,
})

export default {
  data() {
    return {
      folder: null,
      //item: {"id":49,"title":"Screenshot 2022-04-22 020515","created":"2022-04-29T12:15:18.000000Z","type":"Image","folder":"/","name":"screenshot-2022-04-22-020515-1651234518oNnbq.png","private":0,"lp":0,"options":{"mime":"image","wh":[986,267],"size":"27.49 kb","img_sizes":["thumb","medium"]},"preview":"http://127.0.0.1:8000/storage/screenshot-2022-04-22-020515-1651234518oNnbq-thumb.png","url":"http://127.0.0.1:8000/storage/screenshot-2022-04-22-020515-1651234518oNnbq.png","path":"/screenshot-2022-04-22-020515-1651234518oNnbq.png"}
    }
  },
  computed: {
    folders() {
      return this.getFolders(this.$parent.$parent.config.folders, '/', ['/']);
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
      //this.$set(this.$parent.$parent.item, 'private', e.target.checked)
      this.$parent.$parent.item['private'] = e.target.checked;
    },
    update() {
      let cp = this.$parent.$parent.config.can_private;
      this.$parent.$parent.loading = true;
      let data = { id: this.$parent.$parent.item.id, title: this.$parent.$parent.item.title, folder: this.folder };
      if ( cp ) data.private = Boolean(this.$parent.$parent.item.private);

      Nova.request().post('/nova-vendor/nova-media-library/update', data).then(r => {
        toasted.show(this.__('Successfully updated'), { type: 'success' });
        this.$parent.$parent.loading = false;
        this.$parent.$parent.item = null;
        if ( this.folder || cp ) {
          this.$parent.$parent.clearData();
          this.$parent.$parent.get();
          this.folder = null;
        } else {
          let index = this.$parent.$parent.items.array.findIndex(x => x.id === r.data.id);
          if ( index > -1 && r.data.id ) {
            r.data.url += '?'+Date.now();
            //this.$set(this.$parent.$parent.items.array, index, r.data);
            this.$parent.$parent.items.array[index] = r.data;
            this.$parent.$parent.items.array[index] = r.data;
          }
        }
      }).catch(e => {
        this.$parent.$parent.loading = false;
        window.nmlToastHook(e);
      });
    },
    onCopy() {
      toasted.show(this.__('URL has been copied'), { type: 'success' });
    }
  },

  mounted() {
    document.body.classList.add('overflow-hidden');
  },
  beforeUnmount() {
    document.body.classList.remove('overflow-hidden');
  }
}
