export default {
  data() {
    let all = null;
    let types = this.$parent.field ? (this.$parent.$props.types || []) : [];
    if ( !types.length ) {
      types = this.$parent.config.types;
    } else {
      all = types;
    }

    let all_categories = null;
    let categories = this.$parent.field ? (this.$parent.$props.categories || []) : [];
    if ( !categories.length ) {
      categories = this.$parent.config.categories;
    } else {
      all_categories = categories;
    }
    return {
      all,
      all_categories,
      types,
      categories
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
    this.$parent.filter.category = this.all_categories;
  }
}
