<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Chat
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-xl sm:rounded-lg flex"
                    style="min-height: 700px; max-height: 700px"
                >
                    <!-- list Users -->
                    <div
                        class="w-3/12 bg-gray-200 bg-opacity-25 border-r border-gray-200 overflow-y-scroll"
                    >
                        <ul>
                            <li
                                class="p-6 text-lg text-gray-600 leading-7 font-semibold border-b border-gray-200 hover:bg-gray-200 hover:bg-opacity-50 hover:cursor-pointer"
                                v-for="(user, index) in users"
                                :key="index"
                                @click="
                                    () => {
                                        loadingMessage(user.id);
                                    }
                                "
                                :class="
                                    userActive && userActive.id === user.id
                                        ? 'bg-gray-200 bg-opacity-50'
                                        : ''
                                "
                            >
                                <p class="flex items-center">
                                    {{ user.name }}
                                    <span
                                        class="ml-2 w-2 h-2 bg-blue-500 rounded-full"
                                    ></span>
                                </p>
                            </li>
                        </ul>
                    </div>
                    <!-- box message -->
                    <div class="w-9/12 flex flex-col justify-between">
                        <!-- Message -->
                        <div class="w-full p-6 flex flex-col overflow-y-scroll">
                            <div
                                class="w-full mb-3 message"
                                v-for="(message, index) in messages"
                                :key="index"
                                :class="
                                    message.from === $page.props.auth.user.id
                                        ? 'text-right'
                                        : ''
                                "
                            >
                                <p
                                    class="inline-block p-2 rounded-md"
                                    style="max-width: 75%"
                                    :class="
                                        message.from ===
                                        $page.props.auth.user.id
                                            ? 'messageFromMe'
                                            : 'messageToMe'
                                    "
                                >
                                    {{ message.content }}
                                </p>
                                <span class="block mt-1 text-gray-500">{{
                                    formattedData(message.created_at)
                                }}</span>
                            </div>
                        </div>
                        <!-- form -->
                        <div
                            class="w-full bg-gray-200 bg-opacity-25 p-6 border-t border-gray-300"
                            v-if="userActive"
                        >
                            <form v-on:submit.prevent="sendMessage">
                                <div
                                    class="flex rounded-md overflow-hidden border border-gray-300"
                                >
                                    <label for="inputField" class="sr-only"
                                        >Digite algo</label
                                    >
                                    <input
                                        type="text"
                                        id="inputField"
                                        class="flex-1 px-4 py-2 text-sm focus:outline-none"
                                        placeholder="Digite algo"
                                        v-model="message"
                                    />
                                    <button
                                        type="submit"
                                        class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2"
                                    >
                                        Enviar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import axios from "axios";
import moment from "moment";
import store from "../store";

export default {
    data() {
        return {
            users: [],
            messages: [],
            userActive: null,
            message: "",
        };
    },
    mounted() {
        axios
            .get("/api/users")
            .then((response) => {
                this.users = response.data.data;
            })
            .catch((error) => {
                console.log(error.data.message);
            });
    },
    methods: {
        scrollToBottom() {
            this.$nextTick(() => {
                if (this.messages.length) {
                    const lastMessage = document.querySelector(
                        ".message:last-child"
                    );
                    if (lastMessage) {
                        lastMessage.scrollIntoView();
                    }
                }
            });
        },
        async loadingMessage(userId) {
            axios
                .get(`/api/users/${userId}`)
                .then((response) => {
                    this.userActive = response.data.data;
                })
                .catch((error) => {
                    console.log(error.data.message);
                });

            await axios
                .get(`/api/messages/${userId}`)
                .then((response) => {
                    this.messages = response.data.data;
                    this.scrollToBottom();
                })
                .catch((error) => {
                    console.log(error.data.message);
                });
        },
        async sendMessage() {
            await axios
                .post(`/api/messages`, {
                    content: this.message,
                    to: this.userActive.id,
                })
                .then((response) => {
                    this.messages.push({
                        from: this.user.id,
                        to: this.userActive.id,
                        content: this.message,
                        created_at: new Date().toISOString(),
                        updated_at: new Date().toISOString(),
                    });
                    this.message = "";
                    this.scrollToBottom();
                })
                .catch((error) => {
                    console.log(error.data.message);
                });
        },
    },
    computed: {
        formattedData() {
            return (value) => {
                if (value) {
                    return moment(value).format("DD/MM/YYYY HH:mm");
                }
            };
        },
        user() {
            return store.state.user;
        },
    },
};
</script>

<style setup>
.messageFromMe {
    @apply bg-indigo-300 bg-opacity-25;
}
.messageToMe {
    @apply bg-gray-300 bg-opacity-25;
}

/* Track */
::-webkit-scrollbar {
    width: 12px;
}

/* Handle */
::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 6px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background-color: #555;
}

/* Track (background) on hover */
::-webkit-scrollbar-thumb:active {
    background-color: #999;
}

/* Button (up and down arrows) */
::-webkit-scrollbar-button {
    display: none;
}
</style>
