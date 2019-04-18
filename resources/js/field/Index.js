export default {
  props: ['resourceName', 'field'],
  data() {
    return {
      count: 0,
      src: null
    }
  },
  created() {
    let field = this.field;
    if ( !field.value ) return;
    if ( field.isGallery ) {
      try {
        let array = JSON.parse(this.field.value);
        if ( Array.isArray(array) && array.length > 0 ) {
          this.src = array[0];
          this.count = array.length - 1
        }
      } catch (e) {}
    } else {
      this.src = field.value;
    }
  }
}
