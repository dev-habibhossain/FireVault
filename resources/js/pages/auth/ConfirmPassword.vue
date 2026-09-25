<script setup lang="ts">
import { Form, Head } from "@inertiajs/vue3";
import InputError from "@/components/InputError.vue";
import PasswordInput from "@/components/PasswordInput.vue";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import { Spinner } from "@/components/ui/spinner";
import { store } from "@/routes/password/confirm";
import { ShieldAlert } from "@lucide/vue";

defineOptions({
    layout: {
        title: "Confirm Password",
        description:
            "এটি একটি সুরক্ষিত অংশ। অনুগ্রহ করে এগিয়ে যেতে আপনার পাসওয়ার্ড নিশ্চিত করুন।",
    },
});
</script>

<template>
    <Head title="Confirm Password - Gaming With Masum" />

    <Form
        v-bind="store.form()"
        reset-on-success
        v-slot="{ errors, processing }"
        class="space-y-5"
    >
        <div class="grid gap-1.5">
            <Label for="password" class="text-xs font-semibold text-[#EDE9DE]">
                Password
            </Label>
            <PasswordInput
                id="password"
                name="password"
                required
                autocomplete="current-password"
                autofocus
                placeholder="Enter your password"
                class="bg-[#14161B] border-[#2B2F38] text-[#EDE9DE] placeholder-[#8E93A0] focus-visible:border-[#E3A339] focus-visible:ring-[#E3A339]/20"
            />
            <InputError :message="errors.password" />
        </div>

        <Button
            type="submit"
            class="w-full bg-[#E3A339] hover:bg-[#E3A339]/90 text-[#0F1115] font-['Sora',sans-serif] font-bold text-xs sm:text-sm py-2.5 rounded-md shadow-md transition-all active:scale-98 flex items-center justify-center space-x-2"
            :disabled="processing"
            data-test="confirm-password-button"
        >
            <Spinner v-if="processing" class="mr-2" />
            <span>Confirm & Continue</span>
        </Button>
    </Form>
</template>
