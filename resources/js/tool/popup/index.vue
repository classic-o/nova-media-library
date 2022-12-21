<template>
  <div class="popup fixed top-10 left-0 right-0 pin z-20 py-view bg-primary-70% overflow-y-auto">
    <div class="absolute pin z-20" @click="$parent.$parent.item = null"></div>

    <div class="relative z-30 bg-white p-8 rounded-lg shadow-lg m-auto">

      <button type="button" class="nml-close select-none" @click="$parent.$parent.item = null">&times;</button>

      <div class="flex border-b border-40">
        <div class="w-1/4 py-4 font-bold text-80">{{ __('ID') }}</div>
        <div class="w-3/4 py-4">{{ $parent.$parent.item.id }}</div>
      </div>

      <div class="flex border-b border-40">
        <div class="w-1/4 py-4 font-bold text-80">{{ __('Uploaded') }}</div>
        <div class="w-3/4 py-4">{{ $parent.$parent.item.created }}</div>
      </div>

      <div class="flex border-b border-40">
        <div class="w-1/4 py-4 font-bold text-80">{{ __('Type') }}</div>
        <div class="w-3/4 py-4">{{ $parent.$parent.item.type }}</div>
      </div>

      <div class="flex border-b border-40">
        <div class="w-1/4 py-4 font-bold text-80">{{ __('Size') }}</div>
        <div class="w-3/4 py-4">{{ $parent.$parent.item.options.size }}</div>
      </div>

      <div class="flex border-b border-40">
        <div class="w-1/4 py-4 font-bold text-80 leading-36">{{ __('Title') }}</div>
        <div class="w-3/4 py-4">
          <input class="form-control form-input w-full shadow-md" v-model="$parent.$parent.item.title">
        </div>
      </div>

      <div class="flex border-b border-40" v-if="$parent.$parent.config.can_private">
        <div class="w-1/4 py-4 font-bold text-80">{{ __('Private') }}</div>
        <div class="w-3/4 py-4">
          <checkbox
            class="cursor-pointer"
            :checked="$parent.$parent.item.private"
            @click="onPrivate"
          />
        </div>
      </div>

      <div class="flex border-b border-40" v-if="`folders` === $parent.$parent.config.store">
        <div class="w-1/4 py-4 font-bold text-80 leading-36">{{ __('Move to folder') }}</div>
        <div class="w-3/4 py-4">
          <select v-model="folder" class="shadow-md block border-0 cursor-pointer form-control form-select w-full">
            <option :value="null"></option>
            <option
              v-for="key in folders"
              :value="key"
              :key="key">
              {{ key }}
            </option>
          </select>
        </div>
      </div>

      <div class="flex border-b border-40">
        <div class="w-1/4 py-4 font-bold text-80">{{ __('Url') }}</div>
        <div class="w-3/4 py-4">
          <a class="text-primary font-bold no-underline" :href="$parent.$parent.item.url" target="_blank">{{ __('Open') }}</a>
          <button type="button" class="text-80 font-bold no-underline float-right"
                  v-clipboard:copy="$parent.$parent.item.url" v-clipboard:success="onCopy">{{ __('Copy') }}</button>
        </div>
      </div>

      <div class="flex mt-6">
        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded btn-primary cursor-pointer mr-6 shadow-md"
                @click="update">
          {{ __('Update') }}
        </button>

        <button type="button" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded bg-success text-black cursor-pointer mr-6 shadow-md"
                @click="$parent.$parent.popup = 'crop'"
                v-if="$parent.$parent.config.front_crop && 'image' === $parent.$parent.item.options.mime">
          {{ __('Edit Image') }}
        </button>

        <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded btn-danger cursor-pointer ml-auto shadow-md"
                @click="$parent.$parent.deleteFiles([$parent.$parent.item.id])">
          {{ __('Delete') }}
        </button>

      </div>

    </div>
  </div>
</template>

<script src="./script.js"></script>
