<template>
    <div>
        <sidebar></sidebar>
        <div class="flex flex-col h-screen md:ml-64">
            <navbar></navbar>
            <div class="relative pb-32 pt-12 flex-grow bg-blueGray-100">
                <div class="px-4 md:px-10 mx-auto w-full">
                    <div>
                        <!-- Page Content -->
                        <slot></slot>
                    </div>
                </div>
            </div>
            <div class="pt-4 md:px-10 mx-auto w-full bg-blueGray-100">
                <div class="flex flex-wrap">
                    <footer class="block py-4 w-full">
                        <div class="container mx-auto px-4">
                            <hr class="mb-4 border-b-1 border-blueGray-200" />
                            <div
                                class="flex flex-wrap items-center md:justify-between justify-center"
                            >
                                <div class="w-full md:w-4/12 px-4">
                                    <div
                                        class="text-sm text-blueGray-500 font-semibold py-1"
                                    >
                                        Copyright © 2021
                                        <a
                                            href="https://www.creative-tim.com"
                                            class="text-blueGray-500 hover:text-blueGray-700 text-sm font-semibold py-1"
                                        >
                                            Sugarcoated Cashews Pty Ltd
                                        </a>
                                    </div>
                                </div>
                                <div class="w-full md:w-8/12 px-4">
                                    <ul
                                        class="flex flex-wrap list-none md:justify-end justify-center"
                                    >
                                        <li>
                                            <a
                                                href="https://marcusphilipp.com"
                                                class="text-blueGray-600 hover:text-blueGray-800 text-sm font-semibold block py-1 px-3"
                                                target="_blank"
                                            >
                                                marcusphilipp.com
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Sidebar from '@/Custom/Sidebar'
import Navbar from '@/Custom/Navbar'

export default {
    components: {
        Sidebar,
        Navbar,
    },
    data() {
        return {}
    },
}
</script>
