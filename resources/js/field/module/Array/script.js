import draggable from 'vuedraggable';
import Library from '../Library';
import Mixin from '../../../_mixin';

export default {
    props: ['field', 'handler'],
    mixins: [Mixin],
    components: { draggable, Library },
    data() {
        let type = this.field.nmlArray;
        if ('auto' === type) type = 'list' === localStorage.getItem('nml-display') ? 'list' : 'gallery';

        return {
            popup: false,
            isForm: this.$parent.$parent.$parent.isFormField === true,
            array: [],
            type,
        };
    },
    methods: {
        changeArray(array) {
            //this.$set(this, 'array', array || []);
            this['array'] = array || [];
            if (this.handler) this.handler(array);
        },
        remove(num) {
            this.changeArray(this.array.slice().filter((item, i) => i !== num));
        },
    },
    created() {
        Nova.$on(`nmlSelectFiles[${this.field.attribute}]`, (array) => {
            this.popup = false;
            this.array = this.array.concat(array);
            this.changeArray(this.array);
        });

        try {
            if (Array.isArray(this.field.value)) this.array = this.field.value;
        } catch (e) {}
    },
    beforeUnmount() {
        Nova.$off(`nmlSelectFiles[${this.field.attribute}]`);
    },

    watch: {
        'field.value'(newValue) {
            this.array = newValue;
        },
    },
};
