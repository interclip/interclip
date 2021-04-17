module.exports = {
	globPatterns: [
		'**/*.{js,css,svg,ico,png,gif,webmanifest}'
	],
	runtimeCaching: [
		{
			urlPattern: /\.(?:png|jpg|jpeg|svg)$/,
			handler: 'CacheFirst',
			options: {
				expiration: { maxEntries: 10 },
				cacheName: "images",
			}
		}
	],
	ignoreURLParametersMatching: [
		/^utm_/,
		/^fbclid$/
	],
	swDest: 'service-worker.js'
};