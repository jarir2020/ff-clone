<script setup>
import { onMounted, ref } from 'vue';
import MobileBottomNav from './components/MobileBottomNav.vue';
import MobileDrawer from './components/MobileDrawer.vue';
import StorefrontFooter from './components/StorefrontFooter.vue';
import StorefrontHeader from './components/StorefrontHeader.vue';
import { storefrontFetch } from './commerce';

const menuOpen = ref(false);
const searchOpen = ref(false);
const search = ref('');
const cart = ref({ items: [], count: 0, subtotalFormatted: '৳ 0' });
const form = ref({ name: '', phone: '', address: '', area: '', payment_method: 'cod' });
const loading = ref(true);
const submitting = ref(false);
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

async function submitOrder() {
    submitting.value = true;
    error.value = '';
    try {
        const payload = await storefrontFetch('/storefront/order', { method: 'POST', body: JSON.stringify(form.value) });
        window.location.href = payload.redirect;
    } catch (exception) {
        error.value = exception.message;
        submitting.value = false;
    }
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
        <main class="container-falaq py-8 md:py-14"><p class="text-xs font-bold uppercase tracking-[.2em] text-falaq-500">Almost there</p><h1 class="mt-2 font-display text-3xl font-bold text-falaq-900">Checkout</h1><div v-if="loading" class="mt-8 h-64 animate-pulse rounded-md bg-slate-100"></div><div v-else-if="!cart.items.length" class="mt-8 rounded-md bg-falaq-50 p-12 text-center"><p class="font-display text-2xl font-bold text-falaq-900">Your cart is empty</p><a href="/shop" class="mt-6 inline-block rounded-md bg-falaq-500 px-6 py-3 text-sm font-bold text-white">Browse products</a></div><div v-else class="mt-8 grid gap-8 lg:grid-cols-[1fr_340px]"><form class="rounded-md border border-slate-100 bg-white p-5 sm:p-7" @submit.prevent="submitOrder"><h2 class="font-display text-xl font-bold text-falaq-900">Delivery details</h2><p v-if="error" class="mt-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700">{{ error }}</p><div class="mt-6 grid gap-4 sm:grid-cols-2"><label class="grid gap-1 text-sm font-medium text-slate-700">Full name<input v-model="form.name" required class="h-11 rounded-md border border-slate-200 px-3 outline-none focus:border-falaq-500" placeholder="Your name"></label><label class="grid gap-1 text-sm font-medium text-slate-700">Phone number<input v-model="form.phone" required class="h-11 rounded-md border border-slate-200 px-3 outline-none focus:border-falaq-500" placeholder="01XXXXXXXXX"></label><label class="grid gap-1 text-sm font-medium text-slate-700 sm:col-span-2">Delivery address<textarea v-model="form.address" required rows="3" class="rounded-md border border-slate-200 px-3 py-2 outline-none focus:border-falaq-500" placeholder="House, road, area"></textarea></label><label class="grid gap-1 text-sm font-medium text-slate-700 sm:col-span-2">Area / landmark<input v-model="form.area" class="h-11 rounded-md border border-slate-200 px-3 outline-none focus:border-falaq-500" placeholder="Optional"></label></div><div class="mt-7 border-t border-slate-100 pt-6"><h2 class="font-display text-xl font-bold text-falaq-900">Payment method</h2><label class="mt-4 flex cursor-pointer items-center gap-3 rounded-md border border-falaq-200 bg-falaq-50 p-4"><input v-model="form.payment_method" type="radio" value="cod" class="accent-falaq-500"><span><strong class="block text-sm text-falaq-900">Cash on delivery</strong><small class="text-xs text-slate-500">Pay when your order arrives.</small></span></label></div><button :disabled="submitting" class="mt-7 w-full rounded-md bg-falaq-500 px-5 py-3 text-sm font-bold uppercase tracking-wide text-white hover:bg-falaq-700 disabled:opacity-60">{{ submitting ? 'Placing order…' : 'Place order' }}</button></form><aside class="h-fit rounded-md border border-slate-100 bg-white p-5"><h2 class="font-display text-xl font-bold text-falaq-900">Order summary</h2><div class="mt-5 grid gap-3"> <div v-for="item in cart.items" :key="item.rowId" class="flex items-center justify-between gap-3 text-sm"><span class="line-clamp-1 text-slate-600">{{ item.name }} × {{ item.quantity }}</span><strong class="text-falaq-900">{{ item.subtotalFormatted }}</strong></div></div><div class="mt-5 flex justify-between border-t border-slate-100 pt-4 text-base font-bold text-falaq-900"><span>Total</span><span>{{ cart.subtotalFormatted }}</span></div></aside></div></main>
        <StorefrontFooter />
        <MobileBottomNav :cart-count="cart.count" />
    </div>
</template>
