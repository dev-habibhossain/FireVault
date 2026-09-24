<script setup lang="ts">
defineOptions({ layout: null });

import { ref, onMounted, onUnmounted } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import { home, login } from "@/routes";
import {
    Flame,
    ArrowLeft,
    ShieldCheck,
    CheckCircle2,
    ExternalLink,
    Copy,
    Check,
    ChevronLeft,
    ChevronRight,
    Lock,
    Sparkles,
    AlertTriangle,
    Eye,
    MessageCircle,
    Menu,
    X,
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

const props = defineProps<{
    listing: Listing;
    relatedListings: Listing[];
}>();

// Mobile Navigation
const isMobileNavOpen = ref(false);
function toggleMobileNav() {
    isMobileNavOpen.value = !isMobileNavOpen.value;
}
function closeMobileNav() {
    isMobileNavOpen.value = false;
}

// Active Image Gallery
const activeImageIndex = ref(0);

function nextImage() {
    if (!props.listing.images || props.listing.images.length <= 1) return;
    activeImageIndex.value =
        (activeImageIndex.value + 1) % props.listing.images.length;
}

function prevImage() {
    if (!props.listing.images || props.listing.images.length <= 1) return;
    activeImageIndex.value =
        (activeImageIndex.value - 1 + props.listing.images.length) %
        props.listing.images.length;
}

function handleKeyDown(e: KeyboardEvent) {
    if (e.key === "ArrowRight") {
        nextImage();
    } else if (e.key === "ArrowLeft") {
        prevImage();
    }
}

onMounted(() => {
    window.addEventListener("keydown", handleKeyDown);
});

onUnmounted(() => {
    window.removeEventListener("keydown", handleKeyDown);
});

// Copy UID
const isCopied = ref(false);
function copyUid() {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(props.listing.uid);
        isCopied.value = true;
        setTimeout(() => {
            isCopied.value = false;
        }, 2000);
    }
}

function formatPrice(val: number): string {
    return "৳ " + Number(val).toLocaleString("en-US");
}

function getActiveImage(): string {
    if (props.listing.images && props.listing.images.length > 0) {
        return (
            props.listing.images[activeImageIndex.value]?.image_path ||
            props.listing.images[0].image_path
        );
    }
    if (props.listing.cover_image?.image_path) {
        return props.listing.cover_image.image_path;
    }
    return "https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=800&auto=format&fit=crop";
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
    <Head :title="`${listing.title} — FireVault`" />

    <div
        class="min-h-screen bg-[#0F1115] text-[#EDE9DE] font-['Inter',sans-serif] selection:bg-[#E3A339]/30 selection:text-[#E3A339]"
    >
        <!-- TOP NAVIGATION (English Only) -->
        <header
            class="sticky top-0 z-40 bg-[#0F1115]/95 backdrop-blur-md border-b border-[#2B2F38]"
        >
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between"
            >
                <!-- Brand -->
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
                        <Link
                            href="/listings"
                            class="px-3 py-1.5 text-xs text-[#E3A339] font-medium bg-[#181B21] rounded-md border border-[#2B2F38]"
                        >
                            All IDs
                        </Link>
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

                <!-- Right Actions -->
                <div class="flex items-center space-x-3">
                    <Link
                        href="/listings"
                        class="hidden sm:inline-flex items-center text-xs text-[#8E93A0] hover:text-[#EDE9DE] transition-colors pr-2"
                    >
                        <ArrowLeft class="w-3.5 h-3.5 mr-1" />
                        Back to Catalog
                    </Link>

                    <Link
                        :href="login.url()"
                        class="px-3 py-1.5 text-xs text-[#8E93A0] hover:text-[#EDE9DE] border border-[#2B2F38] hover:border-[#8E93A0] rounded-md bg-[#181B21] transition-all"
                    >
                        Staff Login
                    </Link>

                    <!-- Mobile Hamburger -->
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

            <!-- Mobile Navigation Dropdown -->
            <div
                v-if="isMobileNavOpen"
                class="md:hidden border-b border-[#2B2F38] bg-[#181B21] px-4 py-3 space-y-1 animate-in slide-in-from-top duration-200"
            >
                <Link
                    :href="home.url()"
                    @click="closeMobileNav"
                    class="block px-3 py-2 rounded-md text-xs text-[#8E93A0] hover:text-[#EDE9DE]"
                >
                    Home
                </Link>
                <Link
                    href="/listings"
                    @click="closeMobileNav"
                    class="block px-3 py-2 rounded-md text-xs font-semibold text-[#E3A339] bg-[#20242C]"
                >
                    All IDs
                </Link>
                <Link
                    :href="`${home.url()}#how-it-works`"
                    @click="closeMobileNav"
                    class="block px-3 py-2 rounded-md text-xs text-[#8E93A0] hover:text-[#EDE9DE]"
                >
                    How It Works
                </Link>
                <Link
                    :href="`${home.url()}#scam-alert`"
                    @click="closeMobileNav"
                    class="block px-3 py-2 rounded-md text-xs text-amber-400"
                >
                    Scam Alert & Safety
                </Link>
                <Link
                    :href="`${home.url()}#terms`"
                    @click="closeMobileNav"
                    class="block px-3 py-2 rounded-md text-xs text-[#8E93A0] hover:text-[#EDE9DE]"
                >
                    Terms & Rules
                </Link>
            </div>
        </header>

        <!-- BREADCRUMB BAR -->
        <div class="border-b border-[#2B2F38] bg-[#181B21]/40 py-3">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between text-xs text-[#8E93A0]"
            >
                <div class="flex items-center space-x-2">
                    <Link
                        :href="home.url()"
                        class="hover:text-[#EDE9DE] transition-colors"
                        >Home</Link
                    >
                    <span>/</span>
                    <Link
                        href="/listings"
                        class="hover:text-[#EDE9DE] transition-colors"
                        >Listings</Link
                    >
                    <span>/</span>
                    <span
                        class="text-[#EDE9DE] font-medium line-clamp-1 max-w-[200px] sm:max-w-sm"
                        >{{ listing.title }}</span
                    >
                </div>

                <Link
                    href="/listings"
                    class="inline-flex items-center space-x-1 text-[#E3A339] hover:underline font-medium"
                >
                    <ArrowLeft class="w-3.5 h-3.5" />
                    <span>Back to browse</span>
                </Link>
            </div>
        </div>

        <!-- MAIN LISTING DETAIL (Design Guideline 60/40 Split) -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <div
                class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start"
            >
                <!-- LEFT COLUMN: FULL-BLEED GALLERY (~60% on desktop) -->
                <div class="lg:col-span-7 space-y-4">
                    <!-- Main High-Resolution Screenshot Stage -->
                    <div
                        class="relative bg-[#0F1115] border border-[#2B2F38] rounded-xl overflow-hidden aspect-[16/10] flex items-center justify-center group shadow-2xl"
                    >
                        <img
                            :src="getActiveImage()"
                            :alt="listing.title"
                            class="w-full h-full object-contain"
                        />

                        <!-- Left / Right Controls -->
                        <button
                            v-if="listing.images && listing.images.length > 1"
                            @click="prevImage"
                            class="absolute left-3 top-1/2 -translate-y-1/2 p-2 rounded-full bg-[#0F1115]/80 hover:bg-[#0F1115] text-[#EDE9DE] border border-[#2B2F38] transition-all opacity-80 group-hover:opacity-100 shadow-lg"
                            title="Previous image (Left Arrow)"
                        >
                            <ChevronLeft class="w-5 h-5" />
                        </button>
                        <button
                            v-if="listing.images && listing.images.length > 1"
                            @click="nextImage"
                            class="absolute right-3 top-1/2 -translate-y-1/2 p-2 rounded-full bg-[#0F1115]/80 hover:bg-[#0F1115] text-[#EDE9DE] border border-[#2B2F38] transition-all opacity-80 group-hover:opacity-100 shadow-lg"
                            title="Next image (Right Arrow)"
                        >
                            <ChevronRight class="w-5 h-5" />
                        </button>

                        <!-- Counter Tag -->
                        <div
                            class="absolute bottom-3 right-3 px-3 py-1 rounded bg-[#0F1115]/85 backdrop-blur-sm text-xs text-[#EDE9DE] border border-[#2B2F38] shadow font-mono"
                        >
                            Screenshot {{ activeImageIndex + 1 }} of
                            {{ listing.images?.length || 1 }}
                        </div>
                    </div>

                    <!-- Thumbnails Strip -->
                    <div
                        v-if="listing.images && listing.images.length > 1"
                        class="flex items-center space-x-3 overflow-x-auto pb-2 scrollbar-none"
                    >
                        <button
                            v-for="(img, idx) in listing.images"
                            :key="img.id"
                            @click="activeImageIndex = idx"
                            :class="[
                                'relative w-20 h-14 rounded-lg overflow-hidden border shrink-0 transition-all cursor-pointer',
                                activeImageIndex === idx
                                    ? 'border-[#E3A339] ring-2 ring-[#E3A339]/50 shadow-md'
                                    : 'border-[#2B2F38] opacity-60 hover:opacity-100',
                            ]"
                        >
                            <img
                                :src="img.image_path"
                                class="w-full h-full object-cover"
                            />
                        </button>
                    </div>

                    <!-- Trust Verification Banner Under Gallery -->
                    <div
                        class="p-4 bg-[#181B21] border border-[#2B2F38] rounded-xl flex items-center justify-between text-xs"
                    >
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 rounded-md bg-[#3FA79B]/15 text-[#3FA79B] flex items-center justify-center shrink-0"
                            >
                                <ShieldCheck class="w-5 h-5" />
                            </div>
                            <div>
                                <span class="font-bold text-[#EDE9DE] block"
                                    >১০০% ভেরিফাইড স্ক্রিনশট ও আইডি</span
                                >
                                <span
                                    class="text-[#8E93A0] text-[11px] font-['Hind_Siliguri',sans-serif]"
                                    >আমাদের মডারেটর টিম সরাসরি গেম আইডি লগইন করে
                                    ভেরিফাই করেছে।</span
                                >
                            </div>
                        </div>
                        <span
                            class="hidden sm:inline-block px-2.5 py-1 bg-[#20242C] text-[#3FA79B] font-mono text-[11px] rounded border border-[#2B2F38]"
                        >
                            Verified
                        </span>
                    </div>

                    <!-- Account Description Block -->
                    <div
                        class="p-6 bg-[#181B21] border border-[#2B2F38] rounded-xl space-y-3"
                    >
                        <h3
                            class="font-['Sora',sans-serif] font-bold text-base text-[#EDE9DE] flex items-center space-x-2"
                        >
                            <span>অ্যাকাউন্ট বিস্তারিত বিবরণ</span>
                        </h3>
                        <div
                            class="text-xs sm:text-sm text-[#EDE9DE]/90 font-['Hind_Siliguri',sans-serif] leading-relaxed whitespace-pre-line"
                        >
                            {{
                                listing.description ||
                                "সম্পূর্ণ ফ্রেশ ও নিরাপদ আইডি। কোনো প্রকার থার্ড পার্টি টুল বা ইল্লিগ্যাল অ্যাট্রিবিউট ব্যবহার করা হয়নি। স্ক্রিনশটে প্রদর্শিত প্রতিটি গান স্কিন এবং বান্ডেল ইনভেন্টরিতে বিদ্যমান।"
                            }}
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: SPECS, PRICING & WHATSAPP ACTION (~40% on desktop) -->
                <div class="lg:col-span-5 space-y-6">
                    <!-- Title, Badges & Price Header Card -->
                    <div
                        class="p-6 bg-[#181B21] border border-[#2B2F38] rounded-xl space-y-4 shadow-xl"
                    >
                        <!-- Status and Tag -->
                        <div class="flex items-center justify-between">
                            <span
                                class="px-2.5 py-1 rounded text-xs font-semibold bg-[#3FA79B]/10 border border-[#3FA79B]/30 text-[#3FA79B] flex items-center space-x-1.5"
                            >
                                <span
                                    class="w-2 h-2 rounded-full bg-[#3FA79B] animate-pulse"
                                ></span>
                                <span>Available ●</span>
                            </span>

                            <span
                                v-if="listing.is_featured"
                                class="px-2.5 py-0.5 rounded text-xs font-bold bg-[#E3A339] text-[#0F1115] shadow flex items-center space-x-1"
                            >
                                <Sparkles class="w-3.5 h-3.5 fill-current" />
                                <span>Featured Account</span>
                            </span>
                        </div>

                        <!-- Title -->
                        <h1
                            class="text-xl sm:text-2xl font-['Sora',sans-serif] font-bold text-[#EDE9DE] leading-snug"
                        >
                            {{ listing.title }}
                        </h1>

                        <!-- UID & Age Row -->
                        <div
                            class="p-3 bg-[#20242C] rounded-lg border border-[#2B2F38] flex items-center justify-between text-xs"
                        >
                            <div class="flex items-center space-x-2">
                                <span class="text-[#8E93A0]">UID:</span>
                                <span
                                    class="font-mono font-bold text-[#EDE9DE] select-all"
                                    >{{ listing.uid }}</span
                                >
                            </div>
                            <button
                                @click="copyUid"
                                class="px-2 py-1 bg-[#181B21] hover:bg-[#2B2F38] text-[11px] text-[#EDE9DE] rounded border border-[#2B2F38] flex items-center space-x-1 transition-colors"
                            >
                                <Check
                                    v-if="isCopied"
                                    class="w-3 h-3 text-[#3FA79B]"
                                />
                                <Copy v-else class="w-3 h-3 text-[#8E93A0]" />
                                <span>{{
                                    isCopied ? "Copied" : "Copy UID"
                                }}</span>
                            </button>
                        </div>

                        <!-- Price Row (Primary Eye Attraction) -->
                        <div
                            class="pt-2 border-t border-[#2B2F38] flex items-end justify-between"
                        >
                            <div>
                                <span
                                    class="text-xs text-[#8E93A0] block uppercase tracking-wider font-semibold"
                                    >নির্ধারিত বিক্রয়মূল্য</span
                                >
                                <span
                                    class="font-['Sora',sans-serif] font-bold text-3xl sm:text-4xl text-[#E3A339]"
                                >
                                    {{ formatPrice(listing.price) }}
                                </span>
                            </div>
                            <span
                                class="text-xs text-[#8E93A0] font-['Hind_Siliguri',sans-serif]"
                                >কোনো গোপন ফি নেই</span
                            >
                        </div>

                        <!-- Single Gold WhatsApp CTA Button (Per Design Guideline) -->
                        <div class="pt-3 space-y-2">
                            <a
                                :href="getWhatsAppUrl(listing)"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="w-full py-3.5 px-6 bg-[#E3A339] hover:bg-[#E3A339]/90 text-[#0F1115] font-['Sora',sans-serif] font-bold text-base rounded-md flex items-center justify-center space-x-2 transition-all shadow-xl active:scale-98"
                            >
                                <span>Contact on WhatsApp</span>
                                <ExternalLink class="w-4 h-4" />
                            </a>
                            <p
                                class="text-[11px] text-[#8E93A0] text-center font-['Hind_Siliguri',sans-serif]"
                            >
                                সরাসরি নির্ধারিত ভেরিফাইড মডারেটরের সাথে কথা বলে
                                আইডি হস্তান্তর নিশ্চিত করুন।
                            </p>
                        </div>
                    </div>

                    <!-- Specification Metric Grid -->
                    <div
                        class="p-6 bg-[#181B21] border border-[#2B2F38] rounded-xl space-y-3"
                    >
                        <span
                            class="text-xs font-bold text-[#8E93A0] uppercase tracking-wider block"
                            >অ্যাকাউন্ট স্পেসিফিকেশন</span
                        >
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div
                                class="p-3 bg-[#20242C] rounded-lg border border-[#2B2F38]"
                            >
                                <span class="text-[11px] text-[#8E93A0] block"
                                    >লেভেল (Level)</span
                                >
                                <span
                                    class="font-['Sora',sans-serif] font-bold text-lg text-[#EDE9DE]"
                                    >{{ listing.level }}</span
                                >
                            </div>
                            <div
                                class="p-3 bg-[#20242C] rounded-lg border border-[#2B2F38]"
                            >
                                <span class="text-[11px] text-[#8E93A0] block"
                                    >অ্যাকাউন্টের বয়স</span
                                >
                                <span
                                    class="font-['Sora',sans-serif] font-semibold text-sm text-[#EDE9DE]"
                                    >{{ listing.account_age }}</span
                                >
                            </div>
                            <div
                                class="p-3 bg-[#20242C] rounded-lg border border-[#2B2F38]"
                            >
                                <span class="text-[11px] text-[#8E93A0] block"
                                    >ইভো গান স্কিন</span
                                >
                                <span
                                    class="font-['Sora',sans-serif] font-bold text-lg text-[#E3A339]"
                                    >{{ listing.gun_skin_count }} টি</span
                                >
                            </div>
                            <div
                                class="p-3 bg-[#20242C] rounded-lg border border-[#2B2F38]"
                            >
                                <span class="text-[11px] text-[#8E93A0] block"
                                    >এলিট পাস</span
                                >
                                <span
                                    class="font-['Sora',sans-serif] font-bold text-lg text-[#EDE9DE]"
                                    >{{ listing.elite_pass_count }} টি</span
                                >
                            </div>
                            <div
                                class="p-3 bg-[#20242C] rounded-lg border border-[#2B2F38]"
                            >
                                <span class="text-[11px] text-[#8E93A0] block"
                                    >ক্যারেক্টার সংখ্যা</span
                                >
                                <span
                                    class="font-['Sora',sans-serif] font-bold text-lg text-[#EDE9DE]"
                                    >{{ listing.character_count }} টি</span
                                >
                            </div>
                            <div
                                class="p-3 bg-[#20242C] rounded-lg border border-[#2B2F38]"
                            >
                                <span class="text-[11px] text-[#8E93A0] block"
                                    >রেয়ার আইটেম</span
                                >
                                <span
                                    class="font-['Sora',sans-serif] font-bold text-lg text-[#3FA79B]"
                                    >{{ listing.rare_item_count }} টি</span
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Assigned Staff Moderator Card -->
                    <div
                        class="p-5 bg-[#181B21] border border-[#2B2F38] rounded-xl space-y-3"
                    >
                        <span
                            class="text-xs font-bold text-[#8E93A0] uppercase tracking-wider block"
                            >নির্ধারিত ভেরিফাইড মডারেটর</span
                        >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-[#3FA79B]/20 text-[#3FA79B] flex items-center justify-center font-bold text-base border border-[#3FA79B]/30"
                                >
                                    ✓
                                </div>
                                <div>
                                    <span
                                        class="font-['Sora',sans-serif] font-bold text-sm text-[#EDE9DE] block"
                                    >
                                        {{
                                            listing.moderator?.name ||
                                            "Masum (Admin)"
                                        }}
                                    </span>
                                    <span class="text-xs text-[#8E93A0]"
                                        >@{{
                                            listing.moderator?.username ||
                                            "masum"
                                        }}</span
                                    >
                                </div>
                            </div>
                            <div class="text-right">
                                <span
                                    class="text-xs text-[#3FA79B] font-mono font-semibold block"
                                >
                                    {{
                                        listing.moderator?.whatsapp_number ||
                                        "+880 1700-000000"
                                    }}
                                </span>
                                <span class="text-[10px] text-[#8E93A0]"
                                    >Official WhatsApp</span
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Safety Handover Checklist Reminder -->
                    <div
                        class="p-4 bg-gradient-to-r from-amber-500/10 via-[#181B21] to-[#181B21] border border-amber-500/30 rounded-xl space-y-2 text-xs"
                    >
                        <div
                            class="flex items-center space-x-2 text-amber-400 font-bold"
                        >
                            <AlertTriangle class="w-4 h-4" />
                            <span>নিরাপদ লেনদেন সতর্কতা</span>
                        </div>
                        <p
                            class="text-[#8E93A0] font-['Hind_Siliguri',sans-serif] leading-relaxed"
                        >
                            শুধুমাত্র উপরে প্রদর্শিত নম্বরে WhatsApp-এ লেনদেন
                            করুন। হ্যান্ডওভারের সময় লাইভ 2FA অন করে নিজের ফোন
                            নম্বর ও রিকভারি ইমেইল যুক্ত করে নিন।
                        </p>
                    </div>
                </div>
            </div>

            <!-- RELATED LISTINGS SECTION -->
            <section
                v-if="relatedListings && relatedListings.length > 0"
                class="mt-16 pt-12 border-t border-[#2B2F38]"
            >
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <span
                            class="text-xs font-semibold text-[#3FA79B] uppercase tracking-wider block"
                            >আরও অপশন</span
                        >
                        <h2
                            class="text-xl sm:text-2xl font-['Sora',sans-serif] font-bold text-[#EDE9DE]"
                        >
                            সম্পর্কিত অন্যান্য অ্যাকাউন্টসমূহ
                        </h2>
                    </div>
                    <Link
                        href="/listings"
                        class="text-xs text-[#E3A339] hover:underline font-semibold flex items-center space-x-1"
                    >
                        <span>সব আইডি দেখুন</span>
                        <ChevronRight class="w-3.5 h-3.5" />
                    </Link>
                </div>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <div
                        v-for="rel in relatedListings"
                        :key="rel.id"
                        class="bg-[#181B21] border border-[#2B2F38] hover:border-[#8E93A0]/60 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 flex flex-col justify-between group"
                    >
                        <!-- Cover Image -->
                        <Link
                            :href="`/listings/${rel.slug}`"
                            class="relative aspect-[16/10] overflow-hidden bg-[#0F1115] block"
                        >
                            <img
                                :src="getListingCover(rel)"
                                :alt="rel.title"
                                loading="lazy"
                                class="w-full h-full object-cover transform group-hover:scale-103 transition-transform duration-500"
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-[#181B21] via-transparent to-transparent opacity-80"
                            ></div>
                            <div
                                class="absolute top-3 inset-x-3 flex items-center justify-between"
                            >
                                <span
                                    class="px-2 py-0.5 rounded text-[10px] font-semibold bg-[#E3A339] text-[#0F1115]"
                                >
                                    Level {{ rel.level }}
                                </span>
                                <span
                                    class="px-2 py-0.5 rounded text-[10px] font-medium bg-[#0F1115]/85 border border-[#2B2F38] text-[#3FA79B]"
                                >
                                    Available ●
                                </span>
                            </div>
                        </Link>

                        <!-- Content -->
                        <div
                            class="p-4 space-y-3 flex-1 flex flex-col justify-between"
                        >
                            <div>
                                <Link
                                    :href="`/listings/${rel.slug}`"
                                    class="font-['Sora',sans-serif] font-bold text-sm text-[#EDE9DE] group-hover:text-[#E3A339] transition-colors line-clamp-1 block"
                                >
                                    {{ rel.title }}
                                </Link>
                                <div
                                    class="flex items-center space-x-2 text-[11px] text-[#8E93A0] mt-0.5"
                                >
                                    <span>UID: {{ rel.uid }}</span>
                                    <span>•</span>
                                    <span
                                        >Evo Guns:
                                        {{ rel.gun_skin_count }}</span
                                    >
                                </div>
                            </div>

                            <div
                                class="pt-2 border-t border-[#2B2F38] flex items-center justify-between"
                            >
                                <span
                                    class="font-['Sora',sans-serif] font-bold text-base text-[#EDE9DE]"
                                >
                                    {{ formatPrice(rel.price) }}
                                </span>

                                <a
                                    :href="getWhatsAppUrl(rel)"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="px-3 py-1 bg-[#E3A339] hover:bg-[#E3A339]/90 text-[#0F1115] text-xs font-bold rounded-md flex items-center space-x-1 transition-colors"
                                >
                                    <span>WhatsApp</span>
                                    <ExternalLink class="w-3 h-3" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- FOOTER (English Only Navigation) -->
        <footer
            class="border-t border-[#2B2F38] bg-[#0F1115] py-12 text-xs text-[#8E93A0]"
        >
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
                            :href="home.url()"
                            class="hover:text-[#EDE9DE] transition-colors"
                            >Home</Link
                        >
                        <Link
                            href="/listings"
                            class="text-[#E3A339] font-medium hover:underline"
                            >All IDs</Link
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
    </div>
</template>
