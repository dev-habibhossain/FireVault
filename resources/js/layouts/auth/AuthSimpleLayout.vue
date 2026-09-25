<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { home } from "@/routes";
import { Flame, ArrowLeft, ShieldCheck } from "@lucide/vue";

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="min-h-screen bg-[#0F1115] text-[#EDE9DE] flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden"
    >
        <!-- Ambient Vault Glow -->
        <div
            class="absolute -top-32 left-1/2 -translate-x-1/2 w-[700px] h-[350px] bg-gradient-to-b from-[#E3A339]/12 via-[#3FA79B]/6 to-transparent rounded-full blur-3xl pointer-events-none"
        ></div>

        <!-- Back to Marketplace Navigation -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md px-4 mb-4 relative z-10">
            <Link
                :href="home()"
                class="inline-flex items-center space-x-2 text-xs font-semibold text-[#8E93A0] hover:text-[#EDE9DE] transition-colors"
            >
                <ArrowLeft class="w-3.5 h-3.5" />
                <span>Back to Marketplace</span>
            </Link>
        </div>

        <!-- Center Card Container -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md px-4 relative z-10">
            <!-- Header Brand Emblem & Title -->
            <div class="text-center mb-6">
                <Link
                    :href="home()"
                    class="inline-flex flex-col items-center group"
                >
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#E3A339] to-[#3FA79B] flex items-center justify-center shadow-lg shadow-[#E3A339]/15 group-hover:scale-105 transition-transform"
                    >
                        <Flame class="w-6 h-6 text-[#0F1115] fill-current" />
                    </div>
                    <span
                        class="mt-3 font-['Sora',sans-serif] font-bold text-lg text-[#EDE9DE] tracking-tight block"
                    >
                        Gaming With Masum
                    </span>
                    <span
                        class="text-[11px] text-[#8E93A0] uppercase tracking-wider font-semibold block -mt-0.5"
                    >
                        Verified Free Fire Marketplace
                    </span>
                </Link>

                <div class="mt-4 space-y-1">
                    <h1
                        class="font-['Sora',sans-serif] font-bold text-xl text-[#EDE9DE]"
                    >
                        {{ title }}
                    </h1>
                    <p
                        v-if="description"
                        class="text-xs text-[#8E93A0] font-['Hind_Siliguri',sans-serif]"
                    >
                        {{ description }}
                    </p>
                </div>
            </div>

            <!-- Card Box with Hairline Border -->
            <div
                class="bg-[#181B21] border border-[#2B2F38] rounded-xl shadow-2xl p-6 sm:p-8 relative"
            >
                <slot />
            </div>

            <!-- Bottom Security Footer -->
            <div
                class="mt-6 text-center text-xs text-[#8E93A0] flex items-center justify-center space-x-1.5"
            >
                <ShieldCheck class="w-4 h-4 text-[#3FA79B]" />
                <span
                    >Encrypted & Moderated Session • FireVault Protection</span
                >
            </div>
        </div>
    </div>
</template>
