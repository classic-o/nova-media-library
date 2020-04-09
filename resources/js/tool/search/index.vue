<template>
  <div class="flex flex-wrap mb-6 select-none">

    <div class="relative mr-4 max-w-full">
      <icon type="search" class="absolute search-icon-center ml-3 text-70" />
      <input
        class="form-control form-input w-search pl-search shadow-md w-full"
        type="search"
        :placeholder="__('Search by name')"
        v-model="$parent.filter.title"
        @input="$parent.doSearch()"
      />
    </div>

    <date-time-picker
      class="form-control form-input shadow-md max-w-full mr-4"
      autocomplete="off"
      :placeholder="__('Upload From')"
      dateFormat="Y-m-d"
      :firstDayOfWeek="1"
      :enable-time="false"
      @change="val => updateDate(val, 'from')"
    />

    <date-time-picker
      class="form-control form-input shadow-md max-w-full"
      autocomplete="off"
      :placeholder="__('Upload To')"
      dateFormat="Y-m-d"
      :firstDayOfWeek="1"
      :enable-time="false"
      @change="val => updateDate(val, 'to')"
    />

    <div :title="__('Change Display Type')" class="nml-display bg-white shadow-md rounded-lg cursor-pointer ml-auto mr-4 active:shadow-outline">
      <svg v-if="'gallery' === $parent.config.display" @click="display('list')" style="padding:8px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
        <path d="M3 1h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2H3c-1.1045695 0-2-.8954305-2-2V3c0-1.1045695.8954305-2 2-2zm0 2v4h4V3H3zm10-2h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2h-4c-1.1045695 0-2-.8954305-2-2V3c0-1.1045695.8954305-2 2-2zm0 2v4h4V3h-4zM3 11h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2H3c-1.1045695 0-2-.8954305-2-2v-4c0-1.1045695.8954305-2 2-2zm0 2v4h4v-4H3zm10-2h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2h-4c-1.1045695 0-2-.8954305-2-2v-4c0-1.1045695.8954305-2 2-2zm0 2v4h4v-4h-4z"></path>
      </svg>
      <svg v-else @click="display('gallery')" style="padding:6px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm14 8V5H5v6h14zm0 2H5v6h14v-6zM8 9a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm0 8a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
      </svg>
    </div>

    <select class="shadow-md block border-0 cursor-pointer form-control form-select mr-4"
            v-if="types.length > 1"
            v-model="$parent.filter.type"
            @change="$parent.doSearch()">
      <option :value="all">{{ __("All Types") }}</option>
      <option
        v-for="key in types"
        :value="[key]"
        :key="key">
        {{ key }}
      </option>
    </select>


    <select
            class="shadow-md block border-0 cursor-pointer form-control form-select"
            v-model="$parent.filter.category"
            @change="$parent.doSearch()"
    >
      <option :value="null">{{ __("All Categories") }}</option>
      <option
              v-for="key in categories"
              :value="key.id"
              :key="key.id">
        {{ key.title }}
      </option>
    </select>
  </div>
</template>

<script src="./script.js"></script>
