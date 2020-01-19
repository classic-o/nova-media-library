<template>
  <default-field :field="field" :errors="errors" :fullWidthContent="true">
    <template slot="field">
      <div class="nml-field-form">

        <span class="cursor-pointer dim inline-block text-primary font-bold pt-2 leading-tight"
              v-if="isHidden && field.value"
              @click="isHidden = false"
        >{{ __('Show') }}</span>

        <template v-else>
          <nmlCallback v-if="field.nmlJsCallback" :field="field" />
          <nmlTrix v-else-if="field.nmlTrix" :field="field" />
          <nmlArray v-else-if="field.nmlArray" :field="field" :handler="handleChange" />
          <nmlFile v-else :field="field" :handler="handleChange" />
        </template>

      </div>
    </template>
  </default-field>
</template>

<script src="./script.js"></script>
