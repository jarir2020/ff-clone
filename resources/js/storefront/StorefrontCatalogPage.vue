<script setup>
import { computed, onMounted, ref } from 'vue';
import MobileBottomNav from './components/MobileBottomNav.vue';
import MobileDrawer from './components/MobileDrawer.vue';
import StorefrontFooter from './components/StorefrontFooter.vue';
import StorefrontHeader from './components/StorefrontHeader.vue';
import { formatMoney, storefrontFetch } from './commerce';

const menuOpen = ref(false);
const searchOpen = ref(false);
const search = ref(new URLSearchParams(window.location.search).get('search') || '');
const sort = ref(new URLSearchParams(window.location.search).get('sort') || 'latest');
const products = ref([]);
const categories = ref([]);
const pagination = ref({ currentPage: 1, lastPage: 1, total: 0 });
const cartCount = ref(0);
const loading = ref(true);
const error = ref('');
const notice = ref('');

const pathParts = window.location.pathname.split('/').filter(Boolean);
const categorySlug = pathParts[0] === 'category' ? decodeURIComponent(pathParts[1] || '') : '';
const offers = window.location.pathname === '/offers';

const title = computed(() => offers ? 'Current offers' : categorySlug ? (categories.value.find((item) => item.slug === categorySlug)?.name || 'Category') : 'Shop all products');
const subtitle = computed(() => offers ? 'Save more on carefully selected Falaq Food favourites.' : 'Pure food, honest value, delivered to your door.');

async function loadCart() {
    const payload = await storefrontFetch('/storefront/cart');
    cartCount.value = payload.count || 0;
}

async function loadCatalog(page = 1) {
    loading.value = true;
    error.value = '';
    const params = new URLSearchParams({ page, per_page: 12, sort: sort.value });
    if (categorySlug) params.set('category', categorySlug);
    if (search.value.trim()) params.set('search', search.value.trim());
    if (offers) params.set('offers', '1');

    try {
        const payload = await storefrontFetch(`/api/v1/storefront/catalog?${params.toString()}`);
        products.value = payload.products || [];
        categories.value = payload.categories || [];
        pagination.value = payload.pagination || pagination.value;
    } catch (exception) {
        error.value = exception.message;
    } finally {
        loading.value = false;
    }
}

function submitSearch() {
    const value = search.value.trim();
    window.location.href = value ? `/shop?search=${encodeURIComponent(value)}` : '/shop';
}

async function addToCart(product) {
    notice.value = '';
    try {
        const payload = await storefrontFetch('/storefront/cart', {
            method: 'POST',
            body: JSON.stringify({ product_id: product.id, quantity: 1 }),
        });
        cartCount.value = payload.count || 0;
        notice.value = `${product.name} added to cart`;
        window.setTimeout(() => { notice.value = ''; }, 2400);
    } catch (exception) {
        notice.value = exception.message;
    }
}

onMounted(() => Promise.all([loadCatalog(), loadCart()]));
</script>

<template>
    <div class="storefront-shell mobile-bottom-space">
        <StorefrontHeader v-model:search="search" :cart-count="cartCount" :search-open="searchOpen" @open-menu="menuOpen = true" @toggle-search="searchOpen = !searchOpen" @submit-search="submitSearch" />
        <MobileDrawer :open="menuOpen" @close="menuOpen = false" />

        <main>
            <section class="border-b border-falaq-100 bg-gradient-to-r from-falaq-50 to-white"><div class="container-falaq py-10 md:py-14"><p class="text-xs font-bold uppercase tracking-[.2em] text-falaq-500">Falaq Food collection</p><h1 class="mt-2 font-display text-3xl font-bold text-falaq-900 sm:text-4xl">{{ title }}</h1><p class="mt-3 max-w-xl text-sm text-slate-600">{{ subtitle }}</p></div></section>

            <section class="container-falaq py-8 md:py-12">
                <div v-if="notice" class="mb-5 rounded-md bg-falaq-50 px-4 py-3 text-sm font-medium text-falaq-700">{{ notice }}</div>
                <div class="mb-6 flex flex-wrap items-center justify-between gap-3"><p class="text-sm text-slate-500">{{ pagination.total || 0 }} products</p><label class="flex items-center gap-2 text-sm text-slate-500">Sort by <select v-model="sort" class="rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none focus:border-falaq-500" @change="loadCatalog()"><option value="latest">Latest</option><option value="price-low">Price: low to high</option><option value="price-high">Price: high to low</option><option value="name">Name</option></select></label></div>
                <div class="grid gap-6 lg:grid-cols-[220px_1fr]">
                    <aside class="hidden rounded-md border border-slate-100 bg-white p-4 lg:block"><h2 class="font-display text-lg font-bold text-falaq-900">Categories</h2><div class="mt-4 grid gap-1"><a href="/shop" class="rounded-md px-3 py-2 text-sm" :class="!categorySlug && !offers ? 'bg-falaq-50 font-bold text-falaq-700' : 'text-slate-600 hover:bg-falaq-50'">All products</a><a v-for="category in categories" :key="category.slug" :href="`/category/${category.slug}`" class="rounded-md px-3 py-2 text-sm" :class="category.slug === categorySlug ? 'bg-falaq-50 font-bold text-falaq-700' : 'text-slate-600 hover:bg-falaq-50'">{{ category.name }}</a><a href="/offers" class="rounded-md px-3 py-2 text-sm" :class="offers ? 'bg-falaq-50 font-bold text-falaq-700' : 'text-slate-600 hover:bg-falaq-50'">Offers</a></div></aside>
                    <div class="min-w-0"><div v-if="loading" class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4"><div v-for="n in 8" :key="n" class="h-72 animate-pulse rounded-md bg-slate-100"></div></div><div v-else-if="error" class="rounded-md bg-red-50 p-8 text-center text-sm text-red-700">{{ error }}</div><div v-else-if="products.length" class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-5 lg:grid-cols-4"><article v-for="product in products" :key="product.id" class="group relative overflow-hidden rounded-md border border-slate-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-soft"><a :href="product.href" class="product-card-image relative block overflow-hidden"><img :src="product.image" :alt="product.name" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"><span v-if="product.badge" class="absolute left-3 top-3 rounded-md bg-falaq-500 px-2.5 py-1 text-[10px] font-bold text-white">{{ product.badge }}</span></a><div class="p-3 sm:p-4"><a :href="product.href" class="line-clamp-2 min-h-10 text-sm font-bold leading-5 text-falaq-900 hover:text-falaq-600 sm:text-base">{{ product.name }}</a><div class="mt-3 flex items-end justify-between gap-2"><div><span class="text-base font-bold text-falaq-700">{{ product.price }}</span><del v-if="product.oldPrice" class="ml-1 text-[10px] text-slate-400">{{ product.oldPrice }}</del></div><button :disabled="product.stock < 1" @click="addToCart(product)" class="grid h-8 w-8 shrink-0 place-items-center rounded-md bg-falaq-50 text-falaq-700 transition hover:bg-falaq-500 hover:text-white disabled:cursor-not-allowed disabled:opacity-40" aria-label="Add to cart">+</button></div></div></article></div><div v-else class="rounded-md bg-falaq-50 p-10 text-center text-sm text-slate-600">No products found for this selection.</div><div v-if="pagination.lastPage > 1" class="mt-8 flex justify-center gap-2"><button v-for="page in pagination.lastPage" :key="page" class="grid h-9 min-w-9 place-items-center rounded-md border px-2 text-sm" :class="page === pagination.currentPage ? 'border-falaq-500 bg-falaq-500 text-white' : 'border-slate-200 bg-white text-slate-600'" @click="loadCatalog(page)">{{ page }}</button></div></div>
                </div>
            </section>
        </main>
        <StorefrontFooter />
        <MobileBottomNav :cart-count="cartCount" />
    </div>
</template>
