import { sync } from '@/routes/clerking'
import { router } from '@inertiajs/vue3'
import { useDebounceFn } from '@vueuse/core'
import type { Ref } from 'vue'

import type { Clerking } from '@/types/dashboard'

export const useSyncClerking = (clerking: Ref<Clerking>) => {
    const syncClerking = useDebounceFn((payload: Record<string, any>) => {
        router.post(sync(clerking.value.session_id), payload, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: ({ props }) => {
                clerking.value = props.clerking as Clerking
            },
        })
    }, 500)

    return { syncClerking }
}
