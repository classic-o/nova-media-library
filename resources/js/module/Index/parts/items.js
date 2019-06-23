export default {
  methods: {
    bg(item) {
      return 'image' === item.mime ? { backgroundImage: `url(${item.url})` } : {};
    },
    mime(item) {
      switch (item.mime) {
        case 'image': return 'image';
        case 'audio': return 'audio';
        case 'video': return 'video';
        default: return 'file';
      }
    },
    clickCard(item) {
      if ( this.$parent.bulk.is ) {
        if ( this.$parent.bulk.array.includes(item.id) ) {
          this.$parent.bulk.array = this.$parent.bulk.array.filter(id => id !== item.id).slice();
        } else {
          this.$parent.bulk.array.push(item.id);
        }
      } else {
        if ( !this.$parent.tool ) {
          Nova.$emit('nml-select-file', [this.$parent.field, item.url]);
          return;
        }
        this.$parent.popup = item;
        this.$parent.popupType = 'info';
      }
    }
  }
}
