import draggable from 'vuedraggable'
import mixin from '../../mixin'
import Library from '../Library'

export default {
  mixins: [mixin],
  props: ['field', 'handler'],
  components: { draggable, Library },
  data() {
    return {
      type: 'line' === this.field.listing ? `line` : '',
      array: [],
      isForm: this.$parent.$parent.isFormField === true,
      popup: false
    }
  },
  methods: {
    changeListing(value) {
      this.array = value;
      if ( this.handler ) this.handler(JSON.stringify(value));
    },
    remove(num) {
      this.changeListing(this.array.slice().filter((file, i) => i !== num));
    },
    getName(item) {
      return item.split('/').reverse()[0].toString();
    }
  },
  created() {
    Nova.$on('nml-select-file', value => {
      if ( value[0] !== this.field.attribute ) return;
      this.array.push(value[1]);
      this.changeListing(this.array);
      this.popup = false;
    });
    Nova.$on('nml-select-files', value => {
      if ( value[0] !== this.field.attribute ) return;
      this.array = this.array.concat(value[1]);
      this.changeListing(this.array);
      this.popup = false;
    });

    try {
      if ( Array.isArray(this.field.value) ) this.array = this.field.value;
    } catch (e) {}
  }
}
