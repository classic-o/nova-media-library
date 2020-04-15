<template>
    <div class="popup fixed pin z-20 py-view bg-primary-70% overflow-y-auto">
        <div class="relative z-30 bg-white p-8 rounded-lg shadow-lg m-auto w-1/2">

            <button type="button" class="nml-close select-none" @click="hideUploader">&times;</button>

            <div class="upload-full">
                <h1 class="upload-title mb-4">{{ __('Upload') }}</h1>

                <div v-show="$refs.upload && $refs.upload.dropActive" class="drop-active">
                    <h3>{{ __('Drop files to upload') }}</h3>
                </div>
                <div class="upload">
                    <div class="table-responsive">
                        <table class="table w-full">
                            <thead v-if="files.length > 0">
                            <tr>
                                <th>{{ __('Preview') }}</th>
                                <th>{{ __('Details') }}</th>
                                <th>{{ __('Size') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="!files.length">
                                <td colspan="7">
                                    <div class="text-center p-5 py-4">
                                        <h4>{{ __('Drop files to upload') }}</h4>
                                        <div class="my-4">{{ __('or') }}</div>
                                        <label :for="name"
                                               class="btn btn-default btn-primary inline-flex items-center relative"
                                        >
                                            {{ __('Select Files') }}
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr v-for="(file, index) in files" :key="file.id">
                                <td>
                                    <img v-if="file.thumb" :src="file.thumb" width="80" height="auto"/>
                                    <span v-else>No Image</span>
                                </td>
                                <td>
                                    <div class="filename mt-4">
                                        {{file.name}}
                                    </div>
                                    <div class="category">
                                        <select v-model="file.category_id"
                                                class="shadow-md block border-0 cursor-pointer form-control form-select my-4">
                                            <option :value="null">{{ __('Choose category') }}</option>
                                            <option :value="category.id" v-for="category in categories"
                                                    :key="category.id">
                                                {{ category.title }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="progress" v-if="file.active || file.progress !== '0.00'">
                                        <div :class="{'progress-bar': true, 'progress-bar-striped': true, 'bg-danger': file.error, 'progress-bar-animated': file.active}"
                                             role="progressbar" :style="{width: file.progress + '%'}">{{file.progress}}%
                                        </div>
                                    </div>
                                </td>
                                <td>{{ formatSize(file.size) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <file-upload
                            style="display: none"
                            id="nml_upload"
                            :extensions="extensions"
                            :multiple="true"
                            :size="0"
                            :thread="1"
                            :drop-directory="false"
                            :dropActive="true"
                            :add-index="false"
                            :accept="accept"
                            :directory="false"
                            :drop="true"
                            v-model="files"
                            @input-filter="inputFilter"
                            ref="upload">
                    </file-upload>

                    <div class="upload-footer" v-if="files.length > 0">
                        <button type="button"
                                class="btn btn-default btn-primary text-center w-full"
                                v-if="!$refs.upload || !$refs.upload.active"
                                @click.prevent="startUpload"
                        >
                            <span>
                                {{ __('Upload') }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    .upload-full .btn-group .dropdown-menu {
        display: block;
        visibility: hidden;
        transition: all .2s
    }

    .upload-full .btn-group:hover > .dropdown-menu {
        visibility: visible;
    }

    .upload-full label.dropdown-item {
        margin-bottom: 0;
    }

    .upload-full .btn-group .dropdown-toggle {
        margin-right: .6rem
    }


    .upload-full .filename {
        margin-bottom: .3rem
    }

    .upload-full .btn-is-option {
        margin-top: 0.25rem;
    }

    .upload-full .upload-footer {
        padding: .5rem 0;
        border-top: 1px solid #e9ecef;
        border-bottom: 1px solid #e9ecef;
    }


    .upload-full .edit-image img {
        max-width: 100%;
    }

    .upload-full .edit-image-tool {
        margin-top: .6rem;
    }

    .upload-full .edit-image-tool .btn-group {
        margin-right: .6rem;
    }

    .upload-full .footer-status {
        padding-top: .4rem;
    }

    .upload-full .drop-active {
        top: 0;
        bottom: 0;
        right: 0;
        left: 0;
        position: fixed;
        z-index: 9999;
        opacity: .6;
        text-align: center;
        background: #000;
    }

    .upload-full .drop-active h3 {
        margin: -.5em 0 0;
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
        font-size: 40px;
        color: #fff;
        padding: 0;
    }
</style>

<script src="./script.js"></script>