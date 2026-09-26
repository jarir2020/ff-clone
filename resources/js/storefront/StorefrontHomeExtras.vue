<script setup>
import StorefrontFloatingCart from './components/StorefrontFloatingCart.vue';

defineProps({ cartCount: { type: Number, default: 0 } });

const combos = [
    { name: 'Honey Wellness Combo', text: 'প্রতিদিনের সুস্থতার জন্য মধু ও প্রাকৃতিক খাবারের যত্নশীল কম্বো।', image: 'https://cdn.falaqfood.com/uploads/media/2026/09/01a05d0a-6e5d-7d09-9ab5-ea6f9aa37fc9.webp', href: '/product/kalojira-garlic-honey' },
    { name: 'Falaq Food Special Masala', text: 'রান্নাঘরের জন্য বাছাই করা মসলা ও ঘরোয়া স্বাদের আয়োজন।', image: 'https://cdn.falaqfood.com/uploads/media/2026/06/019eb115-1925-7d45-ac12-a5368d6da41e.webp', href: '/product/shahi-masala' },
    { name: 'Natural Ghee Combo', text: 'বিশুদ্ধ ঘি ও পুষ্টিকর খাবার দিয়ে পরিবারের পছন্দের প্যাক।', image: 'https://cdn.falaqfood.com/uploads/media/2026/06/019eb576-c138-764d-ae30-d5c3b5b27ca1.webp', href: '/product/ghee' },
];

const offers = [
    { name: 'Primal Gold', price: '৳ 950 – ৳ 1,500', oldPrice: '৳ 1,200', badge: 'Best seller', image: 'https://cdn.falaqfood.com/uploads/media/2026/08/01a05135-6532-7173-8fce-069b93b0f86a.webp', href: '/product/primal-gold' },
    { name: 'Fermented Garlic & Honey', price: '৳ 780', oldPrice: '৳ 1,000', badge: '22% off', image: 'https://cdn.falaqfood.com/uploads/media/2026/08/01a05704-1d59-7f17-aa56-70f277bc2547.webp', href: '/product/fermented-garlic' },
    { name: 'Flavour Box Honey - 2 KG', price: '৳ 1,600', oldPrice: '৳ 2,500', badge: '36% off', image: 'https://cdn.falaqfood.com/uploads/media/2026/09/01a07b04-4d65-7a3c-b9a5-f0778437fb7e.webp', href: '/product/flavour-box-honey' },
    { name: 'Mustard Flower Honey - 2 KG', price: '৳ 1,050', oldPrice: '৳ 1,400', badge: '25% off', image: 'https://cdn.falaqfood.com/uploads/media/2026/09/01a07b71-b6d0-79f0-8d13-1c31e766f5eb.webp', href: '/product/mustard-flower-honey' },
];

const reviews = [
    { name: 'Nusrat Jahan', city: 'Dhaka', text: 'প্রোডাক্টের মান খুব ভালো, প্যাকেজিংও সুন্দর ছিল। আবার অর্ডার করব ইনশাআল্লাহ।' },
    { name: 'Sakib Hasan', city: 'Chattogram', text: 'মধুর স্বাদ একদম প্রাকৃতিক। ডেলিভারি সময়মতো পেয়েছি এবং সাপোর্ট টিম খুব helpful।' },
    { name: 'Rumana Akter', city: 'Dhaka', text: 'ফ্যামিলির জন্য নিয়মিত ফালাक़ ফুডের পণ্য নিচ্ছি। কোয়ালিটি ও সার্ভিস দুটোই সন্তোষজনক।' },
];

const blogs = [
    { title: 'Why choose natural food for everyday wellbeing?', text: 'Small ingredient choices can make everyday meals more thoughtful.', image: 'https://cdn.falaqfood.com/uploads/media/2026/08/01a05135-6532-7173-8fce-069b93b0f86a.webp', href: '/blog/why-choose-natural-food' },
    { title: 'A simple guide to choosing pure honey', text: 'Know what to look for when bringing trusted honey into your pantry.', image: 'https://cdn.falaqfood.com/uploads/media/2026/09/01a076a2-31c7-7b88-b0e7-b1234b0bf8c7.webp', href: '/blog' },
    { title: 'Healthy pantry essentials for every home', text: 'Build an everyday pantry with carefully sourced natural food.', image: 'https://cdn.falaqfood.com/uploads/media/2026/08/01a05158-d193-749f-9eb3-e77f5b9a0bf9.webp', href: '/blog' },
];

const videos = [
    { title: 'Discover the Falaq Food difference', image: 'https://cdn.falaqfood.com/uploads/media/2026/09/01a05bcc-f528-7951-95f1-9d3df069ff6d.webp' },
    { title: 'From nature to your family table', image: 'https://cdn.falaqfood.com/uploads/media/2026/06/019eb517-ff57-7941-8d15-b1cf92b5c18f.webp' },
    { title: 'Pure ingredients, honest living', image: 'https://cdn.falaqfood.com/uploads/media/2026/06/019eaae7-e684-7d5b-82ab-94aa2a4143aa.webp' },
];
</script>

<template>
    <div>
        <section class="container-falaq py-8 md:py-12">
            <div class="mb-5 flex items-end justify-between gap-4"><div><p class="section-kicker">Curated for you</p><h2 class="section-heading">Combos</h2></div><a href="/shop" class="section-link">View all →</a></div>
            <div class="grid gap-4 md:grid-cols-3">
                <a v-for="combo in combos" :key="combo.name" :href="combo.href" class="group relative min-h-[220px] overflow-hidden rounded-2xl bg-[#eaf6ef] p-5 shadow-sm"><img :src="combo.image" :alt="combo.name" class="absolute inset-0 h-full w-full object-cover opacity-75 transition duration-500 group-hover:scale-105"><div class="absolute inset-0 bg-gradient-to-r from-[#063d25]/80 via-[#063d25]/35 to-transparent"></div><div class="relative z-10 flex h-full max-w-[260px] flex-col justify-end text-white"><h3 class="font-display text-xl font-bold">{{ combo.name }}</h3><p class="mt-2 text-sm leading-5 text-white/85">{{ combo.text }}</p><span class="mt-4 text-xs font-bold uppercase tracking-wide">Shop combo →</span></div></a>
            </div>
        </section>

        <section class="bg-[#f1f8f5] py-8 md:py-12">
            <div class="container-falaq"><div class="mb-5 flex items-end justify-between gap-4"><div><p class="section-kicker">Limited-time savings</p><h2 class="section-heading">Offers</h2></div><a href="/offers" class="section-link">View all offers →</a></div><div class="grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-4"><article v-for="offer in offers" :key="offer.name" class="group overflow-hidden rounded-2xl border border-[#dfe3e8] bg-white p-3 shadow-sm sm:p-4"><a :href="offer.href" class="relative block aspect-square overflow-hidden rounded-xl"><img :src="offer.image" :alt="offer.name" class="h-full w-full object-contain transition duration-300 group-hover:scale-105"><span class="absolute left-2 top-2 rounded-full bg-[#159758] px-2 py-1 text-[10px] font-bold text-white">{{ offer.badge }}</span></a><div class="pt-3"><h3 class="line-clamp-2 min-h-10 text-sm font-bold leading-5 text-[#212b36]">{{ offer.name }}</h3><p class="mt-2 font-bold text-[#159758]">{{ offer.price }} <del class="ml-1 text-[10px] font-normal text-[#919eab]">{{ offer.oldPrice }}</del></p></div></article></div></div>
        </section>

        <section class="container-falaq py-8 md:py-12"><div class="mb-5 flex items-end justify-between gap-4"><div><p class="section-kicker">Loved by our customers</p><h2 class="section-heading">Customer Reviews</h2></div><span class="text-sm font-semibold text-[#637381]">★★★★★ 4.9/5</span></div><div class="grid gap-4 md:grid-cols-3"><article v-for="review in reviews" :key="review.name" class="rounded-2xl border border-[#dfe3e8] bg-white p-5 shadow-sm"><div class="text-lg tracking-[.15em] text-[#f59e0b]">★★★★★</div><p class="mt-3 min-h-[72px] text-sm leading-6 text-[#637381]">“{{ review.text }}”</p><div class="mt-4 border-t border-[#f0f2f4] pt-3"><p class="m-0 text-sm font-bold text-[#212b36]">{{ review.name }}</p><p class="m-0 text-xs text-[#919eab]">{{ review.city }} · Verified customer</p></div></article></div></section>

        <section class="bg-[#f9fafb] py-8 md:py-12"><div class="container-falaq"><div class="mb-5 flex items-end justify-between gap-4"><div><p class="section-kicker">Watch and learn</p><h2 class="section-heading">Video Gallery</h2></div><a href="https://www.youtube.com/@falaqfood" target="_blank" rel="noopener" class="section-link">Visit YouTube →</a></div><div class="grid gap-4 md:grid-cols-3"><a v-for="video in videos" :key="video.title" href="https://www.youtube.com/@falaqfood" target="_blank" rel="noopener" class="group relative aspect-video overflow-hidden rounded-2xl bg-[#dfeee4]"><img :src="video.image" :alt="video.title" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"><div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div><span class="absolute left-1/2 top-1/2 grid h-12 w-12 -translate-x-1/2 -translate-y-1/2 place-items-center rounded-full bg-white text-[#159758] shadow-lg">▶</span><h3 class="absolute bottom-4 left-4 right-4 font-display text-lg font-bold text-white">{{ video.title }}</h3></a></div></div></section>

        <section class="container-falaq py-8 md:py-12"><div class="mb-5 flex items-end justify-between gap-4"><div><p class="section-kicker">Falaq Food Journal</p><h2 class="section-heading">From Our Blog</h2></div><a href="/blog" class="section-link">View all articles →</a></div><div class="grid gap-4 md:grid-cols-3"><article v-for="blog in blogs" :key="blog.title" class="overflow-hidden rounded-2xl border border-[#dfe3e8] bg-white shadow-sm"><a :href="blog.href" class="block aspect-[1.8/1] overflow-hidden"><img :src="blog.image" :alt="blog.title" class="h-full w-full object-cover transition duration-500 hover:scale-105"></a><div class="p-5"><p class="text-xs font-bold uppercase tracking-wide text-[#159758]">Falaq Food Journal</p><h3 class="mt-2 font-display text-xl font-bold leading-7 text-[#212b36]">{{ blog.title }}</h3><p class="mt-2 text-sm leading-6 text-[#637381]">{{ blog.text }}</p><a :href="blog.href" class="mt-4 inline-flex text-sm font-bold text-[#159758]">Read article →</a></div></article></div></section>

        <section class="container-falaq py-8 md:py-12" aria-labelledby="business-meeting-title">
            <div class="relative overflow-hidden rounded-2xl bg-[#063d25] px-6 py-10 text-white shadow-[0_12px_30px_rgba(6,61,37,.16)] md:px-12 md:py-14">
                <span class="absolute -right-12 -top-20 h-56 w-56 rounded-full border-[28px] border-white/10"></span>
                <span class="absolute -bottom-24 right-28 h-48 w-48 rounded-full border-[20px] border-[#159758]/30"></span>
                <div class="relative z-10 max-w-2xl">
                    <p class="m-0 text-[11px] font-bold uppercase tracking-[.16em] text-[#9de0b9]">For business and corporate orders</p>
                    <h2 id="business-meeting-title" class="mt-3 font-display text-2xl font-bold leading-tight text-white md:text-4xl">Join 100+ Companies That Trust Falaq Food</h2>
                    <p class="mt-3 text-base text-white/80 md:text-lg">Let&#39;s schedule a meeting</p>
                    <a href="/contact-us" class="mt-6 inline-flex items-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-bold text-[#063d25] transition hover:-translate-y-0.5 hover:bg-[#eaf6ef]">Schedule a meeting <span aria-hidden="true">→</span></a>
                </div>
            </div>
        </section>

        <a href="https://wa.me/8801765890646?text=" target="_blank" rel="noopener" aria-label="Message us on WhatsApp" class="fixed bottom-24 right-4 z-50 grid h-14 w-14 place-items-center rounded-full bg-gradient-to-br from-[#25d366] to-[#128c7e] text-2xl text-white shadow-[0_4px_14px_rgba(37,211,102,.4)] transition hover:scale-110 lg:bottom-5 lg:right-5"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" class="h-7 w-7"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.198-.347.223-.644.075-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg></a>
        <StorefrontFloatingCart :cart-count="cartCount" />
    </div>
</template>

<style scoped>
.section-kicker { margin: 0; color: #159758; font-size: 11px; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; }
.section-heading { margin: 4px 0 0; color: #212b36; font-family: var(--font-secondary); font-size: 28px; font-weight: 600; line-height: 1.3; }
.section-link { color: #159758; font-size: 13px; font-weight: 700; white-space: nowrap; }
@media (max-width: 767px) { .section-heading { font-size: 23px; } }
</style>
