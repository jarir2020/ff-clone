<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
    search: { type: String, default: '' },
    variant: { type: String, default: 'desktop' },
});

const emit = defineEmits(['update:search', 'submit']);

const placeholderPhrases = ['Search Honey', 'Search Nuts', 'Search Dates', 'Search Mustard Oil'];
const animatedPlaceholder = ref(placeholderPhrases[0]);
const placeholderPhraseIndex = ref(0);
const placeholderCharacterIndex = ref(placeholderPhrases[0].length);
const deletingPlaceholder = ref(true);
const searchFocused = ref(false);
const search = computed(() => props.search);
let placeholderTimer;

function getCommonPrefixLength(current, next) {
    const length = Math.min(current.length, next.length);
    let index = 0;
    while (index < length && current[index] === next[index]) index += 1;
    return index;
}

function schedulePlaceholderTick(delay) {
    window.clearTimeout(placeholderTimer);
    placeholderTimer = window.setTimeout(tickPlaceholder, delay);
}

function tickPlaceholder() {
    const current = placeholderPhrases[placeholderPhraseIndex.value] || 'Search products';
    const nextIndex = (placeholderPhraseIndex.value + 1) % placeholderPhrases.length;
    const next = placeholderPhrases[nextIndex] || current;

    if (search.value || searchFocused.value) {
        schedulePlaceholderTick(200);
        return;
    }

    if (deletingPlaceholder.value) {
        const commonPrefixLength = getCommonPrefixLength(current, next);
        if (placeholderCharacterIndex.value > commonPrefixLength) {
            placeholderCharacterIndex.value -= 1;
            animatedPlaceholder.value = current.slice(0, placeholderCharacterIndex.value);
            schedulePlaceholderTick(55);
            return;
        }
        deletingPlaceholder.value = false;
        placeholderPhraseIndex.value = nextIndex;
        schedulePlaceholderTick(120);
        return;
    }

    placeholderCharacterIndex.value = Math.min(placeholderCharacterIndex.value + 1, current.length);
    animatedPlaceholder.value = current.slice(0, placeholderCharacterIndex.value);
    if (placeholderCharacterIndex.value === current.length) {
        deletingPlaceholder.value = true;
        schedulePlaceholderTick(1400);
        return;
    }
    schedulePlaceholderTick(95);
}

function handleFocus() {
    searchFocused.value = true;
}

function handleBlur() {
    searchFocused.value = false;
    schedulePlaceholderTick(120);
}

onMounted(() => schedulePlaceholderTick(1400));
onUnmounted(() => window.clearTimeout(placeholderTimer));
</script>

<template>
    <form :class="variant === 'desktop' ? 'relative hidden min-w-0 flex-1 lg:block' : 'container-falaq relative pb-3 lg:hidden'" @submit.prevent="emit('submit')">
        <span v-if="!search && !searchFocused" aria-hidden="true" :class="variant === 'desktop' ? 'pointer-events-none absolute left-4 top-1/2 inline-flex max-w-[calc(100%-5rem)] -translate-y-1/2 items-center overflow-hidden whitespace-nowrap text-sm leading-5 text-[#919eab]' : 'pointer-events-none absolute left-4 top-5 inline-flex max-w-[calc(100%-5rem)] -translate-y-1/2 items-center overflow-hidden whitespace-nowrap text-sm leading-[22px] text-[#919eab]'">
            <span>{{ animatedPlaceholder }}</span><span class="header-search-caret ml-px inline-block h-[18px] w-px shrink-0 bg-falaq-500"></span>
        </span>
        <input :value="search" type="search" aria-label="Search products" :placeholder="searchFocused ? 'Search products' : ''" :class="variant === 'desktop' ? 'h-[45px] w-full rounded-full border border-falaq-500 bg-white px-4 pr-16 text-sm outline-none transition focus:ring-2 focus:ring-falaq-100' : 'h-10 w-full rounded-full border border-falaq-500 bg-white px-4 pr-16 text-sm outline-none focus:ring-2 focus:ring-falaq-100'" @input="emit('update:search', $event.target.value)" @focus="handleFocus" @blur="handleBlur">
        <button :class="variant === 'desktop' ? 'absolute right-1 top-1/2 grid h-8 w-8 -translate-y-1/2 place-items-center rounded-full bg-slate-100 text-slate-500' : 'absolute right-1 top-1 grid h-8 w-8 place-items-center rounded-full bg-slate-100 text-slate-500'" aria-label="Submit search"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg></button>
    </form>
</template>

<style scoped>
.header-search-caret { animation: header-search-caret .85s step-end infinite; }
@keyframes header-search-caret { 0%, 49% { opacity: 1; } 50%, 100% { opacity: 0; } }
</style>
