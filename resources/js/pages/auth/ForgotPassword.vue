<script setup lang="ts">
import { Form, Head } from "@inertiajs/vue3";
import InputError from "@/components/InputError.vue";
import TextLink from "@/components/TextLink.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Spinner } from "@/components/ui/spinner";
import { login } from "@/routes";
import { email } from "@/routes/password";
import { ArrowLeft, Send } from "@lucide/vue";

defineOptions({
    layout: {
        title: "Reset Your Password",
        description: "আপনার রেজিস্টার্ড ইমেইলে পাসওয়ার্ড রিসেট লিংক পাঠানো হবে",
    },
});

defineProps<{
    status?: string;
}>();
</script>

<template>
    <Head title="Forgot Password - Gaming With Masum" />

    <div
        v-if="status"
        class="mb-5 p-3 rounded-md bg-[#3FA79B]/15 border border-[#3FA79B]/30 text-center text-xs font-semibold text-[#3FA79B]"
    >
        {{ status }}
    </div>

    <Form
        v-bind="email.form()"
        v-slot="{ errors, processing }"
        class="space-y-5"
    >
        <div class="grid gap-1.5">
            <Label for="email" class="text-xs font-semibold text-[#EDE9DE]">
                Email Address
            </Label>
            <Input
                id="email"
                type="email"
                name="email"
                autocomplete="off"
                v-focus
                placeholder="yourname@gmail.com"
                class="bg-[#14161B] border-[#2B2F38] text-[#EDE9DE] placeholder-[#8E93A0] focus-visible:border-[#E3A339] focus-visible:ring-[#E3A339]/20"
            />
            <InputError :message="errors.email" />
        </div>

        <Button
            type="submit"
            class="w-full bg-[#E3A339] hover:bg-[#E3A339]/90 text-[#0F1115] font-['Sora',sans-serif] font-bold text-xs sm:text-sm py-2.5 rounded-md shadow-md transition-all active:scale-98 flex items-center justify-center space-x-2"
            :disabled="processing"
            data-test="email-password-reset-link-button"
        >
            <Spinner v-if="processing" class="mr-2" />
            <span>Send Reset Link</span>
            <Send v-if="!processing" class="w-4 h-4 ml-1" />
        </Button>

        <div
            class="pt-4 border-t border-[#2B2F38] text-center text-xs text-[#8E93A0]"
        >
            <TextLink
                :href="login()"
                class="inline-flex items-center space-x-1.5 text-[#E3A339] hover:underline font-semibold"
            >
                <ArrowLeft class="w-3.5 h-3.5" />
                <span>Return to Log In</span>
            </TextLink>
        </div>
    </Form>
</template>
