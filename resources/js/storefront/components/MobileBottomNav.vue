<script setup>
defineProps({ cartCount: { type: Number, default: 0 } });
</script>

<template>
    <nav class="fixed bottom-0 left-0 right-0 z-[99] flex h-[78px] items-center justify-around border-t border-slate-200 bg-white/95 px-2 pb-[env(safe-area-inset-bottom)] shadow-[0_-8px_24px_rgba(0,0,0,.06)] backdrop-blur md:hidden"><a href="/" class="flex flex-col items-center gap-1 text-[10px] font-medium text-falaq-700"><span class="text-lg">⌂</span>Home</a><a href="/shop" class="flex flex-col items-center gap-1 text-[10px] font-medium text-slate-500"><span class="text-lg">⌕</span>Categories</a><a href="/cart" class="relative flex flex-col items-center gap-1 text-[10px] font-medium text-slate-500"><span class="text-lg">🛒</span>Cart<span v-if="cartCount" class="absolute -right-2 -top-1 grid h-4 min-w-4 place-items-center rounded-full bg-falaq-500 px-1 text-[9px] text-white">{{ cartCount }}</span></a><a href="/account/login" class="flex flex-col items-center gap-1 text-[10px] font-medium text-slate-500"><span class="text-lg">♙</span>Sign in</a></nav>
</template>
