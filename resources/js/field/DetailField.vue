<template>
    <div class="flex border-b border-40">
        <slot>
            <div class="w-1/4 py-4 font-normal text-80">
                {{ field.indexName }}
            </div>
        </slot>
        <slot name="value">
            <div id="nml-field" class="w-3/4 py-4">

                <span v-if="isHidden" @click="isHidden = 0" class="cursor-pointer dim text-primary font-bold">Show</span>

                <template v-else-if="field.value">
                    <NmlGallery v-if="field.isGallery" :field="field" />
                    <NmlImage v-else :field="field" />
                </template>

                <template v-else>—</template>

            </div>
        </slot>
    </div>
</template>

<script src="./Detail.js"></script>
