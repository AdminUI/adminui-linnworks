<template>
	<v-row>
		<v-col cols="9">
			<v-card class="py-4">
				<v-card-title>
					<div class="d-flex px-4 justify-space-between w-full">
						<LinnworksLogo class="logo" />
						<div>
							<v-switch label="Enable" hide-details />
						</div>
					</div>
				</v-card-title>
				<v-card-text>
					<AuiSetting
						title="Sync Orders"
						help="When enabled, orders placed through AdminUI will be automatically pushed to the linked Xero account"
					>
						<v-switch v-model="form.xero_sync_orders" label="Sync Orders" />
					</AuiSetting>
					<v-divider class="my-4" />
					<div class="d-flex justify-space-between">
						<p>
							To integrate Linnworks with AdminUI, you'll first need to create an application on your
							Linnworks account.
						</p>
						<v-btn color="info" outlined @click="showHelp = !showHelp">
							<span v-if="!showHelp">Show Instructions</span>
							<span v-else>Hide Instructions</span>
						</v-btn>
					</div>
					<div>
						<v-slide-y-transition mode="out-in">
							<div v-if="showHelp">
								<v-list dense>
									<SetupStep :step="1">
										Go to
										<v-btn
											href="https://developer.linnworks.com/#/secure/applications"
											target="_blank"
											text
											color="primary"
										>
											Linnworks > Applications
										</v-btn>
										and create an application
									</SetupStep>
									<SetupStep :step="2"> Select `System Integration` from the dropdown </SetupStep>
									<SetupStep :step="3">
										Click the `Installation URL` for your newly created app</SetupStep
									>
									<SetupStep :step="4">
										Select `Development Version` from the version dropdown</SetupStep
									>
									<SetupStep :step="5">
										Copy your access token, together with the app ID and app secret into the fields
										below</SetupStep
									>
								</v-list>
							</div>
						</v-slide-y-transition>
					</div>
					<AuiSetting
						title="Credentials"
						help="Follow the instructions available above to gather the information required. Then you can test the connection and finally enable the integration."
					>
						<AuiInputText
							v-model="form.linnworks_app_id"
							label="Application ID"
							:error="formErrors.linnworks_app_id"
						/>

						<AuiInputPassword
							v-model="form.linnworks_app_secret"
							label="Application Secret"
							:error="formErrors.linnworks_app_secret"
						/>

						<AuiInputPassword
							v-model="form.linnworks_refresh_token"
							label="Access Token"
							:error="formErrors.linnworks_refresh_token"
						/>
					</AuiSetting>
				</v-card-text>
			</v-card>
		</v-col>
		<v-col cols="3">
			<AuiCard title="Connection Status">
				<template v-if="props.linnworksActive">
					<p class="text-center text-h6"><v-icon class="mr-4" color="success">mdi-flash</v-icon>Connected</p>
				</template>
				<template v-else>
					<p class="text-center text-h6"><v-icon class="mr-4">mdi-flash-off</v-icon>Not Connected</p>
				</template>
				<v-btn color="primary" block @click="retestConnection">Re-test Connection</v-btn>
			</AuiCard>
		</v-col>
	</v-row>
</template>

<script setup>
import { useApiForm, useRoute, axios, router, computed, ref } from "adminui";
import LinnworksLogo from "../components/LinnworksLogo.vue";
import SetupStep from "../components/SetupStep.vue";

const props = defineProps({
	linnworksActive: {
		type: Boolean,
		default: false
	},
	linnworksSettings: {
		type: Array,
		default: () => []
	}
});

const getInitialData = () => {
	return props.linnworksSettings.reduce((acc, curr) => {
		const value = curr.value_cast === "integer" ? +curr.value : curr.value;
		acc[curr.name] = value;
		return acc;
	}, {});
};

let { form, formErrors } = useApiForm({ route: "admin.api.config.preferences", initialData: getInitialData() });
const showHelp = ref(false);

const retestConnection = () => {
	router.reload({
		only: ["linnworksActive"],
		preserveScroll: true
	});
};
</script>

<style scoped>
.logo {
	height: 50px;
}
</style>
