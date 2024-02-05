<template>
	<v-row>
		<v-col cols="9">
			<v-card class="py-4">
				<v-card-title>
					<div class="d-flex px-4 justify-space-between w-full">
						<LinnworksLogo class="logo" />
						<div>
							<v-switch v-model="form.linnworks_enabled" label="Enable" hide-details />
						</div>
					</div>
				</v-card-title>
				<v-card-text>
					<AuiSetting
						title="Sync Orders"
						help="When enabled, orders placed through AdminUI will be automatically pushed to the linked Linnworks account"
					>
						<v-switch
							v-model="form.linnworks_sync_orders"
							:disabled="!form.linnworks_enabled"
							label="Sync Orders"
						/>
					</AuiSetting>
					<AuiSetting title="Live Stock">
						<template #help>
							<p>Retrieve stock level directly from Linnworks for linked products.</p>
							<p>
								You can select a stock location to use for stock levels, or leave it blank to use the
								first available location
							</p>
						</template>
						<v-switch
							v-model="form.linnworks_live_stock"
							:disabled="!form.linnworks_enabled"
							label="Use Live Stock"
						/>
						<v-select
							v-model="form.linnworks_live_stock_location"
							clearable
							:items="props.linnworksStockLocations"
							item-value="StockLocationId"
							item-text="LocationName"
							outlined
							dense
							:disabled="!form.linnworks_live_stock"
							label="Live Stock Location"
						>
							<template #item="{ item }">
								<div>
									<span>{{ item.LocationName }}</span>
									<small class="ml-4">({{ item.ZipCode }})</small>
								</div>
							</template>
						</v-select>
					</AuiSetting>
					<AuiSetting
						title="Live Pricing"
						help="Retrieve pricing directly from Linnworks for linked products"
					>
						<v-switch
							v-model="form.linnworks_live_pricing"
							disabled
							label="Use Live Pricing"
							persistent-hint
							hint="Not yet available"
						/>
					</AuiSetting>
					<v-divider class="my-4" />
					<div class="d-flex justify-space-between">
						<p>To integrate Linnworks with AdminUI, you'll first need to link your Linnworks account.</p>
					</div>
					<div>
						<v-list dense>
							<SetupStep :step="1">
								Click the "Connect App" button and follow the instructions
							</SetupStep>
							<SetupStep :step="2"> Paste your token in the text field below </SetupStep>
						</v-list>
					</div>
					<AuiSetting
						title="Credentials"
						help="Follow the instructions available above to gather the information required. Then you can test the connection and finally enable the integration."
					>
						<AuiInputPassword
							v-model="form.linnworks_refresh_token"
							label="Token"
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
					<v-btn color="primary" block :href="connectUrl" target="_blank">Connect App</v-btn>
				</template>
			</AuiCard>
		</v-col>
	</v-row>
</template>

<script setup>
import { useApiForm, router, computed, ref } from "adminui";
import LinnworksLogo from "../components/LinnworksLogo.vue";
import SetupStep from "../components/SetupStep.vue";

const props = defineProps({
	linnworksActive: {
		type: Boolean,
		default: false
	},
	linnworksAppId: {
		type: String,
		default: ""
	},
	linnworksSettings: {
		type: Array,
		default: () => []
	},
	linnworksStockLocations: {
		type: Array,
		default: () => []
	}
});

const connectUrl = computed(() => `https://apps.linnworks.net/Authorization/Authorize/${props.linnworksAppId}`);

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
