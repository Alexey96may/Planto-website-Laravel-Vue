<script setup lang="ts">
    import { onMounted, onUnmounted, ref } from 'vue';

    import gsap from 'gsap';
    import { Howl } from 'howler';

    import leafSound from '@/../audio/sounds/leaf.mp3';

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
        particleCount: 20,
        minSpeedY: 0.5,
        maxSpeedY: 0.5,
        windStrength: 1.0,
        gravity: 0.0001,
        imageCount: 19,
    });

    const leafSfx = new Howl({
        src: [leafSound],
        volume: 0.01,
        preload: true,
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

    const mouse = { x: -1000, y: -1000 };

    class Petal {
        x: number;
        y: number;
        size: number;
        speedY: number;
        speedX: number;
        rotation: number;
        spin: number;
        image: HTMLImageElement;
        isHit: boolean = false;

        angle: number = Math.random() * Math.PI * 2;
        oscillationSpeed: number = Math.random() * 0.02 + 0.01;

        constructor(canvasWidth: number, canvasHeight: number) {
            this.x = Math.random() * canvasWidth;
            this.y = Math.random() * canvasHeight * -2; // Start a little off the top of the screen
            this.size = Math.random() * 20 + 15;
            this.speedY = Math.random() * (props.maxSpeedY - props.minSpeedY) + props.minSpeedY;
            this.speedX = (Math.random() - 0.5) * props.windStrength * 2;
            this.rotation = Math.random() * 360;
            this.spin = (Math.random() - 0.5) * 2;

            // Select a random image from the array when a leaf is born
            this.image = images[Math.floor(Math.random() * images.length)];
        }

        update(w: number, h: number) {
            const dx = this.x - mouse.x;
            const dy = this.y - mouse.y;
            const distance = Math.sqrt(dx * dx + dy * dy);
            const influenceRadius = 100;

            if (distance < influenceRadius && !this.isHit) {
                this.isHit = true;
                leafSfx.rate(gsap.utils.random(0.7, 1.5));
                leafSfx.play();

                gsap.to(this, {
                    speedX: gsap.utils.clamp(-3, 3, dx / 12),
                    speedY: gsap.utils.clamp(-1, 2, dy / 15),
                    spin: gsap.utils.random(-1.5, 1.5),
                    duration: 1.5,
                    ease: 'power1.out',
                    onComplete: () => {
                        this.isHit = false;
                    },
                });
            }

            // GRAVITY AND BRAKING
            this.angle += 0.015;
            const sway = Math.sin(this.angle) * 0.5;

            if (!this.isHit) {
                this.speedX *= 0.99;
                if (this.speedY > props.maxSpeedY) this.speedY *= 0.98;
            }

            this.speedY += props.gravity;

            this.x += this.speedX + sway;
            this.y += this.speedY;

            this.rotation += this.spin + sway;

            if (this.y > h + 50) this.reset(w, h);
            if (this.x > w + 50) this.x = -50;
            if (this.x < -50) this.x = w + 50;
        }

        reset(canvasWidth: number, canvasHeight: number) {
            this.y = -(Math.random() * 100 + 50);
            this.x = Math.random() * canvasWidth;
            this.speedY = Math.random() * (props.maxSpeedY - props.minSpeedY) + props.minSpeedY;
            this.speedX = (Math.random() - 0.5) * props.windStrength * 2;
            this.spin = (Math.random() - 0.5) * 2;

            this.isHit = false;
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
    let ctx: CanvasRenderingContext2D | null = null;

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

    const handleMouseMove = (e: MouseEvent) => {
        mouse.x = e.clientX;
        mouse.y = e.clientY;
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
        window.addEventListener('mousemove', handleMouseMove);

        for (let i = 0; i < props.particleCount; i++) {
            petals.value.push(new Petal(canvasRef.value.width, canvasRef.value.height));
        }

        animate();
    });

    onUnmounted(() => {
        window.removeEventListener('resize', resizeCanvas);
        window.removeEventListener('mousemove', handleMouseMove);

        if (animationFrameId) {
            cancelAnimationFrame(animationFrameId);
        }
        leafSfx.unload();
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
