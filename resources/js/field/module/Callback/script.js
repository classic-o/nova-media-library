import mixin from '../../mixin'
import Library from '../Library'

export default {
  mixins: [mixin],
  props: ['field'],
  components: { Library },
  data() {
    this.field.listing = true;
    return {
      popup: false
    }
  },
  methods: {
    select(value) {
      if ( 'string' === typeof this.field.jsCallback ) {
        return eval(this.field.jsCallback)(value, this.field.jsCbConfig || []);
      }
    }
  },
  created() {
    Nova.$on('nml-select-file', value => {
      if ( value[0] !== this.field.attribute ) return;
      this.popup = false;
      this.select([value[1]]);
    });
    Nova.$on('nml-select-files', value => {
      if ( value[0] !== this.field.attribute ) return;
      this.popup = false;
      this.select(value[1]);
    });
  }
}
