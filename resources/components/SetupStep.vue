<template functional>
	<v-list-item>
		<v-list-item-avatar color="accent lighten-2">
			<v-icon color="white" v-text="`mdi-numeric-${props.step}`"></v-icon>
		</v-list-item-avatar>
		<v-list-item-content>
			<v-list-item-title>
				<slot />
			</v-list-item-title>
		</v-list-item-content>
	</v-list-item>
</template>

<script>
export default {
	props: {
		step: {
			type: Number,
			required: true
		}
	}
};
</script>
