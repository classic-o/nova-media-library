<template>
  <div>

    <draggable :class="'flex flex-wrap nml-display-'+type"
               v-if="array && array.length"
               v-model="array"
               @end="changeArray(array)"
               :disabled="!isForm">
      <div class="nml-item relative mb-2 cursor-pointer" v-for="(item,i) in array" :key="'nml'+i">

        <div
          :class="'icon rounded-lg shadow-md nml-icon-'+mime(item)"
          :style="bg(item)"
        />

        <div class="title truncate" v-text="item.title || item.name" />

        <a :href="item.url" target="_blank" class="absolute pin"></a>

        <svg v-if="isForm" class="delete shadow-md dim" @click="remove(i)"><icon-delete/></svg>

      </div>
      <template #item>
        <div></div>
      </template>
    </draggable> -->

    <div class="card border border-lg border-50 max-w-xs p-8 text-center cursor-pointer max-w-xs"
         v-else-if="isForm"
         @click="popup = true">
      {{ __('Select Files') }}
    </div>


    <div class="mt-4" v-if="isForm && array && array.length">
      <a class="cursor-pointer dim inline-block text-primary font-bold" @click="popup = true">
        {{ __('Media Library') }}
      </a>

      <a class="cursor-pointer dim inline-block text-danger font-bold ml-8" @click="changeArray([])">
        {{ __('Clear') }}
      </a>
    </div>


    <transition name="fade" mode="out-in">
      <Library v-if="popup" isArray :field="field" />
    </transition>


  </div>
</template>

<script src="./script.js"></script>
