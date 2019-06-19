<template>
  <div class="flex flex-wrap -mx-1 card-array">


    <template v-if="$parent.items.array.length">
    <div
      class="item px-1 mb-2 w-1/6 cursor-pointer"
      v-for="item in $parent.items.array"
      :key="item.id"
    >

      <div
        class="card shadow-md"
        :class="`nml-icon-`+mime(item)"
        :style="bg(item)"
        :title="item.description"
        @click="clickCard(item)"
      >
        <checkbox v-if="$parent.bulk.is" :checked="$parent.bulk.array.includes(item.id)"></checkbox>
      </div>

    </div>
    </template>


    <div class="w-full text-center p-4" v-else>
      {{ $parent.loading ? __("nml_loading") : __("nml_no_files_found") }}
    </div>


  </div>
</template>

<script src="./items.js"></script>
