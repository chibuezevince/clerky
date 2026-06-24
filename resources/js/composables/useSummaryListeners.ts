import { router } from '@inertiajs/vue3'
import { useEcho } from '@laravel/echo-vue'
import { toast } from 'vue-sonner'
import { watch, type Ref } from 'vue'
import type { Summary } from '@/types/dashboard'
import { loadGeneratingIds } from '@/data/functions'
import { STORAGE_KEY } from '@/data/constants'

export const useSummaryListeners = (
    pendingSummaries: Ref<Summary[]>,
    recentSummaries: Ref<Summary[]>,
) => {
    const listenedSessionIds = new Set<string>()

    watch(
        [pendingSummaries, recentSummaries],
        ([pending, recent]) => {
            if (typeof window === 'undefined') return

            const all = [...(pending ?? []), ...(recent ?? [])]

            all.forEach((summary) => {
                if (!listenedSessionIds.has(summary.session_id)) {
                    listenedSessionIds.add(summary.session_id)

                    useEcho(
                        `clerking.${summary.session_id}`,
                        '.summary.ready',
                        ({ clerking, generated }) => {
                            console.log(clerking, generated)
                            if (!generated) {
                                const generatingIds = loadGeneratingIds()
                                generatingIds.delete(clerking.id)
                                sessionStorage.setItem(STORAGE_KEY, JSON.stringify(generatingIds))
                                window.dispatchEvent(new CustomEvent('session-storage'))
                                toast.error(
                                    `Summary for ${summary.case_number} could not be generated. Please, try again later.`,
                                )
                                return
                            }

                            toast.success(
                                `Summary updated for ${summary.case_number}`,
                            )
                            router.reload({
                                only: ['pendingSummaries', 'recentSummaries'],
                            })
                        },
                    )
                }
            })
        },
        { immediate: true },
    )
}
