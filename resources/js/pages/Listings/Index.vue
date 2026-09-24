<script setup lang="ts">
defineOptions({ layout: null });

import { ref, computed } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { home, login } from "@/routes";
import {
    Search,
    ShieldCheck,
    CheckCircle2,
    Sparkles,
    Flame,
    X,
    ExternalLink,
    Lock,
    Users,
    ChevronRight,
    Crosshair,
    Calendar,
    Award,
    Eye,
    ArrowLeft,
    SlidersHorizontal,
    RotateCcw,
} from "@lucide/vue";

interface Moderator {
    id: number;
    name: string;
    username: string;
    phone?: string;
    whatsapp_number?: string;
    is_active: boolean;
}

interface ListingImage {
    id: number;
    listing_id: number;
    image_path: string;
    sort_order: number;
    is_cover: boolean;
}

interface Listing {
    id: number;
    title: string;
    slug: string;
    uid: string;
    level: number;
    account_age: string;
    price: number;
    description: string;
    character_count: number;
    gun_skin_count: number;
    elite_pass_count: number;
    rare_item_count: number;
    status: "available" | "sold" | "hidden";
    is_featured: boolean;
    assigned_to: number;
    created_at: string;
    moderator?: Moderator;
    images?: ListingImage[];
    cover_image?: ListingImage;
    whatsapp_cta_url?: string;
}

const props = withDefaults(
    defineProps<{
        listings?: Listing[];
        stats?: {
            totalAvailable: number;
            filteredCount: number;
        };
        filters?: {
            search?: string;
            category?: string;
            price?: string;
            sort?: string;
        };
    }>(),
    {
        listings: () => [],
        stats: () => ({ totalAvailable: 0, filteredCount: 0 }),
        filters: () => ({}),
    },
);

// Filter States
const searchQuery = ref(props.filters?.search || "");
const selectedCategory = ref(props.filters?.category || "all");
const selectedPrice = ref(props.filters?.price || "all");
const selectedSort = ref(props.filters?.sort || "newest");

const categories = [
    {
        id: "all",
        label: "সব আইডি (All)",
        count: props.stats?.totalAvailable || 0,
    },
    { id: "evo", label: "ইভো গান (Evo Guns)", badge: "Evo Max" },
    { id: "old", label: "ওল্ড সিজন (Old Season)", badge: "S1-S10" },
    { id: "high_level", label: "৭০+ লেভেল (High Level)", badge: "Pro Rank" },
    { id: "budget", label: "বাজেট ফ্রেন্ডলি (Budget)", badge: "≤ ৳৫,০০০" },
];

const priceRanges = [
    { id: "all", label: "সকল প্রাইস রেঞ্জ" },
    { id: "under_2k", label: "৳২,০০০ এর নিচে" },
    { id: "2k_5k", label: "৳২,০০০ - ৳৫,০০০" },
    { id: "above_5k", label: "৳৫,০০০ এর বেশি" },
];

const sortOptions = [
    { id: "newest", label: "নতুন আইডি আগে (Newest)" },
    { id: "price_asc", label: "দাম: কম থেকে বেশি (Price Low)" },
    { id: "price_desc", label: "দাম: বেশি থেকে কম (Price High)" },
    { id: "level_desc", label: "লেভেল: বেশি থেকে কম (Level High)" },
];

function applyFilters() {
    const params: Record<string, string> = {};
    if (searchQuery.value) params.search = searchQuery.value;
    if (selectedCategory.value && selectedCategory.value !== "all")
        params.category = selectedCategory.value;
    if (selectedPrice.value && selectedPrice.value !== "all")
        params.price = selectedPrice.value;
    if (selectedSort.value && selectedSort.value !== "newest")
        params.sort = selectedSort.value;

    router.get("/listings", params, {
        preserveState: true,
        preserveScroll: true,
    });
}

function handleSearch(e: Event) {
    e.preventDefault();
    applyFilters();
}

function setCategory(id: string) {
    selectedCategory.value = id;
    applyFilters();
}

function resetAllFilters() {
    searchQuery.value = "";
    selectedCategory.value = "all";
    selectedPrice.value = "all";
    selectedSort.value = "newest";
    router.get("/listings");
}

// Modal State
const activeListing = ref<Listing | null>(null);
const activeImageIndex = ref(0);

function openModal(listing: Listing) {
    activeListing.value = listing;
    activeImageIndex.value = 0;
}

function closeModal() {
    activeListing.value = null;
}

function formatPrice(val: number): string {
    return "৳ " + Number(val).toLocaleString("en-US");
}

function getListingCover(listing: Listing): string {
    if (listing.cover_image?.image_path) {
        return listing.cover_image.image_path;
    }
    if (listing.images && listing.images.length > 0) {
        return listing.images[0].image_path;
    }
    return "https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=800&auto=format&fit=crop";
}

function getWhatsAppUrl(listing: Listing): string {
    if (listing.whatsapp_cta_url) {
        return listing.whatsapp_cta_url;
    }
    const phone =
        listing.moderator?.whatsapp_number ||
        listing.moderator?.phone ||
        "8801700000000";
    const cleanPhone = phone.replace(/[^0-9]/g, "");
    const text = encodeURIComponent(
        `Hello FireVault, I am interested in buying Free Fire ID: "${listing.title}" (UID: ${listing.uid}). Price: ৳${listing.price}. Is this account still available?`,
    );
    return `https://wa.me/${cleanPhone}?text=${text}`;
}
</script>

<template>
    <Head title="সব ভেরিফাইড ফ্রি ফায়ার আইডি — FireVault Catalog" />

    <div
        class="min-h-screen bg-[#0F1115] text-[#EDE9DE] font-['Inter',sans-serif] selection:bg-[#E3A339]/30 selection:text-[#E3A339]"
    >
        <!-- TOP NAVIGATION -->
        <header
            class="sticky top-0 z-40 bg-[#0F1115]/95 backdrop-blur-md border-b border-[#2B2F38]"
        >
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between"
            >
                <div class="flex items-center space-x-6">
                    <Link
                        :href="home.url()"
                        class="flex items-center space-x-2.5 group"
                    >
                        <div
                            class="w-8 h-8 rounded-md bg-[#E3A339] flex items-center justify-center text-[#0F1115] font-black text-lg shadow-sm"
                        >
                            <Flame class="w-5 h-5 fill-current" />
                        </div>
                        <div class="flex flex-col">
                            <span
                                class="font-['Sora',sans-serif] font-bold text-lg tracking-tight text-[#EDE9DE] group-hover:text-[#E3A339] transition-colors"
                            >
                                FireVault
                            </span>
                            <span
                                class="text-[10px] text-[#8E93A0] uppercase tracking-wider -mt-1 font-semibold"
                            >
                                Verified Marketplace
                            </span>
                        </div>
                    </Link>

                    <nav
                        class="hidden md:flex items-center space-x-1 pl-4 border-l border-[#2B2F38]"
                    >
                        <Link
                            :href="home.url()"
                            class="px-3 py-1.5 text-xs text-[#8E93A0] hover:text-[#EDE9DE] rounded-md hover:bg-[#181B21] transition-colors"
                        >
                            Home
                        </Link>
                        <span
                            class="px-3 py-1.5 text-xs text-[#E3A339] font-medium bg-[#181B21] rounded-md border border-[#2B2F38]"
                        >
                            All IDs
                        </span>
                        <Link
                            :href="`${home.url()}#how-it-works`"
                            class="px-3 py-1.5 text-xs text-[#8E93A0] hover:text-[#EDE9DE] rounded-md hover:bg-[#181B21] transition-colors"
                        >
                            How It Works
                        </Link>
                        <Link
                            :href="`${home.url()}#scam-alert`"
                            class="px-3 py-1.5 text-xs text-[#8E93A0] hover:text-[#EDE9DE] rounded-md hover:bg-[#181B21] transition-colors"
                        >
                            Scam Alert
                        </Link>
                        <Link
                            :href="`${home.url()}#terms`"
                            class="px-3 py-1.5 text-xs text-[#8E93A0] hover:text-[#EDE9DE] rounded-md hover:bg-[#181B21] transition-colors"
                        >
                            Terms & Rules
                        </Link>
                    </nav>
                </div>

                <div class="flex items-center space-x-3">
                    <Link
                        :href="home.url()"
                        class="hidden sm:inline-flex items-center text-xs text-[#8E93A0] hover:text-[#EDE9DE] transition-colors pr-2"
                    >
                        <ArrowLeft class="w-3.5 h-3.5 mr-1" />
                        Back to Home
                    </Link>

                    <Link
                        :href="login.url()"
                        class="px-3 py-1.5 text-xs text-[#8E93A0] hover:text-[#EDE9DE] border border-[#2B2F38] hover:border-[#8E93A0] rounded-md bg-[#181B21] transition-all"
                    >
                        Staff Login
                    </Link>
                </div>
            </div>
        </header>

        <!-- PAGE HEADER BANNER -->
        <section
            class="border-b border-[#2B2F38] bg-gradient-to-b from-[#181B21]/60 to-[#0F1115] py-8 sm:py-10"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="flex flex-col md:flex-row md:items-end justify-between gap-4"
                >
                    <div>
                        <div
                            class="inline-flex items-center space-x-2 px-2.5 py-1 rounded-md bg-[#3FA79B]/10 border border-[#3FA79B]/20 text-[#3FA79B] text-xs font-medium mb-3"
                        >
                            <ShieldCheck class="w-3.5 h-3.5" />
                            <span
                                >১০০% ভেরিফাইড স্ক্রিনশট ও আইডি লাইভ
                                ক্যাটালগ</span
                            >
                        </div>
                        <h1
                            class="text-2xl sm:text-3xl font-['Sora',sans-serif] font-bold text-[#EDE9DE] tracking-tight"
                        >
                            সব ফ্রি ফায়ার আইডি কালেকশন
                        </h1>
                        <p
                            class="text-sm text-[#8E93A0] mt-1 max-w-2xl font-['Hind_Siliguri',sans-serif]"
                        >
                            আপনার বাজেট ও পছন্দের গান স্কিন অনুযায়ী ফিল্টার
                            করুন। প্রতিটি আইডির ডিল সরাসরি ভেরিফাইড অ্যাডমিনের
                            WhatsApp-এ সম্পন্ন হয়।
                        </p>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div
                            class="px-3.5 py-2 rounded-md bg-[#181B21] border border-[#2B2F38] text-right"
                        >
                            <span class="text-xs text-[#8E93A0] block"
                                >উপলব্ধ আইডি</span
                            >
                            <span
                                class="text-lg font-['Sora',sans-serif] font-bold text-[#3FA79B]"
                            >
                                {{ props.stats.filteredCount }} টি
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- STICKY FILTER BAR -->
        <section
            class="sticky top-16 z-30 bg-[#0F1115]/95 backdrop-blur-md border-b border-[#2B2F38] py-3.5 shadow-sm"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3">
                <!-- Search & Dropdowns Row -->
                <div
                    class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3"
                >
                    <!-- Search Input -->
                    <form @submit="handleSearch" class="flex-1 relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#8E93A0]"
                        >
                            <Search class="w-4 h-4" />
                        </div>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search by Title, UID (e.g. 19283...), or Gun Skin..."
                            class="w-full pl-10 pr-20 py-2 bg-[#181B21] border border-[#2B2F38] focus:border-[#E3A339] focus:ring-1 focus:ring-[#E3A339] rounded-md text-xs sm:text-sm text-[#EDE9DE] placeholder-[#8E93A0] transition-colors"
                        />
                        <button
                            type="submit"
                            class="absolute inset-y-1 right-1 px-3 bg-[#20242C] hover:bg-[#2B2F38] text-xs text-[#EDE9DE] rounded font-medium border border-[#2B2F38] transition-colors"
                        >
                            Search
                        </button>
                    </form>

                    <!-- Price Dropdown -->
                    <div class="flex items-center space-x-2">
                        <select
                            v-model="selectedPrice"
                            @change="applyFilters"
                            aria-label="Filter by Price Range"
                            class="bg-[#181B21] border border-[#2B2F38] text-xs text-[#EDE9DE] rounded-md px-3 py-2 focus:border-[#3FA79B] focus:ring-1 focus:ring-[#3FA79B] transition-colors cursor-pointer"
                        >
                            <option
                                v-for="price in priceRanges"
                                :key="price.id"
                                :value="price.id"
                            >
                                {{ price.label }}
                            </option>
                        </select>

                        <!-- Sort Dropdown -->
                        <select
                            v-model="selectedSort"
                            @change="applyFilters"
                            aria-label="Sort listings"
                            class="bg-[#181B21] border border-[#2B2F38] text-xs text-[#EDE9DE] rounded-md px-3 py-2 focus:border-[#3FA79B] focus:ring-1 focus:ring-[#3FA79B] transition-colors cursor-pointer"
                        >
                            <option
                                v-for="sort in sortOptions"
                                :key="sort.id"
                                :value="sort.id"
                            >
                                {{ sort.label }}
                            </option>
                        </select>

                        <!-- Reset Button -->
                        <button
                            v-if="
                                searchQuery ||
                                selectedCategory !== 'all' ||
                                selectedPrice !== 'all' ||
                                selectedSort !== 'newest'
                            "
                            @click="resetAllFilters"
                            title="ফিল্টার রিসেট করুন"
                            class="p-2 text-[#8E93A0] hover:text-[#EDE9DE] bg-[#181B21] border border-[#2B2F38] hover:border-[#8E93A0] rounded-md transition-colors"
                        >
                            <RotateCcw class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <!-- Category Pills Row -->
                <div
                    class="flex items-center space-x-2 overflow-x-auto pb-1 scrollbar-none text-xs"
                >
                    <button
                        v-for="cat in categories"
                        :key="cat.id"
                        @click="setCategory(cat.id)"
                        :class="[
                            'px-3 py-1.5 rounded-md transition-all whitespace-nowrap font-medium flex items-center space-x-1.5',
                            selectedCategory === cat.id
                                ? 'bg-[#3FA79B] text-[#0F1115] font-semibold shadow-sm'
                                : 'bg-[#181B21] text-[#8E93A0] hover:text-[#EDE9DE] border border-[#2B2F38]',
                        ]"
                    >
                        <span>{{ cat.label }}</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- MAIN MASONRY CATALOG -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <!-- EMPTY STATE -->
            <div
                v-if="!listings || listings.length === 0"
                class="py-20 text-center bg-[#181B21] border border-[#2B2F38] rounded-xl p-8 max-w-lg mx-auto"
            >
                <div
                    class="w-12 h-12 rounded-full bg-[#20242C] flex items-center justify-center mx-auto mb-4 text-[#8E93A0]"
                >
                    <Search class="w-6 h-6" />
                </div>
                <h3
                    class="font-['Sora',sans-serif] font-bold text-lg text-[#EDE9DE]"
                >
                    কোনো আইডি পাওয়া যায়নি
                </h3>
                <p
                    class="text-xs sm:text-sm text-[#8E93A0] mt-1 font-['Hind_Siliguri',sans-serif]"
                >
                    আপনার সার্চ বা ফিল্টারের সাথে মিলে এমন কোনো ফ্রি ফায়ার
                    অ্যাকাউন্ট পাওয়া যায়নি।
                </p>
                <button
                    @click="resetAllFilters"
                    class="mt-5 px-4 py-2 bg-[#E3A339] text-[#0F1115] rounded-md text-xs font-bold hover:bg-[#E3A339]/90 transition-colors"
                >
                    সকল ফিল্টার ক্লিয়ার করুন
                </button>
            </div>

            <!-- PINTEREST STYLE MASONRY GRID -->
            <div
                v-else
                class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-5 [column-fill:_balance]"
            >
                <div
                    v-for="listing in listings"
                    :key="listing.id"
                    class="break-inside-avoid mb-5 group cursor-pointer"
                    @click="openModal(listing)"
                >
                    <div
                        class="relative bg-[#181B21] border border-[#2B2F38] hover:border-[#8E93A0]/60 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1"
                    >
                        <!-- Top Tag Badges -->
                        <div
                            class="absolute top-3 inset-x-3 z-10 flex items-center justify-between pointer-events-none"
                        >
                            <span
                                v-if="listing.is_featured"
                                class="px-2 py-0.5 rounded text-[11px] font-semibold bg-[#E3A339] text-[#0F1115] shadow"
                            >
                                Featured
                            </span>
                            <div v-else></div>

                            <span
                                class="px-2 py-0.5 rounded text-[11px] font-medium bg-[#0F1115]/80 backdrop-blur-sm border border-[#2B2F38] text-[#3FA79B] flex items-center space-x-1 shadow"
                            >
                                <span
                                    class="w-1.5 h-1.5 rounded-full bg-[#3FA79B]"
                                ></span>
                                <span>Available</span>
                            </span>
                        </div>

                        <!-- Natural Aspect Image -->
                        <div class="relative overflow-hidden bg-[#0F1115]">
                            <img
                                :src="getListingCover(listing)"
                                :alt="listing.title"
                                loading="lazy"
                                class="w-full h-auto object-cover transform group-hover:scale-103 transition-transform duration-500 block"
                            />
                            <!-- Gradient Scrim for Legibility -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-[#181B21] via-transparent to-transparent opacity-90"
                            ></div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-4 -mt-6 relative z-10 space-y-3">
                            <!-- Title & UID -->
                            <div>
                                <h3
                                    class="font-['Sora',sans-serif] font-bold text-sm text-[#EDE9DE] group-hover:text-[#E3A339] transition-colors line-clamp-1"
                                >
                                    {{ listing.title }}
                                </h3>
                                <div
                                    class="flex items-center space-x-2 text-[11px] text-[#8E93A0] mt-0.5"
                                >
                                    <span>UID: {{ listing.uid }}</span>
                                    <span>•</span>
                                    <span>{{ listing.account_age }}</span>
                                </div>
                            </div>

                            <!-- Specs Chips Grid -->
                            <div class="grid grid-cols-2 gap-1.5 text-[11px]">
                                <div
                                    class="px-2 py-1 bg-[#20242C] rounded-md border border-[#2B2F38] flex items-center justify-between"
                                >
                                    <span class="text-[#8E93A0]">Level</span>
                                    <span
                                        class="font-['Sora',sans-serif] font-semibold text-[#EDE9DE]"
                                        >{{ listing.level }}</span
                                    >
                                </div>
                                <div
                                    class="px-2 py-1 bg-[#20242C] rounded-md border border-[#2B2F38] flex items-center justify-between"
                                >
                                    <span class="text-[#8E93A0]">Evo Guns</span>
                                    <span
                                        class="font-['Sora',sans-serif] font-semibold text-[#E3A339]"
                                        >{{ listing.gun_skin_count }}</span
                                    >
                                </div>
                                <div
                                    class="px-2 py-1 bg-[#20242C] rounded-md border border-[#2B2F38] flex items-center justify-between"
                                >
                                    <span class="text-[#8E93A0]"
                                        >Elite Pass</span
                                    >
                                    <span
                                        class="font-['Sora',sans-serif] font-semibold text-[#EDE9DE]"
                                        >{{ listing.elite_pass_count }}</span
                                    >
                                </div>
                                <div
                                    class="px-2 py-1 bg-[#20242C] rounded-md border border-[#2B2F38] flex items-center justify-between"
                                >
                                    <span class="text-[#8E93A0]">Chars</span>
                                    <span
                                        class="font-['Sora',sans-serif] font-semibold text-[#EDE9DE]"
                                        >{{ listing.character_count }}</span
                                    >
                                </div>
                            </div>

                            <!-- Price & WhatsApp Action Row -->
                            <div
                                class="pt-2 border-t border-[#2B2F38] flex items-center justify-between"
                            >
                                <div>
                                    <span
                                        class="text-[10px] text-[#8E93A0] block uppercase tracking-wider"
                                        >দাম (Price)</span
                                    >
                                    <span
                                        class="font-['Sora',sans-serif] font-bold text-base text-[#EDE9DE]"
                                    >
                                        {{ formatPrice(listing.price) }}
                                    </span>
                                </div>

                                <a
                                    :href="getWhatsAppUrl(listing)"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    @click.stop
                                    class="px-3 py-1.5 bg-[#E3A339] hover:bg-[#E3A339]/90 text-[#0F1115] text-xs font-bold rounded-md flex items-center space-x-1.5 transition-colors shadow-sm"
                                >
                                    <span>WhatsApp</span>
                                    <ExternalLink class="w-3.5 h-3.5" />
                                </a>
                            </div>

                            <!-- Assigned Moderator Micro-note -->
                            <div
                                class="text-[10px] text-[#8E93A0] flex items-center justify-between pt-0.5"
                            >
                                <span class="flex items-center space-x-1">
                                    <ShieldCheck
                                        class="w-3 h-3 text-[#3FA79B]"
                                    />
                                    <span
                                        >Verified by @{{
                                            listing.moderator?.username ||
                                            "masum"
                                        }}</span
                                    >
                                </span>
                                <span
                                    class="text-[#E3A339] font-medium flex items-center group-hover:translate-x-0.5 transition-transform"
                                >
                                    Details <ChevronRight class="w-3 h-3" />
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- QUICK-VIEW MODAL -->
        <div
            v-if="activeListing"
            class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-black/80 backdrop-blur-sm animate-in fade-in duration-200"
            @click.self="closeModal"
        >
            <div
                class="bg-[#181B21] border border-[#2B2F38] rounded-xl max-w-4xl w-full max-h-[92vh] flex flex-col overflow-hidden shadow-2xl"
            >
                <!-- Modal Header -->
                <div
                    class="px-5 py-4 border-b border-[#2B2F38] flex items-center justify-between bg-[#181B21]"
                >
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-2.5 h-2.5 rounded-full bg-[#3FA79B]"
                        ></div>
                        <h2
                            class="font-['Sora',sans-serif] font-bold text-base sm:text-lg text-[#EDE9DE]"
                        >
                            {{ activeListing.title }}
                        </h2>
                    </div>
                    <button
                        @click="closeModal"
                        class="p-1.5 text-[#8E93A0] hover:text-[#EDE9DE] hover:bg-[#20242C] rounded-md transition-colors"
                    >
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <!-- Modal Body -->
                <div
                    class="flex-1 overflow-y-auto p-5 sm:p-6 grid grid-cols-1 md:grid-cols-12 gap-6"
                >
                    <!-- Left: Gallery (7 Cols) -->
                    <div class="md:col-span-7 space-y-3">
                        <!-- Main Preview Image -->
                        <div
                            class="relative rounded-lg overflow-hidden bg-[#0F1115] border border-[#2B2F38] aspect-video flex items-center justify-center"
                        >
                            <img
                                :src="
                                    activeListing.images &&
                                    activeListing.images.length > 0
                                        ? activeListing.images[activeImageIndex]
                                              ?.image_path
                                        : getListingCover(activeListing)
                                "
                                :alt="activeListing.title"
                                class="w-full h-full object-contain"
                            />
                            <div
                                class="absolute bottom-2 right-2 px-2 py-1 rounded bg-[#0F1115]/80 text-[10px] text-[#EDE9DE] border border-[#2B2F38]"
                            >
                                Image {{ activeImageIndex + 1 }} of
                                {{ activeListing.images?.length || 1 }}
                            </div>
                        </div>

                        <!-- Thumbnails Row -->
                        <div
                            v-if="
                                activeListing.images &&
                                activeListing.images.length > 1
                            "
                            class="flex items-center space-x-2 overflow-x-auto pb-1"
                        >
                            <button
                                v-for="(img, idx) in activeListing.images"
                                :key="img.id"
                                @click="activeImageIndex = idx"
                                :class="[
                                    'w-16 h-12 rounded-md overflow-hidden border shrink-0 transition-all',
                                    activeImageIndex === idx
                                        ? 'border-[#E3A339] ring-2 ring-[#E3A339]/40'
                                        : 'border-[#2B2F38] opacity-60 hover:opacity-100',
                                ]"
                            >
                                <img
                                    :src="img.image_path"
                                    class="w-full h-full object-cover"
                                />
                            </button>
                        </div>

                        <!-- Description Box -->
                        <div
                            class="p-3.5 bg-[#20242C] rounded-lg border border-[#2B2F38] text-xs space-y-1"
                        >
                            <span
                                class="font-semibold text-[#8E93A0] uppercase tracking-wider text-[10px]"
                                >অ্যাকাউন্ট বিবরণ (Details)</span
                            >
                            <p
                                class="text-[#EDE9DE] leading-relaxed font-['Hind_Siliguri',sans-serif]"
                            >
                                {{
                                    activeListing.description ||
                                    "সম্পূর্ণ ফ্রেশ আইডি। কোনো ভুয়া ক্লেইম নেই। স্ক্রিনশটে যা দেখছেন হুবহু তাই পাবেন।"
                                }}
                            </p>
                        </div>
                    </div>

                    <!-- Right: Info & Actions (5 Cols) -->
                    <div
                        class="md:col-span-5 flex flex-col justify-between space-y-5"
                    >
                        <div class="space-y-4">
                            <!-- Price & Status -->
                            <div
                                class="p-4 bg-[#20242C] rounded-lg border border-[#2B2F38] flex items-center justify-between"
                            >
                                <div>
                                    <span
                                        class="text-[11px] text-[#8E93A0] block"
                                        >নির্ধারিত মূল্য (Price)</span
                                    >
                                    <span
                                        class="font-['Sora',sans-serif] font-bold text-2xl text-[#E3A339]"
                                    >
                                        {{ formatPrice(activeListing.price) }}
                                    </span>
                                </div>
                                <span
                                    class="px-2.5 py-1 rounded text-xs font-semibold bg-[#3FA79B]/10 border border-[#3FA79B]/30 text-[#3FA79B] flex items-center space-x-1.5"
                                >
                                    <span
                                        class="w-2 h-2 rounded-full bg-[#3FA79B] animate-pulse"
                                    ></span>
                                    <span>Available</span>
                                </span>
                            </div>

                            <!-- Spec Metrics -->
                            <div class="space-y-2">
                                <span
                                    class="text-xs font-semibold text-[#8E93A0] uppercase tracking-wider"
                                    >স্পেসিফিকেশন</span
                                >
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div
                                        class="p-2.5 bg-[#20242C]/70 rounded-md border border-[#2B2F38]"
                                    >
                                        <span
                                            class="text-[10px] text-[#8E93A0] block"
                                            >UID</span
                                        >
                                        <span
                                            class="font-mono font-bold text-[#EDE9DE] select-all"
                                            >{{ activeListing.uid }}</span
                                        >
                                    </div>
                                    <div
                                        class="p-2.5 bg-[#20242C]/70 rounded-md border border-[#2B2F38]"
                                    >
                                        <span
                                            class="text-[10px] text-[#8E93A0] block"
                                            >লেভেল (Level)</span
                                        >
                                        <span
                                            class="font-bold text-[#EDE9DE]"
                                            >{{ activeListing.level }}</span
                                        >
                                    </div>
                                    <div
                                        class="p-2.5 bg-[#20242C]/70 rounded-md border border-[#2B2F38]"
                                    >
                                        <span
                                            class="text-[10px] text-[#8E93A0] block"
                                            >ইভো গান স্কিন</span
                                        >
                                        <span class="font-bold text-[#E3A339]"
                                            >{{
                                                activeListing.gun_skin_count
                                            }}
                                            টি</span
                                        >
                                    </div>
                                    <div
                                        class="p-2.5 bg-[#20242C]/70 rounded-md border border-[#2B2F38]"
                                    >
                                        <span
                                            class="text-[10px] text-[#8E93A0] block"
                                            >এলিট পাস</span
                                        >
                                        <span class="font-bold text-[#EDE9DE]"
                                            >{{
                                                activeListing.elite_pass_count
                                            }}
                                            টি</span
                                        >
                                    </div>
                                    <div
                                        class="p-2.5 bg-[#20242C]/70 rounded-md border border-[#2B2F38]"
                                    >
                                        <span
                                            class="text-[10px] text-[#8E93A0] block"
                                            >ক্যারেক্টার সংখ্যা</span
                                        >
                                        <span class="font-bold text-[#EDE9DE]"
                                            >{{
                                                activeListing.character_count
                                            }}
                                            টি</span
                                        >
                                    </div>
                                    <div
                                        class="p-2.5 bg-[#20242C]/70 rounded-md border border-[#2B2F38]"
                                    >
                                        <span
                                            class="text-[10px] text-[#8E93A0] block"
                                            >রেয়ার আইটেম</span
                                        >
                                        <span class="font-bold text-[#EDE9DE]"
                                            >{{
                                                activeListing.rare_item_count
                                            }}
                                            টি</span
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Assigned Moderator Info -->
                            <div
                                class="p-3 bg-[#20242C] rounded-lg border border-[#2B2F38] text-xs"
                            >
                                <span
                                    class="text-[10px] text-[#8E93A0] uppercase tracking-wider block mb-1"
                                    >ভেরিফাইড ডিল এজেন্ট</span
                                >
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="w-7 h-7 rounded-full bg-[#3FA79B]/20 text-[#3FA79B] flex items-center justify-center font-bold text-xs"
                                        >
                                            ✓
                                        </div>
                                        <div>
                                            <span
                                                class="font-bold text-[#EDE9DE] block"
                                                >{{
                                                    activeListing.moderator
                                                        ?.name ||
                                                    "Masum (Admin)"
                                                }}</span
                                            >
                                            <span
                                                class="text-[10px] text-[#8E93A0]"
                                                >@{{
                                                    activeListing.moderator
                                                        ?.username || "masum"
                                                }}</span
                                            >
                                        </div>
                                    </div>
                                    <span
                                        class="text-[11px] text-[#3FA79B] font-medium font-mono"
                                    >
                                        {{
                                            activeListing.moderator
                                                ?.whatsapp_number ||
                                            "+880 1700-000000"
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Gold CTA -->
                        <div class="pt-3 border-t border-[#2B2F38] space-y-2">
                            <a
                                :href="getWhatsAppUrl(activeListing)"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="w-full py-3 px-4 bg-[#E3A339] hover:bg-[#E3A339]/90 text-[#0F1115] font-bold text-sm rounded-md flex items-center justify-center space-x-2 transition-all shadow-lg active:scale-98"
                            >
                                <span>WhatsApp-এ সরাসরি কথা বলুন</span>
                                <ExternalLink class="w-4 h-4" />
                            </a>
                            <p
                                class="text-[10px] text-[#8E93A0] text-center font-['Hind_Siliguri',sans-serif]"
                            >
                                ক্লিক করলে সরাসরি এজেন্টের সাথে এই আইডির চ্যাট
                                ওপেন হবে।
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <footer
            class="border-t border-[#2B2F38] bg-[#0F1115] py-8 text-center text-xs text-[#8E93A0]"
        >
            <div
                class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4"
            >
                <div class="flex items-center space-x-2">
                    <Flame class="w-4 h-4 text-[#E3A339]" />
                    <span
                        class="font-['Sora',sans-serif] font-bold text-[#EDE9DE]"
                        >FireVault</span
                    >
                    <span>— Verified Free Fire ID Catalog</span>
                </div>
                <div class="flex items-center space-x-4">
                    <Link
                        :href="home.url()"
                        class="hover:text-[#EDE9DE] transition-colors"
                        >Home</Link
                    >
                    <Link
                        :href="`${home.url()}#how-it-works`"
                        class="hover:text-[#EDE9DE] transition-colors"
                        >How It Works</Link
                    >
                    <Link
                        :href="`${home.url()}#scam-alert`"
                        class="hover:text-[#EDE9DE] transition-colors"
                        >Scam Alert</Link
                    >
                    <Link
                        :href="`${home.url()}#terms`"
                        class="hover:text-[#EDE9DE] transition-colors"
                        >Terms & Rules</Link
                    >
                    <Link
                        :href="login.url()"
                        class="hover:text-[#EDE9DE] transition-colors"
                        >Staff Portal</Link
                    >
                </div>
            </div>
        </footer>
    </div>
</template>
