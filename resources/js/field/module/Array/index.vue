<template>
  <div>
    <draggable
      :class="'flex flex-wrap nml-display-' + type"
      v-if="array && array.length"
      item-key="id"
      v-model="array"
      @end="changeArray(array)"
      
    >
      <template #item="{ element }">
        <div class="w-32">
          <div class="title truncate text-center" v-text="element.title || element.name" />
          <img class="w-32 h-40" :src="element.url" />
          <!-- <button v-if="isForm" @click="remove(i)">Remove</button> -->
        </div>
      </template>
    </draggable>

    <div
      class="card border border-lg border-50 max-w-xs p-8 text-center cursor-pointer max-w-xs"
      v-else-if="isForm"
      @click="popup = true"
    >
      {{ __("Select Files") }}
    </div>

    <div class="mt-4" v-if="isForm && array && array.length">
      <a
        class="cursor-pointer dim inline-block text-primary font-bold"
        @click="popup = true"
      >
        {{ __("Media Library") }}
      </a>

      <a
        class="cursor-pointer dim inline-block text-danger font-bold ml-8"
        @click="changeArray([])"
      >
        {{ __("Clear") }}
      </a>
    </div>

    <transition name="fade" mode="out-in">
      <Library v-if="popup" isArray :field="field" />
    </transition>
  </div>
</template>

<script src="./script.js"></script>
