<script setup lang="ts">
import AlertError from '@/components/AlertError.vue';
import InputError from '@/components/InputError.vue';
import { AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { GlitchText } from '@/components/ui/glitch-text';
import { Input } from '@/components/ui/input';
import { Keypad } from '@/components/ui/keypad';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { Textarea } from '@/components/ui/textarea';
import { store } from '@/routes/contact';
import { Form, useForm, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const showSuccess = ref(false);
const showError = ref(false);

const page = usePage();

const form = useForm({
    name: '',
    email: '',
    message: '',
});

// Watch for flash messages and set timeouts
watch(
    () => page.props.flash,
    (newValue) => {
        if (newValue?.success) {
            showSuccess.value = true;
            setTimeout(() => {
                showSuccess.value = false;
            }, 10000);
        }

        if (newValue?.error) {
            showError.value = true;
            setTimeout(() => {
                showError.value = false;
            }, 10000);
        }
    },
    { immediate: true, deep: true },
);

const submit = () => {
    const routeData = store();
    form.post(routeData.url, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const handleKeyPress = () => {
    submit();
};
</script>

<template>
    <section id="main-contact" class="main-contact w-full px-5">
        <div class="container mx-auto mt-20 mb-20 max-w-4xl">
            <div class="grid md:grid-cols-2">
                <div>
                    <Card class="rounded-xl">
                        <CardHeader class="px-4 pt-4 pb-0 text-center sm:px-10">
                            <GlitchText
                                class="content-center text-4xl font-bold sm:text-5xl lg:text-6xl"
                                data-text="Contact me"
                                >Contact me</GlitchText
                            >
                        </CardHeader>
                        <CardContent class="px-4 py-4 sm:px-10">
                            <Form
                                @submit.prevent="submit"
                                :reset-on-success="['name', 'email', 'message']"
                                class="flex flex-col"
                            >
                                <div class="grid gap-4">
                                    <div class="grid gap-2">
                                        <Label class="text-lg" for="email"
                                            >Name*</Label
                                        >
                                        <Input
                                            class="text-lg"
                                            id="name"
                                            type="text"
                                            name="name"
                                            required
                                            :tabindex="1"
                                            placeholder="Name"
                                            v-model="form.name"
                                        />
                                        <InputError
                                            :message="form.errors.name"
                                        />
                                    </div>
                                    <div class="grid gap-2">
                                        <Label class="text-lg" for="email"
                                            >Email*</Label
                                        >
                                        <Input
                                            class="text-lg"
                                            id="email"
                                            type="email"
                                            name="email"
                                            required
                                            :tabindex="1"
                                            placeholder="email@example.com"
                                            v-model="form.email"
                                        />
                                        <InputError
                                            :message="form.errors.email"
                                        />
                                    </div>
                                    <div class="grid gap-2">
                                        <Label class="text-lg" for="email"
                                            >Message*</Label
                                        >
                                        <Textarea
                                            class="h-30 text-lg"
                                            id="message"
                                            name="message"
                                            required
                                            :tabindex="1"
                                            placeholder="Message"
                                            v-model="form.message"
                                        />
                                        <InputError
                                            :message="form.errors.message"
                                        />
                                    </div>
                                    <Button
                                        type="submit"
                                        size="xl"
                                        class="mt-4 w-full cursor-pointer"
                                        :tabindex="4"
                                        :disabled="form.processing"
                                        data-test="login-button"
                                    >
                                        <Spinner v-if="form.processing" />
                                        <span v-else>Send Message</span>
                                    </Button>
                                </div>
                            </Form>
                            <Transition name="fade">
                                <AlertTitle
                                    v-if="
                                        showSuccess && $page.props.flash.success
                                    "
                                    class="mt-4 mb-4 text-center text-lg font-medium text-chart-2"
                                    :errors="[$page.props.flash.success]"
                                    >{{ $page.props.flash.success }}</AlertTitle
                                >
                            </Transition>
                            <Transition name="fade">
                                <AlertError
                                    v-if="showError && $page.props.flash.error"
                                    class="mt-4 mb-4 text-lg text-chart-5"
                                    :errors="[$page.props.flash.error]"
                                    >{{ $page.props.flash.error }}</AlertError
                                >
                            </Transition>
                        </CardContent>
                    </Card>
                </div>
                <div
                    class="mt-10 flex flex-col items-center justify-center md:mt-0"
                >
                    <Keypad @keyPress="handleKeyPress" />
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

:root {
    --travel: 20;
    --hue: 0; /* Default hue value */
    --saturate: 1; /* Default saturation value */
    --brightness: 1; /* For completeness, since it's also used */
}
.main-contact {
    position: relative;
    display: block;
    overflow: hidden;
    z-index: 1;
}

.main-contact:before {
    --size: 45px;
    --line: hsla(0, 0%, 0%, 0.2);
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
        linear-gradient(90deg, var(--line) 1px, transparent 1px var(--size))
            calc(var(--size) * 0.36) 50% / var(--size) var(--size),
        linear-gradient(var(--line) 1px, transparent 1px var(--size)) 0%
            calc(var(--size) * 0.32) / var(--size) var(--size);
    mask: linear-gradient(-20deg, transparent 50%, white);
    opacity: 0.5;
    z-index: -1;
}

.dark .main-contact:before {
    --line: hsla(0, 0%, 100%, 0.8);
}
</style>
