import { DependentFormField, HandlesValidationErrors } from 'laravel-nova';

import nmlArray from '../module/Array/';
import nmlCallback from '../module/Callback/';
import nmlFile from '../module/File/';
import nmlTrix from '../module/Trix/';

export default {
    mixins: [DependentFormField, HandlesValidationErrors],
    components: { nmlArray, nmlFile, nmlCallback, nmlTrix },
    props: ['field'],
    data() {
        return {
            isFormField: true,
            isHidden: this.field.nmlHidden === true,
        };
    },
    methods: {
        display() {
            console.log(this.field.nmlTrix);
        },
        setInitialValue() {
            this.value = this.field.value || null;
        },
        fill(formData) {
            let data = null;

            if (this.value) {
                if (this.field.nmlArray && Array.isArray(this.value)) {
                    data = this.value.map((item) => item.id);
                } else if (!this.field.nmlArray && 'object' === typeof this.value && this.value.id) {
                    data = this.value.id;
                }
                if (Array.isArray(data)) data = JSON.stringify(data);
            }

            formData.append(this.field.attribute, data);
        },
        handleChange(value) {
            this.value = value;
        },
    },

    watch: {
        'currentField.value'(newValue) {
            this.value = newValue;
        },
    },
};
