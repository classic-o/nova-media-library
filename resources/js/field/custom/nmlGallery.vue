<template>
  <div>

    <draggable v-if="array.length" class="flex flex-wrap -mx-1 -mb-2" v-model="array" @end="changeGallery(array)" :disabled="!isForm">
      <div class="px-1 mb-2 w-1/6" v-for="(item,i) in array" :key="'nml'+i">
          <div class="gal card block shadow-md" :style="{ backgroundImage: `url(${item})` }">

            <svg v-if="isForm" class="del dim" @click="remove(item)"><icon-delete/></svg>

            <a v-else :href="item" target="_blank" class="absolute pin"></a>

          </div>
      </div>
    </draggable>

    <div v-else class="card border border-lg border-50 max-w-xs p-8 text-center">
      No images selected
    </div>



    <div class="mt-4" v-if="isForm">
      <a class="cursor-pointer dim inline-block text-primary font-bold" @click="popup = true">
        Open Library
      </a>

      <a class="cursor-pointer dim inline-block text-danger font-bold ml-8" @click="changeGallery([])" v-if="array.length">
        Clear
      </a>

      <transition name="fade">
        <Library v-if="popup" :gallery="true" :field="field.attribute" />
      </transition>
    </div>

  </div>
</template>

<script src="./nmlGallery.js"></script>
