import { configureEcho } from '@laravel/echo-vue';
import { createInertiaApp } from '@inertiajs/vue3'

configureEcho({
    broadcaster: 'pusher'
});

const appName = import.meta.env.VITE_APP_NAME || 'Clerky'
createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    progress: {
        popover: true,
        color: '#f4fd3b',
    },
    defaults: {
        visitOptions: (href, option) => {
            const doNotAnimate =
                option.only?.length ||
                (option.data && Object.keys(option.data as any).length > 0)
            const isClerking =
                href.includes('/clerking/') && option.method == 'post'
            return {
                viewTransition: !doNotAnimate,
                showProgress: !isClerking,
            }
        },
    },
})
