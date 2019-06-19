import mixin from '../mixin'

export default {
  mixins: [mixin],
  props: ['field'],
  data() {
    return {
      count: 0,
      url: false
    }
  },
  created() {
    let field = this.field;
    if ( !field.value ) return;

    if ( field.listing ) {
      try {
        if ( Array.isArray(field.value) && field.value.length > 0 ) {
          this.count = field.value.length;
          this.url = field.value[0];
        }
      } catch (e) {}
    } else {
      this.url = field.value;
    }
  }
}
