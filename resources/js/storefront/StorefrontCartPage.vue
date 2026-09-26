<script setup>
import { onMounted, ref } from 'vue';
import MobileBottomNav from './components/MobileBottomNav.vue';
import MobileDrawer from './components/MobileDrawer.vue';
import StorefrontFooter from './components/StorefrontFooter.vue';
import StorefrontHeader from './components/StorefrontHeader.vue';
import { formatMoney, storefrontFetch } from './commerce';

const menuOpen = ref(false);
const searchOpen = ref(false);
const search = ref('');
const cart = ref({ items: [], count: 0, subtotal: 0, subtotalFormatted: '৳ 0' });
const loading = ref(true);
const error = ref('');

async function load() {
    try {
        cart.value = await storefrontFetch('/storefront/cart');
    } catch (exception) {
        error.value = exception.message;
    } finally {
        loading.value = false;
    }
}

async function update(item, quantity) {
    try {
        cart.value = await storefrontFetch(`/storefront/cart/${item.rowId}`, { method: 'PATCH', body: JSON.stringify({ quantity }) });
    } catch (exception) {
        error.value = exception.message;
    }
}

async function remove(item) {
    cart.value = await storefrontFetch(`/storefront/cart/${item.rowId}`, { method: 'DELETE' });
}

async function clear() {
    cart.value = await storefrontFetch('/storefront/cart', { method: 'DELETE' });
}

function submitSearch() {
    if (search.value.trim()) window.location.href = `/shop?search=${encodeURIComponent(search.value.trim())}`;
}

onMounted(load);
</script>

<template>
    <div class="storefront-shell mobile-bottom-space">
        <StorefrontHeader v-model:search="search" :cart-count="cart.count" :search-open="searchOpen" @open-menu="menuOpen = true" @toggle-search="searchOpen = !searchOpen" @submit-search="submitSearch" />
        <MobileDrawer :open="menuOpen" @close="menuOpen = false" />
        <main class="container-falaq py-8 md:py-14"><p class="text-xs font-bold uppercase tracking-[.2em] text-falaq-500">Your selection</p><h1 class="mt-2 font-display text-3xl font-bold text-falaq-900">Shopping cart</h1><div v-if="loading" class="mt-8 h-64 animate-pulse rounded-md bg-slate-100"></div><div v-else-if="error" class="mt-8 rounded-md bg-red-50 p-8 text-red-700">{{ error }}</div><div v-else-if="cart.items.length" class="mt-8 grid gap-8 lg:grid-cols-[1fr_340px]"><section class="overflow-hidden rounded-md border border-slate-100 bg-white"><div v-for="item in cart.items" :key="item.rowId" class="flex gap-4 border-b border-slate-100 p-4 last:border-0 sm:p-5"><a :href="`/product/${item.slug}`" class="h-24 w-24 shrink-0 overflow-hidden rounded-md bg-falaq-50"><img :src="item.image" :alt="item.name" class="h-full w-full object-cover"></a><div class="min-w-0 flex-1"><a :href="`/product/${item.slug}`" class="line-clamp-2 text-sm font-bold text-falaq-900 hover:text-falaq-600">{{ item.name }}</a><p v-if="item.color || item.size" class="mt-1 text-xs text-slate-500">{{ [item.color, item.size].filter(Boolean).join(' · ') }}</p><p class="mt-2 text-sm font-bold text-falaq-700">{{ item.priceFormatted }}</p><div class="mt-3 flex items-center justify-between gap-3"><div class="flex h-9 items-center rounded-md border border-slate-200"><button class="grid h-full w-8 place-items-center text-slate-500" @click="update(item, Math.max(1, item.quantity - 1))">−</button><span class="w-8 text-center text-xs font-bold">{{ item.quantity }}</span><button class="grid h-full w-8 place-items-center text-slate-500" @click="update(item, item.quantity + 1)">+</button></div><button class="text-xs font-medium text-red-500 hover:text-red-700" @click="remove(item)">Remove</button></div></div><div class="hidden text-right text-sm font-bold text-falaq-700 sm:block">{{ item.subtotalFormatted }}</div></div><div class="flex justify-end border-t border-slate-100 p-4"><button class="text-xs font-bold text-red-500 hover:text-red-700" @click="clear">Clear cart</button></div></section><aside class="h-fit rounded-md border border-slate-100 bg-white p-5"><h2 class="font-display text-xl font-bold text-falaq-900">Order summary</h2><div class="mt-5 flex justify-between border-b border-slate-100 pb-4 text-sm text-slate-600"><span>Subtotal</span><strong class="text-falaq-900">{{ cart.subtotalFormatted }}</strong></div><div class="mt-4 flex justify-between text-base font-bold text-falaq-900"><span>Total</span><span>{{ cart.subtotalFormatted }}</span></div><a href="/checkout" class="mt-6 block rounded-md bg-falaq-500 px-5 py-3 text-center text-sm font-bold uppercase tracking-wide text-white hover:bg-falaq-700">Proceed to checkout</a><a href="/shop" class="mt-3 block text-center text-sm font-medium text-falaq-600">Continue shopping</a></aside></div><div v-else class="mt-8 rounded-md bg-falaq-50 p-12 text-center"><p class="font-display text-2xl font-bold text-falaq-900">Your cart is empty</p><p class="mt-2 text-sm text-slate-600">Add something naturally good to get started.</p><a href="/shop" class="mt-6 inline-block rounded-md bg-falaq-500 px-6 py-3 text-sm font-bold text-white hover:bg-falaq-700">Browse products</a></div></main>
        <StorefrontFooter />
        <MobileBottomNav :cart-count="cart.count" />
    </div>
</template>
