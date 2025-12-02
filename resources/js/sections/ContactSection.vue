<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { GlitchText } from '@/components/ui/glitch-text';
import { Input } from '@/components/ui/input';
import { Keypad } from '@/components/ui/keypad';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { Textarea } from '@/components/ui/textarea';
import { Form, useForm, usePage } from '@inertiajs/vue3';
import { watch } from 'vue';

const form = useForm({
    name: '',
    email: '',
    message: '',
});

const page = usePage();

// Watch for flash messages
watch(() => page.props.flash, (newFlash) => {
    if (newFlash?.success) {
        console.log('Success:', newFlash.success);
        // You can add a toast notification here if you want
    }
    if (newFlash?.error) {
        console.error('Error:', newFlash.error);
    }
}, { immediate: true });

const submit = () => {
    form.post('/contact', {
        preserveScroll: true,
        onSuccess: (page) => {
            // form.reset();
            // The flash message will be available in page.props.flash
            if (page.props.flash?.success) {
               console.log(route('contact.store'));
                console.log('Success:', page.props.flash.success);
            }
        },
        onError: (errors) => {
            console.error('Form submission error:', errors);
        }
    });
};
</script>

<template>
    <section id="main-contact" class="main-contact w-full">
        <div class="container mx-auto mt-20 mb-20 max-w-4xl">
            <div class="grid grid-cols-2">
                <div>
                    <Card class="rounded-xl">
                        <CardHeader class="px-10 pt-8 pb-0 text-center">
                            <CardTitle class="text-xl">
                                <GlitchText
                                    class="content-center text-6xl font-bold"
                                    data-text="Contact me"
                                    >Contact me</GlitchText
                                >
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="px-10 py-8">
                            <Form
                                @submit.prevent="submit"
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
                                            autofocus
                                            :tabindex="1"
                                            placeholder="Name"
                                            autocomplete="name"
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
                                            autocomplete="email"
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
                                        class="mt-4 w-full"
                                        :tabindex="4"
                                        :disabled="form.processing"
                                        data-test="login-button"
                                    >
                                        <Spinner v-if="form.processing" />
                                        <span v-else>Send Message</span>
                                    </Button>
                                </div>
                            </Form>
                        </CardContent>
                    </Card>
                </div>
                <div class="flex items-center justify-center">
                    <Keypad />
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
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
    /*--line: color-mix(in hsl, canvasText, transparent 80%);*/
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
