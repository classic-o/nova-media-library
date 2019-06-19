import mixin from '../../mixin'
import Library from '../Library'

export default {
  mixins: [mixin],
  props: ['field'],
  components: { Library },
  data() {
    this.field.listing = true;
    return {
      popup: false
    }
  },
  methods: {
    select(value) {
      let trix = document.querySelector(`[trix-nml="${this.field.forTrix}"]`);
      if ( !trix ) return this.$toasted.show(this.__("nml_trix_id_incorrect"), { type: 'error' });

      if ( Array.isArray(value) ) {
        value.forEach(item => {
          let html = this.isImage(item) ? `<img src="${item}">` : `<a href="${item}">${item}</a>`;
          trix.editor.insertHTML(html);
        });
      }

      this.clearAttach();
    },
    clearAttach() {
      let trix = document.querySelector(`[trix-nml="${this.field.forTrix}"]`);
      if ( trix ) trix.editor.composition.attachments = [];
    }
  },
  created() {
    addEventListener('trix-attachment-add', this.clearAttach);
    Nova.$on('nml-select-file', value => {
      if ( value[0] !== this.field.attribute ) return;
      this.select([value[1]]);
      this.popup = false;
    });
    Nova.$on('nml-select-files', value => {
      if ( value[0] !== this.field.attribute ) return;
      this.select(value[1]);
      this.popup = false;
    });
  },
  beforeDestroy() {
    removeEventListener('trix-attachment-add', this.clearAttach);
  }
}
