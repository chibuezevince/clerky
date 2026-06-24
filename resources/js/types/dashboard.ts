import { Component } from 'vue'

export type Clerking = {
    id: number
    case_number: string
    status: 'draft' | 'complete' | 'in_progress'
    created_at: string
    completed_at: string | null
    unit: ClerkingUnit
    is_processing: boolean
    question_history: number[]
    template: { name: string }
    complaint_template: { name: string } | null
    summary: Summary | null
    session_id: string
    current_section_id: number
    patient: null | Patient
    elapsed_seconds: number
    started_at: string
    current_question_index: number
    all_questions: SectionQuestions
    summary_count: number
    current_section: ClerkingSection
    presenting_complaint_response?: {
        answer: { key: string }[] | null
    }
}

export type ClerkingUnit = {
    id: number
    name: string
    slug: string
    description: string
    is_active: boolean
    icon: string | Component | null
}

export type InputType =
    | 'text'
    | 'select'
    | 'scale'
    | 'textarea'
    | 'boolean'
    | 'multi_select'
    | 'number'
    | 'key_value'

export type UnitQuestion = {
    id: number
    clerking_section_id: number
    question: string
    field_key: string
    input_type: InputType
    options: Record<string, string> | null
    depends_on_section_question_id: number | null
    depends_on_complaint_question_id: number | null
    depends_on_answer: string | null
    sex: 'male' | 'female' | 'both'
    minimum_age: number | null
    maximum_age: number | null
    max_char: number
    created_at: string
    order: number
    updated_at: string
    patient_info: string
    complaint_template_id: undefined | number
    sort_index: number
    pivot:
        | undefined
        | {
              clerking_template_id: number
              section_question_id: number
              order: number
              is_required: boolean
          }

    section: {
        id: number
        name: string
    }
}

export type ClerkingSection = {
    id: number
    name: string
    slug: string
    description: string
    is_active: number
    complaint_question_position: 'after' | 'before'
    pivot: {
        order: number
    }
    questions_count: number
    created_at: string
    updated_at: string
}

export type Summary = {
    id: number
    content: string | null
    edited_content: string | null
    is_confirmed: boolean
    model_used: string | null
    generated_at: string | null
    created_at: string
    updated_at: string
    preview: string
    unit: {
        name: string
    }
    session_id: string
    case_number: string
    unit_name: string
}

export type Patient = {
    name: string | null
    age: number
    sex: 'male' | 'female' | null
    occupation: string | null
    address: string | null
    phone: string | null
    religion: string | null
    ethnicity: string | null
    marital_status:
        | 'single'
        | 'married'
        | 'divorced'
        | 'widowed'
        | 'other'
        | null
}

export type Answer = {
    value: string
    clerking_section_id: number
    section_question_id?: number
    complaint_question_id?: number
}

export type SectionQuestions = Record<string, UnitQuestion[]>

export type NavItem = {
    id: string
    label: string
    icon: string
    url: any
    children?: NavItem[]
}

export type AppNotification = {
    id: string
    title: string
    message: string
    data: Record<string, string> | null
    read_at: string | null
    created_at: string
}

export type ComplaintTemplate = {
    id: number
    name: string
    slug: string
    is_verified: boolean
}
