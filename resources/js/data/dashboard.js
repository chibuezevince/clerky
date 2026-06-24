import { logout } from '@/actions/App/Http/Controllers/Auth/LoginController'
import { contribute, dashboard, donate, settings } from '@/routes'
import { all } from '@/routes/cases'
import { index as sectionQuestionsIndex } from '@/routes/section-questions'

export const navItems = [
    {
        id: 'dashboard',
        url: dashboard(),
        label: 'Dashboard',
        icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
    },
    {
        id: 'cases',
        url: all(),
        label: 'Cases',
        icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
    },
    {
        id: 'contribute',
        url: '#',
        label: 'Contribute',
        icon: 'M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z',
        children: [
            {
                id: 'complaint-questions',
                url: contribute(),
                label: 'Complaint Questions',
                icon: 'M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z',
            },
            {
                id: 'section-questions',
                url: sectionQuestionsIndex(),
                label: 'Section Questions',
                icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
            },
        ],
    },
    {
        id: 'settings',
        url: settings(),
        label: 'Settings',
        icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
    },
     {
        id: 'donate',
        url: donate(),
        label: 'Donate',
        icon: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
    },
    {
        id: 'logout',
        url: logout(),
        label: 'Logout',
        icon: 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1',
    },
]

export const glass =
    'bg-brand-surface/45 backdrop-blur-2xl backdrop-saturate-150 border border-white/[0.06] rounded-3xl'

export const icons = {
    stethoscope:
        'M6 3v6a4 4 0 0 0 8 0V3 M6 3h2 M12 3h2 M10 13v3a4 4 0 0 0 8 0v-2 M18 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4z',
    bell: 'M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5 M9 17a3 3 0 0 0 6 0',
    mic: 'M12 2a3 3 0 0 0-3 3v6a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3z M5 11a7 7 0 0 0 14 0 M12 18v4 M8 22h8',
    brain: 'M9 4a3 3 0 0 0-3 3 2.5 2.5 0 0 0-2 4 2.5 2.5 0 0 0 1 4.5A3 3 0 0 0 9 20V4z M15 4a3 3 0 0 1 3 3 2.5 2.5 0 0 1 2 4 2.5 2.5 0 0 1-1 4.5A3 3 0 0 1 15 20V4z',
    calendar:
        'M3 7h18 M5 4v3 M19 4v3 M4 9h16v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9z',
    arrowUp: 'M12 19V5 M5 12l7-7 7 7',
    arrowDown: 'M12 5v14 M19 12l-7 7-7-7',
    arrowUpRight: 'M7 17 17 7 M8 7h9v9',
}

export const pillBtn =
    'px-[18px] py-[9px] rounded-full text-[13px] font-semibold transition-all cursor-pointer'

export const convertText = (text) => {
    return text.split('_').join(' ')
}

export const formattedTime = () => {
    const now = new Date()

    const formatter = new Intl.DateTimeFormat('en-CA', {
        timeZone: 'UTC',
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false,
    })

    const [
        { value: year },
        ,
        { value: month },
        ,
        { value: day },
        ,
        { value: hour },
        ,
        { value: minute },
        ,
        { value: second },
    ] = formatter.formatToParts(now)
    const formattedUtcDate = `${year}-${month}-${day} ${hour}:${minute}:${second}`

    return formattedUtcDate
}

export const HPCslug = 'history-of-presenting-complaint'

export const processingVerbs = [
    'Running',
    'Analyzing',
    'Generating',
    'Synthesizing',
    'Compiling',
    'Indexing',
    'Classifying',
    'Extracting',
    'Formulating',
    'Correlating',
    'Structuring',
    'Optimizing',
    'Parsing',
    'Tokenizing',
    'Embedding',
    'Vectorizing',
    'Scoring',
    'Ranking',
    'Filtering',
    'Aggregating',
    'Normalizing',
    'Validating',
    'Cross-referencing',
    'Reinforcing',
    'Weighting',
    'Projecting',
    'Mapping',
    'Resolving',
    'Inferring',
    'Deduplicating',
    'Summarizing',
    'Abstracting',
    'Refining',
    'Benchmarking',
    'Categorizing',
    'Clustering',
    'Decoding',
    'Encrypting',
    'Interpolating',
    'Quantizing',
    'Sampling',
    'Thresholding',
    'Harmonizing',
    'Smoothing',
    'Bolstering',
    'Augmenting',
    'Pipelining',
    'Orchestrating',
    'Dispatching',
    'Buffering',
    'Streaming',
    'Batching',
    'Caching',
    'Memoizing',
    'Pruning',
    'Truncating',
    'Padding',
    'Flattening',
]
