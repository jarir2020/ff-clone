<script setup>
import StorefrontAppPhase4 from './StorefrontAppPhase4.vue';
import StorefrontHomePage from './StorefrontHomePage.vue';
import StorefrontCartPage from './StorefrontCartPage.vue';
import StorefrontCatalogPage from './StorefrontCatalogPage.vue';
import StorefrontCheckoutPage from './StorefrontCheckoutPage.vue';
import StorefrontProductPage from './StorefrontProductPage.vue';

const path = window.location.pathname;
const view = path === '/' ? 'home'
    : path === '/cart'
    ? 'cart'
    : path === '/checkout'
        ? 'checkout'
        : path === '/shop' || path === '/offers' || path.startsWith('/category/')
            ? 'catalog'
            : path.startsWith('/product/')
                ? 'product'
                : 'content';
</script>

<template>
    <StorefrontHomePage v-if="view === 'home'" />
    <StorefrontCartPage v-else-if="view === 'cart'" />
    <StorefrontCheckoutPage v-else-if="view === 'checkout'" />
    <StorefrontCatalogPage v-else-if="view === 'catalog'" />
    <StorefrontProductPage v-else-if="view === 'product'" />
    <StorefrontAppPhase4 v-else />
</template>
