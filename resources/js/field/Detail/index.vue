<template>
    <div class="flex border-b border-40">
        <slot>
            <div class="w-1/4 py-4 font-normal text-80">
                {{ field.indexName }}
            </div>
        </slot>
        <slot name="value">
            <div class="nml-field-form w-3/4 py-4">

                <span class="cursor-pointer dim text-primary font-bold"
                      v-if="isHidden" @click="isHidden = false"
                >{{ __("nml_show") }}</span>

                <template v-else-if="field.value">
                    <nmlList v-if="field.listing" :field="field" />
                    <nmlFile v-else :field="field" />
                </template>

                <template v-else>—</template>

            </div>
        </slot>
    </div>
</template>

<script src="./script.js"></script>
