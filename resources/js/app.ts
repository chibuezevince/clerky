import { configureEcho } from '@laravel/echo-vue'
import { createInertiaApp } from '@inertiajs/vue3'

configureEcho({
    broadcaster: 'pusher',
})

const appName = import.meta.env.VITE_APP_NAME || 'Clerky'
createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    progress: {
        color: '#f4fd3b',
    },
    defaults: {
        visitOptions: (href, option) => {
            const isClerking =
                href.includes('/clerking/') && option.method !== 'get'
            return { showProgress: !isClerking }
        },
    },
})
