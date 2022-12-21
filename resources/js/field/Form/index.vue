<template>
    <DefaultField @click="display()" :field="currentField" :errors="errors" :show-help-text="showHelpText" :fullWidthContent="true">
        <template v-slot:field>
            <div class="nml-field-form">
                <span
                    class="dim text-primary inline-block cursor-pointer pt-2 font-bold leading-tight"
                    v-if="isHidden && field.value"
                    @click="isHidden = false"
                    >{{ __('Show') }}</span
                >

                <template v-else
                    >
                    <nmlCallback v-if="field.nmlJsCallback" :field="currentField" />
                    <nmlTrix v-else-if="field.nmlTrix" :field="currentField" />
                    <nmlArray v-else-if="field.nmlArray" :field="currentField" :handler="handleChange" />
                    <nmlFile v-else :field="currentField" :handler="handleChange" />
                </template>
            </div>
        </template>
    </DefaultField>
</template>

<script src="./script.js"></script>
