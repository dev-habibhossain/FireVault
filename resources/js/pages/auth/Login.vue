<script setup lang="ts">
import { Form, Head } from "@inertiajs/vue3";
import InputError from "@/components/InputError.vue";
import PasswordInput from "@/components/PasswordInput.vue";
import TextLink from "@/components/TextLink.vue";
import { Button } from "@/components/ui/button";
import { Checkbox } from "@/components/ui/checkbox";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Spinner } from "@/components/ui/spinner";
import { register } from "@/routes";
import { store } from "@/routes/login";
import { request } from "@/routes/password";
import { ArrowRight } from "@lucide/vue";

defineOptions({
    layout: {
        title: "Sign In to Your Account",
        description:
            "অ্যাকাউন্টে প্রবেশ করতে আপনার ইমেইল ও পাসওয়ার্ড প্রদান করুন",
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();
</script>

<template>
    <Head title="Log In - Gaming With Masum" />

    <div
        v-if="status"
        class="mb-5 p-3 rounded-md bg-[#3FA79B]/15 border border-[#3FA79B]/30 text-center text-xs font-semibold text-[#3FA79B]"
    >
        {{ status }}
    </div>

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-5"
    >
        <div class="grid gap-5">
            <div class="grid gap-1.5">
                <Label for="email" class="text-xs font-semibold text-[#EDE9DE]">
                    Email Address
                </Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    required
                    v-focus
                    :tabindex="1"
                    autocomplete="email"
                    placeholder="moderator@firevault.gg"
                    class="bg-[#14161B] border-[#2B2F38] text-[#EDE9DE] placeholder-[#8E93A0] focus-visible:border-[#E3A339] focus-visible:ring-[#E3A339]/20"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-1.5">
                <div class="flex items-center justify-between">
                    <Label
                        for="password"
                        class="text-xs font-semibold text-[#EDE9DE]"
                    >
                        Password
                    </Label>
                    <TextLink
                        v-if="canResetPassword"
                        :href="request()"
                        class="text-xs text-[#E3A339] hover:text-[#E3A339]/80 transition-colors"
                        :tabindex="5"
                    >
                        Forgot password?
                    </TextLink>
                </div>
                <PasswordInput
                    id="password"
                    name="password"
                    required
                    :tabindex="2"
                    autocomplete="current-password"
                    placeholder="Enter your password"
                    class="bg-[#14161B] border-[#2B2F38] text-[#EDE9DE] placeholder-[#8E93A0] focus-visible:border-[#E3A339] focus-visible:ring-[#E3A339]/20"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="flex items-center justify-between pt-0.5">
                <Label
                    for="remember"
                    class="flex items-center space-x-2.5 text-xs text-[#8E93A0] cursor-pointer select-none"
                >
                    <Checkbox
                        id="remember"
                        name="remember"
                        :tabindex="3"
                        class="border-[#2B2F38] data-[state=checked]:bg-[#E3A339] data-[state=checked]:text-[#0F1115] data-[state=checked]:border-[#E3A339]"
                    />
                    <span class="text-[#EDE9DE] text-xs font-medium"
                        >Remember this device</span
                    >
                </Label>
            </div>

            <Button
                type="submit"
                class="mt-2 w-full bg-[#E3A339] hover:bg-[#E3A339]/90 text-[#0F1115] font-['Sora',sans-serif] font-bold text-xs sm:text-sm py-2.5 rounded-md shadow-md transition-all active:scale-98 flex items-center justify-center space-x-2"
                :tabindex="4"
                :disabled="processing"
                data-test="login-button"
            >
                <Spinner v-if="processing" class="mr-2" />
                <span>Sign In to Portal</span>
                <ArrowRight v-if="!processing" class="w-4 h-4 ml-1" />
            </Button>
        </div>

        <div
            class="pt-4 border-t border-[#2B2F38] text-center text-xs text-[#8E93A0]"
        >
            Don't have an account yet?
            <TextLink
                :href="register()"
                class="text-[#E3A339] hover:underline font-semibold ml-1"
                :tabindex="6"
            >
                Sign up here
            </TextLink>
        </div>
    </Form>
</template>
