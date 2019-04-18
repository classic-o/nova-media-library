import { copy } from 'v-copy';

export default {
  directives: { copy },
  methods: {
    error() {
      this.$toasted.show('Something went wrong.', { type: 'error' });
    },
    update() {
      this.$parent.loading = true;
      Nova.request().post('/nova-vendor/nova-media-library/update', {
        id: this.$parent.popup.id,
        title: this.$parent.popup.title
      }).then(r => {
        if ( r.data.status ) {
          this.$toasted.show('Successfully updated.', { type: 'success' });
          this.$parent.popup = null;
        } else {
          this.error();
        }
        this.$parent.loading = false;
      }).catch(() => {
        this.$parent.loading = false;
        this.error();
      });
    },
    onCopy() {
      this.$toasted.show('URL has been copied', { type: 'success' });
    }
  },
  created() {
    document.body.classList.add('overflow-hidden');
  },
  beforeDestroy() {
    document.body.classList.remove('overflow-hidden');
  }
}
