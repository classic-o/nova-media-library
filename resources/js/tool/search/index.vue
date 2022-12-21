<template>
  <div class="flex flex-wrap mb-6 select-none">

    <div class="relative mr-4 max-w-full">
      <icon type="search" class="absolute left-0 search-icon-center mr-3 mt-1.5 text-70 ml-2" />
      <input
        class="form-control form-input w-search pl-search shadow-md w-full pl-10"
        type="search"
        :placeholder="__('Search by name')"
        v-model="$parent.filter.title"
        @input="$parent.doSearch()"
      />
    </div>

    <datepicker
      class="form-control shadow-md max-w-full mr-4 text-center"
      :placeholder="uploadFromText"
      ref="uploadFrom"
      v-model="uploadFrom"
      inputFormat= "yyyy-MM-dd"
      :weekStartsOn= "1"
      @change="updateDate(this.$refs.uploadFrom.input, 'from')"
    />

    <datepicker
      class="form-control shadow-md max-w-full text-center"
      :placeholder="uploadToText"
      ref="uploadTo"
      v-model="uploadTo"
      inputFormat= "yyyy-MM-dd"
      :weekStartsOn= "1"
      @change="updateDate(this.$refs.uploadTo.input, 'to')"
    />

    <div :title="__('Change Display Type')" class="nml-display bg-white shadow-md rounded-lg cursor-pointer ml-auto mr-4 active:shadow-outline">
      <svg v-if="'gallery' === $parent.config.display" @click="display('list')" style="padding:8px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
        <path d="M3 1h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2H3c-1.1045695 0-2-.8954305-2-2V3c0-1.1045695.8954305-2 2-2zm0 2v4h4V3H3zm10-2h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2h-4c-1.1045695 0-2-.8954305-2-2V3c0-1.1045695.8954305-2 2-2zm0 2v4h4V3h-4zM3 11h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2H3c-1.1045695 0-2-.8954305-2-2v-4c0-1.1045695.8954305-2 2-2zm0 2v4h4v-4H3zm10-2h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2h-4c-1.1045695 0-2-.8954305-2-2v-4c0-1.1045695.8954305-2 2-2zm0 2v4h4v-4h-4z"></path>
      </svg>
      <svg v-else @click="display('gallery')" style="padding:6px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm14 8V5H5v6h14zm0 2H5v6h14v-6zM8 9a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm0 8a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
      </svg>
    </div>

    <select class="shadow-md block border-0 cursor-pointer form-control form-select"
            v-if="types.length > 1"
            v-model="$parent.filter.type"
            @change="$parent.doSearch()">
      <option :value="all">{{ __('All Types') }}</option>
      <option
        v-for="key in types"
        :value="[key]"
        :key="key">
        {{ __(key) }}
      </option>
    </select>



  </div>
</template>

<script src="./script.js"></script>
