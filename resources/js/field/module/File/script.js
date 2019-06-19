import mixin from '../../mixin'
import Library from '../Library'

export default {
  mixins: [mixin],
  props: ['field','handler'],
  components: { Library },
  data() {
    return {
      isForm: this.$parent.$parent.isFormField === true,
      popup: false,
      url: this.field.value
    }
  },
  methods: {
    changeFile(value) {
      this.url = value;
      if ( this.handler ) this.handler(value);
    }
  },
  created() {
    Nova.$on('nml-select-file', value => {
      if ( value[0] !== this.field.attribute ) return;
      this.changeFile(value[1]);
      this.popup = false;
    });
  }
}
