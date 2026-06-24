# Clerky 🩺

> Open-source medical clerking application for medical students and junior doctors.

![Beta](https://img.shields.io/badge/status-beta-yellow)
![License](https://img.shields.io/badge/license-MIT-green)

---

## What is Clerky?

Clerky is a structured clinical documentation tool that guides medical students and junior doctors through the clerking process; from chief complaint to clinical summary; using intelligent templates, conditional logic, and AI-assisted follow-up questions.

It's built to make clinical training more consistent, thorough, and educationally meaningful.

---

## Features

### Structured Clerking Sessions
- Step-by-step question navigation tailored to the patient's complaint
- Skip logic for dependent questions; only relevant questions are shown
- Backward navigation with full question history
- Autosave with debounced sync; never lose progress
- Pauseable elapsed timer to track clerking duration

### AI-Powered Assistance
- **AI Follow-Up Questions**; when a complaint has no template match, AI generates contextually relevant follow-up questions
- **Clinical Summary Generation**; AI produces a structured clinical summary from your clerking session
- Discipline-aware section ordering (e.g. surgical vs medical history flow)
- Prompt caching for token-efficient AI calls

### Complaint Templates
- Curated question templates per complaint
- Fuzzy matching to find the closest template for any complaint
- Drag-to-reorder questions within sections
- Conditional question logic via `depends_on` relationships

### Patient-Aware Filtering
- Questions filtered by patient age and sex
- Age range (`min_age`, `max_age`) and sex (`male`, `female`, `both`) per question
- Ensures clinically appropriate question sets for each patient

### Contribution System
- Verified contributors can submit and improve question templates
- Contribution tracking with verification status per question

### Mobile-First UI
- Contextual bottom dock navigation with drill-down
- Liquid glass styling with spring physics animations
- `h-dvh` viewport fix for mobile browsers
- Drag-to-dismiss sidebars

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel (PHP) |
| Frontend | Vue 3 + TypeScript + Inertia.js |
| Styling | Tailwind CSS |
| Real-time | Laravel Reverb (WebSockets) |
| Database | MySQL |
| Queue | Laravel Queues + Supervisor |
| Hosting | VPS (Coolify) |

---

## Getting Started

### Requirements
- PHP 8.2+
- Node.js 18+
- MySQL
- Composer

### Installation

```bash
git clone https://github.com/chibuezevince/clerky.git
cd clerky

composer install
npm install

cp .env.example .env
php artisan key:generate

# Configure your .env (DB, AI keys, Reverb, etc.)

php artisan migrate --seed
npm run build

php artisan serve
```

### Queue Worker
```bash
php artisan queue:work
```

---

## Contributing

Clerky is open-source and welcomes contributions; especially question templates, clinical content, and bug fixes.

1. Fork the repo
2. Create a feature branch: `git checkout -b feat/your-feature`
3. Commit your changes: `git commit -m "feat: add your feature"`
4. Push and open a PR

See `CONTRIBUTING.md` for more details.

---

## Support the Project

Clerky is free and open-source. If it's helped you, consider [supporting development](https://paystack.shop/pay/ug8-4-bulr).

---

## License

MIT © [Chibueze Vince](https://github.com/chibuezevince)