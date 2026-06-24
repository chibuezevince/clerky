<script setup lang="ts">
import { onMounted } from 'vue'

const props = defineProps<{ status: 'success' | 'error'; error?: string }>()

onMounted(() => {
    if (window.opener) {
        window.opener.postMessage(
            props.status === 'success'
                ? { type: 'oauth_success' }
                : { type: 'oauth_error', error: props.error },
            window.location.origin,
        )
        window.close()
    }
})
</script>

<template>
    <div />
</template>
