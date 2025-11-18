<script setup lang="ts">
import { dashboard, login } from '@/routes';
import { Link } from '@inertiajs/vue3';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
</script>

<template>
    <header
        class="container fixed shadow top-0 z-50 mb-6 w-full text-sm not-has-[nav]:hidden"
    >
        <nav class="flex items-center justify-end gap-4">
            <Link
                v-if="$page.props.auth.user"
                :href="dashboard()"
                class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
            >
                Dashboard
            </Link>
            <template v-else>
                <Link
                    :href="login()"
                    class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                >
                    Log in
                </Link>
                <!--                    <Link-->
                <!--                        v-if="canRegister"-->
                <!--                        :href="register()"-->
                <!--                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"-->
                <!--                    >-->
                <!--                        Register-->
                <!--                    </Link>-->
            </template>
            <AppearanceTabs />
        </nav>
    </header>
</template>

<style scoped></style>
