import Action from './action'
import Search from './search'
import Items  from './items'
import Loader from './loader'
import Popup from './popup'
import Crop from './crop'

let timeout = null;
let wheel = null;

export default {
  props: {
    field: { type: String, default: null },
    isArray: { default: false },
    types: { type: Array, default: [] },
  },

  components: {
    Action,
    Search,
    Items,
    Loader,
    Crop,
    Popup,
  },

  data() {
    let config = window.Nova.config.novaMediaLibrary;
    config.display = 'list' === localStorage.getItem('nml-display') ? 'list' : 'gallery';
    return {
      config,

      bulk: {
        ids: {},
        enable: false
      },

      items: {
        array: [],
        total: null
      },

      filter: {
        title: null,
        type: this.types,
        from: null,
        to: null,
        page: 0,
        folder: 'folders' === config.store ? '/' : null
      },
      oldFilter: {},

      loading: false,
      item: null,
      popup: null
    }
  },

  methods: {
    bulkLen() {
      return Object.keys(this.bulk.ids).length
    },
    clearData() {
      this.items = { array: [], total: null };
      this.filter.page = 0;
    },
    get() {
      this.loading = true;
      Nova.request().post('/nova-vendor/nova-media-library/get', this.filter).then(r => {
        this.loading = false;
        this.items = {
          array: this.items.array.concat(r.data.array),
          total: r.data.total
        };
      }).catch(e => {
        this.loading = false;
        window.nmlToastHook(e);
      });
    },
    deleteFiles(ids) {
      if ( !ids.length || !confirm(this.__('Delete selected files?')) ) return;
      this.loading = true;
      Nova.request().post('/nova-vendor/nova-media-library/delete', { ids: ids }).then(r => {
        this.popup = null;
        this.$set(this.bulk, 'ids', {});
        this.clearData();
        this.get();
        this.loading = false;
      }).catch(e => {
        this.loading = false;
        window.nmlToastHook(e);
      });
    },
    doSearch() {
      if ( JSON.stringify(this.filter) === JSON.stringify(this.oldFilter) ) return;
      this.oldFilter = {...this.filter};
      clearTimeout(timeout);
      timeout = setTimeout(() => {
        this.clearData();
        this.get();
      }, 1000);
    },
    loader() {
      this.filter.page++;
      this.oldFilter.page++;
      this.get();
    },
    scroller() {
      if ( this.loading || this.items.array.length === this.items.total ) return false;
      try {
        if ( (window.innerHeight + window.scrollY) >= document.body.offsetHeight ) this.loader();
      } catch (e) {}
    },
  },
  created() {
    if ( 'onwheel' in document )      wheel = 'wheel';
    if ( 'onmousewheel' in document ) wheel = 'mousewheel';
    this.oldFilter = {...this.filter};
    this.get();

    if ( !this.field && wheel ) document.addEventListener(wheel, this.scroller);
  },

  beforeDestroy() {
    if ( !this.field && wheel ) document.removeEventListener(wheel, this.scroller);
  }
}
