import mixin from '../mixin'
import nmlList from '../module/List/'
import nmlFile from '../module/File/'

export default {
  mixins: [mixin],
  props: ['field'],
  components: { nmlList, nmlFile },
  data() {
    return {
      isHidden: this.field.isHidden === true
    }
  }
}
