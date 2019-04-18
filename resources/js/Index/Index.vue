<template>
  <div id="nml-tool">
    <heading class="mb-6">Media Library</heading>

    <!-- Delete and upload files -->

    <div class="flex mb-6">

      <checkbox-with-label class="mr-6" @change="changeBulk" :checked="bulk.is" v-if="tool || gallery">Bulk select</checkbox-with-label>

      <button type="button" v-if="bulk.is" @click="bulkAll"
              class="form-file-btn btn-default btn-primary cursor-pointer shadow-md mr-6">
        Select all
      </button>

      <button type="button" v-if="bulk.is && bulk.array.length" @click="deleteFiles(bulk.array)"
              class="form-file-btn btn-default btn-danger cursor-pointer shadow-md">
        Delete selected: {{ bulk.array.length }}
      </button>

      <button type="button" v-if="!tool && bulk.is && bulk.array.length" @click="pushFiles"
              class="form-file-btn btn-default text-white bg-success cursor-pointer shadow-md ml-6">
        Add to Gallery: {{ bulk.array.length }}
      </button>

      <label class="form-file-btn btn btn-default btn-primary cursor-pointer shadow-md ml-auto">
        <input id="nml_upload" class="form-file-input" :accept="types" type="file" multiple @change="selectFiles" />
        Upload Files
      </label>
    </div>

    <!-- Search -->

    <div class="flex mb-6">
      <div class="relative mr-4">
        <icon type="search" class="absolute search-icon-center ml-3 text-70" />
        <input class="form-control form-input w-search pl-search shadow-md" type="search"
               placeholder="Search by Title" v-model="filter.title" @input="doSearch()"/>
      </div>

      <date-time-picker class="form-control form-input shadow-md mr-4" autocomplete="off" placeholder="Upload date from"
                        dateFormat="Y-m-d" :firstDayOfWeek="1" :enable-time="false" @change="val => updateDate(val, 'from')" />

      <date-time-picker class="form-control form-input shadow-md mr-4" autocomplete="off" placeholder="Upload date to"
                        dateFormat="Y-m-d" :firstDayOfWeek="1" :enable-time="false" @change="val => updateDate(val, 'to')" />

    </div>

    <!-- Items -->

    <div class="flex flex-wrap -mx-1 pb-4 card-array">
      <div class="item px-1 mb-2 w-1/6 cursor-pointer" v-for="(item, i) in data.array" :key="'nml-'+i">

        <div class="card shadow-md" @click="clickCard(item)" :style="{ backgroundImage: `url(${item.image.url})` }">
          <checkbox v-if="bulk.is" :checked="bulk.array.includes(item.id)"></checkbox>
        </div>

      </div>
    </div>

    <!-- Loader -->

    <template v-if="!loading && !data.full">
      <button type="button" @click="loader" class="form-file-btn btn-default btn-primary cursor-pointer mt-8 m-auto block shadow-md">
        {{ tool ? 'Click if auto load is not working' : 'Load More' }}
      </button>
      <div v-if="tool" class="help-text help-text pb-4 mt-2 m-auto block text-center">
        Scroll down to automatically download files or click the button above
      </div>
    </template>

    <div v-if="loading" class="fixed pin z-30 bg-white-50%">
      <loading-view/>
    </div>

    <!-- Popup for single info -->

    <transition name="fade">
      <Popup v-if="popup" />
    </transition>

  </div>
</template>

<script src="./script.js"></script>
