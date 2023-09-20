import { defineConfig, loadEnv } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue2";
import { resolve } from "node:path";
import AdminUI from "vite-plugin-adminui";
import viteBasicSslPlugin from "@vitejs/plugin-basic-ssl";

export default ({ mode }) => {
	const env = loadEnv(mode, resolve("../../../"));

	return defineConfig({
		plugins: [
			laravel({
				input: "./resources/index.js",
				publicDirectory: "publish/js",
				hotFile: "publish/js/hot",
				buildDirectory: "vendor/adminui-linnworks"
			}),
			vue({
				template: {
					transformAssetUrls: {
						base: ".",
						includeAbsolute: false
					}
				}
			}),
			AdminUI(),
			env.VITE_HTTPS ? viteBasicSslPlugin() : undefined
		],
		build: {
			emptyOutDir: true,
			outDir: "./publish/js"
		},
		server:
			mode === "development"
				? {
						host: env.VITE_DEV_SERVER_HOST
				  }
				: {}
	});
};
