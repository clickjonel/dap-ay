<script setup>
    import { computed, ref } from 'vue'
    import { Icon } from '@iconify/vue'
    import { usePage, router, Link } from '@inertiajs/vue3'
    import Popover from 'primevue/popover'
    import Divider from 'primevue/divider'
    import Toast from 'primevue/toast'
    import { provide } from 'vue'

    // ── Inertia page props ─────────────────────────────────
    const page = usePage()
    const user = computed(() => page.props?.auth?.user)
    const userInitials = computed(() =>
        user.value.name
            .split(' ')
            .map(n => n[0])
            .join('')
            .toUpperCase()
            .slice(0, 2)
    )

    // ── Sidebar toggle (mobile) ────────────────────────────
    const sidebarOpen = ref(false)
    const toggleSidebar = () => sidebarOpen.value = !sidebarOpen.value
    const closeSidebar  = () => sidebarOpen.value = false

    // ── Navigation ─────────────────────────────────────────
    const navItems = [
        {
            id: 'dashboard',
            label: 'Dashboard',
            icon: 'hugeicons:dashboard-square-02',
            children: [
                { label: 'Admin Dashboard',  href: '/dashboard/access-level-one',   icon: 'hugeicons:user-square',   accessLevels: [1] },
                { label: 'HRH Dashboard',    href: '/dashboard/access-level-two',   icon: 'hugeicons:building-03',   accessLevels: [2] },
                { label: 'PDOHO Dashboard',  href: '/dashboard/access-level-three', icon: 'hugeicons:shield-user',   accessLevels: [3] },
            ],
            accessLevels: [1, 2, 3]
        },
        {
            id: 'program',
            label: 'Program',
            icon: 'hugeicons:folder-library',
            children: [
                { label: 'Programs', href: '/program', icon: 'hugeicons:book-open-01', accessLevels: [1, 3] },
            ],
            accessLevels: [1, 3]
        },
        {
            id: 'indicator',
            label: 'Indicator',
            icon: 'hugeicons:chart-increase',
            children: [
                { label: 'Organizational Indicators', href: '/indicator/organizational', icon: 'hugeicons:analytics-up', accessLevels: [1, 3] },
                { label: 'Program Indicators',        href: '/indicator/program',        icon: 'hugeicons:analytics-up', accessLevels: [1, 3] },
            ],
            accessLevels: [1, 3]
        },
        {
            id: 'disaggregation',
            label: 'Disaggregation',
            icon: 'hugeicons:chart-increase',
            children: [
                { label: 'Disaggregations', href: '/disaggregations', icon: 'hugeicons:analytics-up', accessLevels: [1, 3] },
            ],
            accessLevels: [1, 3]
        },
        {
            id: 'barangay',
            label: 'Barangay',
            icon: 'hugeicons:maps-location-01',
            children: [
                { label: 'Barangays', href: '/barangays', icon: 'hugeicons:home-07', accessLevels: [1, 2, 3] },
            ],
            accessLevels: [1, 2, 3]
        },
        {
            id: 'team',
            label: 'Team',
            icon: 'hugeicons:user-multiple-02',
            children: [
                { label: 'Teams', href: '/teams', icon: 'hugeicons:user-group', accessLevels: [1, 2, 3] },
            ],
            accessLevels: [1, 2, 3]
        },
        {
            id: 'report',
            label: 'Report',
            icon: 'hugeicons:file-02',
            children: [
                { label: 'Reports', href: '/reports', icon: 'hugeicons:note-done', accessLevels: [1, 2, 3] },
            ],
            accessLevels: [1, 2, 3]
        },
        {
            id: 'Activities',
            label: 'PK Activities',
            icon: 'hugeicons:file-02',
            children: [
                { label: 'PK Activities', href: '/pk-activities', icon: 'hugeicons:note-done', accessLevels: [1, 3] },
            ],
            accessLevels: [1, 3]
        },
        {
            id: 'User',
            label: 'User Management',
            icon: 'hugeicons:file-02',
            children: [
                { label: 'Users', href: '/users', icon: 'hugeicons:note-done', accessLevels: [1, 3] },
            ],
            accessLevels: [1, 3]
        },
        {
            id: 'Generate',
            label: 'Generate Reports',
            icon: 'hugeicons:file-02',
            children: [
                { label: 'Quarterly Large Scale Report', href: '/generate/quarterly-large-scale-report', icon: 'hugeicons:note-done', accessLevels: [1, 3] },
                { label: 'Monthly Report Submissions',   href: '/generate/monthly-submissions',          icon: 'hugeicons:note-done', accessLevels: [1, 3] },
            ],
            accessLevels: [1, 3]
        },
    ]

    // ── Access level ───────────────────────────────────────
    const userLevel = computed(() => user.value?.access_levels?.access_level)

    provide('user', user)

    const visibleNavItems = computed(() =>
        navItems
            .filter(item => item.accessLevels.includes(userLevel.value))
            .map(item => ({
                ...item,
                children: item.children.filter(child => child.accessLevels.includes(userLevel.value))
            }))
    )

    // ── Active state ───────────────────────────────────────
    const isActive = (href) => href !== '#' && page.url.startsWith(href)

    const activeLabel = computed(() => {
        for (const item of visibleNavItems.value) {
            const match = item.children.find(c => isActive(c.href))
            if (match) return match.label
        }
        return ''
    })

    // ── Popover ────────────────────────────────────────────
    const popoverRef = ref(null)
    const togglePopover = (event) => popoverRef.value?.toggle(event)

    // ── User menu ──────────────────────────────────────────
    const userMenuItems = [
        { label: 'My Profile',       icon: 'hugeicons:user-circle',     href: '/profile',       badge: null },
        { label: 'Account Settings', icon: 'hugeicons:settings-01',     href: '/account-settings',      badge: null },
        { label: 'Notifications',    icon: 'hugeicons:notification-02', href: '/notifications', badge: 3    },
        { label: 'Help & Support',   icon: 'hugeicons:help-circle',     href: '/help',          badge: null },
    ]

    const logout = () => router.post('/logout')
</script>

<template>
    <div class="flex w-full h-screen bg-slate-100 overflow-hidden">

        <!-- ── Mobile overlay ────────────────────────────── -->
        <Transition name="fade">
            <div
                v-if="sidebarOpen"
                class="fixed inset-0 z-20 bg-black/40 lg:hidden"
                @click="closeSidebar"
            />
        </Transition>

        <!-- ── Sidebar ───────────────────────────────────── -->
        <Transition name="slide">
            <aside
                v-show="sidebarOpen"
                class="fixed inset-y-0 left-0 z-30 w-64 flex flex-col h-full bg-white border-r border-slate-200 lg:hidden"
            >
                <!-- Brand -->
                <div class="flex items-center justify-between gap-3 px-5 h-16 border-b border-slate-100 flex-shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center shadow-md shadow-indigo-200 flex-shrink-0">
                            <Icon icon="hugeicons:analytics-02" class="text-white text-base" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-slate-800 font-extrabold text-sm tracking-widest leading-none">DAPAY</p>
                            <p class="text-slate-400 text-[10px] mt-0.5 tracking-wide">Reporting System</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="closeSidebar"
                        class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors flex-shrink-0"
                    >
                        <Icon icon="hugeicons:cancel-01" class="text-sm" />
                    </button>
                </div>

                <!-- Nav -->
                <nav class="flex-1 overflow-y-auto py-3 px-2 space-y-0.5 scrollbar-none">
                    <div v-for="item in visibleNavItems" :key="item.id" class="mb-1">
                        <p class="px-3 py-1.5 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">
                            {{ item.label }}
                        </p>
                        <div class="ml-4 pl-3 border-l-2 border-slate-100 space-y-0.5">
                            <Link
                                v-for="child in item.children"
                                :key="child.label"
                                :href="child.href"
                                @click="closeSidebar"
                                class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs transition-colors duration-150"
                                :class="isActive(child.href)
                                    ? 'bg-indigo-50 text-indigo-600 font-semibold'
                                    : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50 font-medium'"
                            >
                                <span class="w-1 h-1 rounded-full flex-shrink-0" :class="isActive(child.href) ? 'bg-indigo-500' : 'bg-slate-300'" />
                                <Icon :icon="child.icon" class="text-sm flex-shrink-0" :class="isActive(child.href) ? 'text-indigo-500' : 'text-slate-400'" />
                                <span class="truncate">{{ child.label }}</span>
                            </Link>
                        </div>
                    </div>
                </nav>

                <!-- User footer -->
                <div class="border-t border-slate-100 p-3 flex-shrink-0">
                    <div class="flex items-center gap-3 px-2 py-1.5 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="relative flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-white text-[11px] font-bold">
                                {{ userInitials }}
                            </div>
                            <span class="absolute bottom-0 right-0 w-2 h-2 bg-emerald-400 rounded-full border-2 border-white" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-slate-700 text-xs font-semibold truncate leading-none">{{ user.name }}</p>
                            <p class="text-slate-400 text-[10px] mt-0.5 truncate">{{ user.role ?? 'User' }}</p>
                        </div>
                        <button type="button" @click="togglePopover" class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors flex-shrink-0">
                            <Icon icon="hugeicons:more-vertical" class="text-sm" />
                        </button>
                    </div>
                </div>
            </aside>
        </Transition>

        <!-- ── Sidebar (desktop — always visible) ────────── -->
        <aside class="hidden lg:flex w-64 flex-shrink-0 flex-col h-full bg-white border-r border-slate-200">
            <!-- Brand -->
            <div class="flex items-center gap-3 px-5 h-16 border-b border-slate-100 flex-shrink-0">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center shadow-md shadow-indigo-200 flex-shrink-0">
                    <Icon icon="hugeicons:analytics-02" class="text-white text-base" />
                </div>
                <div class="min-w-0">
                    <p class="text-slate-800 font-extrabold text-sm tracking-widest leading-none">DAPAY</p>
                    <p class="text-slate-400 text-[10px] mt-0.5 tracking-wide">Reporting System</p>
                </div>
            </div>

            <!-- Nav -->
            <nav class="flex-1 overflow-y-auto py-3 px-2 space-y-0.5 scrollbar-none">
                <div v-for="item in visibleNavItems" :key="item.id" class="mb-1">
                    <p class="px-3 py-1.5 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">
                        {{ item.label }}
                    </p>
                    <div class="ml-4 pl-3 border-l-2 border-slate-100 space-y-0.5">
                        <Link
                            v-for="child in item.children"
                            :key="child.label"
                            :href="child.href"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs transition-colors duration-150"
                            :class="isActive(child.href)
                                ? 'bg-indigo-50 text-indigo-600 font-semibold'
                                : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50 font-medium'"
                        >
                            <span class="w-1 h-1 rounded-full flex-shrink-0" :class="isActive(child.href) ? 'bg-indigo-500' : 'bg-slate-300'" />
                            <Icon :icon="child.icon" class="text-sm flex-shrink-0" :class="isActive(child.href) ? 'text-indigo-500' : 'text-slate-400'" />
                            <span class="truncate">{{ child.label }}</span>
                        </Link>
                    </div>
                </div>
            </nav>

            <!-- User footer -->
            <div class="border-t border-slate-100 p-3 flex-shrink-0">
                <div class="flex items-center gap-3 px-2 py-1.5 rounded-xl hover:bg-slate-50 transition-colors">
                    <div class="relative flex-shrink-0">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-white text-[11px] font-bold">
                            {{ userInitials }}
                        </div>
                        <span class="absolute bottom-0 right-0 w-2 h-2 bg-emerald-400 rounded-full border-2 border-white" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-slate-700 text-xs font-semibold truncate leading-none">{{ user.name }}</p>
                        <p class="text-slate-400 text-[10px] mt-0.5 truncate">{{ user.role ?? 'User' }}</p>
                    </div>
                    <button type="button" @click="togglePopover" class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors flex-shrink-0">
                        <Icon icon="hugeicons:more-vertical" class="text-sm" />
                    </button>
                </div>
            </div>
        </aside>

        <!-- ── User Popover ───────────────────────────────── -->
        <Popover ref="popoverRef">
            <div class="w-56 -m-2">
                <div class="flex items-center gap-3 px-4 py-3 bg-slate-50 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        {{ userInitials }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-800 truncate">{{ user.name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ user.email }}</p>
                    </div>
                </div>
                <div class="py-1">
                    <button
                        v-for="item in userMenuItems"
                        :key="item.label"
                        type="button"
                        @click="router.visit(item.href)"
                        class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 transition-colors group"
                    >
                        <Icon :icon="item.icon" class="text-base text-slate-400 group-hover:text-indigo-500 transition-colors flex-shrink-0" />
                        <span class="flex-1 text-left text-xs font-medium text-slate-600 group-hover:text-slate-900 transition-colors truncate">{{ item.label }}</span>
                        <span v-if="item.badge" class="inline-flex items-center justify-center min-w-[20px] h-5 px-1 rounded-full bg-red-500 text-white text-[10px] font-bold flex-shrink-0">
                            {{ item.badge }}
                        </span>
                    </button>
                </div>
                <Divider class="!my-0" />
                <div class="py-1">
                    <button type="button" @click="logout" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-red-50 transition-colors">
                        <Icon icon="hugeicons:logout-02" class="text-base text-red-400 flex-shrink-0" />
                        <span class="text-xs font-medium text-red-500">Sign out</span>
                    </button>
                </div>
            </div>
        </Popover>

        <!-- ── Main ──────────────────────────────────────── -->
        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">

            <!-- Topbar -->
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 lg:px-6 flex-shrink-0">

                <div class="flex items-center gap-3 min-w-0 overflow-hidden">
                    <!-- Hamburger (mobile only) -->
                    <button
                        type="button"
                        @click="toggleSidebar"
                        class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 transition-colors flex-shrink-0"
                        aria-label="Open sidebar"
                    >
                        <Icon icon="hugeicons:menu-01" class="text-base" />
                    </button>

                    <!-- Breadcrumb -->
                    <div class="flex items-center gap-2 min-w-0 overflow-hidden">
                        <span class="text-xs text-slate-400 font-medium whitespace-nowrap hidden sm:inline">DAPAY</span>
                        <Icon icon="hugeicons:arrow-right-01" class="text-slate-300 text-xs flex-shrink-0 hidden sm:inline" />
                        <span class="text-xs text-slate-500 font-medium whitespace-nowrap hidden sm:inline">Navigation</span>
                        <Icon icon="hugeicons:arrow-right-01" class="text-slate-300 text-xs flex-shrink-0 hidden sm:inline" />
                        <span class="text-xs text-slate-700 font-semibold truncate">{{ activeLabel }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2 flex-shrink-0 ml-4">
                    <button type="button" class="relative w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 transition-colors flex-shrink-0">
                        <Icon icon="hugeicons:help-circle" class="text-base" />
                    </button>
                    <button type="button" class="relative w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 transition-colors flex-shrink-0">
                        <Icon icon="hugeicons:hotel-bell" class="text-base" />
                        <span class="absolute top-2 right-2 w-1.5 h-1.5 bg-red-500 rounded-full" />
                    </button>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6 bg-slate-100">
                <slot />
            </main>

        </div>

        <Toast position="bottom-right" />
    </div>
</template>

<style scoped>
    .scrollbar-none::-webkit-scrollbar { display: none; }
    .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }

    .fade-enter-active,
    .fade-leave-active { transition: opacity 0.2s ease; }
    .fade-enter-from,
    .fade-leave-to { opacity: 0; }

    .slide-enter-active,
    .slide-leave-active { transition: transform 0.25s ease; }
    .slide-enter-from,
    .slide-leave-to { transform: translateX(-100%); }
</style>