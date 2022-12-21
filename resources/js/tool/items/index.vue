<template>
  <div :class="'flex flex-wrap nml-display-'+$parent.config.display">
    

    <template v-if="'folders' === this.$parent.config.store">
      <template v-if="'/' !== folder">
        <div class="pl-3 text-white rounded p-1 mx-1 mb-4 w-full break-words bg-green-600 text-white font-bold" style="font-family:monospace">{{ folder }}</div>
        <Folders :key="folder" type="back" />
        <Folders :key="folder" v-if="!$parent.items.array.length && !getFolders.length" type="remove" />
      </template>
      <Folders type="create" />
      <Folders v-for="item in getFolders" :key="item" type="folder" :label="item" />
    </template>


    <template v-if="$parent.items.array.length">
      <div
        v-for="item in $parent.items.array"
        :key="item.id"
        @click="clickItem(item)"
        :class="['nml-item relative mb-2 cursor-pointer', ($parent.bulk.enable && $parent.bulk.ids[item.id]) ? checked:'' ]"
        :title="item.title || item.name"
      >
        <div
          :class="'icon rounded-lg shadow-md nml-icon-'+mime(item)"
          :style="bg(item)"
        />

        <div class="title truncate" v-text="item.title || item.name" />

      </div>
    </template>


    <div class="w-full text-center p-4" v-else>
      {{ $parent.loading ? 'Loading...' : __('No files found') }}
    </div>


  </div>
</template>

<script src="./script.js"></script>
