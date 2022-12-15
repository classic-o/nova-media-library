import Library from '../Library';
import Mixin from '../../../_mixin';

export default {
    props: ['field', 'handler'],
    mixins: [Mixin],
    components: { Library },
    data() {
        return {
            popup: false,
            isForm: this.$parent.$parent.$parent.isFormField === true,
            item: this.field.value,
        };
    },
    methods: {
        changeFile(item) {
            this.item = item;
            if (this.handler) this.handler(item);
        },
    },
    created() {
        Nova.$on(`nmlSelectFiles[${this.field.attribute}]`, (array) => {
            this.popup = false;
            this.changeFile(array[0]);
        });
    },
    beforeUnmount() {
        Nova.$off(`nmlSelectFiles[${this.field.attribute}]`);
    },

    watch: {
        'field.value'(newValue) {
            this.item = newValue;
        },
    },
};
