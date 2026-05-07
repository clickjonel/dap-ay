
const navLinks = [
    {
        id: 'dashboard',
        label: 'Dashboard',
        icon: 'hugeicons:dashboard-square-02',
        children: [
            { label: 'Admin Dashboard',  href: '/dashboard/access-level-one',   icon: 'hugeicons:user-square',   accessLevels: [1] },
            { label: 'HRH Dashboard',    href: '/dashboard/access-level-two',   icon: 'hugeicons:building-03',   accessLevels: [2] },
            { label: 'PDOHO Dashboard',  href: '/dashboard/access-level-three', icon: 'hugeicons:shield-user',   accessLevels: [3] },
            { label: 'DMO Dashboard',  href: '/dashboard/access-level-four', icon: 'hugeicons:shield-user',   accessLevels: [4] },
            { label: 'DMO Municipalities Dashboard',  href: '/dashboard/dmo/municipalities', icon: 'hugeicons:shield-user',   accessLevels: [4] },
            { label: 'Barangay Priority Programs Dashboard',  href: '/dashboard/barangay-monitoring/priority-programs', icon: 'hugeicons:shield-user',   accessLevels: [1,4] },
            { label: 'Barangay PK Profile Dashboard',  href: '/dashboard/barangay-monitoring/organizational-indicators', icon: 'hugeicons:shield-user',   accessLevels: [1,4] },
            { label: 'Barangay PK Profile Dashboard',  href: '/dashboard/barangay-monitoring/pk-profile', icon: 'hugeicons:shield-user',   accessLevels: [1] },
            { label: 'Barangay Geography Dashboard',  href: '/dashboard/barangay-monitoring/geography', icon: 'hugeicons:shield-user',   accessLevels: [1] },
            { label: 'Barangay Population Dashboard',  href: '/dashboard/barangay-monitoring/population', icon: 'hugeicons:shield-user',   accessLevels: [1] },
            { label: 'Report PK Activities Monitoring',  href: '/dashboard/barangay-monitoring/pk-activities', icon: 'hugeicons:shield-user',   accessLevels: [1] },
            { label: 'Report Approval Monitoring',  href: '/dashboard/report-monitoring/dmo-approvals', icon: 'hugeicons:shield-user',   accessLevels: [1] },
        ],
        accessLevels: [1, 2, 3, 4]
    },
    {
        id: 'program',
        label: 'Program',
        icon: 'hugeicons:folder-library',
        children: [
            { label: 'Programs', href: '/program', icon: 'hugeicons:book-open-01', accessLevels: [1, 3] },
        ],
        accessLevels: [1]
    },
    {
        id: 'indicator',
        label: 'Indicator',
        icon: 'hugeicons:chart-increase',
        children: [
            { label: 'Organizational Indicators', href: '/indicator/organizational', icon: 'hugeicons:analytics-up', accessLevels: [1] },
            { label: 'Program Indicators',        href: '/indicator/program',        icon: 'hugeicons:analytics-up', accessLevels: [1] },
        ],
        accessLevels: [1]
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
            { label: 'Reports', href: '/reports', icon: 'hugeicons:note-done', accessLevels: [1, 2, 3, 4] },
        ],
        accessLevels: [1, 2, 3, 4]
    },
    {
        id: 'Activities',
        label: 'PK Activities',
        icon: 'hugeicons:file-02',
        children: [
            { label: 'PK Activities', href: '/pk-activities', icon: 'hugeicons:note-done', accessLevels: [1, 2, 3] },
        ],
        accessLevels: [1, 2, 3]
    },
    {
        id: 'User',
        label: 'Management',
        icon: 'hugeicons:file-02',
        children: [
            { label: 'Users', href: '/users', icon: 'hugeicons:note-done', accessLevels: [1, 3] },
            { label: 'Set Handled Municipalities', href: '/users/handled-municipalities', icon: 'hugeicons:note-done', accessLevels: [4] },
        ],
        accessLevels: [1, 3, 4]
    },
    {
        id: 'Generate',
        label: 'Generator',
        icon: 'hugeicons:file-02',
        children: [
            // { label: 'PK Activities', href: '/generate/quarterly-large-scale-report', icon: 'hugeicons:note-done', accessLevels: [1, 3] },
            //{ label: 'Report',   href: '/generate/approved-report-submissions',  icon: 'hugeicons:note-done', accessLevels: [1, 3] },
            { label: 'Report Generator',   href: '/generate/report',  icon: 'hugeicons:note-done', accessLevels: [1, 2, 3] },
            { label: 'PK Activities Generator',   href: '/generate/pk_activities',  icon: 'hugeicons:note-done', accessLevels: [1, 2, 3] },
            { label: 'Report Form',   href: '/generate/report-form',  icon: 'hugeicons:note-done', accessLevels: [1, 2, 3] },
        ],
        accessLevels: [1, 2,  3]
    },
]

export const filterNavLinks = (accessLevel:number) => {
    return navLinks.filter(item => item.accessLevels.includes(accessLevel)).map(item => ({
        ...item,
        children: item.children.filter(child => child.accessLevels.includes(accessLevel))
    }))
}