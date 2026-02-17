<script setup>
import { handleImageError } from "@/utils";

defineProps(["src", "alt", "className"]);
</script>

<template>
    <img :src="src" :alt="alt" :class="className" @error="handleImageError" />
</template>
