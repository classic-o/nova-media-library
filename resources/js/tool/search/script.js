export default {
  data() {
    let all = null;
    let types = this.$parent.field ? (this.$parent.$props.types || []) : [];
    if ( !types.length ) {
      types = this.$parent.config.types;
    } else {
      all = types;
    }
    return {
      all,
      types
    }
  },
  methods: {
    updateDate(value, target) {
      this.$parent.filter[target] = value || null;
      this.$parent.doSearch();
    },
    display(val) {
      this.$parent.config.display = val;
      localStorage.setItem('nml-display', val);
    }
  },
  created() {
    this.$parent.filter.type = this.all;
  }
}
