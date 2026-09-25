<script setup lang="ts">
import { Form, Head } from "@inertiajs/vue3";
import { ref } from "vue";
import InputError from "@/components/InputError.vue";
import PasswordInput from "@/components/PasswordInput.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Spinner } from "@/components/ui/spinner";
import { update } from "@/routes/password";
import { ArrowRight, CheckCircle2 } from "@lucide/vue";

defineOptions({
    layout: {
        title: "Set New Password",
        description: "আপনার নতুন পাসওয়ার্ড সেট করে অ্যাকাউন্টে প্রবেশ করুন",
    },
});

const props = defineProps<{
    token: string;
    email: string;
    passwordRules: string;
}>();

const inputEmail = ref(props.email);
</script>

<template>
    <Head title="Reset Password - Gaming With Masum" />

    <Form
        v-bind="update.form()"
        :transform="(data) => ({ ...data, token, email })"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-5"
    >
        <div class="grid gap-4">
            <div class="grid gap-1.5">
                <Label for="email" class="text-xs font-semibold text-[#EDE9DE]">
                    Email Address
                </Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    autocomplete="email"
                    v-model="inputEmail"
                    readonly
                    class="bg-[#14161B]/60 border-[#2B2F38] text-[#8E93A0] cursor-not-allowed"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-1.5">
                <Label
                    for="password"
                    class="text-xs font-semibold text-[#EDE9DE]"
                >
                    New Password
                </Label>
                <PasswordInput
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    autofocus
                    placeholder="Enter new password"
                    :passwordrules="passwordRules"
                    class="bg-[#14161B] border-[#2B2F38] text-[#EDE9DE] placeholder-[#8E93A0] focus-visible:border-[#E3A339] focus-visible:ring-[#E3A339]/20"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="grid gap-1.5">
                <Label
                    for="password_confirmation"
                    class="text-xs font-semibold text-[#EDE9DE]"
                >
                    Confirm New Password
                </Label>
                <PasswordInput
                    id="password_confirmation"
                    name="password_confirmation"
                    autocomplete="new-password"
                    placeholder="Repeat new password"
                    :passwordrules="passwordRules"
                    class="bg-[#14161B] border-[#2B2F38] text-[#EDE9DE] placeholder-[#8E93A0] focus-visible:border-[#E3A339] focus-visible:ring-[#E3A339]/20"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <Button
                type="submit"
                class="mt-2 w-full bg-[#E3A339] hover:bg-[#E3A339]/90 text-[#0F1115] font-['Sora',sans-serif] font-bold text-xs sm:text-sm py-2.5 rounded-md shadow-md transition-all active:scale-98 flex items-center justify-center space-x-2"
                :disabled="processing"
                data-test="reset-password-button"
            >
                <Spinner v-if="processing" class="mr-2" />
                <span>Save New Password</span>
                <ArrowRight v-if="!processing" class="w-4 h-4 ml-1" />
            </Button>
        </div>
    </Form>
</template>
