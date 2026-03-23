<script setup>
import Main from '@/layouts/main.vue'
import { Icon } from '@iconify/vue'
import { usePage } from '@inertiajs/vue3'

defineOptions({ layout: Main })

const user = {
    name: 'Maria Santos',
    role: 'Field Officer',
}
const page = usePage()

const stats = {
    total:      148,
    this_month: 23,
    pending:    5,
    approved:   18,
}

const teams = [
    { id: 1, name: 'Baguio Health Team',     members: 8,  role: 'Member', color: 'indigo'  },
    { id: 2, name: 'Mountain Province Unit', members: 5,  role: 'Lead',   color: 'emerald' },
    { id: 3, name: 'CAR Monitoring Group',   members: 12, role: 'Member', color: 'amber'   },
]

const recentReports = [
    { id: 1, title: 'Q2 Health Coverage Report',  barangay: 'Brgy. Lualhati',    date: 'Jul 10, 2025', status: 'approved' },
    { id: 2, title: 'Nutrition Program Update',    barangay: 'Brgy. Kagitingan',  date: 'Jul 8, 2025',  status: 'pending'  },
    { id: 3, title: 'Monthly Field Assessment',    barangay: 'Brgy. Trancoville', date: 'Jul 5, 2025',  status: 'approved' },
    { id: 4, title: 'Immunization Drive Summary',  barangay: 'Brgy. Lualhati',    date: 'Jul 1, 2025',  status: 'draft'    },
    { id: 5, title: 'Water Sanitation Monitoring', barangay: 'Brgy. Burnham',     date: 'Jun 28, 2025', status: 'approved' },
]

const barangays = [
    { id: 1, name: 'Brgy. Lualhati',    municipality: 'Baguio City', reports: 42, active: true  },
    { id: 2, name: 'Brgy. Kagitingan',  municipality: 'Baguio City', reports: 31, active: true  },
    { id: 3, name: 'Brgy. Trancoville', municipality: 'Baguio City', reports: 28, active: true  },
    { id: 4, name: 'Brgy. Burnham',     municipality: 'Baguio City', reports: 19, active: false },
]

const statusConfig = {
    approved: { label: 'Approved', class: 'bg-emerald-50 text-emerald-600 ring-1 ring-emerald-200' },
    pending:  { label: 'Pending',  class: 'bg-amber-50  text-amber-600  ring-1 ring-amber-200'  },
    draft:    { label: 'Draft',    class: 'bg-slate-100 text-slate-500  ring-1 ring-slate-200'  },
}

const teamColors = {
    indigo:  { bg: 'bg-indigo-100',  text: 'text-indigo-600',  dot: 'bg-indigo-500'  },
    emerald: { bg: 'bg-emerald-100', text: 'text-emerald-600', dot: 'bg-emerald-500' },
    amber:   { bg: 'bg-amber-100',   text: 'text-amber-600',   dot: 'bg-amber-500'   },
}

const initials = (name) => name?.split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase()

const greeting = () => {
    const h = new Date().getHours()
    if (h < 12) return 'Good morning'
    if (h < 18) return 'Good afternoon'
    return 'Good evening'
}

const today = new Date().toLocaleDateString('en-PH', { month: 'long', day: 'numeric', year: 'numeric' })
</script>

<template>
    <div class="h-full flex flex-col gap-6 min-h-0 overflow-y-auto">

        <!-- ── Welcome banner ─────────────────────────────── -->
        <div class="shrink-0 relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-indigo-900 px-6 py-5">
            <div class="absolute inset-0 opacity-10"
                style="background-image: linear-gradient(rgba(255,255,255,.15) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.15) 1px, transparent 1px); background-size: 24px 24px;">
            </div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 ring-2 ring-white/30 flex items-center justify-center text-white font-bold text-sm shrink-0">
                    {{ initials(page.props.auth.user.name) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-indigo-200 text-xs font-medium">{{ greeting() }},</p>
                    <h1 class="text-white text-lg font-bold leading-tight truncate">{{ page.props.auth.user.name }}</h1>
                    <p class="text-indigo-300 text-[11px] mt-0.5">PDOHO Purokalusugan Dashboard</p>
                </div>
                <div class="hidden sm:flex items-center gap-1.5 bg-white/10 border border-white/20 rounded-lg px-3 py-1.5">
                    <Icon icon="hugeicons:calendar-03" class="text-indigo-200 text-sm" />
                    <span class="text-white text-xs font-medium">{{ today }}</span>
                </div>
            </div>
        </div>



    </div>
</template>