<template>
	<v-container fluid>
		<portal to="title">
			<LinnworksLogo class="logo" />
		</portal>
	</v-container>
</template>

<script setup>
import { useApiForm, useRoute, axios, router, computed, ref } from "adminui";
import LinnworksLogo from "../components/LinnworksLogo.vue";
</script>

<style scoped>
.logo {
	height: 60px;
}
</style>
