<script setup lang="ts">
    import { onMounted, onUnmounted, ref, shallowRef } from 'vue';

    import { usePage } from '@inertiajs/vue3';

    import mapboxgl from 'mapbox-gl';
    import 'mapbox-gl/dist/mapbox-gl.css';

    import WindEffect from '@/Components/UI/WindEffect.vue';
    import { SharedData } from '@/types';

    const mapboxToken = import.meta.env.VITE_MAPBOX_TOKEN;

    const page = usePage<SharedData>();

    const contactAddress = page.props.settings?.contact_address || 'Address not specified';

    const mapContainer = ref<HTMLElement | null>(null);
    const map = shallowRef<mapboxgl.Map | null>(null);
    const marker = shallowRef<mapboxgl.Marker | null>(null);

    const getCoords = async (address: string): Promise<[number, number] | null> => {
        try {
            const query = encodeURIComponent(address);
            const response = await fetch(
                `https://api.mapbox.com/geocoding/v5/mapbox.places/${query}.json?access_token=${mapboxToken}&limit=1`,
            );
            const data = await response.json();

            if (data.features && data.features.length > 0) {
                return data.features[0].center;
            }
            return null;
        } catch (e) {
            console.error('Geocoding error:', e);
            return null;
        }
    };

    onMounted(async () => {
        if (!mapContainer.value) return;

        mapboxgl.accessToken = mapboxToken;

        const coords = await getCoords(contactAddress);

        const finalCoords = coords || [37.6173, 55.7558];

        map.value = new mapboxgl.Map({
            container: mapContainer.value,
            style: 'mapbox://styles/mapbox/dark-v11',
            center: finalCoords,
            zoom: 16,
            pitch: 45,
        });

        const el = document.createElement('div');
        el.className = 'map-marker';
        el.innerHTML = `
    <div class="relative flex items-center justify-center">
      <div class="absolute h-10 w-10 animate-ping rounded-full bg-emerald-400/30"></div>
      <div class="relative h-8 w-8 rounded-full border-2 border-white bg-emerald-500 shadow-lg"></div>
    </div>
  `;

        new mapboxgl.Marker(el).setLngLat(finalCoords).addTo(map.value);
    });

    onUnmounted(() => {
        if (map.value) {
            map.value.remove();
        }
    });
</script>

<template>
    <div
        class="relative z-0 flex h-[650px] w-full flex-col overflow-hidden rounded-[1rem] border border-emerald-400/20 bg-zinc-900 shadow-2xl xl:h-[550px] xl:rounded-[1.5rem]"
    >
        <WindEffect :particleCount="20" :windStrength="1" />

        <div ref="mapContainer" class="absolute inset-0 h-full w-full" />

        <div class="bottom-4 left-4 z-10 sm:absolute sm:bottom-6 sm:left-6 sm:max-w-sm">
            <div
                class="border-white/10 bg-[#0a2d1d]/85 p-6 shadow-2xl backdrop-blur-xl sm:rounded-[1rem] sm:border xl:rounded-[1.5rem]"
            >
                <h3 class="mb-4 font-serif text-lg italic leading-tight text-white lg:text-xl">
                    Where we are
                </h3>

                <div class="mb-8 flex items-center gap-2">
                    <div class="leading-relaxed text-emerald-500">
                        <svg
                            class="h-4 w-4 leading-none"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                            />
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-emerald-50/90">
                        {{ contactAddress }}
                    </p>
                </div>

                <a
                    :href="`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(contactAddress)}`"
                    target="_blank"
                    class="flex w-full items-center justify-center rounded-full bg-emerald-600 py-4 text-[8px] font-black uppercase tracking-[0.2em] text-white transition-all hover:bg-emerald-400 active:scale-[0.98] sm:text-[10px]"
                >
                    Route on Google Maps
                </a>
            </div>
        </div>

        <div
            class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent"
        ></div>
    </div>
</template>

<style>
    .mapboxgl-ctrl-bottom-right,
    .mapboxgl-ctrl-bottom-left {
        display: none !important;
    }

    .custom-marker {
        cursor: pointer;
    }
</style>
