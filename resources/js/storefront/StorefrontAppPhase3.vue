<script setup>
import { computed, onMounted, ref } from 'vue';
import MobileBottomNav from './components/MobileBottomNav.vue';
import MobileDrawer from './components/MobileDrawer.vue';
import StorefrontFooter from './components/StorefrontFooter.vue';
import StorefrontHeader from './components/StorefrontHeader.vue';

const menuOpen = ref(false);
const searchOpen = ref(false);
const search = ref('');
const cartCount = ref(0);

const categories = [
    { name: 'Dates (খেজুর)', icon: '🌴', href: '/category/dates-খেজুর' },
    { name: 'Dry Food', icon: '🥜', href: '/category/dry-food' },
    { name: 'Masala', icon: '🌶️', href: '/category/masala-মসলা-কম্বো' },
    { name: 'Honey', icon: '🍯', href: '/category/honey' },
    { name: 'Mix Food', icon: '🥣', href: '/category/mix-food' },
    { name: 'Nuts & Seeds', icon: '🌰', href: '/category/nuts-seeds' },
    { name: 'Tea', icon: '🍵', href: '/category/tea' },
];

const products = [
    { name: 'Primal Gold - (প্রাইমাল গোল্ড)', price: '৳ 950 – ৳ 1,500', oldPrice: '', badge: '-21%', image: 'https://cdn.falaqfood.com/uploads/media/2026/08/01a05135-6532-7173-8fce-069b93b0f86a.webp', href: '/product/primal-gold' },
    { name: 'Fermented Garlic & Honey (ফার্মেন্টেড গার্লিক হানি) - 450gm', price: '৳ 780', oldPrice: '৳ 1,000', badge: '-22%', image: 'https://cdn.falaqfood.com/uploads/media/2026/08/01a05704-1d59-7f17-aa56-70f277bc2547.webp', href: '/product/fermented-garlic' },
    { name: 'রসুনজিরা - (কালোজিরা, রসুন, মধু মিক্স) – 450gm', price: '৳ 750', oldPrice: '৳ 1,200', badge: '-38%', image: 'https://cdn.falaqfood.com/uploads/media/2026/06/019ef93a-f9df-7731-9976-0ba2c308205f.webp', href: '/product/kalojira-garlic-honey' },
    { name: 'শিলাজিৎ মধু (Shilajit Honey) - 1KG', price: '৳ 1,200', oldPrice: '৳ 1,800', badge: '-33%', image: 'https://cdn.falaqfood.com/uploads/media/2026/09/01a07628-b6b6-7c3b-b0c4-86408d538941.webp', href: '/product/shilajit-honey' },
    { name: 'জিঞ্জার হানি (Ginger Honey) - 1KG', price: '৳ 850', oldPrice: '৳ 1,200', badge: '-29%', image: 'https://cdn.falaqfood.com/uploads/media/2026/09/01a084ef-ccab-7779-a91c-75eb6a427f9c.webp', href: '/product/ginger-honey' },
    { name: 'Flavour Box Honey - 2 KG', price: '৳ 1,600', oldPrice: '৳ 2,500', badge: '-36%', image: 'https://cdn.falaqfood.com/uploads/media/2026/09/01a07b04-4d65-7a3c-b9a5-f0778437fb7e.webp', href: '/product/flavour-box-honey' },
    { name: 'সরিষা ফুলের মধু ২ কেজি (Mustard Flower Honey 2kg)', price: '৳ 1,050', oldPrice: '৳ 1,400', badge: '-25%', image: 'https://cdn.falaqfood.com/uploads/media/2026/09/01a07b71-b6d0-79f0-8d13-1c31e766f5eb.webp', href: '/product/mustard-flower-honey' },
    { name: 'প্রিমিয়াম ঘি (Ghee)', price: '৳ 900 – ৳ 1,650', oldPrice: '', badge: '-14%', image: 'https://cdn.falaqfood.com/uploads/media/2026/08/01a05158-d193-749f-9eb3-e77f5b9a0bf9.webp', href: '/product/ghee' },
];

const heroImage = ref('https://cdn.falaqfood.com/uploads/media/2026/09/01a05bcc-f528-7951-95f1-9d3df069ff6d.webp'); const heroBadge = ref('-21%'); onMounted(async () => { try { const response = await fetch('/api/v1/storefront/home'); if (!response.ok) throw new Error('Storefront API returned ' + response.status); const payload = await response.json(); if (payload.categories?.length) categories.splice(0, categories.length, ...payload.categories); if (payload.products?.length) products.splice(0, products.length, ...payload.products); const hero = payload.banners?.find((banner) => banner.link?.includes('primal-gold')) || payload.banners?.[0]; if (hero?.image) heroImage.value = hero.image; } catch (error) { console.warn('Using storefront fallback data', error); } });
const visibleProducts = computed(() => {
    const term = search.value.trim().toLowerCase();
    return term ? products.filter((product) => product.name.toLowerCase().includes(term)) : products;
});

function submitSearch() {
    if (search.value.trim()) window.location.href = `/search?keyword=${encodeURIComponent(search.value.trim())}`;
}
</script>

<template>
    <div class="storefront-shell mobile-bottom-space">
        <StorefrontHeader v-model:search="search" :cart-count="cartCount" :search-open="searchOpen" @open-menu="menuOpen = true" @toggle-search="searchOpen = !searchOpen" @submit-search="submitSearch" />
        <MobileDrawer :open="menuOpen" @close="menuOpen = false" />

        <main>
            <section class="hero-wash border-b border-falaq-100"><div class="container-falaq grid min-h-[470px] items-center gap-8 py-10 md:grid-cols-[1.08fr_.92fr] md:py-14 lg:min-h-[540px]"><div class="max-w-xl"><p class="mb-4 text-xs font-bold uppercase tracking-[.24em] text-falaq-600">Pure food, honest living</p><h1 class="font-display text-4xl font-bold leading-[1.08] text-falaq-900 sm:text-5xl lg:text-6xl">Naturally good food for a healthier you.</h1><p class="mt-5 max-w-lg text-base leading-7 text-slate-600 md:text-lg">Discover carefully sourced honey, dates, nuts, tea and pantry essentials delivered fresh to your door.</p><div class="mt-8 flex flex-wrap gap-3"><a href="/shop" class="rounded-md bg-falaq-500 px-7 py-3 text-sm font-bold uppercase tracking-wide text-white shadow-lg shadow-falaq-500/20 transition hover:bg-falaq-700">Shop now</a><a href="/offers" class="rounded-md border border-falaq-500 bg-white/60 px-7 py-3 text-sm font-bold uppercase tracking-wide text-falaq-700 transition hover:bg-white">View offers</a></div><div class="mt-9 flex flex-wrap gap-x-6 gap-y-2 text-xs font-medium text-slate-500"><span>✓ 100% natural products</span><span>✓ Fast delivery</span><span>✓ Secure checkout</span></div></div><div class="relative mx-auto w-full max-w-[530px]"><div class="absolute -left-4 top-9 h-24 w-24 rounded-full bg-falaq-100 blur-2xl"></div><div class="absolute -right-4 bottom-2 h-32 w-32 rounded-full bg-amber-100 blur-2xl"></div><div class="relative overflow-hidden rounded-2xl border-8 border-white bg-white shadow-soft"><img class="h-[310px] w-full object-cover sm:h-[380px]"  :src="heroImage" alt="Primal Gold"><div class="absolute bottom-4 left-4 right-4 flex items-center justify-between rounded-xl bg-white/90 p-4 backdrop-blur"><div><p class="text-xs text-slate-500">Featured pick</p><p class="font-display text-lg font-bold text-falaq-900">Primal Gold</p></div><span class="rounded-md bg-falaq-100 px-3 py-1 text-xs font-bold text-falaq-700">{{ heroBadge }}</span></div></div></div></div></section>

            <section class="container-falaq py-10 md:py-14"><div class="mb-6 flex items-end justify-between gap-4"><div><p class="text-xs font-bold uppercase tracking-[.2em] text-falaq-500">Explore our collection</p><h2 class="mt-2 font-display text-3xl font-bold text-falaq-900">Shop by category</h2></div><a href="/shop" class="hidden text-sm font-bold text-falaq-600 hover:text-falaq-900 sm:block">View more →</a></div><div class="hide-scrollbar -mx-1 flex gap-3 overflow-x-auto pb-2 sm:grid sm:grid-cols-4 sm:overflow-visible lg:grid-cols-7"><a v-for="category in categories" :key="category.name" :href="category.href" class="group min-w-[140px] rounded-md border border-slate-100 bg-white p-4 text-center shadow-sm transition hover:-translate-y-1 hover:border-falaq-100 hover:shadow-soft"><span class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-falaq-50 text-3xl transition group-hover:bg-falaq-100">{{ category.icon }}</span><h3 class="mt-3 text-sm font-bold text-falaq-900">{{ category.name }}</h3></a></div></section>

            <section class="bg-white py-10 md:py-14"><div class="container-falaq"><div class="mb-6 flex items-end justify-between gap-4"><div><p class="text-xs font-bold uppercase tracking-[.2em] text-falaq-500">Our best sellers</p><h2 class="mt-2 font-display text-3xl font-bold text-falaq-900">Featured products</h2></div><a href="/shop" class="hidden text-sm font-bold text-falaq-600 hover:text-falaq-900 sm:block">View more →</a></div><div v-if="visibleProducts.length" class="grid grid-cols-2 gap-3 sm:grid-cols-2 sm:gap-5 lg:grid-cols-4"><article v-for="product in visibleProducts" :key="product.name" class="group relative overflow-hidden rounded-md border border-slate-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-soft"><a :href="product.href" class="product-card-image relative block overflow-hidden"><img :src="product.image" :alt="product.name" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"><span class="absolute left-3 top-3 rounded-md bg-falaq-500 px-2.5 py-1 text-[10px] font-bold text-white">{{ product.badge }}</span></a><div class="p-3 sm:p-4"><h3 class="line-clamp-2 min-h-10 text-sm font-bold leading-5 text-falaq-900 sm:text-base">{{ product.name }}</h3><div class="mt-3 flex items-end justify-between gap-2"><div><span class="text-base font-bold text-falaq-700">{{ product.price }}</span><del v-if="product.oldPrice" class="ml-1 text-[10px] text-slate-400">{{ product.oldPrice }}</del></div><button @click.prevent="cartCount += 1" class="grid h-8 w-8 shrink-0 place-items-center rounded-md bg-falaq-50 text-falaq-700 transition hover:bg-falaq-500 hover:text-white" aria-label="Add to cart">+</button></div></div></article></div><p v-else class="rounded-md bg-falaq-50 p-8 text-center text-sm text-slate-500">No products matched your search.</p></div></section>

            <section class="container-falaq py-10 md:py-14"><div class="grid gap-4 sm:grid-cols-3"><div class="rounded-md bg-falaq-50 p-5"><span class="text-2xl">🌱</span><h3 class="mt-3 font-bold text-falaq-900">Naturally sourced</h3><p class="mt-1 text-xs leading-5 text-slate-500">Pure products selected from trusted sources.</p></div><div class="rounded-md bg-amber-50 p-5"><span class="text-2xl">🚚</span><h3 class="mt-3 font-bold text-falaq-900">Reliable delivery</h3><p class="mt-1 text-xs leading-5 text-slate-500">Fresh products delivered safely across Bangladesh.</p></div><div class="rounded-md bg-sky-50 p-5"><span class="text-2xl">💚</span><h3 class="mt-3 font-bold text-falaq-900">Made for wellbeing</h3><p class="mt-1 text-xs leading-5 text-slate-500">Everyday choices that make healthy living simple.</p></div></div></section>
        </main>

        <StorefrontFooter />
        <MobileBottomNav :cart-count="cartCount" />
    </div>
</template>
