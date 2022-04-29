import Datepicker from 'vue3-datepicker'
import { ref } from 'vue'

export default {
  components: { Datepicker },
  data() {
    let picked = ref(new Date());
    let all = null;
    let types = this.$parent.field ? (this.$parent.$props.types || []) : [];
    if ( !types.length ) {
      types = this.$parent.config.types;
    } else {
      all = types;
    }
    return {
      all,
      types,
      picked,
      uploadFromText:'Upload From',
      uploadToText:'Upload To',
      uploadFrom:new Date(),
      uploadTo:new Date()
      
    }
  },
  watch: {
    uploadFrom () {
      return this.updateDate(this.$refs.uploadFrom.input, 'from');
    },
    uploadTo () {
      return this.updateDate(this.$refs.uploadTo.input, 'to');
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
