export default {
  methods: {
    bgSize(item) {
      let wh = item.options.wh;
      if (!wh) return '';
      return ( wh[0] < 151 || wh[1] < 151 ) ? 'auto !important' : '';
    },
    bg(item) {
      return 'image' === item.options.mime ? {
        backgroundImage: `url(${item.preview || item.url})`,
        backgroundSize: this.bgSize(item)
      } : {};
    },
    mime(item) {
      return ['image', 'audio', 'video'].indexOf(item.options.mime) > -1
        ? item.options.mime : 'file';
    },
  }
}
