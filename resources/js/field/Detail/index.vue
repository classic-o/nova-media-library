<template>
  <div class="flex border-b border-40">

    <slot>
      <div class="w-1/4 py-4 font-normal text-80">
        {{ field.indexName }}
      </div>
    </slot>

    <slot name="value">
      <div class="nml-field-form w-3/4 py-4">

        <template v-if="!field.value">—</template>

        <span class="cursor-pointer dim text-primary font-bold"
              v-else-if="isHidden"
              @click="isHidden = false"
        >{{ __('Show') }}</span>

        <template v-else-if="!field.nmlJsCallback && !field.nmlTrix">
          <nmlArray v-if="field.nmlArray" :field="field" />
          <nmlFile v-else :field="field" />
        </template>

      </div>
    </slot>

  </div>
</template>

<script src="./script.js"></script>
