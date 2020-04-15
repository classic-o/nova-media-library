// Based on https://github.com/lian-yue/vue-upload-component

import FileUpload from 'vue-upload-component'

export default {
    components: {
        FileUpload,
    },

    data() {
        return {
            files: [],
            accept: 'image/png,image/gif,image/jpeg,image/webp,image/svg+xml,video/mp4,audio/mpeg,application/pdf,application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            accept_types: this.$parent.config.accept,
            categories: this.$parent.config.categories,
            multiple: true,
            directory: false,
            drop: true,
            dropDirectory: false,
            addIndex: false,
            thread: 1,
            name: 'file',
            postAction: '/upload/post',
            headers: {
                'X-Csrf-Token': 'xxxx',
            },
            data: {
                '_csrf_token': 'xxxxxx',
            },
            uploadAuto: false,
            upload: {
                done: 0,
                total: 0
            }
        }
    },
    methods: {
        hideUploader() {
            this.$parent.popup = null;
            this.$parent.show_uploader = false;
            this.clearUpload();
        },

        formatSize(bytes) {
            // https://stackoverflow.com/a/14919494
            let si = true;
            let thresh = si ? 1000 : 1024;
            if (Math.abs(bytes) < thresh) {
                return bytes + ' B';
            }
            let units = si
                ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
                : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
            let u = -1;
            do {
                bytes /= thresh;
                ++u;
            } while (Math.abs(bytes) >= thresh && u < units.length - 1);
            return bytes.toFixed(0) + ' ' + units[u];
        },

        inputFilter(newFile, oldFile, prevent) {
            if (newFile && !oldFile) {
                if (/(\/|^)(Thumbs\.db|desktop\.ini|\..+)$/.test(newFile.name)) {
                    return prevent()
                }

                if (/\.(php5?|html?|jsx?)$/i.test(newFile.name)) {
                    return prevent()
                }
            }

            if (newFile && (!oldFile || newFile.file !== oldFile.file)) {
                // Create a blob field
                newFile.blob = ''
                let URL = window.URL || window.webkitURL
                if (URL && URL.createObjectURL) {
                    newFile.blob = URL.createObjectURL(newFile.file)
                }

                // Thumbnails
                newFile.thumb = ''
                if (newFile.blob && newFile.type.substr(0, 6) === 'image/') {
                    newFile.thumb = newFile.blob
                }

                // Category
                newFile.category_id = null;
            }
        },

        onAddData() {
            this.addData.show = false
            if (!this.$refs.upload.features.html5) {
                this.alert('Your browser does not support html5');
                return;
            }

            let file = new window.File([this.addData.content], this.addData.name, {
                type: this.addData.type
            })
            this.$refs.upload.add(file);
        },

        clearUpload(length = 0) {
            this.upload = {total: length, done: 0}
        },

        startUpload() {
            if (!this.files.length) return;
            this.$parent.loading = true;
            this.clearUpload(this.files.length);

            this.uploadFile(0);

            document.getElementById('nml_upload').value = null;
        },

        uploadFile(i) {
            let file = this.files[i];
            if (!file) return this.uploadCheck();

            let config = {headers: {'Content-Type': 'multipart/form-data'}};
            let data = new FormData();
            data.append('file', file.file);

            if (file.category_id) {
                data.append('category_id', file.category_id);
            }

            let folder = this.$parent.filter.folder;
            if (folder) {
                data.append('folder', folder);
            }

            Nova.request().post('/nova-vendor/nova-media-library/upload', data, config).then(r => {
                this.upload.done++;
                this.$toasted.show(this.upload.done + ' / ' + this.upload.total, {type: 'info', duration: 500});
                this.uploadFile(i + 1);
                if (r.data.message) this.$toasted.show(r.data.message, {type: 'success'});
            }).catch(e => {
                this.uploadFile(i + 1);
                window.nmlToastHook(e);
            });
        },

        uploadCheck() {
            this.$parent.loading = false;
            this.$toasted.show(this.__('Uploaded') + ': ' + this.upload.done + '/' + this.upload.total, {type: 'success'});
            this.$parent.clearData();
            return this.$parent.get();
        },
    },

    mounted() {
        document.body.classList.add('overflow-hidden');
    },

    beforeDestroy() {
        document.body.classList.remove('overflow-hidden');
    },

    computed: {
        extensions() {
            return this.accept_types.join(',').replace(/\./g, '');
        }
    }
}