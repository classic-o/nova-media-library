<template>
    <div class="border-40 flex items-center border-b" :class="elementSize">
        <slot>
            <div class="text-80 w-1/4 py-4 font-normal">
                {{ field.indexName }}
            </div>
        </slot>

        <slot name="value">
            <div class="nml-field-form w-3/4 py-4">
                <template v-if="!field.value">—</template>

                <span class="dim text-primary cursor-pointer font-bold" v-else-if="isHidden" @click="isHidden = false">{{
                    __('Show')
                }}</span>

                <template v-else-if="!field.nmlJsCallback && !field.nmlTrix">
                    <nmlArray v-if="field.nmlArray" :field="field" />
                    <nmlFile v-else :field="field" />
                </template>
            </div>
        </slot>
    </div>
</template>

<script src="./script.js"></script>
