<script setup>
import { computed, ref, onMounted } from 'vue';
import { Icon } from '@iconify/vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import { Popover, Divider, Toast, Dialog } from 'primevue';
import { provide } from 'vue';
import { filterNavLinks } from '@/utils/navigationLinks';

const page = usePage();
const user = computed(() => page.props?.auth?.user);
const userInitials = computed(() =>
    user.value.name
        .split(' ')
        .map((n) => n[0])
        .join('')
        .toUpperCase()
        .slice(0, 2),
);

const sidebarOpen = ref(false);
const toggleSidebar = () => (sidebarOpen.value = !sidebarOpen.value);
const closeSidebar = () => (sidebarOpen.value = false);
const userLevel = computed(() => user.value?.access_levels?.access_level);
provide('user', user);
const visibleNavItems = computed(() => filterNavLinks(userLevel.value));
const isActive = (href) => href !== '#' && page.url.startsWith(href);

const popoverRef = ref(null);
const togglePopover = (event) => popoverRef.value?.toggle(event);

const userMenuItems = [
    { label: 'My Profile', icon: 'hugeicons:user-circle', href: '/user/profile', badge: null },
    { label: 'Account Settings', icon: 'hugeicons:settings-01', href: '/account-settings', badge: null },
    { label: 'Notifications', icon: 'hugeicons:notification-02', href: '/notifications', badge: 3 },
    { label: 'Help & Support', icon: 'hugeicons:help-circle', href: '/help', badge: null },
];

const logout = () => router.post('/logout');

// ── Announcement ───────────────────────────────────────
const ANNOUNCEMENT_KEY = 'dapay_announcement_april10_2026';
const showAnnouncement = ref(false);

onMounted(() => {
    const dismissed = localStorage.getItem(ANNOUNCEMENT_KEY);
    if (!dismissed) showAnnouncement.value = true;
});

const dismissAnnouncement = () => {
    localStorage.setItem(ANNOUNCEMENT_KEY, 'dismissed');
    showAnnouncement.value = false;
};
const remindLater = () => { showAnnouncement.value = false; };

const announcementDate = new Date().toLocaleDateString('en-US', {
    month: 'long', day: 'numeric', year: 'numeric',
});

// ── Glossary Modal ─────────────────────────────────────
const glossaryModalVisible = ref(false);
const glossarySearch = ref('');
const selectedTerm = ref(null);

const terms = computed(() => page.props?.glossaries ?? []);

const filteredGlossary = computed(() =>
    terms.value.filter(t =>
        t.term.toLowerCase().includes(glossarySearch.value.toLowerCase())
    )
);

function openGlossaryModal() {
    glossarySearch.value = '';
    selectedTerm.value = null;
    glossaryModalVisible.value = true;
}
</script>

<template>
    <div class="flex h-screen w-full overflow-hidden bg-slate-100">

        <!-- Mobile overlay -->
        <Transition name="fade">
            <div v-if="sidebarOpen" class="fixed inset-0 z-20 bg-black/40 lg:hidden" @click="closeSidebar" />
        </Transition>

        <!-- Sidebar mobile -->
        <Transition name="slide">
            <aside v-show="sidebarOpen" class="fixed inset-y-0 left-0 z-30 flex h-full w-64 flex-col border-r border-slate-200 bg-white lg:hidden">
                <div class="flex h-16 flex-shrink-0 items-center justify-between gap-3 border-b border-slate-100 px-5">
                    <div class="flex min-w-0 items-center gap-3">
                        <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-600 shadow-md shadow-indigo-200">
                            <Icon icon="hugeicons:analytics-02" class="text-base text-white" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm leading-none font-extrabold tracking-widest text-slate-800">DAPAY</p>
                            <p class="mt-0.5 text-[10px] tracking-wide text-slate-400">Reporting System</p>
                        </div>
                    </div>
                    <button type="button" @click="closeSidebar" class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600">
                        <Icon icon="hugeicons:cancel-01" class="text-sm" />
                    </button>
                </div>
                <nav class="scrollbar-none flex-1 space-y-0.5 overflow-y-auto px-2 py-3">
                    <div v-for="item in visibleNavItems" :key="item.id" class="mb-1">
                        <p class="px-3 py-1.5 text-[10px] font-semibold tracking-widest text-slate-400 uppercase">{{ item.label }}</p>
                        <div class="ml-4 space-y-0.5 border-l-2 border-slate-100 pl-3">
                            <Link
                                v-for="child in item.children"
                                :key="child.label"
                                :href="child.href"
                                @click="closeSidebar"
                                class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-xs transition-colors duration-150"
                                :class="isActive(child.href) ? 'bg-indigo-50 font-semibold text-indigo-600' : 'font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-700'"
                            >
                                <span class="h-1 w-1 flex-shrink-0 rounded-full" :class="isActive(child.href) ? 'bg-indigo-500' : 'bg-slate-300'" />
                                <Icon :icon="child.icon" class="flex-shrink-0 text-sm" :class="isActive(child.href) ? 'text-indigo-500' : 'text-slate-400'" />
                                <span class="truncate">{{ child.label }}</span>
                            </Link>
                        </div>
                    </div>
                </nav>
                <div class="flex-shrink-0 border-t border-slate-100 p-3">
                    <div class="flex items-center gap-3 rounded-xl px-2 py-1.5 transition-colors hover:bg-slate-50">
                        <div class="relative flex-shrink-0">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-indigo-400 to-violet-500 text-[11px] font-bold text-white">{{ userInitials }}</div>
                            <span class="absolute right-0 bottom-0 h-2 w-2 rounded-full border-2 border-white bg-emerald-400" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-xs leading-none font-semibold text-slate-700">{{ user.name }}</p>
                            <p class="mt-0.5 truncate text-[10px] text-slate-400">{{ user.role ?? 'User' }}</p>
                        </div>
                        <button type="button" @click="togglePopover" class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600">
                            <Icon icon="hugeicons:more-vertical" class="text-sm" />
                        </button>
                    </div>
                </div>
            </aside>
        </Transition>

        <!-- Sidebar desktop -->
        <aside class="hidden h-full w-64 flex-shrink-0 flex-col border-r border-slate-200 bg-white lg:flex">
            <div class="flex h-16 flex-shrink-0 items-center gap-3 border-b border-slate-100 px-5">
                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-600 shadow-md shadow-indigo-200">
                    <Icon icon="hugeicons:analytics-02" class="text-base text-white" />
                </div>
                <div class="min-w-0">
                    <p class="text-sm leading-none font-extrabold tracking-widest text-slate-800">DAPAY</p>
                    <p class="mt-0.5 text-[10px] tracking-wide text-slate-400">Reporting System</p>
                </div>
            </div>
            <nav class="scrollbar-none flex-1 space-y-0.5 overflow-y-auto px-2 py-3">
                <div v-for="item in visibleNavItems" :key="item.id" class="mb-1">
                    <p class="px-3 py-1.5 text-[10px] font-semibold tracking-widest text-slate-400 uppercase">{{ item.label }}</p>
                    <div class="ml-4 space-y-0.5 border-l-2 border-slate-100 pl-3">
                        <Link
                            v-for="child in item.children"
                            :key="child.label"
                            :href="child.href"
                            class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-xs transition-colors duration-150"
                            :class="isActive(child.href) ? 'bg-indigo-50 font-semibold text-indigo-600' : 'font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-700'"
                        >
                            <span class="h-1 w-1 flex-shrink-0 rounded-full" :class="isActive(child.href) ? 'bg-indigo-500' : 'bg-slate-300'" />
                            <Icon :icon="child.icon" class="flex-shrink-0 text-sm" :class="isActive(child.href) ? 'text-indigo-500' : 'text-slate-400'" />
                            <span class="truncate">{{ child.label }}</span>
                        </Link>
                    </div>
                </div>
            </nav>
            <div class="flex-shrink-0 border-t border-slate-100 p-3">
                <div class="flex items-center gap-3 rounded-xl px-2 py-1.5 transition-colors hover:bg-slate-50">
                    <div class="relative flex-shrink-0">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-indigo-400 to-violet-500 text-[11px] font-bold text-white">{{ userInitials }}</div>
                        <span class="absolute right-0 bottom-0 h-2 w-2 rounded-full border-2 border-white bg-emerald-400" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-xs leading-none font-semibold text-slate-700">{{ user.name }}</p>
                        <p class="mt-0.5 truncate text-[10px] text-slate-400">{{ user.role ?? 'User' }}</p>
                    </div>
                    <button type="button" @click="togglePopover" class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600">
                        <Icon icon="hugeicons:more-vertical" class="text-sm" />
                    </button>
                </div>
            </div>
        </aside>

        <!-- User Popover -->
        <Popover ref="popoverRef">
            <div class="-m-2 w-56">
                <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50 px-4 py-3">
                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-400 to-violet-500 text-xs font-bold text-white">{{ userInitials }}</div>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-slate-800">{{ user.name }}</p>
                        <p class="truncate text-xs text-slate-500">{{ user.email }}</p>
                    </div>
                </div>
                <div class="py-1">
                    <button
                        v-for="item in userMenuItems"
                        :key="item.label"
                        type="button"
                        @click="router.visit(item.href)"
                        class="group flex w-full items-center gap-3 px-4 py-2.5 transition-colors hover:bg-slate-50"
                    >
                        <Icon :icon="item.icon" class="flex-shrink-0 text-base text-slate-400 transition-colors group-hover:text-indigo-500" />
                        <span class="flex-1 truncate text-left text-xs font-medium text-slate-600 transition-colors group-hover:text-slate-900">{{ item.label }}</span>
                        <span v-if="item.badge" class="inline-flex h-5 min-w-[20px] flex-shrink-0 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white">{{ item.badge }}</span>
                    </button>
                </div>
                <Divider class="!my-0" />
                <div class="py-1">
                    <button type="button" @click="logout" class="flex w-full items-center gap-3 px-4 py-2.5 transition-colors hover:bg-red-50">
                        <Icon icon="hugeicons:logout-02" class="flex-shrink-0 text-base text-red-400" />
                        <span class="text-xs font-medium text-red-500">Sign out</span>
                    </button>
                </div>
            </div>
        </Popover>

        <!-- Main -->
        <div class="flex h-full min-w-0 flex-1 flex-col overflow-hidden">
            <header class="flex h-16 flex-shrink-0 items-center justify-between border-b border-slate-200 bg-white px-4 lg:px-6">
                <div class="flex min-w-0 items-center gap-3 overflow-hidden">
                    <button
                        type="button"
                        @click="toggleSidebar"
                        class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition-colors hover:bg-slate-50 lg:hidden"
                        aria-label="Open sidebar"
                    >
                        <Icon icon="hugeicons:menu-01" class="text-base" />
                    </button>
                    <div class="flex min-w-0 items-center gap-2 overflow-hidden">
                        <span class="hidden text-xs font-medium whitespace-nowrap text-slate-400 sm:inline">DAPAY</span>
                        <Icon icon="hugeicons:arrow-right-01" class="hidden flex-shrink-0 text-xs text-slate-300 sm:inline" />
                        <span class="hidden text-xs font-medium whitespace-nowrap text-slate-500 sm:inline">Navigation</span>
                    </div>
                </div>
                <div class="ml-4 flex flex-shrink-0 items-center gap-2">
                    <button
                        @click="openGlossaryModal"
                        type="button"
                        class="relative flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition-colors hover:bg-slate-50"
                    >
                        <Icon icon="hugeicons:help-circle" class="text-base" />
                    </button>
                    <button type="button" class="relative flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition-colors hover:bg-slate-50">
                        <Icon icon="hugeicons:hotel-bell" class="text-base" />
                        <span class="absolute top-2 right-2 h-1.5 w-1.5 rounded-full bg-red-500" />
                    </button>
                </div>
            </header>
            <main class="flex-1 overflow-y-auto bg-slate-100 p-4 lg:p-6">
                <slot />
            </main>
        </div>

        <!-- Announcement Dialog -->
        <Dialog
            v-model:visible="showAnnouncement"
            modal
            :closable="false"
            :style="{ width: '440px', padding: '0', borderRadius: '16px', overflow: 'hidden' }"
            :pt="{
                root: { style: 'border-radius: 16px; overflow: hidden; padding: 0;' },
                header: { style: 'display: none;' },
                content: { style: 'padding: 0;' },
            }"
        >
            <div class="bg-indigo-600 px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg" style="background: rgba(255,255,255,0.15)">
                        <Icon icon="hugeicons:notification-02" class="text-base text-white" />
                    </div>
                    <div>
                        <p class="text-sm leading-none font-semibold text-white">System Announcement</p>
                        <p class="mt-1 text-[10px] text-indigo-200">{{ announcementDate }}</p>
                    </div>
                </div>
            </div>
            <div class="px-6 pt-5 pb-0">
                <p class="mb-4 text-xs leading-relaxed text-slate-500">
                    Please be reminded of the following upcoming deadlines. Ensure all submissions are completed on time to avoid any delays in report processing.
                </p>
                <div class="mb-4 space-y-3">
                    <div class="flex items-start gap-3 rounded-xl border border-slate-100 p-3">
                        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-50">
                            <Icon icon="hugeicons:note-done" class="text-base text-indigo-600" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="mb-1 text-xs font-semibold text-slate-700">Encoding of Reports</p>
                            <p class="mb-2 text-[11px] leading-relaxed text-slate-400">Report submissions must be encoded and finalized by the specified deadline to ensure inclusion in the quarterly report.</p>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-medium text-amber-800">
                                <Icon icon="hugeicons:calendar-03" class="flex-shrink-0 text-[11px]" />
                                Deadline: April 10, 2026
                            </span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 rounded-xl border border-slate-100 p-3">
                        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-50">
                            <Icon icon="hugeicons:user-group" class="text-base text-indigo-600" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="mb-1 text-xs font-semibold text-slate-700">Encoding of Teams</p>
                            <p class="mb-2 text-[11px] leading-relaxed text-slate-400">Please ensure that all team-related data is accurately encoded and finalized by the deadline to facilitate smooth report generation.</p>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-medium text-amber-800">
                                <Icon icon="hugeicons:calendar-03" class="flex-shrink-0 text-[11px]" />
                                Deadline: April 10, 2026
                            </span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 rounded-xl border border-slate-100 p-3">
                        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-50">
                            <Icon icon="hugeicons:user-group" class="text-base text-indigo-600" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="mb-1 text-xs font-semibold text-slate-700">Encoding of Barangay Profiles</p>
                            <p class="mb-2 text-[11px] leading-relaxed text-slate-400">Please ensure that all barangay profile data is accurately encoded and finalized by the deadline to facilitate smooth report generation.</p>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-medium text-amber-800">
                                <Icon icon="hugeicons:calendar-03" class="flex-shrink-0 text-[11px]" />
                                Deadline: April 10, 2026
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mb-5 flex gap-2 rounded-lg bg-slate-50 p-3">
                    <Icon icon="hugeicons:information-circle" class="mt-0.5 flex-shrink-0 text-sm text-indigo-400" />
                    <p class="text-[11px] leading-relaxed text-slate-400">Please make sure to review all the information and complete the necessary details to ensure a smooth transition and report generation.</p>
                </div>
            </div>
            <div class="flex gap-2 px-6 pb-5">
                <button type="button" @click="remindLater" class="flex-1 rounded-lg border border-slate-200 py-2.5 text-xs font-medium text-slate-500 transition-colors hover:bg-slate-50">Close</button>
                <button type="button" @click="dismissAnnouncement" class="flex-1 rounded-lg bg-indigo-600 py-2.5 text-xs font-semibold text-white transition-colors hover:bg-indigo-700">Got it</button>
            </div>
        </Dialog>

        <!-- Glossary Modal -->
        <Dialog
            v-model:visible="glossaryModalVisible"
            modal
            class="w-[95vw] max-w-2xl"
            :pt="{
                header: { style: 'display: none;' },
                content: { style: 'padding: 0;' },
            }"
        >
            <!-- Header -->
            <div class="flex items-center gap-3 bg-indigo-600 px-5 py-4">
                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg" style="background: rgba(255,255,255,0.15)">
                    <Icon icon="hugeicons:book-open-01" class="text-base text-white" />
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white leading-none">Definition of Terms</p>
                    <p class="mt-1 text-[10px] text-indigo-200">Purokalusugan and Dap-ay terms defined</p>
                </div>
                <button type="button" @click="glossaryModalVisible = false"
                    class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg text-indigo-200 hover:text-white hover:bg-white/10 transition-colors">
                    <Icon icon="hugeicons:cancel-01" class="text-sm" />
                </button>
            </div>

            <!-- Search -->
            <div class="px-5 py-3 border-b border-slate-100 bg-white sticky top-0 z-10">
                <div class="relative">
                    <Icon icon="hugeicons:search-01" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" />
                    <input
                        v-model="glossarySearch"
                        type="text"
                        placeholder="Search term..."
                        class="w-full pl-9 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                    />
                </div>
            </div>

            <!-- Terms list -->
            <div class="overflow-y-auto bg-white" style="max-height: 60vh;">

                <!-- Empty -->
                <div v-if="filteredGlossary.length === 0" class="flex flex-col items-center justify-center gap-2 py-16 text-slate-400">
                    <Icon icon="hugeicons:book-open-01" class="text-3xl text-slate-300" />
                    <p class="text-xs">No terms found.</p>
                </div>

                <!-- Terms -->
                <div v-else class="divide-y divide-slate-100">
                    <div
                        v-for="term in filteredGlossary"
                        :key="term.id"
                        class="px-5 py-4 hover:bg-slate-50 transition-colors cursor-pointer"
                        @click="selectedTerm = selectedTerm?.id === term.id ? null : term"
                    >
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-50">
                                    <Icon icon="hugeicons:text-font" class="text-xs text-indigo-500" />
                                </div>
                                <p class="text-xs font-semibold text-slate-700 truncate">{{ term.term }}</p>
                            </div>
                            <Icon
                                :icon="selectedTerm?.id === term.id ? 'hugeicons:arrow-up-01' : 'hugeicons:arrow-down-01'"
                                class="text-sm flex-shrink-0 text-slate-400"
                            />
                        </div>
                        <div v-if="selectedTerm?.id === term.id" class="mt-3 ml-10">
                            <div class="ql-snow ql-editor text-xs leading-relaxed text-slate-500 !p-0" v-html="term.description" />
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50 px-5 py-3">
                <p class="text-[10px] text-slate-400">
                    {{ filteredGlossary.length }} {{ filteredGlossary.length === 1 ? 'term' : 'terms' }} found
                </p>
                <button
                    type="button"
                    @click="glossaryModalVisible = false"
                    class="px-4 py-1.5 text-xs font-medium text-slate-500 border border-slate-200 rounded-lg hover:bg-white transition-colors"
                >
                    Close
                </button>
            </div>
        </Dialog>

        <Toast position="bottom-right" />
    </div>
</template>

<style scoped>
.scrollbar-none::-webkit-scrollbar { display: none; }
.scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }

.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.slide-enter-active, .slide-leave-active { transition: transform 0.25s ease; }
.slide-enter-from, .slide-leave-to { transform: translateX(-100%); }
</style>