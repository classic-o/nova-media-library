import draggable from 'vuedraggable'
import Library from './nmlLibrary.vue'

export default {
  props: ['field', 'handler'],
  components: { draggable, Library },
  data() {
    return {
      array: [],
      isForm: this.$parent.$parent.isFormField === true,
      popup: false
    }
  },
  methods: {
    changeGallery(value) {
      this.array = value;
      if ( this.handler ) this.handler(JSON.stringify(value));
    },
    remove(item) {
      this.changeGallery(this.array.slice().filter(image => image !== item));
    }
  },
  created() {
    Nova.$on('nml-select-file', value => {
      if ( value[0] !== this.field.attribute ) return;
      this.array.push(value[1]);
      this.changeGallery(this.array);
      this.popup = false;
    });
    Nova.$on('nml-select-files', value => {
      if ( value[0] !== this.field.attribute ) return;
      this.array = this.array.concat(value[1]);
      this.changeGallery(this.array);
      this.popup = false;
    });

    try {
      let array = JSON.parse(this.field.value);
      if ( Array.isArray(array) ) this.array = array;
    } catch (e) {}
  }
}
