import type {
    Answer,
    ClerkingSection,
    SectionQuestions,
    UnitQuestion,
} from '@/types/dashboard'
import type { ComputedRef, Ref } from 'vue'

import { HPCslug } from '@/data/dashboard'
import { STORAGE_KEY } from './constants'

export const getNextSection = (
    sections: ClerkingSection[],
    currentSection: ClerkingSection,
) =>
    sections.find(
        (section) => section.pivot.order > currentSection.pivot.order,
    ) as ClerkingSection

export const getPreviousSection = (
    sections: ClerkingSection[],
    currentSection: ClerkingSection,
) =>
    sections.findLast(
        (section) => section.pivot.order < currentSection.pivot.order,
    ) as ClerkingSection

export const recordAnswerLocally = (
    answer: string | string[],
    answers: Ref<Answer[]>,
    currentSection: Ref<ClerkingSection>,
    answeredQuestion: ComputedRef<UnitQuestion>,
): Record<string, unknown> => {
    answers.value = answers.value.filter((a) => {
        return !(
            a.clerking_section_id === currentSection.value.id &&
            (a.section_question_id === answeredQuestion.value.id ||
                a.complaint_question_id === answeredQuestion.value.id)
        )
    })

    const isComplaintQuestion =
        answeredQuestion.value.complaint_template_id !== undefined

    answers.value.push({
        value: Array.isArray(answer) ? answer.join(', ') : answer,
        clerking_section_id: currentSection.value.id,
        section_question_id: isComplaintQuestion
            ? null
            : answeredQuestion.value.id,
        complaint_question_id: isComplaintQuestion
            ? answeredQuestion.value.id
            : null,
    } as Answer)

    return {
        answer,
        question_id: answeredQuestion.value.id,
        is_complaint_question: isComplaintQuestion,
        clerking_section_id: currentSection.value.id,
    }
}

export const transitionToSection = (
    nextSection: ClerkingSection,
    sectionQuestions: UnitQuestion[],
    refs: {
        direction: Ref<number>
        currentQuestionIndex: Ref<number>
        questions: Ref<UnitQuestion[]>
        currentSection: Ref<ClerkingSection>
        nextSectionQuestions: Ref<UnitQuestion[]>
        previousSectionQuestions: Ref<UnitQuestion[]>
        allQuestions: Ref<SectionQuestions>
        processing: Ref<boolean>
    },
) => {
    const {
        direction,
        currentQuestionIndex,
        questions,
        currentSection,
        allQuestions,
        processing,
    } = refs

    direction.value = 1
    currentQuestionIndex.value = 1
    questions.value = sectionQuestions
    currentSection.value = nextSection

    if (nextSection.slug === HPCslug) {
        processing.value = true
        allQuestions.value = {}
    }
}

export const ucfirst = (text: string) =>
    `${text.charAt(0).toUpperCase()}${text.slice(1)}`

export const loadGeneratingIds = (): Set<number> => {
    try {
        const stored = sessionStorage.getItem(STORAGE_KEY)
        return stored ? new Set(JSON.parse(stored)) : new Set()
    } catch {
        return new Set()
    }
}
