export default {
  data() {
    return {
      types: this.$parent.$props.types || []
    }
  },
  methods: {
    updateDate(value, target) {
      this.$parent.filter[target] = value || null;
      this.$parent.doSearch();
    },
  },
  created() {
    if ( this.types.length )
      this.$parent.filter.type = this.types[0];
  }
}
