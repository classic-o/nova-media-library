import Mixin from '../../_mixin'

export default {
  props: ['field'],
  mixins: [Mixin],
  data() {
    return {
      count: 0,
      item: null
    }
  },
  created() {
    let field = this.field;
    if ( !field.value ) return;

    if ( Array.isArray(field.value) && field.value.length > 0 ) {
      this.count = field.value.length;
      this.item = field.value[0];
    } else if ( 'object' === typeof field.value ) {
      this.item = field.value;
    }
  }
}
