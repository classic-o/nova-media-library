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
      let trix = document.querySelector(`[nml-trix="${this.field.nmlTrix}"]`);

      if ( trix && Array.isArray(array) ) {
        array.forEach(item => {
          trix.editor.insertHTML(
            'image' === item.options.mime
              ? `<img src="${item.url}">`
              : `<a href="${item.url}">${item.url}</a>`
          );
        });
      }

      this.clearAttach();
    },
    clearAttach() {
      let trix = document.querySelector(`[nml-trix="${this.field.nmlTrix}"]`);
      if ( trix ) trix.editor.composition.attachments = [];
    }
  },
  created() {
    addEventListener('trix-attachment-add', this.clearAttach);
    Nova.$on(`nmlSelectFiles[${this.field.attribute}]`, array => {
      this.popup = false;
      this.select(array);
    });
  },
  beforeUnmount() {
    removeEventListener('trix-attachment-add', this.clearAttach);
    Nova.$off(`nmlSelectFiles[${this.field.attribute}]`);
  }
}
