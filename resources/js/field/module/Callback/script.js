import Library from '../Library'

export default {
  props: ['field'],
  components: { Library },
  data() {
    return {
      popup: false
    }
  },
  methods: {
    select(array) {
      let cb = this.field.nmlJsCallback;
      if ( 'object' === typeof cb && cb[0] && window[cb[0]] )
        eval(cb[0])(array, cb[1]);
    }
  },
  created() {
    Nova.$on(`nmlSelectFiles[${this.field.attribute}]`, array => {
      this.popup = false;
      this.select(array);
    });
  },
  beforeUnmount() {
    Nova.$off(`nmlSelectFiles[${this.field.attribute}]`);
  }
}
