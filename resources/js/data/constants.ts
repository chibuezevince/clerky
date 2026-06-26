import { InputType } from '@/types/dashboard'
import { SelectOption } from '@/types/forms'
import { Baby, HeartPulse, Scissors, Stethoscope, Venus } from '@lucide/vue'
import { Component } from 'vue'

export const iconMap: Record<string, Component> = {
    Baby,
    Venus,
    Stethoscope,
    Scissors,
    HeartPulse,
}

export const inputTypeLabels: Record<string, string> = {
    text: 'Text',
    textarea: 'Textarea',
    select: 'Select',
    boolean: 'Yes / No',
    scale: 'Scale',
    multi_select: 'Multi Select',
    number: 'Number',
    key_value: 'Key / Value',
}

export const STORAGE_KEY = 'clerky:generating-ids'

export const statusConfig: Record<string, { label: string; classes: string }> =
    {
        draft: {
            label: 'Draft',
            classes: 'bg-white/10 text-white/60',
        },
        in_progress: {
            label: 'In Progress',
            classes: 'bg-brand-yellow/10 text-brand-yellow',
        },
        complete: {
            label: 'Complete',
            classes: 'bg-teal-400/10 text-teal-300',
        },
    }

export const noQuestionError =
    "We couldn't generate follow-up questions for that presenting complaint. Try rephrasing or entering a different one."

export const INPUT_TYPES: { label: string; value: InputType }[] = [
    { label: 'Text', value: 'text' },
    { label: 'Textarea', value: 'textarea' },
    { label: 'Select', value: 'select' },
    { label: 'Yes / No', value: 'boolean' },
    { label: 'Scale', value: 'scale' },
    { label: 'Multi Select', value: 'multi_select' },
    { label: 'Number', value: 'number' },
]

export const sexOptions: SelectOption[] = [
    {
        label: 'Male',
        value: 'male',
    },
    {
        label: 'Female',
        value: 'female',
    },
    {
        label: 'Both',
        value: 'both',
    },
]
