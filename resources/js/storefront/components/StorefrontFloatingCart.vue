<script setup>
defineProps({ cartCount: { type: Number, default: 0 } });
</script>

<template>
    <a href="/cart" aria-label="View cart, 0 items, total ৳ 0" class="floating-cart-clone fixed right-0 top-[324px] z-40 hidden h-[100px] w-[100px] overflow-hidden rounded-[12px] border border-[#dfe3e8] bg-white text-[#919eab] shadow-[0_24px_48px_rgba(0,0,0,.16)] transition hover:text-[#159758] lg:flex lg:flex-col">
        <span class="absolute left-1/2 top-6 grid h-8 w-8 -translate-x-1/2 place-items-center">
            <svg viewBox="0 0 20 21" fill="none" aria-hidden="true" class="h-8 w-8"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 7C12 7.55 11.55 8 11 8C10.45 8 10 7.55 10 7V5H8C7.45 5 7 4.55 7 4C7 3.45 7.45 3 8 3H10V1C10 0.45 10.45 0 11 0C11.55 0 12 0.45 12 1V3H14C14.55 3 15 3.45 15 4C15 4.55 14.55 5 14 5H12V7ZM4.01 19C4.01 17.9 4.9 17 6 17C7.1 17 8 17.9 8 19C8 20.1 7.1 21 6 21C4.9 21 4.01 20.1 4.01 19ZM16 17C14.9 17 14.01 17.9 14.01 19C14.01 20.1 14.9 21 16 21C17.1 21 18 20.1 18 19C18 17.9 17.1 17 16 17ZM14.55 12H7.1L6 14H17C17.55 14 18 14.45 18 15C18 15.55 17.55 16 17 16H6C4.48 16 3.52 14.37 4.25 13.03L5.6 10.59L2 3H1C0.45 3 0 2.55 0 2C0 1.45 0.45 1 1 1H2.64C3.02 1 3.38 1.22 3.54 1.57L7.53 10H14.55L17.94 3.87C18.2 3.39 18.81 3.22 19.29 3.48C19.77 3.75 19.95 4.36 19.68 4.84L16.3 10.97C15.96 11.59 15.3 12 14.55 12Z" fill="currentColor"></path></svg>
            <span v-if="cartCount" class="absolute -right-2 -top-2 grid h-5 min-w-5 place-items-center rounded-[10px] bg-[#ff4842] px-1.5 text-[12px] font-bold leading-[19px] text-white">{{ cartCount }}</span>
        </span>
        <span class="absolute bottom-0 left-0 right-0 flex h-[33px] items-center justify-center rounded-[0_0_12px_12px] bg-[#159758] px-2 text-base font-semibold leading-6 text-white">৳ 0</span>
    </a>
</template>

<style scoped>
@media (min-width: 992px) and (max-height: 640px) {
    .floating-cart-clone { top: 50%; transform: translateY(-50%); }
}
</style>
