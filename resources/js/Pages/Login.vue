<script setup>
import { reactive, ref, onMounted } from 'vue'
import { Link, useForm, router } from '@inertiajs/vue3'
import axios from 'axios'


// middleware logic

// this is to avoid the page from loading for a split second
const loading = ref(true)

const token = localStorage.getItem('token')

if (token) {
	router.visit('/start')
} else {
	loading.value = false
}


onMounted(() => {
	if (localStorage.getItem('token')) {
		router.visit('/start')
		// console.log(localStorage.getItem('token'))
	}
})

const credentials = reactive({
	email: null,
	login_code: null
})

const otpSent = ref(false)

const handleLogin = () => {
	axios.post('http://localhost:8000/api/login', credentials)
	.then((response) => {
		console.log(response.data)
		otpSent.value = true
	}).catch((error) => {
		console.warn(error)
	})
}

const handleOtpVerification = () => {
	axios.post('http://localhost:8000/api/login/verify', {
		email: credentials.email,
		login_code: credentials.login_code
	})
	.then((response) => {
		console.log(response.data.token) // auth token
		localStorage.setItem('token', response.data.token)
		router.visit('/start')
	})
	.catch((error) => {
		console.log(error)
	})
}

</script>

<template>
	<header class="flex items-center justify-end">
		<h1 class="bg-blue-300">to login or sign up page</h1>
	</header>
	
	<form v-if="!otpSent" @submit.prevent="handleLogin" class="text-center">
		<div class="p-2">Enter your email to get started...</div>
		<input type="text" v-model="credentials.email" class="rounded p-2 m-2 h-6">

		<button type="submit" class="bg-blue-500 rounded w-20 hover:bg-blue-400">Submit</button>
	</form>

	<form v-else @submit.prevent="handleOtpVerification" class="text-center">
		<div class="p-2">Your OTP has been sent to your email. Enter it below to verify and continue...</div>
		<input type="text" v-model="credentials.login_code" class="rounded p-2 m-2 h-6">

		<button type="submit" class="bg-blue-500 rounded w-20 hover:bg-blue-400">Submit</button>
	</form>
</template>
