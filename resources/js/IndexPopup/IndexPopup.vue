<template>
  <div class="popup fixed pin z-20 py-view bg-primary-70% overflow-y-auto">
    <div class="bg-white p-8 rounded-lg shadow-lg m-auto">

      <button type="button" class="close select-none" @click="$parent.popup = null">&times;</button>

      <div class="flex border-b border-40">
        <div class="w-1/4 py-4 font-bold text-80">ID</div>
        <div class="w-3/4 py-4">{{ $parent.popup.id }}</div>
      </div>

      <div class="flex border-b border-40">
        <div class="w-1/4 py-4 font-bold text-80">Created</div>
        <div class="w-3/4 py-4">{{ $parent.popup.created }}</div>
      </div>

      <div class="flex border-b border-40">
        <div class="w-1/4 py-4 font-bold text-80">Type</div>
        <div class="w-3/4 py-4">{{ $parent.popup.type }}</div>
      </div>

      <div class="flex border-b border-40">
        <div class="w-1/4 py-4 font-bold text-80">Size</div>
        <div class="w-3/4 py-4">{{ $parent.popup.size }}</div>
      </div>

      <div class="flex border-b border-40">
        <div class="w-1/4 py-4 font-bold text-80 leading-loose">Title</div>
        <div class="w-3/4 py-4">
          <input class="form-control form-input w-full shadow-md" v-model="$parent.popup.title">
        </div>
      </div>

      <div class="flex border-b border-40">
        <div class="w-1/4 py-4 font-bold text-80">URL</div>
        <div class="w-3/4 py-4">
          <a class="text-primary font-bold no-underline" :href="$parent.popup.url" target="_blank">Open</a>
          <button type="button" class="text-80 font-bold no-underline float-right"
                  v-copy="$parent.popup.url" v-copy:callback="onCopy">Copy</button>
        </div>
      </div>

      <div class="flex mt-6">
        <button type="button" class="btn-default btn-primary cursor-pointer mr-6 shadow-md" @click="update">
          Update Title
        </button>
        <button type="button" class="btn-default btn-danger cursor-pointer ml-auto shadow-md"
                @click="$parent.deleteFiles([$parent.popup.id])">
          Delete File
        </button>
      </div>

    </div>
  </div>
</template>

<script src="./script.js"></script>
