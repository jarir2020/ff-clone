<script setup>
import { onMounted, ref } from 'vue';
import MobileBottomNav from './components/MobileBottomNav.vue';
import MobileDrawer from './components/MobileDrawer.vue';
import StorefrontFooter from './components/StorefrontFooter.vue';
import StorefrontHeader from './components/StorefrontHeader.vue';

const props = defineProps({ kind: { type: String, required: true } });
const menuOpen = ref(false);
const searchOpen = ref(false);
const search = ref('');
const cartCount = ref(0);
const loading = ref(true);
const page = ref(null);
const blogs = ref([]);
const products = ref([]);
const contact = ref({});

const fallbackTitles = {
    about: 'About Falaq Food',
    corporate: 'Corporate Deals',
    offers: 'Offers',
    contact: 'Contact Us',
    blog: 'Falaq Food Blog',
};

const endpointForKind = {
    about: '/api/v1/storefront/content/about-us',
    corporate: '/api/v1/storefront/content/corporate-deal',
    contact: '/api/v1/storefront/contact',
    blog: '/api/v1/storefront/blogs',
    offers: '/api/v1/storefront/home',
};

function submitSearch() {
    if (search.value.trim()) window.location.href = `/search?keyword=${encodeURIComponent(search.value.trim())}`;
}

onMounted(async () => {
    try {
        const response = await fetch(endpointForKind[props.kind]);
        if (!response.ok) throw new Error(`Content endpoint returned ${response.status}`);
        const payload = await response.json();
        if (props.kind === 'blog') blogs.value = payload.items || [];
        else if (props.kind === 'contact') contact.value = payload.contact || {};
        else if (props.kind === 'offers') products.value = payload.products || [];
        else page.value = payload;
    } catch (error) {
        console.warn('Using content fallback', error);
        page.value = { title: fallbackTitles[props.kind], description: '<p>Falaq Food brings natural, carefully sourced food to your family.</p>' };
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <div class="storefront-shell mobile-bottom-space">
        <StorefrontHeader v-model:search="search" :cart-count="cartCount" :search-open="searchOpen" @open-menu="menuOpen = true" @toggle-search="searchOpen = !searchOpen" @submit-search="submitSearch" />
        <MobileDrawer :open="menuOpen" @close="menuOpen = false" />

        <main>
            <section class="hero-wash border-b border-falaq-100"><div class="container-falaq py-14 md:py-20"><p class="text-xs font-bold uppercase tracking-[.24em] text-falaq-600">Falaq Food</p><h1 class="mt-3 max-w-3xl font-display text-4xl font-bold leading-tight text-falaq-900 sm:text-5xl">{{ page?.title || fallbackTitles[kind] }}</h1><p class="mt-4 max-w-2xl text-base leading-7 text-slate-600">Pure organic honey &amp; foods, sourced from nature for your family's health.</p></div></section>

            <section v-if="loading" class="container-falaq py-16 text-center text-sm text-slate-500">Loading content…</section>

            <section v-else-if="kind === 'blog'" class="container-falaq py-10 md:py-14"><div v-if="blogs.length" class="grid gap-5 md:grid-cols-2 lg:grid-cols-3"><article v-for="blog in blogs" :key="blog.id" class="overflow-hidden rounded-md border border-slate-200 bg-white shadow-sm"><img v-if="blog.image" :src="blog.image" :alt="blog.title" class="h-48 w-full object-cover"><div class="p-5"><p class="text-xs font-bold uppercase tracking-wide text-falaq-500">Falaq Food Journal</p><h2 class="mt-2 font-display text-xl font-bold text-falaq-900">{{ blog.title }}</h2><p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600">{{ blog.shortDescription }}</p><a :href="`/blog/${blog.slug}`" class="mt-4 inline-flex text-sm font-bold text-falaq-600">Read article →</a></div></article></div><p v-else class="rounded-md bg-falaq-50 p-8 text-center text-sm text-slate-500">New articles are coming soon.</p></section>

            <section v-else-if="kind === 'offers'" class="container-falaq py-10 md:py-14"><div class="mb-6"><p class="text-xs font-bold uppercase tracking-[.2em] text-falaq-500">Limited-time savings</p><h2 class="mt-2 font-display text-3xl font-bold text-falaq-900">Shop current offers</h2></div><div class="grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-4"><article v-for="product in products" :key="product.id" class="overflow-hidden rounded-md border border-slate-100 bg-white shadow-sm"><a :href="product.href"><img :src="product.image" :alt="product.name" class="product-card-image h-full w-full object-cover"></a><div class="p-4"><h2 class="line-clamp-2 min-h-10 text-sm font-bold text-falaq-900">{{ product.name }}</h2><p class="mt-2 font-display font-bold text-falaq-700">{{ product.price }}</p><del v-if="product.oldPrice" class="text-xs text-slate-400">{{ product.oldPrice }}</del></div></article></div></section>

            <section v-else-if="kind === 'contact'" class="container-falaq grid gap-8 py-10 md:grid-cols-[.8fr_1.2fr] md:py-14"><div class="rounded-md bg-falaq-50 p-6"><h2 class="font-display text-2xl font-bold text-falaq-900">Get in touch</h2><p class="mt-3 text-sm leading-6 text-slate-600">Our customer support team is ready to help with orders, products, and delivery.</p><div class="mt-6 grid gap-4 text-sm text-slate-700"><p>☎ {{ contact.hotline || '09613-821489' }}</p><p>✉ {{ contact.email || 'hello@falaqfood.com' }}</p><p>⌖ Bosila Future Town, Mohammadpur, Dhaka-1207</p></div></div><form class="rounded-md border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent><h2 class="font-display text-2xl font-bold text-falaq-900">Send us a message</h2><div class="mt-5 grid gap-4"><input class="h-11 rounded-md border border-slate-200 px-4 text-sm outline-none focus:border-falaq-500" placeholder="Your name"><input class="h-11 rounded-md border border-slate-200 px-4 text-sm outline-none focus:border-falaq-500" placeholder="Email address" type="email"><input class="h-11 rounded-md border border-slate-200 px-4 text-sm outline-none focus:border-falaq-500" placeholder="Subject"><textarea class="min-h-32 rounded-md border border-slate-200 px-4 py-3 text-sm outline-none focus:border-falaq-500" placeholder="How can we help?"></textarea><button class="rounded-md bg-falaq-500 px-6 py-3 text-sm font-bold uppercase tracking-wide text-white hover:bg-falaq-700">Send message</button></div></form></section>

            <section v-else class="container-falaq py-10 md:py-14"><article class="rich-content max-w-3xl rounded-md border border-slate-200 bg-white p-6 text-base leading-7 shadow-sm md:p-10" v-html="page?.description || '<p>Falaq Food brings natural, carefully sourced food to your family.</p>'"></article></section>
        </main>

        <StorefrontFooter />
        <MobileBottomNav :cart-count="cartCount" />
    </div>
</template>
