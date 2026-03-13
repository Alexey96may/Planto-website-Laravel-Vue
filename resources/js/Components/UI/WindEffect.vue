<script setup lang="ts">
    import { onMounted, onUnmounted, ref } from 'vue';

    type CanvasContext = CanvasRenderingContext2D;

    interface Props {
        particleCount?: number;
        minSpeedY?: number;
        maxSpeedY?: number;
        windStrength?: number;
        gravity?: number;
        imageCount?: number;
    }

    const props = withDefaults(defineProps<Props>(), {
        particleCount: 40,
        minSpeedY: 0.5,
        maxSpeedY: 0.5,
        windStrength: 1.0,
        gravity: 0.001,
        imageCount: 19,
    });

    const images: HTMLImageElement[] = [];
    const isLoaded = ref(false);

    const preloadImages = () => {
        let loadedCount = 0;

        for (let i = 1; i <= props.imageCount; i++) {
            const img = new Image();
            img.src = `/images/leaves/leaf-${i}.png`;
            img.onload = () => {
                loadedCount++;
                if (loadedCount === props.imageCount) isLoaded.value = true;
            };
            images.push(img);
        }
    };

    class Petal {
        x: number;
        y: number;
        size: number;
        speedY: number;
        speedX: number;
        rotation: number;
        spin: number;
        image: HTMLImageElement;

        constructor(canvasWidth: number, canvasHeight: number) {
            this.x = Math.random() * canvasWidth;
            this.y = Math.random() * canvasHeight * -2; // Start a little off the top of the screen
            this.size = Math.random() * 20 + 15;
            this.speedY = Math.random() * (props.maxSpeedY - props.minSpeedY) + props.minSpeedY;
            this.speedX = (Math.random() - 0.5) * props.windStrength;
            this.rotation = Math.random() * 360;
            this.spin = (Math.random() - 0.5) * 2;

            // Select a random image from the array when a leaf is born
            this.image = images[Math.floor(Math.random() * images.length)];
        }

        update(w: number, h: number) {
            this.speedY += props.gravity;
            this.y += this.speedY;
            this.x += this.speedX;
            this.rotation += this.spin;

            if (this.y > h + 50) {
                this.reset(w, h);
            }
        }

        reset(canvasWidth: number, canvasHeight: number) {
            this.y = -(Math.random() * 100 + 50);
            this.x = Math.random() * canvasWidth;
            this.speedY = Math.random() * (props.maxSpeedY - props.minSpeedY) + props.minSpeedY;
            this.speedX = (Math.random() - 0.5) * props.windStrength;
        }

        draw(ctx: CanvasContext) {
            if (!this.image || !this.image.complete) return;

            ctx.save();
            ctx.translate(this.x, this.y);
            ctx.rotate((this.rotation * Math.PI) / 180);

            // Draw the picture
            ctx.drawImage(this.image, -this.size / 2, -this.size / 2, this.size, this.size);

            ctx.restore();
        }
    }

    const canvasRef = ref<HTMLCanvasElement | null>(null);
    const petals = ref<Petal[]>([]);
    let animationFrameId: number | null = null;
    let ctx: CanvasContext | null = null;

    const animate = () => {
        if (!canvasRef.value || !ctx) return;

        const canvas = canvasRef.value;

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        petals.value.forEach((petal) => {
            petal.update(canvas.width, canvas.height);
            petal.draw(ctx!);
        });

        animationFrameId = requestAnimationFrame(animate);
    };

    const resizeCanvas = () => {
        if (canvasRef.value) {
            canvasRef.value.width = window.innerWidth;
            canvasRef.value.height = window.innerHeight;
        }
    };

    onMounted(() => {
        if (!canvasRef.value) return;

        preloadImages();

        ctx = canvasRef.value.getContext('2d');
        if (!ctx) return;

        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        for (let i = 0; i < props.particleCount; i++) {
            petals.value.push(new Petal(canvasRef.value.width, canvasRef.value.height));
        }

        animate();
    });

    onUnmounted(() => {
        window.removeEventListener('resize', resizeCanvas);
        if (animationFrameId) {
            cancelAnimationFrame(animationFrameId);
        }
    });
</script>

<template>
    <canvas ref="canvasRef" class="wind-effect-canvas"></canvas>
</template>

<style scoped>
    .wind-effect-canvas {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: 2;
        pointer-events: none;
        background: transparent;
    }
</style>
