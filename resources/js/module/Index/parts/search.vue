<template>
  <div class="flex mb-6">
    <div class="relative mr-4">
      <icon type="search" class="absolute search-icon-center ml-3 text-70" />
      <input
        class="form-control form-input w-search pl-search shadow-md"
        type="search"
        :placeholder="__('nml_search_by_description')"
        v-model="$parent.filter.description"
        @input="$parent.doSearch()"
      />
    </div>

    <date-time-picker
      class="form-control form-input shadow-md mr-4"
      autocomplete="off"
      :placeholder="__('nml_upload_from')"
      dateFormat="Y-m-d"
      :firstDayOfWeek="1"
      :enable-time="false"
      @change="val => updateDate(val, 'from')"
    />

    <date-time-picker
      class="form-control form-input shadow-md mr-4"
      autocomplete="off"
      :placeholder="__('nml_upload_to')"
      dateFormat="Y-m-d"
      :firstDayOfWeek="1"
      :enable-time="false"
      @change="val => updateDate(val, 'to')"
    />

    <select
      class="shadow-md block border-0 cursor-pointer form-control form-select ml-auto"
      v-if="$parent.config.nml_types.length > 1"
      v-model="$parent.filter.type"
      @change="$parent.doSearch()"
    >
      <option :value="null" v-if="!types.length">{{ __("nml_all_types") }}</option>
      <option
        v-for="key in $parent.config.nml_types"
        v-if="!types.length || types.indexOf(key) > -1"
        :value="key"
        :key="key">
        {{ key }}
      </option>
    </select>

  </div>
</template>


<script src="./search.js"></script>
