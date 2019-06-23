import nmlAction from './parts/action.vue'
import nmlSearch from './parts/search.vue'
import nmlItems from './parts/items.vue'
import nmlLoader from './parts/loader.vue'
import Popup from '../Popup/index.vue'
import Crop from '../Crop/index.vue'

let timeout = null;
let wheel = null;

export default {
  props: {
    types: { type: Array, default: [] },
    tool: { type: Boolean, default: false },
    field: { type: String, default: 'none' },
    listing: { default: false }
  },
  components: {
    nmlAction,
    nmlSearch,
    nmlItems,
    nmlLoader,
    Popup,
    Crop
  },
  data() {
    return {
      config: window.Nova.config,
      bulk: {
        array: [],
        is: false
      },
      items: {
        array: [],
        full: false
      },
      filter: {
        description: null,
        type: this.types[0] || null,
        from: null,
        to: null,
        step: 0
      },
      oldFilter: {},
      loading: false,
      popup: null,
      popupType: null
    }
  },
  methods: {
    clearData() {
      this.items = { array: [], full: false };
      this.filter.step = 0;
    },

    get() {
      this.items.full = true;
      this.loading = true;
      Nova.request().post('/nova-vendor/nova-media-library/get', this.filter).then(r => {
        this.loading = false;
        this.items.full = r.data.length < 1;
        if ( Array.isArray(r.data) ) {
          this.items.array = this.items.array.concat(r.data);
        }
      }).catch(() => {
        this.loading = false;
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
      this.filter.step++;
      this.oldFilter.step++;
      this.get();
    },

    scroller() {
      if ( this.items.full || this.loading ) return;
      try {
        if ( (window.innerHeight + window.scrollY) >= document.body.offsetHeight ) {
          this.loader();
        }
      } catch (e) {
        console.error(this.__('nml_bad_browser'));
      }
    },

    deleteFiles(ids) {
      if ( !ids.length || !confirm(this.__("nml_delete_selected")) ) return;
      this.loading = true;
      Nova.request().post('/nova-vendor/nova-media-library/delete', { ids: ids }).then(r => {
        this.popup = null;
        this.bulk.array = [];
        this.clearData();
        this.get();
        this.loading = false;
      }).catch(e => {
        this.loading = false;
        this.$toasted.show(e.response.data.message, { type: 'error' });
      });
    }
  },

  created() {
    if ( 'onwheel' in document )      wheel = 'wheel';
    if ( 'onmousewheel' in document ) wheel = 'mousewheel';
    this.oldFilter = {...this.filter};
    this.get();

    if ( this.tool ) document.addEventListener(wheel, this.scroller);
  },

  beforeDestroy() {
    if ( this.tool ) document.removeEventListener(wheel, this.scroller);
  }
}
