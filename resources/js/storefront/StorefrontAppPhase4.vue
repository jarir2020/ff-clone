<script setup>
import StorefrontAppPhase3 from './StorefrontAppPhase3.vue';
import StorefrontContentPage from './StorefrontContentPage.vue';

const routeMap = {
    '/about-us': 'about',
    '/blog': 'blog',
    '/contact-us': 'contact',
    '/offers': 'offers',
    '/corporate-deal': 'corporate',
};

const kind = routeMap[window.location.pathname];
</script>

<template>
    <StorefrontContentPage v-if="kind" :kind="kind" />
    <StorefrontAppPhase3 v-else />
</template>
