<script setup lang="ts">
import { Form, Head } from "@inertiajs/vue3";
import InputError from "@/components/InputError.vue";
import PasswordInput from "@/components/PasswordInput.vue";
import TextLink from "@/components/TextLink.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Spinner } from "@/components/ui/spinner";
import { login } from "@/routes";
import { store } from "@/routes/register";
import { ArrowRight } from "@lucide/vue";

defineProps<{
    passwordRules: string;
}>();

defineOptions({
    layout: {
        title: "Create Your Account",
        description: "নিরাপদ অ্যাকাউন্ট তৈরি করে ট্রেডিং বা লিস্টিং শুরু করুন",
    },
});
</script>

<template>
    <Head title="Register - Gaming With Masum" />

    <Form
        v-bind="store.form()"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-5"
    >
        <div class="grid gap-4">
            <div class="grid gap-1.5">
                <Label for="name" class="text-xs font-semibold text-[#EDE9DE]">
                    Full Name
                </Label>
                <Input
                    id="name"
                    type="text"
                    required
                    v-focus
                    :tabindex="1"
                    autocomplete="name"
                    name="name"
                    placeholder="Masum Ahmed"
                    class="bg-[#14161B] border-[#2B2F38] text-[#EDE9DE] placeholder-[#8E93A0] focus-visible:border-[#E3A339] focus-visible:ring-[#E3A339]/20"
                />
                <InputError :message="errors.name" />
            </div>

            <div class="grid gap-1.5">
                <Label for="email" class="text-xs font-semibold text-[#EDE9DE]">
                    Email Address
                </Label>
                <Input
                    id="email"
                    type="email"
                    required
                    :tabindex="2"
                    autocomplete="email"
                    name="email"
                    placeholder="yourname@gmail.com"
                    class="bg-[#14161B] border-[#2B2F38] text-[#EDE9DE] placeholder-[#8E93A0] focus-visible:border-[#E3A339] focus-visible:ring-[#E3A339]/20"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-1.5">
                <Label
                    for="password"
                    class="text-xs font-semibold text-[#EDE9DE]"
                >
                    Password
                </Label>
                <PasswordInput
                    id="password"
                    required
                    :tabindex="3"
                    autocomplete="new-password"
                    name="password"
                    placeholder="Create a strong password"
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
                    Confirm Password
                </Label>
                <PasswordInput
                    id="password_confirmation"
                    required
                    :tabindex="4"
                    autocomplete="new-password"
                    name="password_confirmation"
                    placeholder="Repeat your password"
                    :passwordrules="passwordRules"
                    class="bg-[#14161B] border-[#2B2F38] text-[#EDE9DE] placeholder-[#8E93A0] focus-visible:border-[#E3A339] focus-visible:ring-[#E3A339]/20"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <Button
                type="submit"
                class="mt-2 w-full bg-[#E3A339] hover:bg-[#E3A339]/90 text-[#0F1115] font-['Sora',sans-serif] font-bold text-xs sm:text-sm py-2.5 rounded-md shadow-md transition-all active:scale-98 flex items-center justify-center space-x-2"
                :tabindex="5"
                :disabled="processing"
                data-test="register-user-button"
            >
                <Spinner v-if="processing" class="mr-2" />
                <span>Create Account</span>
                <ArrowRight v-if="!processing" class="w-4 h-4 ml-1" />
            </Button>
        </div>

        <div
            class="pt-4 border-t border-[#2B2F38] text-center text-xs text-[#8E93A0]"
        >
            Already have an account?
            <TextLink
                :href="login()"
                class="text-[#E3A339] hover:underline font-semibold ml-1"
                :tabindex="6"
            >
                Log in here
            </TextLink>
        </div>
    </Form>
</template>
