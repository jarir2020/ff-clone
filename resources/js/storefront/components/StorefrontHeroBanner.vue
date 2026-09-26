<script setup>
defineProps({
    slides: { type: Array, required: true },
    promos: { type: Array, required: true },
    activeSlide: { type: Number, default: 0 },
});

const emit = defineEmits(['previous', 'next', 'select']);
</script>

<template>
    <section class="py-0">
        <div class="container-falaq">
            <div class="mx-auto mt-6 flex w-full flex-col gap-4 lg:flex-row lg:items-stretch lg:gap-5">
                <div class="relative aspect-[2/1] w-full touch-pan-y overflow-hidden rounded-2xl bg-[#e8f4ed] lg:w-[66.41%] lg:min-w-0 lg:shrink-0">
                    <a v-for="(slide, index) in slides" :key="slide.image" :href="slide.href" class="absolute inset-0 block rounded-2xl transition-opacity duration-500 ease-out" :class="index === activeSlide ? 'pointer-events-auto opacity-100' : 'pointer-events-none opacity-0'" :aria-hidden="index !== activeSlide">
                        <img :src="slide.image" :alt="slide.alt" class="block h-full w-full rounded-2xl object-cover" :loading="index === 0 ? 'eager' : 'lazy'">
                    </a>

                    <div class="pointer-events-none absolute inset-y-0 left-3 right-3 z-20 flex items-center justify-between lg:left-6 lg:right-6">
                        <button type="button" aria-label="Previous hero slide" class="pointer-events-auto grid h-7 w-7 place-items-center rounded-full bg-white text-falaq-500 shadow-sm transition hover:scale-105 lg:h-10 lg:w-10" @click="emit('previous')">‹</button>
                        <button type="button" aria-label="Next hero slide" class="pointer-events-auto grid h-7 w-7 place-items-center rounded-full bg-white text-falaq-500 shadow-sm transition hover:scale-105 lg:h-10 lg:w-10" @click="emit('next')">›</button>
                    </div>

                    <div class="pointer-events-none absolute bottom-3 left-1/2 z-20 flex -translate-x-1/2 items-center justify-center gap-1.5 lg:bottom-4">
                        <button v-for="(_, index) in slides" :key="index" type="button" :aria-label="'Go to slide ' + (index + 1)" class="pointer-events-auto h-1.5 shrink-0 rounded-full transition-all lg:h-2" :class="index === activeSlide ? 'w-6 bg-[#159758] lg:w-10' : 'w-1.5 bg-[#e8f5ee] lg:w-2'" @click="emit('select', index)"></button>
                    </div>
                </div>

                <div class="grid w-full grid-cols-2 gap-4 lg:flex lg:w-[32%] lg:min-w-[280px] lg:flex-col lg:gap-5">
                    <a v-for="promo in promos" :key="promo.image" :href="promo.href" class="block aspect-[41/20] min-h-0 overflow-hidden rounded-2xl lg:flex-1 lg:aspect-auto">
                        <img :src="promo.image" :alt="promo.alt" class="block h-full w-full rounded-2xl object-cover transition-transform duration-300 hover:scale-[1.02]" loading="lazy">
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>
