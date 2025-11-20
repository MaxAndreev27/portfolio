<script setup lang="ts">
import { BemineText } from '@/components/ui/bemine-text';
</script>

<template>
    <section class="main-about w-full">
        <div
            class="container mx-auto mt-20 mb-20 flex flex-col items-center justify-center"
        >
            <BemineText class="mb-8 text-6xl font-bold">About me</BemineText>
            <ul
                class="flex w-full max-w-4xl list-inside list-disc list-image-[url(/public/images/checkmark.svg)] flex-col items-start text-left text-3xl"
            >
                <li class="mb-4 text-justify">I am Maksym Andreev, a full-stack
                    web developer from Ukraine
                    with over 10 years of development experience.
                </li>
                <li class="mb-4 text-justify">Graduated from University of
                    Informatics and Artificial
                    Intelligence 2005-2010, and obtained the qualification of a
                    software engineer with a specialty in "Software for
                    Automated Systems"
                </li>
                <li class="mb-4 text-justify">2010-2011 programmer at UniCredit
                    bank
                </li>
                <li class="mb-4 text-justify">2011-2014 programmer at the
                    Regional Directorate of the Bank
                    "Kyivska Rus"
                </li>
                <li class="mb-4 text-justify">2015-2023 worked in the
                    <a
                        class="font-bold text-pink-600"
                        target="_blank"
                        rel="noopener noreferrer"
                        href="https://specialcase.net"
                        >SpecialCase</a
                    >
                    team
                </li>
                <li>2024-present freelance</li>
            </ul>
        </div>
    </section>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Onest:wght@300;900&display=swap');
.main-about {
    font-family: 'Onest', sans-serif;
}
</style>
