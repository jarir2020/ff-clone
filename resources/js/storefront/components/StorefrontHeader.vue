<script setup>
import StorefrontAnimatedSearch from "./StorefrontAnimatedSearch.vue";
defineProps({
    cartCount: { type: Number, default: 0 },
    searchOpen: { type: Boolean, default: false },
    search: { type: String, default: '' },
});

const emit = defineEmits(['update:search', 'submit-search', 'open-menu', 'toggle-search']);
const updateSearch = (value) => emit("update:search", value);
const submitSearch = () => emit("submit-search");
</script>

<template>
    <div class="contents">

        <header class="border-b border-slate-100 bg-white">
            <div class="container-falaq grid h-[76px] grid-cols-[auto_1fr_auto] items-center gap-3 lg:flex lg:h-20 lg:gap-6">
                <button class="rounded-lg p-2 text-falaq-700 lg:hidden" aria-label="Open menu" @click="emit('open-menu')"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h16M4 12h16M4 18h16" /></svg></button>
                <a href="/" class="flex shrink-0 items-center justify-self-center gap-2 text-falaq-700 lg:justify-self-start" aria-label="Falaq Food home"><img src="https://cdn.falaqfood.com/images/falaq-food-logo.svg" alt="Falaq Food" class="h-7 w-[120px] object-contain lg:h-8 lg:w-[140px]"></a>
                <nav class="hidden shrink-0 items-center gap-4 lg:flex xl:gap-5" aria-label="Main desktop navigation">
                    <a href="/" class="text-[16px] font-semibold leading-6 text-falaq-500 transition hover:text-falaq-700">Home</a>
                    <a href="/blog" class="text-[16px] font-semibold leading-6 text-[#6a7282] transition hover:text-falaq-500">Blog</a>
                    <a href="/shop" class="text-[16px] font-semibold leading-6 text-[#6a7282] transition hover:text-falaq-500">Combos</a>
                    <a href="/offers" class="inline-flex items-center gap-1 text-[16px] font-semibold leading-6 text-[#6a7282] transition hover:text-falaq-500"><span>Offers</span><span class="rounded-full bg-[#fff0f0] px-1.5 py-0.5 text-[10px] font-bold uppercase leading-3.5 tracking-[0.5px] text-[#e7000b]">New</span></a>
                </nav>
                <StorefrontAnimatedSearch variant="desktop" :search="search" v-on:update:search="updateSearch" v-on:submit="submitSearch" />
                <div class="ml-auto flex items-center gap-1 text-falaq-700 md:gap-3"><button class="grid h-10 w-10 place-items-center rounded-full hover:bg-falaq-50 lg:hidden" aria-label="Search" @click="emit('toggle-search')"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg></button><a href="/account/login" class="hidden items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-falaq-50 lg:flex"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="8" r="3.5"/><path d="M5 20c.8-3.5 3-5.2 7-5.2s6.2 1.7 7 5.2"/></svg><span>Sign in</span></a><a href="/cart" class="relative grid h-10 w-10 place-items-center rounded-md hover:bg-falaq-50" aria-label="Cart"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="9" cy="20" r="1"/><circle cx="18" cy="20" r="1"/><path d="M2.5 3h2l2.1 11.3a1.7 1.7 0 0 0 1.7 1.4h8.2a1.7 1.7 0 0 0 1.65-1.3L20.3 7H6"/></svg><span v-if="cartCount" class="absolute -right-0.5 -top-0.5 grid h-4 min-w-4 place-items-center rounded-full bg-falaq-500 px-1 text-[10px] font-bold text-white">{{ cartCount }}</span></a></div>
            </div>
            <StorefrontAnimatedSearch variant="mobile" :search="search" v-on:update:search="updateSearch" v-on:submit="submitSearch" />
        </header>

        <nav class="hidden border-b border-[#f4f6f8] bg-white lg:sticky lg:top-0 lg:z-30 lg:block"><div class="container-falaq h-12"><div class="flex h-full items-center justify-center gap-6 text-[15px] font-semibold text-[#212b36] xl:gap-9"><a class="border-b-2 border-transparent px-0 leading-[46px] transition hover:border-falaq-500 hover:text-falaq-500" href="/category/honey">Honey</a><a class="border-b-2 border-transparent px-0 leading-[46px] transition hover:border-falaq-500 hover:text-falaq-500" href="/category/dry-food">Dry Food</a><a class="border-b-2 border-transparent px-0 leading-[46px] transition hover:border-falaq-500 hover:text-falaq-500" href="/category/mix-food">Mix Food</a><a class="border-b-2 border-transparent px-0 leading-[46px] transition hover:border-falaq-500 hover:text-falaq-500" href="/category/nuts-seeds">Nuts &amp; Seeds</a><a class="border-b-2 border-transparent px-0 leading-[46px] transition hover:border-falaq-500 hover:text-falaq-500" href="/category/pickle">Pickle (আচার)</a><a class="border-b-2 border-transparent px-0 leading-[46px] transition hover:border-falaq-500 hover:text-falaq-500" href="/category/dates-খেজুর">Dates (খেজুর)</a><a class="border-b-2 border-transparent px-0 leading-[46px] transition hover:border-falaq-500 hover:text-falaq-500" href="/category/masala-মসলা-কম্বো">Masala (মসলা কম্বো)</a><a class="border-b-2 border-transparent px-0 leading-[46px] transition hover:border-falaq-500 hover:text-falaq-500" href="/category/tea">Tea</a></div></div></nav>
    </div>
</template>

<script>
export default { inheritAttrs: false };
</script>
