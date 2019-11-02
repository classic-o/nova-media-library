<template>
  <div>

    <draggable v-if="array.length" class="flex flex-wrap -mx-1 -mb-2" v-model="array" @end="changeListing(array)" :disabled="!isForm">
      <div :class="type" class="gal px-1 mb-2 w-1/6" v-for="(item,i) in array" :key="'nml'+i">

          <div class="card block shadow-md nml-icon-file" :style="'line' !== type && setBg(item)" :title="item">

            <div v-if="'line' === type">{{ getName(item) }}</div>

            <svg v-if="isForm" class="delete dim" @click="remove(i)"><icon-delete/></svg>

            <a v-else :href="item" target="_blank" class="absolute pin"></a>

          </div>

      </div>
    </draggable>

    <div v-else class="card border border-lg border-50 max-w-xs p-8 text-center">
      {{ __("nml_no_files_selected") }}
    </div>



    <div class="mt-4" v-if="isForm">
      <a class="cursor-pointer dim inline-block text-primary font-bold" @click="popup = true">
        {{ __("nml_open_library") }}
      </a>

      <a class="cursor-pointer dim inline-block text-danger font-bold ml-8" @click="changeListing([])" v-if="array.length">
        {{ __("nml_clear") }}
      </a>

      <transition name="fade">
        <Library v-if="popup" :listing="true" :field="field" />
      </transition>
    </div>

  </div>
</template>

<script src="./script.js"></script>
