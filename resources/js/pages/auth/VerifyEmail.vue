<script setup lang="ts">
import { Form, Head } from "@inertiajs/vue3";
import TextLink from "@/components/TextLink.vue";
import { Button } from "@/components/ui/button";
import { Spinner } from "@/components/ui/spinner";
import { logout } from "@/routes";
import { send } from "@/routes/verification";
import { MailCheck } from "@lucide/vue";

defineOptions({
    layout: {
        title: "Verify Your Email",
        description:
            "আমরা আপনার ইমেইলে একটি ভেরিফিকেশন লিংক পাঠিয়েছি। অনুগ্রহ করে লিঙ্কটি চেক করুন।",
    },
});

defineProps<{
    status?: string;
}>();
</script>

<template>
    <Head title="Verify Email - Gaming With Masum" />

    <div
        v-if="status === 'verification-link-sent'"
        class="mb-5 p-3 rounded-md bg-[#3FA79B]/15 border border-[#3FA79B]/30 text-center text-xs font-semibold text-[#3FA79B]"
    >
        A new verification link has been sent to your registered email address.
    </div>

    <Form
        v-bind="send.form()"
        class="space-y-5 text-center"
        v-slot="{ processing }"
    >
        <div
            class="p-4 rounded-lg bg-[#14161B] border border-[#2B2F38] text-xs text-[#8E93A0] space-y-2"
        >
            <MailCheck class="w-8 h-8 text-[#E3A339] mx-auto" />
            <p>
                Didn't receive the email? Check your spam folder or request a
                new verification link below.
            </p>
        </div>

        <Button
            type="submit"
            class="w-full bg-[#E3A339] hover:bg-[#E3A339]/90 text-[#0F1115] font-['Sora',sans-serif] font-bold text-xs sm:text-sm py-2.5 rounded-md shadow-md transition-all active:scale-98"
            :disabled="processing"
        >
            <Spinner v-if="processing" class="mr-2" />
            Resend Verification Email
        </Button>

        <div class="pt-2 text-center text-xs text-[#8E93A0]">
            <TextLink
                :href="logout()"
                as="button"
                class="text-[#8E93A0] hover:text-[#EDE9DE] underline"
            >
                Log Out
            </TextLink>
        </div>
    </Form>
</template>
