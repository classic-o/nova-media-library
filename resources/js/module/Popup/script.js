import { copy } from 'v-copy';

export default {
  directives: { copy },
  methods: {
    error() {
      this.$toasted.show(this.__("nml_unknown_error"), { type: 'error' });
    },
    update() {
      this.$parent.loading = true;
      Nova.request().post('/nova-vendor/nova-media-library/update', {
        id: this.$parent.popup.id,
        description: this.$parent.popup.description
      }).then(() => {
        this.$toasted.show(this.__("nml_successfully_updated"), { type: 'success' });
        this.$parent.loading = false;
        this.$parent.popup = null;
      }).catch(() => {
        this.$parent.loading = false;
        this.error();
      });
    },
    onCopy() {
      this.$toasted.show(this.__("nml_url_copied"), { type: 'success' });
    }
  },

  mounted() {
    document.body.classList.add('overflow-hidden');
  },
  beforeDestroy() {
    document.body.classList.remove('overflow-hidden');
  }
}
