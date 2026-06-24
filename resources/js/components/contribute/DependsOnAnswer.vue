<script setup lang="ts">
import { UnitQuestion } from '@/types/dashboard'
import { computed } from 'vue'
import Textarea from '../form/Textarea.vue'
import Select from '../form/Select.vue'
import Input from '../form/Input.vue'
import Checkbox from '../form/Checkbox.vue'
import MultiSelect from '../form/MultiSelect.vue'
import NumberInput from '../form/NumberInput.vue'
import KeyValueInput from '../form/KeyValueInput.vue'

const props = defineProps<{
    question: UnitQuestion
}>()

const model = defineModel<string>()

const booleanModel = computed({
    get: () => {
        if (model.value === 'Yes') return true
        if (model.value === 'No') return false
        return null
    },
    set: (val) => {
        if (val === true) model.value = 'Yes'
        else if (val === false) model.value = 'No'
        else model.value = ''
    },
})

const componentMap = {
    text: Textarea,
    select: Select,
    scale: Input,
    textarea: Textarea,
    boolean: Checkbox,
    multi_select: MultiSelect,
    number: NumberInput,
    key_value: KeyValueInput,
}

const activeComponent = computed(() => componentMap[props.question.input_type])

const selectOptions = computed(() =>
    props.question.options
        ? Object.entries(props.question.options).map(([value, label]) => ({
              value,
              label,
          }))
        : [],
)
</script>

<template>
    <div class="scale-[0.85] origin-top-left w-[118%]">
        <Checkbox
            v-if="question.input_type === 'boolean'"
            v-model="booleanModel"
            label="Show when answer is"
        />
        <component
            v-else
            :is="activeComponent"
            v-model="model"
            label="Show when answer is"
            :options="selectOptions"
        />
    </div>
</template>
