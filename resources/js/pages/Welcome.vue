<script setup lang="ts">
defineOptions({ layout: null });

import { ref, onMounted, onUnmounted } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { login } from "@/routes";
import {
    ShieldCheck,
    CheckCircle2,
    Sparkles,
    Flame,
    X,
    ExternalLink,
    Lock,
    Users,
    ChevronRight,
    ChevronLeft,
    Crosshair,
    Calendar,
    Award,
    Eye,
    AlertTriangle,
    FileText,
    ArrowRight,
    Search,
    ShieldAlert,
    Check,
    Clock,
    Zap,
    MessageCircle,
    Menu,
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
        heroListing?: Listing | null;
        hotListings?: Listing[];
        stats?: {
            availableCount: number;
            soldCount: number;
            moderatorCount: number;
        };
    }>(),
    {
        heroListing: null,
        hotListings: () => [],
        stats: () => ({ availableCount: 0, soldCount: 0, moderatorCount: 0 }),
    },
);

// Mobile Navigation Drawer
const isMobileNavOpen = ref(false);
function toggleMobileNav() {
    isMobileNavOpen.value = !isMobileNavOpen.value;
}
function closeMobileNav() {
    isMobileNavOpen.value = false;
}

// Quick Search in Hero
const heroSearch = ref("");
function executeHeroSearch(e: Event) {
    e.preventDefault();
    if (heroSearch.value.trim()) {
        router.get("/listings", { search: heroSearch.value.trim() });
    } else {
        router.get("/listings");
    }
}

// Quick View Modal State
const activeListing = ref<Listing | null>(null);
const activeImageIndex = ref(0);

function openModal(listing: Listing) {
    activeListing.value = listing;
    activeImageIndex.value = 0;
}

function closeModal() {
    activeListing.value = null;
}

function nextModalImage() {
    if (!activeListing.value?.images || activeListing.value.images.length <= 1)
        return;
    activeImageIndex.value =
        (activeImageIndex.value + 1) % activeListing.value.images.length;
}

function prevModalImage() {
    if (!activeListing.value?.images || activeListing.value.images.length <= 1)
        return;
    activeImageIndex.value =
        (activeImageIndex.value - 1 + activeListing.value.images.length) %
        activeListing.value.images.length;
}

function handleKeyDown(e: KeyboardEvent) {
    if (!activeListing.value) return;
    if (e.key === "Escape") {
        closeModal();
    } else if (e.key === "ArrowRight") {
        nextModalImage();
    } else if (e.key === "ArrowLeft") {
        prevModalImage();
    }
}

onMounted(() => {
    window.addEventListener("keydown", handleKeyDown);
});

onUnmounted(() => {
    window.removeEventListener("keydown", handleKeyDown);
});

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

// FAQ Accordion State
const activeFaq = ref<number | null>(0);
function toggleFaq(index: number) {
    activeFaq.value = activeFaq.value === index ? null : index;
}

const faqs = [
    {
        q: "ডিল কীভাবে শুরু করব ও কথা বলব?",
        a: "ক্যাটালগ থেকে যেকোনো অ্যাকাউন্টের WhatsApp বাটনে ক্লিক করুন। স্বয়ংক্রিয়ভাবে উক্ত আইডির নির্ধারিত মডারেটরের WhatsApp চ্যাট ওপেন হবে। মডারেটর আপনাকে সরাসরি আইডির লাইভ তথ্য ও প্রুফ সরবরাহ করবেন।",
    },
    {
        q: "অ্যাকাউন্টের সিকিউরিটি নিশ্চিত করা হয় কীভাবে?",
        a: "ডিল চলাকালীন মডারেটরের তত্ত্বাবধানে ক্রেতার নিজস্ব মোবাইল নাম্বার ও ইমেইল যুক্ত করে টু-স্টেপ ভেরিফিকেশন (2FA) অন করা হয় এবং আগের সমস্ত ডিভাইস থেকে লগআউট নিশ্চিত করা হয়।",
    },
    {
        q: "FireVault কি কোনো পেমেন্ট গেটওয়ে বা ওয়ালেট?",
        a: "না। FireVault কোনো অটোমেটেড গেটওয়ে নয়। এটি একটি ভেরিফাইড ডিসপ্লে ও ম্যানেজমেন্ট পোর্টাল। সমস্ত লেনদেন সরাসরি ক্রেতা ও মডারেটরের মধ্যে নিজস্ব সম্মতিতে WhatsApp-এ সম্পন্ন হয়।",
    },
    {
        q: "পেমেন্ট করার পর কোনো সমস্যা হলে কী করব?",
        a: "হস্তান্তর প্রক্রিয়ার সময় কোনো ত্রুটি থাকলে আমাদের টিম বিকল্প সমাধান নিশ্চিত করে। এছাড়া যেকোনো প্রয়োজনে সাইটের অ্যাডমিন নাম্বারে সরাসরি জরুরি রিপোর্ট করার ব্যবস্থা রয়েছে।",
    },
];
</script>

<template>
    <Head title="FireVault — Verified Free Fire Marketplace" />

    <div
        class="min-h-screen bg-[#0F1115] text-[#EDE9DE] font-['Inter',sans-serif] selection:bg-[#E3A339]/30 selection:text-[#E3A339]"
    >
        <!-- 1. NAVIGATION BAR (English Only Nav Items) -->
        <header
            class="sticky top-0 z-40 bg-[#0F1115]/95 backdrop-blur-md border-b border-[#2B2F38]"
        >
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between"
            >
                <!-- Brand Logo -->
                <div class="flex items-center space-x-6">
                    <Link href="/" class="flex items-center space-x-2.5 group">
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

                    <!-- Desktop Nav Items (English Only) -->
                    <nav
                        class="hidden md:flex items-center space-x-1 pl-4 border-l border-[#2B2F38]"
                    >
                        <span
                            class="px-3 py-1.5 text-xs text-[#E3A339] font-semibold bg-[#181B21] rounded-md border border-[#2B2F38]"
                        >
                            Home
                        </span>
                        <Link
                            href="/listings"
                            class="px-3 py-1.5 text-xs text-[#8E93A0] hover:text-[#EDE9DE] rounded-md hover:bg-[#181B21] transition-colors flex items-center space-x-1.5"
                        >
                            <span>All IDs</span>
                            <span
                                class="px-1.5 py-0.2 bg-[#3FA79B]/20 text-[#3FA79B] text-[10px] font-bold rounded font-mono"
                                >{{ props.stats.availableCount }}</span
                            >
                        </Link>
                        <a
                            href="#hot-ids"
                            class="px-3 py-1.5 text-xs text-[#8E93A0] hover:text-[#EDE9DE] rounded-md hover:bg-[#181B21] transition-colors"
                        >
                            Featured IDs
                        </a>
                        <a
                            href="#how-it-works"
                            class="px-3 py-1.5 text-xs text-[#8E93A0] hover:text-[#EDE9DE] rounded-md hover:bg-[#181B21] transition-colors"
                        >
                            How It Works
                        </a>
                        <a
                            href="#scam-alert"
                            class="px-3 py-1.5 text-xs text-[#8E93A0] hover:text-[#EDE9DE] rounded-md hover:bg-[#181B21] transition-colors flex items-center space-x-1 text-amber-400"
                        >
                            <AlertTriangle class="w-3.5 h-3.5 text-[#E3A339]" />
                            <span>Scam Alert</span>
                        </a>
                        <a
                            href="#terms"
                            class="px-3 py-1.5 text-xs text-[#8E93A0] hover:text-[#EDE9DE] rounded-md hover:bg-[#181B21] transition-colors"
                        >
                            Terms & Rules
                        </a>
                    </nav>
                </div>

                <!-- Right Action Buttons (English Only) -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <Link
                        href="/listings"
                        class="hidden sm:inline-flex items-center space-x-1.5 px-3.5 py-1.5 bg-[#3FA79B]/15 hover:bg-[#3FA79B]/25 text-[#3FA79B] border border-[#3FA79B]/30 rounded-md text-xs font-semibold transition-all shadow-sm"
                    >
                        <span>Browse Catalog</span>
                        <ChevronRight class="w-3.5 h-3.5" />
                    </Link>

                    <Link
                        :href="login.url()"
                        class="px-3 py-1.5 text-xs text-[#8E93A0] hover:text-[#EDE9DE] border border-[#2B2F38] hover:border-[#8E93A0] rounded-md bg-[#181B21] transition-all"
                    >
                        Staff Login
                    </Link>

                    <!-- Mobile Menu Hamburger Button -->
                    <button
                        @click="toggleMobileNav"
                        class="md:hidden p-1.5 rounded-md text-[#8E93A0] hover:text-[#EDE9DE] hover:bg-[#181B21] border border-[#2B2F38] transition-colors"
                        aria-label="Toggle navigation menu"
                    >
                        <X v-if="isMobileNavOpen" class="w-5 h-5" />
                        <Menu v-else class="w-5 h-5" />
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Dropdown (English Only) -->
            <div
                v-if="isMobileNavOpen"
                class="md:hidden border-b border-[#2B2F38] bg-[#181B21] px-4 py-3 space-y-1 animate-in slide-in-from-top duration-200"
            >
                <Link
                    href="/"
                    @click="closeMobileNav"
                    class="block px-3 py-2 rounded-md text-xs font-semibold text-[#E3A339] bg-[#20242C]"
                >
                    Home
                </Link>
                <Link
                    href="/listings"
                    @click="closeMobileNav"
                    class="flex items-center justify-between px-3 py-2 rounded-md text-xs text-[#EDE9DE] hover:bg-[#20242C]"
                >
                    <span>All IDs</span>
                    <span
                        class="px-1.5 py-0.2 bg-[#3FA79B]/20 text-[#3FA79B] text-[10px] font-bold rounded"
                        >{{ props.stats.availableCount }}</span
                    >
                </Link>
                <a
                    href="#hot-ids"
                    @click="closeMobileNav"
                    class="block px-3 py-2 rounded-md text-xs text-[#8E93A0] hover:text-[#EDE9DE] hover:bg-[#20242C]"
                >
                    Featured IDs
                </a>
                <a
                    href="#how-it-works"
                    @click="closeMobileNav"
                    class="block px-3 py-2 rounded-md text-xs text-[#8E93A0] hover:text-[#EDE9DE] hover:bg-[#20242C]"
                >
                    How It Works
                </a>
                <a
                    href="#scam-alert"
                    @click="closeMobileNav"
                    class="flex items-center space-x-1.5 px-3 py-2 rounded-md text-xs text-amber-400 hover:bg-[#20242C]"
                >
                    <AlertTriangle class="w-3.5 h-3.5 text-[#E3A339]" />
                    <span>Scam Alert & Safety</span>
                </a>
                <a
                    href="#terms"
                    @click="closeMobileNav"
                    class="block px-3 py-2 rounded-md text-xs text-[#8E93A0] hover:text-[#EDE9DE] hover:bg-[#20242C]"
                >
                    Terms & Rules
                </a>
            </div>
        </header>

        <!-- 2. HERO SECTION (Asymmetric, screenshot-led per FireVault design guideline) -->
        <section class="relative border-b border-[#2B2F38] overflow-hidden">
            <!-- Ambient Vault Glow -->
            <div
                class="absolute -top-24 left-1/2 -translate-x-1/2 w-[700px] h-[350px] bg-gradient-to-b from-[#E3A339]/10 via-[#3FA79B]/5 to-transparent rounded-full blur-3xl pointer-events-none"
            ></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
                <div
                    class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center"
                >
                    <!-- Left Hero: Featured Real Screenshot with Rich Overlay -->
                    <div class="lg:col-span-6 relative">
                        <div
                            class="relative bg-[#181B21] border border-[#2B2F38] rounded-xl overflow-hidden shadow-2xl group transition-all duration-300 hover:border-[#8E93A0]/60"
                        >
                            <!-- Top Overlay Badges -->
                            <div
                                class="absolute top-3.5 inset-x-3.5 z-10 flex items-center justify-between"
                            >
                                <span
                                    class="px-2.5 py-1 rounded text-xs font-bold bg-[#E3A339] text-[#0F1115] shadow-md flex items-center space-x-1"
                                >
                                    <Sparkles
                                        class="w-3.5 h-3.5 fill-current"
                                    />
                                    <span>Featured Today</span>
                                </span>

                                <span
                                    class="px-2.5 py-1 rounded text-xs font-medium bg-[#0F1115]/85 backdrop-blur-sm border border-[#2B2F38] text-[#3FA79B] flex items-center space-x-1.5 shadow"
                                >
                                    <span
                                        class="w-2 h-2 rounded-full bg-[#3FA79B] animate-pulse"
                                    ></span>
                                    <span>Available ●</span>
                                </span>
                            </div>

                            <!-- Real Image with Gradient Scrim -->
                            <div
                                class="relative aspect-[16/10] overflow-hidden bg-[#0F1115]"
                            >
                                <img
                                    :src="
                                        heroListing
                                            ? getListingCover(heroListing)
                                            : 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=800&auto=format&fit=crop'
                                    "
                                    :alt="
                                        heroListing?.title ||
                                        'Featured Free Fire Account'
                                    "
                                    class="w-full h-full object-cover transform group-hover:scale-103 transition-transform duration-700"
                                />
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-[#181B21] via-transparent to-transparent opacity-90"
                                ></div>
                            </div>

                            <!-- Card Data Overlay -->
                            <div class="p-5 -mt-8 relative z-10 space-y-3.5">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3
                                            class="font-['Sora',sans-serif] font-bold text-lg text-[#EDE9DE] group-hover:text-[#E3A339] transition-colors"
                                        >
                                            {{
                                                heroListing?.title ||
                                                "Grandmaster Max Evo Loadout"
                                            }}
                                        </h3>
                                        <div
                                            class="flex items-center space-x-2 text-xs text-[#8E93A0] mt-0.5"
                                        >
                                            <span class="font-mono"
                                                >UID:
                                                {{
                                                    heroListing?.uid ||
                                                    "1029384756"
                                                }}</span
                                            >
                                            <span>•</span>
                                            <span>{{
                                                heroListing?.account_age ||
                                                "Season 2 Player"
                                            }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            class="text-[10px] text-[#8E93A0] block uppercase tracking-wider"
                                            >দাম (Price)</span
                                        >
                                        <span
                                            class="font-['Sora',sans-serif] font-bold text-xl sm:text-2xl text-[#E3A339]"
                                        >
                                            {{
                                                heroListing
                                                    ? formatPrice(
                                                          heroListing.price,
                                                      )
                                                    : "৳ ৮,৫০০"
                                            }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Spec Pills Grid -->
                                <div class="grid grid-cols-4 gap-2 text-xs">
                                    <div
                                        class="p-2 bg-[#20242C] rounded-md border border-[#2B2F38] text-center"
                                    >
                                        <span
                                            class="text-[10px] text-[#8E93A0] block"
                                            >Level</span
                                        >
                                        <span
                                            class="font-['Sora',sans-serif] font-bold text-[#EDE9DE]"
                                            >{{
                                                heroListing?.level || 74
                                            }}</span
                                        >
                                    </div>
                                    <div
                                        class="p-2 bg-[#20242C] rounded-md border border-[#2B2F38] text-center"
                                    >
                                        <span
                                            class="text-[10px] text-[#8E93A0] block"
                                            >Evo Guns</span
                                        >
                                        <span
                                            class="font-['Sora',sans-serif] font-bold text-[#E3A339]"
                                            >{{
                                                heroListing?.gun_skin_count ||
                                                14
                                            }}</span
                                        >
                                    </div>
                                    <div
                                        class="p-2 bg-[#20242C] rounded-md border border-[#2B2F38] text-center"
                                    >
                                        <span
                                            class="text-[10px] text-[#8E93A0] block"
                                            >Passes</span
                                        >
                                        <span
                                            class="font-['Sora',sans-serif] font-bold text-[#EDE9DE]"
                                            >{{
                                                heroListing?.elite_pass_count ||
                                                19
                                            }}</span
                                        >
                                    </div>
                                    <div
                                        class="p-2 bg-[#20242C] rounded-md border border-[#2B2F38] text-center"
                                    >
                                        <span
                                            class="text-[10px] text-[#8E93A0] block"
                                            >Rare Items</span
                                        >
                                        <span
                                            class="font-['Sora',sans-serif] font-bold text-[#3FA79B]"
                                            >{{
                                                heroListing?.rare_item_count ||
                                                28
                                            }}</span
                                        >
                                    </div>
                                </div>

                                <!-- Bottom Row with Quick Modal Action -->
                                <div
                                    class="pt-2 border-t border-[#2B2F38] flex items-center justify-between text-xs"
                                >
                                    <span
                                        class="text-[#8E93A0] flex items-center space-x-1.5"
                                    >
                                        <ShieldCheck
                                            class="w-4 h-4 text-[#3FA79B]"
                                        />
                                        <span
                                            >Verified by @{{
                                                heroListing?.moderator
                                                    ?.username || "masum"
                                            }}</span
                                        >
                                    </span>
                                    <button
                                        v-if="heroListing"
                                        @click="openModal(heroListing)"
                                        class="px-3 py-1 bg-[#20242C] hover:bg-[#2B2F38] text-[#E3A339] border border-[#2B2F38] rounded-md text-xs font-semibold flex items-center space-x-1 transition-colors"
                                    >
                                        <span>ডিটেইলস ও ছবি</span>
                                        <ChevronRight class="w-3.5 h-3.5" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Hero: Narrative + Direct Fast Search Bar -->
                    <div class="lg:col-span-6 space-y-6">
                        <div
                            class="inline-flex items-center space-x-2 px-3 py-1 rounded-md bg-[#3FA79B]/10 border border-[#3FA79B]/20 text-[#3FA79B] text-xs font-medium"
                        >
                            <Lock class="w-3.5 h-3.5" />
                            <span>১০০% সেফ ও ট্রাস্টেড ডিল পোর্টাল</span>
                        </div>

                        <div class="space-y-3">
                            <h1
                                class="text-3xl sm:text-4xl lg:text-5xl font-['Sora',sans-serif] font-bold text-[#EDE9DE] leading-[1.15] tracking-tight"
                            >
                                The account is real. The seller is verified. The
                                handoff is on WhatsApp.
                            </h1>
                            <p
                                class="text-sm sm:text-base text-[#8E93A0] font-['Hind_Siliguri',sans-serif] leading-relaxed"
                            >
                                ফায়ারভল্ট — ফ্রি ফায়ার আইডি কেনাবেচার একমাত্র
                                নির্ভরযোগ্য প্ল্যাটফর্ম। কোনো ভুয়া ব্রোকার বা
                                থার্ড-পার্টি বট নয়, সরাসরি আমাদের ভেরিফাইড
                                মডারেটরের মাধ্যমে নিরাপদ ও স্বচ্ছ ডিল।
                            </p>
                        </div>

                        <!-- Direct Search Bar (Design Guideline Hero Feature) -->
                        <form @submit="executeHeroSearch" class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#8E93A0]"
                            >
                                <Search class="w-4 h-4" />
                            </div>
                            <input
                                v-model="heroSearch"
                                type="text"
                                placeholder="Search by Title, UID (e.g. 19283...), or Gun Skin..."
                                class="w-full pl-10 pr-28 py-3 bg-[#181B21] border border-[#2B2F38] focus:border-[#E3A339] focus:ring-1 focus:ring-[#E3A339] rounded-md text-xs sm:text-sm text-[#EDE9DE] placeholder-[#8E93A0] transition-colors shadow-sm"
                            />
                            <button
                                type="submit"
                                class="absolute inset-y-1.5 right-1.5 px-4 bg-[#E3A339] hover:bg-[#E3A339]/90 text-xs font-bold text-[#0F1115] rounded transition-colors shadow flex items-center space-x-1"
                            >
                                <span>ক্যাটালগে খুঁজুন</span>
                            </button>
                        </form>

                        <!-- Quick Category Shortcuts -->
                        <div
                            class="flex flex-wrap items-center gap-2 text-xs font-['Hind_Siliguri',sans-serif]"
                        >
                            <span class="text-[#8E93A0]">জনপ্রিয় ফিল্টার:</span>
                            <Link
                                href="/listings?category=evo"
                                class="px-2.5 py-1 bg-[#181B21] hover:bg-[#20242C] text-[#EDE9DE] border border-[#2B2F38] rounded hover:border-[#E3A339]/60 transition-colors"
                            >
                                ইভো গান
                            </Link>
                            <Link
                                href="/listings?category=old"
                                class="px-2.5 py-1 bg-[#181B21] hover:bg-[#20242C] text-[#EDE9DE] border border-[#2B2F38] rounded hover:border-[#E3A339]/60 transition-colors"
                            >
                                ওল্ড সিজন
                            </Link>
                            <Link
                                href="/listings?category=high_level"
                                class="px-2.5 py-1 bg-[#181B21] hover:bg-[#20242C] text-[#EDE9DE] border border-[#2B2F38] rounded hover:border-[#E3A339]/60 transition-colors"
                            >
                                ৭০+ লেভেল
                            </Link>
                            <Link
                                href="/listings?category=budget"
                                class="px-2.5 py-1 bg-[#181B21] hover:bg-[#20242C] text-[#EDE9DE] border border-[#2B2F38] rounded hover:border-[#E3A339]/60 transition-colors"
                            >
                                বাজেট আইডি (≤ ৫,০০০)
                            </Link>
                        </div>

                        <!-- Trust Metrics Grid -->
                        <div
                            class="grid grid-cols-4 gap-2 pt-2 border-t border-[#2B2F38]"
                        >
                            <div
                                class="p-2.5 bg-[#181B21] rounded-md border border-[#2B2F38] text-center"
                            >
                                <span
                                    class="font-['Sora',sans-serif] font-bold text-lg sm:text-xl text-[#3FA79B] block"
                                    >{{ props.stats.availableCount }}</span
                                >
                                <span
                                    class="text-[10px] text-[#8E93A0] font-['Hind_Siliguri',sans-serif]"
                                    >উপলব্ধ আইডি</span
                                >
                            </div>
                            <div
                                class="p-2.5 bg-[#181B21] rounded-md border border-[#2B2F38] text-center"
                            >
                                <span
                                    class="font-['Sora',sans-serif] font-bold text-lg sm:text-xl text-[#E3A339] block"
                                    >{{ props.stats.soldCount }}+</span
                                >
                                <span
                                    class="text-[10px] text-[#8E93A0] font-['Hind_Siliguri',sans-serif]"
                                    >সফল ডিল</span
                                >
                            </div>
                            <div
                                class="p-2.5 bg-[#181B21] rounded-md border border-[#2B2F38] text-center"
                            >
                                <span
                                    class="font-['Sora',sans-serif] font-bold text-lg sm:text-xl text-[#EDE9DE] block"
                                    >১০০%</span
                                >
                                <span
                                    class="text-[10px] text-[#8E93A0] font-['Hind_Siliguri',sans-serif]"
                                    >ভেরিফাইড প্রুফ</span
                                >
                            </div>
                            <div
                                class="p-2.5 bg-[#181B21] rounded-md border border-[#2B2F38] text-center"
                            >
                                <span
                                    class="font-['Sora',sans-serif] font-bold text-lg sm:text-xl text-[#3FA79B] block"
                                    >০%</span
                                >
                                <span
                                    class="text-[10px] text-[#8E93A0] font-['Hind_Siliguri',sans-serif]"
                                    >স্ক্যাম রেকর্ড</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. HOT & FEATURED COLLECTION (Authentic Pinterest-Style Masonry Grid) -->
        <section
            id="hot-ids"
            class="scroll-mt-20 py-16 border-b border-[#2B2F38] bg-gradient-to-b from-[#181B21]/30 to-[#0F1115]"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Header -->
                <div
                    class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10"
                >
                    <div>
                        <div
                            class="inline-flex items-center space-x-1.5 text-xs font-semibold text-[#E3A339] uppercase tracking-wider mb-1"
                        >
                            <Flame class="w-4 h-4 fill-current" />
                            <span>টপ রেটেড ও রেয়ার অ্যাকাউন্টস</span>
                        </div>
                        <h2
                            class="text-2xl sm:text-3xl font-['Sora',sans-serif] font-bold text-[#EDE9DE]"
                        >
                            হট ও ফিচার্ড আইডিসমূহ
                        </h2>
                        <p
                            class="text-xs sm:text-sm text-[#8E93A0] mt-1 font-['Hind_Siliguri',sans-serif]"
                        >
                            আমাদের আজকের সর্বোচ্চ ভ্যালু, ইভো ম্যাক্স ও রেয়ার
                            আইটেমসমৃদ্ধ বাছাইকৃত কালেকশন।
                        </p>
                    </div>

                    <div>
                        <Link
                            href="/listings"
                            class="inline-flex items-center space-x-2 px-4 py-2.5 bg-[#3FA79B] hover:bg-[#3FA79B]/90 text-[#0F1115] text-xs font-bold rounded-md shadow-md transition-all active:scale-98"
                        >
                            <span
                                >সব {{ props.stats.availableCount }} টি আইডি
                                ব্রাউজ করুন</span
                            >
                            <ArrowRight class="w-4 h-4" />
                        </Link>
                    </div>
                </div>

                <!-- PINTEREST STYLE MASONRY GRID (Varied Heights, Natural Aspect Ratios) -->
                <div
                    class="columns-1 sm:columns-2 lg:columns-3 gap-6 [column-fill:_balance]"
                >
                    <div
                        v-for="listing in hotListings"
                        :key="listing.id"
                        class="break-inside-avoid mb-6 group cursor-pointer"
                        @click="openModal(listing)"
                    >
                        <div
                            class="relative bg-[#181B21] border border-[#2B2F38] hover:border-[#8E93A0]/60 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-1"
                        >
                            <!-- Top Tag Badges -->
                            <div
                                class="absolute top-3.5 inset-x-3.5 z-10 flex items-center justify-between pointer-events-none"
                            >
                                <span
                                    class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-[#E3A339] text-[#0F1115] shadow flex items-center space-x-1"
                                >
                                    <Sparkles class="w-3 h-3 fill-current" />
                                    <span>Hot ID</span>
                                </span>

                                <span
                                    class="px-2.5 py-0.5 rounded text-[11px] font-medium bg-[#0F1115]/85 backdrop-blur-sm border border-[#2B2F38] text-[#3FA79B] flex items-center space-x-1 shadow"
                                >
                                    <span
                                        class="w-1.5 h-1.5 rounded-full bg-[#3FA79B] animate-pulse"
                                    ></span>
                                    <span>Available</span>
                                </span>
                            </div>

                            <!-- Natural Aspect Ratio Image -->
                            <div class="relative overflow-hidden bg-[#0F1115]">
                                <img
                                    :src="getListingCover(listing)"
                                    :alt="listing.title"
                                    loading="lazy"
                                    class="w-full h-auto object-cover transform group-hover:scale-103 transition-transform duration-500 block"
                                />
                                <!-- Dark gradient scrim over the image base -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-[#181B21] via-transparent to-transparent opacity-90"
                                ></div>
                            </div>

                            <!-- Card Bottom Content -->
                            <div class="p-4 -mt-6 relative z-10 space-y-3">
                                <div>
                                    <h3
                                        class="font-['Sora',sans-serif] font-bold text-sm sm:text-base text-[#EDE9DE] group-hover:text-[#E3A339] transition-colors line-clamp-1"
                                    >
                                        {{ listing.title }}
                                    </h3>
                                    <div
                                        class="flex items-center space-x-2 text-[11px] text-[#8E93A0] mt-0.5"
                                    >
                                        <span class="font-mono"
                                            >UID: {{ listing.uid }}</span
                                        >
                                        <span>•</span>
                                        <span>{{ listing.account_age }}</span>
                                    </div>
                                </div>

                                <!-- Spec Pills Grid -->
                                <div
                                    class="grid grid-cols-2 gap-1.5 text-[11px]"
                                >
                                    <div
                                        class="px-2 py-1 bg-[#20242C] rounded-md border border-[#2B2F38] flex items-center justify-between"
                                    >
                                        <span class="text-[#8E93A0]"
                                            >Level</span
                                        >
                                        <span
                                            class="font-['Sora',sans-serif] font-semibold text-[#EDE9DE]"
                                            >{{ listing.level }}</span
                                        >
                                    </div>
                                    <div
                                        class="px-2 py-1 bg-[#20242C] rounded-md border border-[#2B2F38] flex items-center justify-between"
                                    >
                                        <span class="text-[#8E93A0]"
                                            >Evo Guns</span
                                        >
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
                                            >{{
                                                listing.elite_pass_count
                                            }}</span
                                        >
                                    </div>
                                    <div
                                        class="px-2 py-1 bg-[#20242C] rounded-md border border-[#2B2F38] flex items-center justify-between"
                                    >
                                        <span class="text-[#8E93A0]"
                                            >Chars</span
                                        >
                                        <span
                                            class="font-['Sora',sans-serif] font-semibold text-[#EDE9DE]"
                                            >{{ listing.character_count }}</span
                                        >
                                    </div>
                                </div>

                                <!-- Price & Action Row -->
                                <div
                                    class="pt-2 border-t border-[#2B2F38] flex items-center justify-between"
                                >
                                    <div>
                                        <span
                                            class="text-[10px] text-[#8E93A0] block uppercase tracking-wider"
                                            >দাম (Price)</span
                                        >
                                        <span
                                            class="font-['Sora',sans-serif] font-bold text-base sm:text-lg text-[#EDE9DE]"
                                        >
                                            {{ formatPrice(listing.price) }}
                                        </span>
                                    </div>

                                    <a
                                        :href="getWhatsAppUrl(listing)"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        @click.stop
                                        class="px-3.5 py-1.5 bg-[#E3A339] hover:bg-[#E3A339]/90 text-[#0F1115] text-xs font-bold rounded-md flex items-center space-x-1.5 transition-colors shadow-sm"
                                    >
                                        <span>WhatsApp</span>
                                        <ExternalLink class="w-3.5 h-3.5" />
                                    </a>
                                </div>

                                <!-- Moderator Verification Badge -->
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
                                        View Details
                                        <ChevronRight class="w-3 h-3" />
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Big Call-To-Action Banner -->
                <div
                    class="mt-8 p-6 sm:p-8 bg-gradient-to-r from-[#181B21] via-[#20242C] to-[#181B21] border border-[#2B2F38] rounded-xl flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl"
                >
                    <div class="space-y-1 text-center md:text-left">
                        <div
                            class="inline-flex items-center space-x-1.5 text-xs text-[#E3A339] font-semibold uppercase tracking-wider"
                        >
                            <Zap class="w-3.5 h-3.5" />
                            <span>সম্পূর্ণ ক্যাটালগ উপলব্ধ</span>
                        </div>
                        <h3
                            class="font-['Sora',sans-serif] font-bold text-lg sm:text-xl text-[#EDE9DE]"
                        >
                            আরও স্পেশাল গান স্কিন ও বাজেট আইডি খুঁজছেন?
                        </h3>
                        <p
                            class="text-xs sm:text-sm text-[#8E93A0] font-['Hind_Siliguri',sans-serif]"
                        >
                            আমাদের ডেডিকেটেড ক্যাটালগ পেজে বিভিন্ন প্রাইস রেঞ্জ
                            ও ফিল্টারসহ সব আইডি সাজানো রয়েছে।
                        </p>
                    </div>

                    <Link
                        href="/listings"
                        class="px-6 py-3.5 bg-[#E3A339] hover:bg-[#E3A339]/90 text-[#0F1115] font-['Sora',sans-serif] font-bold text-sm rounded-md flex items-center space-x-2 transition-all shrink-0 shadow-lg active:scale-98"
                    >
                        <span
                            >সম্পূর্ণ আইডি ক্যাটালগ দেখুন ({{
                                props.stats.availableCount
                            }}
                            টি)</span
                        >
                        <ArrowRight class="w-4 h-4" />
                    </Link>
                </div>
            </div>
        </section>

        <!-- 4. WHY FIREVAULT / SITE DETAILS -->
        <section class="py-16 border-b border-[#2B2F38] bg-[#0F1115]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <span
                        class="text-xs font-semibold text-[#3FA79B] uppercase tracking-wider block mb-1"
                        >আমাদের বিশ্বস্ততা ও কার্যপদ্ধতি</span
                    >
                    <h2
                        class="text-2xl sm:text-3xl font-['Sora',sans-serif] font-bold text-[#EDE9DE]"
                    >
                        কেন ফায়ারভল্ট থেকে আইডি নিবেন?
                    </h2>
                    <p
                        class="text-xs sm:text-sm text-[#8E93A0] mt-2 font-['Hind_Siliguri',sans-serif]"
                    >
                        ফেসবুক গ্রুপ বা ভুয়া পেজের প্রতারণা এড়িয়ে নিরাপদে আপনার
                        পছন্দের ফ্রি ফায়ার আইডি কিনুন।
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
                >
                    <!-- Feature 1 -->
                    <div
                        class="p-6 bg-[#181B21] border border-[#2B2F38] rounded-xl space-y-3 hover:border-[#8E93A0]/60 transition-all"
                    >
                        <div
                            class="w-10 h-10 rounded-md bg-[#E3A339]/10 text-[#E3A339] flex items-center justify-center font-bold"
                        >
                            <Eye class="w-5 h-5" />
                        </div>
                        <h3
                            class="font-['Sora',sans-serif] font-bold text-base text-[#EDE9DE]"
                        >
                            প্রকৃত স্ক্রিনশট ও স্পেক্স
                        </h3>
                        <p
                            class="text-xs text-[#8E93A0] font-['Hind_Siliguri',sans-serif] leading-relaxed"
                        >
                            কোনো ডামি বা ফেক ছবি নয়। প্রতিটি আইডির গান স্কিন,
                            লেভেল, এলিট পাস ও ক্যারেক্টারের জেনুইন স্ক্রিনশট
                            প্রদর্শিত হয়।
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div
                        class="p-6 bg-[#181B21] border border-[#2B2F38] rounded-xl space-y-3 hover:border-[#8E93A0]/60 transition-all"
                    >
                        <div
                            class="w-10 h-10 rounded-md bg-[#3FA79B]/10 text-[#3FA79B] flex items-center justify-center font-bold"
                        >
                            <Users class="w-5 h-5" />
                        </div>
                        <h3
                            class="font-['Sora',sans-serif] font-bold text-base text-[#EDE9DE]"
                        >
                            সরাসরি WhatsApp ডিল
                        </h3>
                        <p
                            class="text-xs text-[#8E93A0] font-['Hind_Siliguri',sans-serif] leading-relaxed"
                        >
                            কোনো রোবট বা থার্ড-পার্টি মধ্যস্থতাকারী নেই। প্রতিটি
                            আইডিতে নির্ধারিত ভেরিফাইড মডারেটরের সাথে সরাসরি কথা
                            বলুন।
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div
                        class="p-6 bg-[#181B21] border border-[#2B2F38] rounded-xl space-y-3 hover:border-[#8E93A0]/60 transition-all"
                    >
                        <div
                            class="w-10 h-10 rounded-md bg-[#E3A339]/10 text-[#E3A339] flex items-center justify-center font-bold"
                        >
                            <Award class="w-5 h-5" />
                        </div>
                        <h3
                            class="font-['Sora',sans-serif] font-bold text-base text-[#EDE9DE]"
                        >
                            স্বচ্ছ নির্ধারিত মূল্য
                        </h3>
                        <p
                            class="text-xs text-[#8E93A0] font-['Hind_Siliguri',sans-serif] leading-relaxed"
                        >
                            কোনো হিডেন বা গোপন চার্জ নেই। সাইটে যা মূল্য লেখা
                            আছে, ঠিক সেই মূল্যে স্বচ্ছভাবে সরাসরি ডিল সম্পন্ন
                            হয়।
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div
                        class="p-6 bg-[#181B21] border border-[#2B2F38] rounded-xl space-y-3 hover:border-[#8E93A0]/60 transition-all"
                    >
                        <div
                            class="w-10 h-10 rounded-md bg-[#3FA79B]/10 text-[#3FA79B] flex items-center justify-center font-bold"
                        >
                            <Lock class="w-5 h-5" />
                        </div>
                        <h3
                            class="font-['Sora',sans-serif] font-bold text-base text-[#EDE9DE]"
                        >
                            লাইভ 2FA হস্তান্তর গাইড
                        </h3>
                        <p
                            class="text-xs text-[#8E93A0] font-['Hind_Siliguri',sans-serif] leading-relaxed"
                        >
                            আইডি ডেলিভারির সময় ক্রেতার নিজস্ব জিমেইল/ফেসবুক
                            রিকভারি এবং ২-স্টেপ ভেরিফিকেশন সেটআপ নিশ্চিত করা
                            হয়।
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. HOW BUYING WORKS (3 Steps) -->
        <section
            id="how-it-works"
            class="scroll-mt-20 py-16 border-b border-[#2B2F38] bg-gradient-to-b from-[#12141A] to-[#0F1115]"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <span
                        class="text-xs font-semibold text-[#3FA79B] uppercase tracking-wider block mb-1"
                        >স্বচ্ছ ও নিরাপদ প্রক্রিয়া</span
                    >
                    <h2
                        class="text-2xl sm:text-3xl font-['Sora',sans-serif] font-bold text-[#EDE9DE]"
                    >
                        ডিল করার সহজ ৩ ধাপ
                    </h2>
                    <p
                        class="text-xs sm:text-sm text-[#8E93A0] mt-2 font-['Hind_Siliguri',sans-serif]"
                    >
                        কোনো বিভ্রান্তি ছাড়াই কীভাবে FireVault-এ একটি অ্যাকাউন্ট
                        কেনা সম্পন্ন হয় তা জেনে নিন।
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                    <!-- Step 1 -->
                    <div
                        class="p-6 bg-[#181B21] border border-[#2B2F38] rounded-xl relative space-y-4 hover:border-[#8E93A0]/50 transition-all"
                    >
                        <div class="flex items-center justify-between">
                            <span
                                class="font-['Sora',sans-serif] font-black text-3xl text-[#2B2F38]"
                                >01</span
                            >
                            <div
                                class="w-8 h-8 rounded-full bg-[#E3A339]/10 text-[#E3A339] flex items-center justify-center font-bold text-xs"
                            >
                                ✓
                            </div>
                        </div>
                        <h3
                            class="font-['Sora',sans-serif] font-bold text-base text-[#EDE9DE]"
                        >
                            ১. আইডি পছন্দ করুন
                        </h3>
                        <p
                            class="text-xs text-[#8E93A0] font-['Hind_Siliguri',sans-serif] leading-relaxed"
                        >
                            আমাদের ক্যাটালগ থেকে আপনার পছন্দের গান স্কিন, লেভেল
                            ও বাজেটের আইডি নির্বাচন করে বিস্তারিত তথ্য ও
                            স্ক্রিনশট যাচাই করুন।
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div
                        class="p-6 bg-[#181B21] border border-[#2B2F38] rounded-xl relative space-y-4 hover:border-[#8E93A0]/50 transition-all"
                    >
                        <div class="flex items-center justify-between">
                            <span
                                class="font-['Sora',sans-serif] font-black text-3xl text-[#2B2F38]"
                                >02</span
                            >
                            <div
                                class="w-8 h-8 rounded-full bg-[#3FA79B]/10 text-[#3FA79B] flex items-center justify-center font-bold text-xs"
                            >
                                ✓
                            </div>
                        </div>
                        <h3
                            class="font-['Sora',sans-serif] font-bold text-base text-[#EDE9DE]"
                        >
                            ২. WhatsApp-এ কথা বলুন
                        </h3>
                        <p
                            class="text-xs text-[#8E93A0] font-['Hind_Siliguri',sans-serif] leading-relaxed"
                        >
                            আইডির "WhatsApp" বাটনে ক্লিক করুন। স্বয়ংক্রিয়ভাবে
                            নির্ধারিত ভেরিফাইড মডারেটরের ইনবক্সে আইডির UID সহ
                            চ্যাট শুরু হবে।
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div
                        class="p-6 bg-[#181B21] border border-[#2B2F38] rounded-xl relative space-y-4 hover:border-[#8E93A0]/50 transition-all"
                    >
                        <div class="flex items-center justify-between">
                            <span
                                class="font-['Sora',sans-serif] font-black text-3xl text-[#2B2F38]"
                                >03</span
                            >
                            <div
                                class="w-8 h-8 rounded-full bg-[#E3A339]/10 text-[#E3A339] flex items-center justify-center font-bold text-xs"
                            >
                                ✓
                            </div>
                        </div>
                        <h3
                            class="font-['Sora',sans-serif] font-bold text-base text-[#EDE9DE]"
                        >
                            ৩. চেক, পেমেন্ট ও সিকিউর হস্তান্তর
                        </h3>
                        <p
                            class="text-xs text-[#8E93A0] font-['Hind_Siliguri',sans-serif] leading-relaxed"
                        >
                            মডারেটরের উপস্থিতিতে আইডি লাইভ চেক করুন, নিরাপদ
                            পেমেন্ট করুন এবং মেইল/পাসওয়ার্ড ও 2FA পরিবর্তন করে
                            আইডি নিজের নিয়ন্ত্রণে নিন।
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 6. SCAM-RELATED INFORMATION & SAFETY ALERT (Highlighted) -->
        <section
            id="scam-alert"
            class="scroll-mt-20 py-16 border-b border-[#2B2F38] bg-[#12141A]"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Alert Header Banner -->
                <div
                    class="p-6 sm:p-8 rounded-xl bg-gradient-to-r from-amber-500/10 via-[#181B21] to-[#181B21] border border-amber-500/30 mb-10 shadow-lg"
                >
                    <div
                        class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4"
                    >
                        <div
                            class="w-12 h-12 rounded-lg bg-[#E3A339]/20 text-[#E3A339] flex items-center justify-center shrink-0 border border-[#E3A339]/40"
                        >
                            <ShieldAlert class="w-6 h-6" />
                        </div>
                        <div>
                            <span
                                class="text-xs font-bold text-[#E3A339] uppercase tracking-wider"
                                >জরুরি নিরাপত্তা সতর্কতা ও স্ক্যাম এলার্ট</span
                            >
                            <h2
                                class="text-xl sm:text-2xl font-['Sora',sans-serif] font-bold text-[#EDE9DE]"
                            >
                                ফ্রি ফায়ার আইডি কেনাবেচায় স্ক্যাম থেকে কীভাবে
                                বাঁচবেন?
                            </h2>
                            <p
                                class="text-xs sm:text-sm text-[#8E93A0] mt-1 font-['Hind_Siliguri',sans-serif]"
                            >
                                ইন্টারনেটে ফ্রি ফায়ার আইডি কেনাবেচার নামে অসংখ্য
                                ভুয়া ফেসবুক পেজ ও টেলিগ্রাম প্রতারক সক্রিয়।
                                নিচের ৪টি নিয়ম সবসময় মনে রাখুন।
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 4 Major Scam Cautions (Side-by-side Matrix) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Warning 1 -->
                    <div
                        class="p-6 bg-[#181B21] border border-[#2B2F38] rounded-xl space-y-3 hover:border-red-500/40 transition-colors"
                    >
                        <div class="flex items-center space-x-2.5">
                            <div
                                class="w-7 h-7 rounded-md bg-red-500/15 text-red-400 flex items-center justify-center font-bold text-xs shrink-0"
                            >
                                ✕
                            </div>
                            <h3
                                class="font-['Sora',sans-serif] font-bold text-sm sm:text-base text-[#EDE9DE]"
                            >
                                ১. অফিশিয়াল নাম্বার ছাড়া অন্য কোথাও লেনদেন নয়
                            </h3>
                        </div>
                        <p
                            class="text-xs text-[#8E93A0] font-['Hind_Siliguri',sans-serif] leading-relaxed"
                        >
                            FireVault ওয়েবসাইটে প্রতিটি আইডিতে প্রদর্শিত
                            ভেরিফাইড মডারেটরের WhatsApp নাম্বার ছাড়া অন্য কোনো
                            ফেসবুক পেজ, ইনবক্স বা টেলিগ্রাম গ্রুপের মাধ্যমে কখনো
                            টাকা পাঠাবেন না। আমাদের টিম কখনো আপনাকে পার্সোনালি
                            ইনবক্সে নক দিয়ে টাকা দাবি করে না।
                        </p>
                    </div>

                    <!-- Warning 2 -->
                    <div
                        class="p-6 bg-[#181B21] border border-[#2B2F38] rounded-xl space-y-3 hover:border-red-500/40 transition-colors"
                    >
                        <div class="flex items-center space-x-2.5">
                            <div
                                class="w-7 h-7 rounded-md bg-red-500/15 text-red-400 flex items-center justify-center font-bold text-xs shrink-0"
                            >
                                ✕
                            </div>
                            <h3
                                class="font-['Sora',sans-serif] font-bold text-sm sm:text-base text-[#EDE9DE]"
                            >
                                ২. অবিশ্বাস্য কম দামের ফাঁদে পা দেবেন না
                            </h3>
                        </div>
                        <p
                            class="text-xs text-[#8E93A0] font-['Hind_Siliguri',sans-serif] leading-relaxed"
                        >
                            যে আইডিতে ৪০-৫০ হাজার টাকার গান স্কিন ও বান্ডেল আছে,
                            তা যদি কেউ ৫০০ বা ১,০০০ টাকায় দিতে চায়—তাহলে
                            নিশ্চিতভাবে সেটি প্রতারণা বা স্ক্যামারদের ফাঁদ।
                            সবসময় বাস্তবসম্মত মার্কেট প্রাইস যাচাই করে নিরাপদ
                            মাধ্যমে কিনুন।
                        </p>
                    </div>

                    <!-- Warning 3 -->
                    <div
                        class="p-6 bg-[#181B21] border border-[#2B2F38] rounded-xl space-y-3 hover:border-red-500/40 transition-colors"
                    >
                        <div class="flex items-center space-x-2.5">
                            <div
                                class="w-7 h-7 rounded-md bg-red-500/15 text-red-400 flex items-center justify-center font-bold text-xs shrink-0"
                            >
                                ✕
                            </div>
                            <h3
                                class="font-['Sora',sans-serif] font-bold text-sm sm:text-base text-[#EDE9DE]"
                            >
                                ৩. কোনো অবস্থাতেই OTP বা ভেরিফিকেশন কোড শেয়ার
                                করবেন না
                            </h3>
                        </div>
                        <p
                            class="text-xs text-[#8E93A0] font-['Hind_Siliguri',sans-serif] leading-relaxed"
                        >
                            আপনার ফোন বা জিমেইলে আসা কোনো 6-digit কোড বা
                            পাসওয়ার্ড রিসেট লিংক কখনোই অপরিচিত কারো সাথে শেয়ার
                            করবেন না। প্রতারকরা কোড হাতিয়ে নিয়ে ক্রেতার
                            ব্যক্তিগত তথ্য হ্যাক করার চেষ্টা করে।
                        </p>
                    </div>

                    <!-- Warning 4 -->
                    <div
                        class="p-6 bg-[#181B21] border border-[#2B2F38] rounded-xl space-y-3 hover:border-[#3FA79B]/50 transition-colors"
                    >
                        <div class="flex items-center space-x-2.5">
                            <div
                                class="w-7 h-7 rounded-md bg-[#3FA79B]/15 text-[#3FA79B] flex items-center justify-center font-bold text-xs shrink-0"
                            >
                                ✓
                            </div>
                            <h3
                                class="font-['Sora',sans-serif] font-bold text-sm sm:text-base text-[#EDE9DE]"
                            >
                                ৪. আইডি পাওয়ার সাথে সাথে 2FA ও রিকভারি পরিবর্তন
                                করুন
                            </h3>
                        </div>
                        <p
                            class="text-xs text-[#8E93A0] font-['Hind_Siliguri',sans-serif] leading-relaxed"
                        >
                            হ্যান্ডওভার সম্পন্ন হওয়ার সাথে সাথে জিমেইল/ফেসবুকের
                            Two-Step Verification অন করুন এবং আপনার নিজস্ব সচল
                            মোবাইল নাম্বার ও রিকভারি ইমেইল যুক্ত করুন। এতে
                            অ্যাকাউন্ট আজীবনের জন্য সুরক্ষিত থাকে।
                        </p>
                    </div>
                </div>

                <!-- Emergency Report Banner -->
                <div
                    class="mt-8 p-4 bg-[#181B21] border border-[#2B2F38] rounded-lg flex flex-col sm:flex-row items-center justify-between gap-4 text-xs"
                >
                    <span
                        class="text-[#8E93A0] font-['Hind_Siliguri',sans-serif]"
                    >
                        কোনো ব্যক্তি FireVault-এর নাম ব্যবহার করে প্রতারণার
                        চেষ্টা করলে অবিলম্বে আমাদের অ্যাডমিন টিমে জরুরি রিপোর্ট
                        করুন।
                    </span>
                    <a
                        href="https://wa.me/8801700000000?text=Report%20Scam%20Issue"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="px-4 py-2 bg-[#20242C] hover:bg-[#2B2F38] text-[#EDE9DE] rounded-md border border-[#2B2F38] font-medium transition-colors shrink-0 flex items-center space-x-1.5"
                    >
                        <MessageCircle class="w-3.5 h-3.5 text-[#E3A339]" />
                        <span>স্ক্যাম রিপোর্ট করুন</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- 7. TERMS AND CONDITIONS -->
        <section
            id="terms"
            class="scroll-mt-20 py-16 border-b border-[#2B2F38] bg-[#0F1115]"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <div
                        class="inline-flex items-center space-x-1.5 text-xs font-semibold text-[#8E93A0] uppercase tracking-wider mb-1"
                    >
                        <FileText class="w-3.5 h-3.5" />
                        <span>আইনি ও পরিচালনাগত নীতিমালা</span>
                    </div>
                    <h2
                        class="text-2xl sm:text-3xl font-['Sora',sans-serif] font-bold text-[#EDE9DE]"
                    >
                        ডিল করার নিয়মাবলী ও শর্তসমূহ
                    </h2>
                    <p
                        class="text-xs sm:text-sm text-[#8E93A0] mt-2 font-['Hind_Siliguri',sans-serif]"
                    >
                        লেনদেন শুরুর পূর্বে আমাদের নীতিমালাগুলো মনোযোগ সহকারে
                        পড়ে নেওয়ার অনুরোধ করা হচ্ছে।
                    </p>
                </div>

                <div class="max-w-4xl mx-auto space-y-4">
                    <!-- Term 1 -->
                    <div
                        class="p-5 bg-[#181B21] border border-[#2B2F38] rounded-xl space-y-2"
                    >
                        <h3
                            class="font-['Sora',sans-serif] font-bold text-sm sm:text-base text-[#EDE9DE] flex items-center space-x-2"
                        >
                            <span class="text-[#E3A339]">১.</span>
                            <span
                                >প্ল্যাটফর্মের ভূমিকা ও লেনদেন পদ্ধতি (Platform
                                Role)</span
                            >
                        </h3>
                        <p
                            class="text-xs text-[#8E93A0] font-['Hind_Siliguri',sans-serif] leading-relaxed pl-5"
                        >
                            FireVault কোনো অনলাইন পেমেন্ট গেটওয়ে বা ওয়ালেট নয়।
                            এটি একটি সুসংগঠিত ডিসপ্লে ক্যাটালগ ও ম্যানেজমেন্ট
                            সিস্টেম। সমস্ত আর্থিক লেনদেন ও অ্যাকাউন্ট হস্তান্তর
                            ক্রেতা ও সংশ্লিষ্ট অনুমোদিত মডারেটরের মধ্যে সরাসরি
                            WhatsApp-এ নিজস্ব সম্মতিতে সম্পন্ন হয়।
                        </p>
                    </div>

                    <!-- Term 2 -->
                    <div
                        class="p-5 bg-[#181B21] border border-[#2B2F38] rounded-xl space-y-2"
                    >
                        <h3
                            class="font-['Sora',sans-serif] font-bold text-sm sm:text-base text-[#EDE9DE] flex items-center space-x-2"
                        >
                            <span class="text-[#E3A339]">২.</span>
                            <span
                                >আইডি ও তথ্যের সত্যতা যাচাই (Accuracy &
                                Verification)</span
                            >
                        </h3>
                        <p
                            class="text-xs text-[#8E93A0] font-['Hind_Siliguri',sans-serif] leading-relaxed pl-5"
                        >
                            প্রতিটি আইডির লেভেল, গান স্কিন, ক্যারেক্টার ও UID
                            আমাদের টিম দ্বারা যাচাইকৃত। তবে ক্রেতাকে চূড়ান্ত
                            পেমেন্ট করার আগে মডারেটরের কাছে আইডির বর্তমান অবস্থা
                            এবং প্রয়োজনীয় স্ক্রিনশট পুনরায় নিশ্চিত করার পূর্ণ
                            অধিকার ও দায়িত্ব রয়েছে।
                        </p>
                    </div>

                    <!-- Term 3 -->
                    <div
                        class="p-5 bg-[#181B21] border border-[#2B2F38] rounded-xl space-y-2"
                    >
                        <h3
                            class="font-['Sora',sans-serif] font-bold text-sm sm:text-base text-[#EDE9DE] flex items-center space-x-2"
                        >
                            <span class="text-[#E3A339]">৩.</span>
                            <span
                                >হস্তান্তর পরবর্তী সুরক্ষার দায়িত্ব
                                (Post-Handover Responsibility)</span
                            >
                        </h3>
                        <p
                            class="text-xs text-[#8E93A0] font-['Hind_Siliguri',sans-serif] leading-relaxed pl-5"
                        >
                            মডারেটর কর্তৃক ক্রেতাকে সম্পূর্ণ লগইন তথ্য প্রদান
                            করার পর এবং ক্রেতা কর্তৃক পাসওয়ার্ড, রিকভারি
                            ফোন/ইমেইল পরিবর্তন করে নেওয়ার সাথে সাথে উক্ত আইডির
                            সার্বিক নিরাপত্তার দায়িত্ব ক্রেতার ওপর বর্তাবে।
                            পরবর্তীতে ক্রেতার অসতর্কতায় আইডি হ্যাক বা ব্যান হলে
                            FireVault দায়ী থাকবে না।
                        </p>
                    </div>

                    <!-- Term 4 -->
                    <div
                        class="p-5 bg-[#181B21] border border-[#2B2F38] rounded-xl space-y-2"
                    >
                        <h3
                            class="font-['Sora',sans-serif] font-bold text-sm sm:text-base text-[#EDE9DE] flex items-center space-x-2"
                        >
                            <span class="text-[#E3A339]">৪.</span>
                            <span
                                >রিফান্ড ও প্রতিস্থাপন পলিসি (Refund &
                                Replacement Policy)</span
                            >
                        </h3>
                        <p
                            class="text-xs text-[#8E93A0] font-['Hind_Siliguri',sans-serif] leading-relaxed pl-5"
                        >
                            যদি ডেলিভারির মুহূর্তে মডারেটর কোনো কারণে প্রদত্ত
                            স্পেক্স অনুযায়ী আইডি ডেলিভারি দিতে ব্যর্থ হন, তবে
                            তাৎক্ষণিকভাবে বিকল্প আইডি বা পেমেন্ট ফেরত নিশ্চিত
                            করা হয়। কিন্তু সফল হস্তান্তর ও ক্রেতার কাছে লগইন
                            বুঝিয়ে দেওয়ার পর কোনো প্রকার রিটার্ন বা রিফান্ড
                            গ্রহণযোগ্য নয়।
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 8. FREQUENTLY ASKED QUESTIONS (FAQ) -->
        <section class="py-16 border-b border-[#2B2F38] bg-[#0F1115]">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <span
                        class="text-xs font-semibold text-[#3FA79B] uppercase tracking-wider block mb-1"
                        >সাধারণ প্রশ্নোত্তর</span
                    >
                    <h2
                        class="text-2xl sm:text-3xl font-['Sora',sans-serif] font-bold text-[#EDE9DE]"
                    >
                        সচরাচর জিজ্ঞাসিত প্রশ্নাবলী
                    </h2>
                </div>

                <div class="space-y-3">
                    <div
                        v-for="(faq, idx) in faqs"
                        :key="idx"
                        class="bg-[#181B21] border border-[#2B2F38] rounded-xl overflow-hidden transition-colors"
                    >
                        <button
                            @click="toggleFaq(idx)"
                            class="w-full px-5 py-4 text-left flex items-center justify-between text-sm sm:text-base font-semibold text-[#EDE9DE] hover:text-[#E3A339] transition-colors"
                        >
                            <span class="font-['Hind_Siliguri',sans-serif]">{{
                                faq.q
                            }}</span>
                            <span class="text-lg text-[#8E93A0] ml-4 font-mono">
                                {{ activeFaq === idx ? "−" : "+" }}
                            </span>
                        </button>
                        <div
                            v-if="activeFaq === idx"
                            class="px-5 pb-4 text-xs text-[#8E93A0] font-['Hind_Siliguri',sans-serif] leading-relaxed border-t border-[#2B2F38]/60 pt-3"
                        >
                            {{ faq.a }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 9. FOOTER (English Only Navigation Links) -->
        <footer class="bg-[#0F1115] py-12 text-xs text-[#8E93A0]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <div
                    class="flex flex-col md:flex-row items-center justify-between gap-6 pb-8 border-b border-[#2B2F38]"
                >
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-8 h-8 rounded-md bg-[#E3A339] flex items-center justify-center text-[#0F1115] font-black text-lg"
                        >
                            <Flame class="w-5 h-5 fill-current" />
                        </div>
                        <div>
                            <span
                                class="font-['Sora',sans-serif] font-bold text-base text-[#EDE9DE] block"
                                >FireVault</span
                            >
                            <span class="text-[11px] text-[#8E93A0]"
                                >Verified Free Fire Marketplace & Sales
                                Management</span
                            >
                        </div>
                    </div>

                    <div
                        class="flex flex-wrap items-center justify-center gap-6"
                    >
                        <Link
                            href="/"
                            class="hover:text-[#EDE9DE] transition-colors"
                            >Home</Link
                        >
                        <Link
                            href="/listings"
                            class="text-[#E3A339] font-medium hover:underline"
                            >All IDs</Link
                        >
                        <a
                            href="#hot-ids"
                            class="hover:text-[#EDE9DE] transition-colors"
                            >Featured IDs</a
                        >
                        <a
                            href="#how-it-works"
                            class="hover:text-[#EDE9DE] transition-colors"
                            >How It Works</a
                        >
                        <a
                            href="#scam-alert"
                            class="hover:text-[#EDE9DE] transition-colors"
                            >Scam Alert</a
                        >
                        <a
                            href="#terms"
                            class="hover:text-[#EDE9DE] transition-colors"
                            >Terms & Rules</a
                        >
                        <Link
                            :href="login.url()"
                            class="hover:text-[#EDE9DE] transition-colors"
                            >Staff Portal</Link
                        >
                    </div>
                </div>

                <div
                    class="flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left text-[11px]"
                >
                    <p>
                        © {{ new Date().getFullYear() }} FireVault. All rights
                        reserved. Game assets & trademarks belong to their
                        respective owners.
                    </p>
                    <p class="font-['Hind_Siliguri',sans-serif]">
                        সরাসরি ভেরিফাইড WhatsApp ব্যতীত অন্য কোনো মাধ্যমে লেনদেন
                        করবেন না।
                    </p>
                </div>
            </div>
        </footer>

        <!-- QUICK-VIEW MODAL FOR HOT IDs (With keyboard navigation & arrows) -->
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
                        title="Close (Esc)"
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
                        <div
                            class="relative rounded-lg overflow-hidden bg-[#0F1115] border border-[#2B2F38] aspect-video flex items-center justify-center group/img"
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

                            <!-- Left/Right Gallery Nav Buttons -->
                            <button
                                v-if="
                                    activeListing.images &&
                                    activeListing.images.length > 1
                                "
                                @click="prevModalImage"
                                class="absolute left-2 top-1/2 -translate-y-1/2 p-1.5 rounded-full bg-[#0F1115]/70 hover:bg-[#0F1115] text-[#EDE9DE] border border-[#2B2F38] transition-opacity"
                                title="Previous image (Left Arrow)"
                            >
                                <ChevronLeft class="w-4 h-4" />
                            </button>
                            <button
                                v-if="
                                    activeListing.images &&
                                    activeListing.images.length > 1
                                "
                                @click="nextModalImage"
                                class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 rounded-full bg-[#0F1115]/70 hover:bg-[#0F1115] text-[#EDE9DE] border border-[#2B2F38] transition-opacity"
                                title="Next image (Right Arrow)"
                            >
                                <ChevronRight class="w-4 h-4" />
                            </button>

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
    </div>
</template>
