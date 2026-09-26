<script setup>
import { computed, onMounted, ref } from 'vue';
import MobileBottomNav from './components/MobileBottomNav.vue';
import MobileDrawer from './components/MobileDrawer.vue';
import StorefrontFooter from './components/StorefrontFooter.vue';
import StorefrontHeader from './components/StorefrontHeader.vue';
import { formatMoney, storefrontFetch } from './commerce';

const menuOpen = ref(false);
const searchOpen = ref(false);
const search = ref('');
const cartCount = ref(0);
const product = ref(null);
const related = ref([]);
const selectedImage = ref('');
const selectedVariant = ref(null);
const quantity = ref(1);
const loading = ref(true);
const error = ref('');
const notice = ref('');
const slug = decodeURIComponent(window.location.pathname.split('/').filter(Boolean)[1] || '');

const gallery = computed(() => product.value?.images?.length ? product.value.images : [product.value?.image].filter(Boolean));
const shownPrice = computed(() => selectedVariant.value?.price || product.value?.price || '৳ 0');
const maxStock = computed(() => selectedVariant.value?.stock || product.value?.stock || 0);

async function load() {
    try {
        const [payload, cart] = await Promise.all([
            storefrontFetch(`/api/v1/storefront/products/${encodeURIComponent(slug)}`),
            storefrontFetch('/storefront/cart'),
        ]);
        product.value = payload.product;
        related.value = payload.related || [];
        selectedImage.value = payload.product.image;
        cartCount.value = cart.count || 0;
    } catch (exception) {
        error.value = exception.message;
    } finally {
        loading.value = false;
    }
}

function submitSearch() {
    if (search.value.trim()) window.location.href = `/shop?search=${encodeURIComponent(search.value.trim())}`;
}

async function addToCart() {
    if (!product.value) return;
    notice.value = '';
    try {
        const payload = await storefrontFetch('/storefront/cart', {
            method: 'POST',
            body: JSON.stringify({
                product_id: product.value.id,
                quantity: quantity.value,
                color_id: selectedVariant.value?.colorId || null,
                size_id: selectedVariant.value?.sizeId || null,
            }),
        });
        cartCount.value = payload.count || 0;
        notice.value = 'Product added to your cart.';
    } catch (exception) {
        notice.value = exception.message;
    }
}

onMounted(load);
</script>

<template>
    <div class="storefront-shell mobile-bottom-space">
        <StorefrontHeader v-model:search="search" :cart-count="cartCount" :search-open="searchOpen" @open-menu="menuOpen = true" @toggle-search="searchOpen = !searchOpen" @submit-search="submitSearch" />
        <MobileDrawer :open="menuOpen" @close="menuOpen = false" />
        <main class="container-falaq py-8 md:py-14">
            <div v-if="loading" class="grid animate-pulse gap-8 md:grid-cols-2"><div class="aspect-square rounded-md bg-slate-100"></div><div class="space-y-4"><div class="h-8 rounded bg-slate-100"></div><div class="h-24 rounded bg-slate-100"></div><div class="h-12 rounded bg-slate-100"></div></div></div>
            <div v-else-if="error" class="rounded-md bg-red-50 p-10 text-center text-red-700">{{ error }}</div>
            <template v-else-if="product">
                <div class="grid gap-8 md:grid-cols-[1.05fr_.95fr] md:gap-12"><div><div class="product-card-image overflow-hidden rounded-md border border-slate-100 bg-white"><img :src="selectedImage || product.image" :alt="product.name" class="h-full w-full object-cover"></div><div v-if="gallery.length > 1" class="mt-3 grid grid-cols-5 gap-2"><button v-for="image in gallery" :key="image" class="aspect-square overflow-hidden rounded-md border-2" :class="selectedImage === image ? 'border-falaq-500' : 'border-transparent'" @click="selectedImage = image"><img :src="image" :alt="product.name" class="h-full w-full object-cover"></button></div></div><div><p class="text-xs font-bold uppercase tracking-[.2em] text-falaq-500">{{ product.category?.name || 'Falaq Food' }}</p><h1 class="mt-2 font-display text-3xl font-bold leading-tight text-falaq-900 sm:text-4xl">{{ product.name }}</h1><div class="mt-5 flex items-center gap-3"><span class="text-2xl font-bold text-falaq-700">{{ shownPrice }}</span><del v-if="product.oldPrice" class="text-sm text-slate-400">{{ product.oldPrice }}</del><span v-if="product.badge" class="rounded-md bg-falaq-500 px-2 py-1 text-xs font-bold text-white">{{ product.badge }}</span></div><p class="mt-3 text-sm text-slate-500">{{ maxStock > 0 ? `${maxStock} items available` : 'Currently out of stock' }}</p><div v-if="product.variants?.length" class="mt-6"><p class="mb-2 text-sm font-bold text-falaq-900">Choose an option</p><div class="flex flex-wrap gap-2"><button v-for="variant in product.variants" :key="variant.id" class="rounded-md border px-3 py-2 text-sm" :class="selectedVariant?.id === variant.id ? 'border-falaq-500 bg-falaq-50 text-falaq-700' : 'border-slate-200 text-slate-600'" @click="selectedVariant = variant">{{ variant.color || variant.size || 'Option' }}<span v-if="variant.color && variant.size"> · {{ variant.size }}</span></button></div></div><div class="mt-7 flex flex-wrap gap-3"><div class="flex h-12 items-center rounded-md border border-slate-200"><button class="grid h-full w-11 place-items-center text-lg text-slate-500" @click="quantity = Math.max(1, quantity - 1)">−</button><span class="w-10 text-center text-sm font-bold">{{ quantity }}</span><button class="grid h-full w-11 place-items-center text-lg text-slate-500" @click="quantity = Math.min(99, quantity + 1)">+</button></div><button :disabled="maxStock < 1" class="h-12 rounded-md bg-falaq-500 px-8 text-sm font-bold uppercase tracking-wide text-white shadow-lg shadow-falaq-500/20 hover:bg-falaq-700 disabled:cursor-not-allowed disabled:opacity-50" @click="addToCart">Add to cart</button></div><p v-if="notice" class="mt-4 rounded-md bg-falaq-50 px-4 py-3 text-sm text-falaq-700">{{ notice }}</p><div class="mt-8 border-t border-slate-100 pt-6"><h2 class="font-display text-xl font-bold text-falaq-900">Product details</h2><div class="prose prose-sm mt-3 max-w-none text-slate-600" v-html="product.description"></div></div></div></div>
                <section v-if="related.length" class="mt-14 border-t border-slate-100 pt-10"><div class="mb-5 flex items-end justify-between"><div><p class="text-xs font-bold uppercase tracking-[.2em] text-falaq-500">You may also like</p><h2 class="mt-2 font-display text-2xl font-bold text-falaq-900">Related products</h2></div></div><div class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-5"><article v-for="item in related" :key="item.id" class="overflow-hidden rounded-md border border-slate-100 bg-white"><a :href="item.href" class="product-card-image block"><img :src="item.image" :alt="item.name" class="h-full w-full object-cover"></a><div class="p-3"><a :href="item.href" class="line-clamp-2 min-h-10 text-sm font-bold text-falaq-900">{{ item.name }}</a><p class="mt-2 text-sm font-bold text-falaq-700">{{ item.price }}</p></div></article></div></section>
            </template>
        </main>
        <StorefrontFooter />
        <MobileBottomNav :cart-count="cartCount" />
    </div>
</template>
