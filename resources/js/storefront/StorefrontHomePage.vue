<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';
import MobileBottomNav from './components/MobileBottomNav.vue';
import MobileDrawer from './components/MobileDrawer.vue';
import StorefrontCloneFooter from './components/StorefrontCloneFooter.vue';
import StorefrontHomeExtras from './StorefrontHomeExtras.vue';
import StorefrontHeader from './components/StorefrontHeader.vue';
import StorefrontHeroBanner from './components/StorefrontHeroBanner.vue';

const menuOpen = ref(false);
const searchOpen = ref(false);
const search = ref('');
const cartCount = ref(0);
const activeSlide = ref(0);
const products = ref([
    { id: 'primal-gold', name: 'Primal Gold - (প্রাইমাল গোল্ড)', price: '৳ 950 – ৳ 1,500', oldPrice: '৳ 1,200', badge: '-21%', image: 'https://cdn.falaqfood.com/uploads/media/2026/08/01a05135-6532-7173-8fce-069b93b0f86a.webp', href: '/product/primal-gold' },
    { id: 'fermented-garlic', name: 'Fermented Garlic & Honey (ফার্মেন্টেড গার্লিক হানি) - 450gm', price: '৳ 780', oldPrice: '৳ 1,000', badge: '-22%', image: 'https://cdn.falaqfood.com/uploads/media/2026/08/01a05704-1d59-7f17-aa56-70f277bc2547.webp', href: '/product/fermented-garlic' },
    { id: 'kalojira-garlic-honey', name: 'রসুনজিরা - (কালোজিরা, রসুন, মধু মিক্স) – 450gm', price: '৳ 750', oldPrice: '৳ 1,200', badge: '-38%', image: 'https://cdn.falaqfood.com/uploads/media/2026/06/019ef93a-f9df-7731-9976-0ba2c308205f.webp', href: '/product/kalojira-garlic-honey' },
    { id: 'shilajit-honey', name: 'শিলাজিৎ মধু (Shilajit Honey) - 1KG', price: '৳ 1,200', oldPrice: '৳ 1,800', badge: '-33%', image: 'https://cdn.falaqfood.com/uploads/media/2026/09/01a07628-b6b6-7c3b-b0c4-86408d538941.webp', href: '/product/shilajit-honey' },
    { id: 'ginger-honey', name: 'জিঞ্জার হানি (Ginger Honey) - 1KG', price: '৳ 850', oldPrice: '৳ 1,200', badge: '-29%', image: 'https://cdn.falaqfood.com/uploads/media/2026/09/01a084ef-ccab-7779-a91c-75eb6a427f9c.webp', href: '/product/ginger-honey' },
    { id: 'flavour-box-honey', name: 'Flavour Box Honey - 2 KG', price: '৳ 1,600', oldPrice: '৳ 2,500', badge: '-36%', image: 'https://cdn.falaqfood.com/uploads/media/2026/09/01a07b04-4d65-7a3c-b9a5-f0778437fb7e.webp', href: '/product/flavour-box-honey' },
    { id: 'mustard-flower-honey', name: 'সরিষা ফুলের মধু ২ কেজি (Mustard Flower Honey 2kg)', price: '৳ 1,050', oldPrice: '৳ 1,400', badge: '-25%', image: 'https://cdn.falaqfood.com/uploads/media/2026/09/01a07b71-b6d0-79f0-8d13-1c31e766f5eb.webp', href: '/product/mustard-flower-honey' },
    { id: 'ghee', name: 'প্রিমিয়াম ঘি (Ghee)', price: '৳ 900 – ৳ 1,650', oldPrice: '৳ 1,050', badge: '-14%', image: 'https://cdn.falaqfood.com/uploads/media/2026/08/01a05158-d193-749f-9eb3-e77f5b9a0bf9.webp', href: '/product/ghee' },
]);

const categories = [
    { name: "Dates (খেজুর)", slug: "dates-খেজুর", image: "https://cdn.falaqfood.com/uploads/media/2026/06/019ea1ea-4b2b-7aae-a6a4-db1d20856681.webp" },
    { name: "Dry Food", slug: "dry-food", image: "https://cdn.falaqfood.com/uploads/media/2026/06/019ea1f4-25d0-74c4-a281-0703d4cd359b.webp" },
    { name: "Falaq Food Special Masala: Farm-Fresh Quality", slug: "masala-মসলা-কম্বো", image: "https://cdn.falaqfood.com/uploads/media/2026/06/019ea1c2-4640-721f-8421-c86962119571.webp" },
    { name: "Honey", slug: "honey", image: "https://cdn.falaqfood.com/uploads/media/2026/06/019ea1f2-c311-77c1-91f0-9999237b9070.webp" },
    { name: "Mix food", slug: "mix-food", image: "https://cdn.falaqfood.com/uploads/media/2026/06/019ea1f7-081a-7e8f-9e7a-9a5981e59bc4.webp" },
    { name: "Nuts & Seeds", slug: "nuts-seeds", image: "https://cdn.falaqfood.com/uploads/media/2026/06/019ea1f7-9568-72a9-9d71-dca7691e9a92.webp" },
    { name: "Pickle (আচার)", slug: "pickle", image: "https://cdn.falaqfood.com/uploads/media/2026/06/019eb517-ff57-7941-8d15-b1cf92b5c18f.webp" },
    { name: "Tea", slug: "tea", image: "https://cdn.falaqfood.com/uploads/media/2026/06/019ea1f7-081a-7e8f-9e7a-9a5981e59bc4.webp" },
];

const slides = [
    { alt: 'Primal Gold', image: 'https://cdn.falaqfood.com/uploads/media/2026/09/01a05bcc-f528-7951-95f1-9d3df069ff6d.webp', href: '/product/primal-gold' },
    { alt: 'Roshunjira', image: 'https://cdn.falaqfood.com/uploads/media/2026/09/01a05d0a-6e5d-7d09-9ab5-ea6f9aa37fc9.webp', href: '/product/kalojira-garlic-honey' },
    { alt: 'Pickles', image: 'https://cdn.falaqfood.com/uploads/media/2026/06/019eb517-ff57-7941-8d15-b1cf92b5c18f.webp', href: '/category/pickle' },
    { alt: 'Shahi Masala', image: 'https://cdn.falaqfood.com/uploads/media/2026/06/019eb115-1925-7d45-ac12-a5368d6da41e.webp', href: '/product/shahi-masala' },
    { alt: 'Ghee', image: 'https://cdn.falaqfood.com/uploads/media/2026/06/019eb576-c138-764d-ae30-d5c3b5b27ca1.webp', href: '/product/ghee' },
    { alt: 'Flavour Box', image: 'https://cdn.falaqfood.com/uploads/media/2026/06/019ec591-88e2-7de5-a8db-43114454351e.webp', href: '/product/flavour-box-honey' },
];

const heroPromos = [
    { alt: "Mustard oil", image: "https://cdn.falaqfood.com/uploads/media/2026/06/019eaae7-e684-7d5b-82ab-94aa2a4143aa.webp", href: "/product/masala-combo" },
    { alt: "Ghee", image: "https://cdn.falaqfood.com/uploads/media/2026/08/019fbc18-f764-7c70-bf91-50dd67e2dcc4.webp", href: "/product/ghee" },
];

const visibleProducts = computed(() => {
    const term = search.value.trim().toLowerCase();
    return term ? products.value.filter((product) => product.name.toLowerCase().includes(term)) : products.value;
});

let carouselTimer;

function submitSearch() {
    if (search.value.trim()) window.location.href = `/search?keyword=${encodeURIComponent(search.value.trim())}`;
}

function nextSlide() {
    activeSlide.value = (activeSlide.value + 1) % slides.length;
}

function previousSlide() {
    activeSlide.value = (activeSlide.value - 1 + slides.length) % slides.length;
}

onMounted(async () => {
    carouselTimer = window.setInterval(nextSlide, 5000);
    try {
        const response = await fetch('/api/v1/storefront/home');
        if (!response.ok) throw new Error(`Storefront API returned ${response.status}`);
        const payload = await response.json();
        if (payload.products?.length) products.value = payload.products;
    } catch (error) {
        console.warn('Using storefront fallback data', error);
    }
});

onUnmounted(() => window.clearInterval(carouselTimer));
</script>

<template>
    <div class="storefront-shell mobile-bottom-space bg-[#f9fafb]">
        <StorefrontHeader v-model:search="search" :cart-count="cartCount" :search-open="searchOpen" @open-menu="menuOpen = true" @toggle-search="searchOpen = !searchOpen" @submit-search="submitSearch" />
        <MobileDrawer :open="menuOpen" @close="menuOpen = false" />

        <main>
            <StorefrontHeroBanner :slides="slides" :promos="heroPromos" :active-slide="activeSlide" @previous="previousSlide" @next="nextSlide" @select="activeSlide = $event" />

            <section class="py-6">
                <div class="container-falaq">
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center justify-between">
                            <h2 class="m-0 font-display text-[17px] font-bold leading-[26px] text-[#212b36] md:text-2xl md:leading-9">Shop by Category</h2>
                            <div class="flex gap-1.5 md:gap-2">
                                <button type="button" aria-label="Previous categories" class="grid h-6 w-6 place-items-center rounded-full bg-white text-falaq-500 shadow-sm md:h-8 md:w-8" @click="$refs.categoryRail.scrollBy({ left: -320, behavior: 'smooth' })">‹</button>
                                <button type="button" aria-label="Next categories" class="grid h-6 w-6 place-items-center rounded-full bg-falaq-500 text-white shadow-sm md:h-8 md:w-8" @click="$refs.categoryRail.scrollBy({ left: 320, behavior: 'smooth' })">›</button>
                            </div>
                        </div>
                        <div ref="categoryRail" aria-label="Shop by Category" class="hide-scrollbar -mx-1 flex gap-4 overflow-x-auto px-1 py-4">
                            <a v-for="category in categories" :key="category.slug" :href="`/category/${category.slug}`" class="group flex h-[110px] w-28 shrink-0 flex-col items-center justify-center gap-2 rounded-xl bg-white p-2 text-center shadow-[0_2px_8px_rgba(145,158,171,.16)] transition hover:-translate-y-0.5 hover:shadow-md md:h-[134px]">
                                <span class="flex h-[70px] w-[70px] shrink-0 items-center justify-center overflow-hidden rounded-md md:h-[92px] md:w-[92px]"><img :src="category.image" :alt="category.name" class="h-full w-full object-cover object-center transition-transform duration-200 group-hover:scale-105" loading="lazy"></span>
                                <span class="line-clamp-1 max-w-full px-1 text-center text-[11px] font-bold uppercase leading-4 text-[#454f5b] md:text-xs">{{ category.name }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="pb-6">
                <div class="container-falaq">
                    <h2 class="section-title-live">Featured Products</h2>
                    <div class="grid grid-cols-2 gap-3 sm:gap-6 lg:grid-cols-4">
                        <article v-for="product in visibleProducts" :key="product.id || product.name" class="group relative flex h-full min-h-0 cursor-pointer flex-col overflow-hidden rounded-2xl border border-[#dfe3e8] bg-white p-4 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md">
                            <a :href="product.href" class="relative aspect-square w-full shrink-0 overflow-hidden rounded-t-2xl bg-white">
                                <img :src="product.image" :alt="product.name" class="h-full w-full object-contain transition-transform duration-300 ease-out group-hover:scale-105" loading="lazy">
                                <span v-if="product.badge" class="absolute right-2 top-2 rounded-full bg-falaq-500 px-2 py-1 text-[10px] font-bold text-white">{{ product.badge }}</span>
                            </a>
                            <div class="flex flex-1 flex-col pt-3">
                                <h3 class="line-clamp-2 min-h-10 font-display text-sm font-bold leading-5 text-[#212b36] md:text-base">{{ product.name }}</h3>
                                <div class="mt-auto flex items-end justify-between gap-2 pt-3">
                                    <div class="min-w-0"><span class="font-bold text-[#159758]">{{ product.price }}</span><del v-if="product.oldPrice" class="ml-1 text-[10px] text-[#919eab]">{{ product.oldPrice }}</del></div>
                                    <button type="button" aria-label="Add to cart" class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-[#f0faf4] text-lg leading-none text-[#159758] transition hover:bg-[#159758] hover:text-white" @click.prevent="cartCount += 1">+</button>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </section>
        <StorefrontHomeExtras :cart-count="cartCount" />
        </main>

        <StorefrontCloneFooter />
        <MobileBottomNav :cart-count="cartCount" />
    </div>
</template>
