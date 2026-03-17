<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, nextTick, onMounted, onUnmounted, ref } from 'vue';

interface User {
    id: number;
    name: string;
}

interface Message {
    id: number;
    body: string;
    user_id: number;
    created_at?: string;
    sender: { name: string };
    sending?: boolean;
}

const page = usePage();

// Отримуємо поточного користувача.
// Якщо в Filament props.auth не доступний, можна додати логіку завантаження через axios.
const currentUser = computed(() => page.props.auth?.user as User);
const allUsers = computed(() => (page.props.users as User[]) || []);

// Стан віджета
const isOpen = ref(false); // Відкрито/Закрито
const selectedUserIds = ref<number[]>([]);
const messages = ref<Message[]>([]);
const newMessage = ref('');
const onlineUserIds = ref<number[]>([]);
const activeConversationId = ref<number | null>(null);
const chatContainer = ref<HTMLElement | null>(null);

// Стан сповіщень та друку
const unreadCounts = ref<Record<number, number>>({});
const isTyping = ref(false);
const typingUser = ref<string | null>(null);
let typingTimeout: ReturnType<typeof setTimeout>;
let lastTypingTime = 0;

// Загальна кількість непрочитаних для кнопки
const totalUnread = computed(() => {
    return Object.values(unreadCounts.value).reduce((a, b) => a + b, 0);
});

const scrollToBottom = () => {
    nextTick(() => {
        if (chatContainer.value) {
            chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
        }
    });
};

const sendTypingEvent = () => {
    if (!activeConversationId.value) return;
    const now = Date.now();
    if (now - lastTypingTime < 2000) return;
    lastTypingTime = now;
    window.Echo.private(`chat.${activeConversationId.value}`).whisper(
        'typing',
        { name: currentUser.value.name },
    );
};

const subscribeToChannel = (id: number) => {
    if (activeConversationId.value && activeConversationId.value !== id) {
        window.Echo.leave(`chat.${activeConversationId.value}`);
    }
    activeConversationId.value = id;

    window.Echo.private(`chat.${id}`)
        .listen('.MessageSent', (e: any) => {
            const isDuplicate = messages.value.find(
                (m) =>
                    m.id === e.id ||
                    (m.sending &&
                        m.body === e.body &&
                        m.user_id === e.sender.id),
            );
            if (!isDuplicate) {
                messages.value.push({ ...e, sending: false });
                scrollToBottom();
            }
        })
        .listenForWhisper('typing', (e: { name: string }) => {
            typingUser.value = e.name;
            isTyping.value = true;
            clearTimeout(typingTimeout);
            typingTimeout = setTimeout(() => {
                isTyping.value = false;
                typingUser.value = null;
            }, 3000);
        });
};

const selectUser = async (userId: number) => {
    selectedUserIds.value = [userId];
    messages.value = [];
    isTyping.value = false;
    unreadCounts.value[userId] = 0;

    try {
        const response = await axios.get(`/chat/messages/${userId}`);
        const { conversationId, messages: loadedMessages } = response.data;
        messages.value = loadedMessages;
        if (conversationId) subscribeToChannel(conversationId);
        scrollToBottom();
    } catch (error) {
        console.error('Char error:', error);
    }
};

const sendMessage = async () => {
    if (!newMessage.value.trim() || selectedUserIds.value.length === 0) return;

    const messageText = newMessage.value;
    const tempId = -Date.now();
    messages.value.push({
        id: tempId,
        body: messageText,
        user_id: currentUser.value.id,
        sender: { name: currentUser.value.name },
        sending: true,
    });
    newMessage.value = '';
    scrollToBottom();

    try {
        const response = await axios.post('/chat/send', {
            recipient_ids: selectedUserIds.value,
            message: messageText,
            conversation_id: activeConversationId.value,
        });
        const { conversationId, message } = response.data;
        if (activeConversationId.value !== conversationId)
            subscribeToChannel(conversationId);
        const index = messages.value.findIndex((m) => m.id === tempId);
        if (index !== -1)
            messages.value[index] = { ...message, sending: false };
    } catch (error) {
        messages.value = messages.value.filter((m) => m.id !== tempId);
        newMessage.value = messageText;
        console.error('Send chat:', error);
    }
};

// Якщо 'uk', перетворюємо в 'uk-UA', якщо 'en' — в 'en-US' (або залишаємо як є)
const currentLocale = computed(() => {
    const lang = (page.props.locale as string) || 'uk';
    return lang === 'uk' ? 'uk-UA' : 'en-US';
});

// const formatTime = (dateString?: string) => {
//     if (!dateString) return '';
//     const date = new Date(dateString);

//     return new Intl.DateTimeFormat(currentLocale.value, {
//         hour: '2-digit',
//         minute: '2-digit',
//     }).format(date);
// };

const formatFullDate = (dateString?: string) => {
    if (!dateString) return '';
    const date = new Date(dateString);

    return new Intl.DateTimeFormat(currentLocale.value, {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }).format(date);
};

onMounted(() => {
    if (!currentUser.value) return;

    window.Echo.join('online')
        .here((users: User[]) => (onlineUserIds.value = users.map((u) => u.id)))
        .joining((user: User) => onlineUserIds.value.push(user.id))
        .leaving(
            (user: User) =>
                (onlineUserIds.value = onlineUserIds.value.filter(
                    (id) => id !== user.id,
                )),
        );

    window.Echo.private(`App.Models.User.${currentUser.value.id}`).listen(
        '.MessageSent',
        (e: any) => {
            if (!selectedUserIds.value.includes(e.sender.id) || !isOpen.value) {
                unreadCounts.value[e.sender.id] =
                    (unreadCounts.value[e.sender.id] || 0) + 1;
            }
        },
    );
});

onUnmounted(() => {
    window.Echo.leave('online');
    if (currentUser.value)
        window.Echo.leave(`App.Models.User.${currentUser.value.id}`);
    if (activeConversationId.value)
        window.Echo.leave(`chat.${activeConversationId.value}`);
});
</script>

<template>
    <div class="fixed right-6 bottom-6 z-9999 flex flex-col items-end">
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="transform translate-y-10 opacity-0 scale-95"
            enter-to-class="transform translate-y-0 opacity-100 scale-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="transform translate-y-0 opacity-100 scale-100"
            leave-to-class="transform translate-y-10 opacity-0 scale-95"
        >
            <div
                v-if="isOpen"
                class="mb-4 flex h-140 w-100 flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-gray-700 dark:bg-gray-800"
            >
                <div
                    class="flex items-center justify-between bg-indigo-600 p-4 text-white"
                >
                    <h3 class="font-bold">Повідомлення</h3>
                    <button
                        @click="isOpen = false"
                        class="cursor-pointer hover:opacity-75"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>
                    </button>
                </div>

                <div class="flex h-full overflow-hidden">
                    <div
                        class="w-20 overflow-y-auto border-r border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900/20"
                    >
                        <div
                            v-for="user in allUsers.filter(
                                (u) => u.id !== currentUser?.id,
                            )"
                            :key="user.id"
                            @click="selectUser(user.id)"
                            class="relative flex cursor-pointer justify-center p-3 hover:bg-gray-100 dark:hover:bg-gray-700"
                            :title="user.name"
                        >
                            <div
                                :class="[
                                    'flex h-10 w-10 items-center justify-center rounded-full border-2 text-xs font-bold uppercase',
                                    selectedUserIds.includes(user.id)
                                        ? 'border-indigo-500 bg-indigo-100 text-indigo-700'
                                        : 'border-transparent bg-gray-200 text-gray-600',
                                ]"
                            >
                                {{ user.name.charAt(0) }}
                            </div>
                            <span
                                v-if="onlineUserIds.includes(user.id)"
                                class="absolute right-4 bottom-3 h-3 w-3 rounded-full border-2 border-white bg-green-500"
                            ></span>
                            <span
                                v-if="unreadCounts[user.id] > 0"
                                class="absolute top-2 right-4 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[8px] text-white"
                            >
                                {{ unreadCounts[user.id] }}
                            </span>
                        </div>
                    </div>

                    <div class="flex grow flex-col">
                        <div
                            ref="chatContainer"
                            class="grow space-y-4 overflow-y-auto bg-white p-4 dark:bg-gray-800"
                        >
                            <div
                                v-if="selectedUserIds.length === 0"
                                class="flex h-full items-center justify-center text-center text-xs text-gray-400"
                            >
                                Оберіть контакт зліва
                            </div>
                            <div
                                v-for="msg in messages"
                                :key="msg.id"
                                :class="[
                                    'text-md max-w-[85%] rounded-2xl p-3 shadow-sm transition-all duration-200',
                                    msg.user_id === currentUser?.id
                                        ? 'ml-auto bg-indigo-600 text-white'
                                        : 'bg-white text-gray-900 dark:bg-gray-700 dark:text-gray-100',
                                    msg.sending
                                        ? 'scale-95 opacity-50'
                                        : 'scale-100 opacity-100',
                                ]"
                            >
                                <p class="leading-relaxed">{{ msg.body }}</p>

                                <div
                                    :title="formatFullDate(msg.created_at)"
                                    :class="[
                                        'mt-1 flex items-center gap-1 text-sm',
                                        msg.user_id === currentUser?.id
                                            ? 'justify-end text-indigo-200'
                                            : 'text-gray-400',
                                    ]"
                                >
                                    <span>{{
                                        msg.created_at
                                            ? formatFullDate(msg.created_at)
                                            : 'надсилається...'
                                    }}</span>

                                    <svg
                                        v-if="
                                            msg.user_id === currentUser?.id &&
                                            !msg.sending
                                        "
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-3 w-3"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 13l4 4L19 7"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <div
                                v-if="isTyping"
                                class="animate-pulse text-[10px] text-gray-500 italic"
                            >
                                {{ typingUser }} друкує...
                            </div>
                        </div>

                        <div
                            class="border-t bg-gray-50 p-3 dark:bg-gray-900/50"
                        >
                            <div class="flex items-center gap-2">
                                <input
                                    v-model="newMessage"
                                    @keydown="sendTypingEvent"
                                    @keyup.enter="sendMessage"
                                    :disabled="selectedUserIds.length === 0"
                                    placeholder="Текст..."
                                    class="w-full rounded-lg border-gray-300 text-lg focus:ring-indigo-500 dark:bg-gray-800"
                                />
                                <button
                                    @click="sendMessage"
                                    :disabled="!newMessage.trim()"
                                    class="text-indigo-600 hover:scale-110 disabled:opacity-50"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>

        <button
            @click="isOpen = !isOpen"
            class="relative flex h-14 w-14 cursor-pointer items-center justify-center rounded-full bg-indigo-600 text-white shadow-2xl transition-all hover:scale-110 active:scale-95"
        >
            <svg
                v-if="!isOpen"
                xmlns="http://www.w3.org/2000/svg"
                class="h-7 w-7"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
                />
            </svg>
            <svg
                v-else
                xmlns="http://www.w3.org/2000/svg"
                class="h-7 w-7"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                />
            </svg>

            <span
                v-if="totalUnread > 0 && !isOpen"
                class="absolute -top-1 -right-1 flex h-6 w-6 animate-bounce items-center justify-center rounded-full border-2 border-white bg-red-500 text-[10px] font-bold text-white"
            >
                {{ totalUnread }}
            </span>
        </button>
    </div>
</template>
