import nmlArray from '../module/Array/';
import nmlCallback from '../module/Callback/';
import nmlFile from '../module/File/';
import nmlTrix from '../module/Trix/';

export default {
    props: ['field'],
    components: { nmlArray, nmlFile, nmlCallback, nmlTrix },
    data() {
        return {
            isHidden: this.field.nmlHidden === true,
        };
    },

    computed: {
        elementSize() {
            return this.field.size || 'w-full';
        },
    },
};
