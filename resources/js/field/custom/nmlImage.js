import Library from './nmlLibrary.vue'

export default {
  props: ['field','handler'],
  components: { Library },
  data() {
    return {
      error: true,
      isForm: this.$parent.$parent.isFormField === true,
      popup: false,
      src: this.field.value
    }
  },
  methods: {
    changeImage(value) {
      this.src = value;
      if ( this.handler ) this.handler(value);
    }
  },
  created() {
    Nova.$on('nml-select-file', value => {
      if ( value[0] !== this.field.attribute ) return;
      this.changeImage(value[1]);
      this.popup = false;
      this.error = false;
    });

    if ( typeof this.src !== 'string' ) return;
    axios.get(this.src).then( r => {
      this.error = r.headers['content-type'].split('/')[0] !== 'image';
    }).catch(() => {});
  }
}
