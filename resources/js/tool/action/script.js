export default {
  data() {
    return {
      files: []
    }
  },
  methods: {
    showUploader() {
      this.$parent.show_uploader = true;
      this.$parent.popup = 'uploader';
    },

    changeBulk() {
      this.$set(this.$parent.bulk, 'ids', {});
      this.$parent.bulk.enable = !this.$parent.bulk.enable;
    },

    bulkAll() {
      if ( this.$parent.bulkLen() === this.$parent.items.array.length ) {
        this.$set(this.$parent.bulk, 'ids', {});
      } else {
        this.$parent.items.array.forEach(item => {
          this.$set(this.$parent.bulk.ids, item.id, item);
        });
      }
    },

    pushFiles() {
      let data = this.$parent.bulk.ids, array = [];
      for (let key in data)
        array.push(data[key]);

      Nova.$emit(`nmlSelectFiles[${this.$parent.field}]`, array);
    }
  }
}
