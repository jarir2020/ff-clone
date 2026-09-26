<script setup>
defineProps({ open: Boolean });
const emit = defineEmits(['close']);
const links = [
    { label: 'Home', href: '/' },
    { label: 'Categories', href: '/shop' },
    { label: 'Combos', href: '/shop' },
    { label: 'Offers', href: '/offers' },
    { label: 'About us', href: '/about-us' },
    { label: 'Blog', href: '/blog' },
    { label: 'Contact us', href: '/contact-us' },
];
</script>

<template>
    <div v-if="open" class="fixed inset-0 z-[251] bg-slate-900/40 md:hidden" @click.self="emit('close')">
        <aside class="h-full w-[82%] max-w-sm bg-white p-6 shadow-2xl"><div class="flex items-center justify-between"><span class="font-display text-2xl font-bold text-falaq-700">falaqfood</span><button class="text-2xl text-slate-500" aria-label="Close menu" @click="emit('close')">×</button></div><div class="mt-8 grid gap-1 text-base font-medium"><a v-for="link in links" :key="link.label" :href="link.href" class="rounded-md px-4 py-3 hover:bg-falaq-50">{{ link.label }}</a></div></aside>
    </div>
</template>
