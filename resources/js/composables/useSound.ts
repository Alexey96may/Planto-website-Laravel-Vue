import gsap from 'gsap';
import { Howl } from 'howler';

import cancelSrc from '@/../audio/sounds/cancel.mp3';
import clickSrc from '@/../audio/sounds/click.mp3';
import modalCloseSrc from '@/../audio/sounds/modalClose.mp3';
import modalOpenSrc from '@/../audio/sounds/modalOpen.mp3';
import moneySrc from '@/../audio/sounds/money.mp3';
import slideMoveSrc from '@/../audio/sounds/slideMove.mp3';
import successSrc from '@/../audio/sounds/success.mp3';

let sounds: Record<string, Howl> | null = null;

const getSound = (name: string, src: string, volume: number = 0.2): Howl | null => {
    if (typeof window === 'undefined') return null;

    if (!sounds) sounds = {};

    if (!sounds[name]) {
        sounds[name] = new Howl({ src: [src], volume });
    }

    return sounds[name];
};

export function useSound() {
    const playClick = () => {
        const sfx = getSound('click', clickSrc);
        if (sfx) {
            sfx.rate(gsap.utils.random(0.9, 1.1));
            sfx.play();
        }
    };

    const playCancel = () => {
        const sfx = getSound('cancel', cancelSrc);
        if (sfx) {
            sfx.rate(gsap.utils.random(0.9, 1.0));
            sfx.play();
        }
    };

    const moneyClick = () => {
        const sfx = getSound('money', moneySrc, 0.3);
        if (sfx) {
            sfx.rate(gsap.utils.random(0.9, 1.1));
            sfx.play();
        }
    };

    const playModalOpen = () => getSound('modalOpen', modalOpenSrc)?.play();
    const playModalClose = () => getSound('modalClose', modalCloseSrc)?.play();
    const playSlideMove = () => getSound('slideMove', slideMoveSrc)?.play();
    const playSuccess = () => getSound('success', successSrc)?.play();

    return {
        playClick,
        playCancel,
        moneyClick,
        playModalOpen,
        playModalClose,
        playSlideMove,
        playSuccess,
    };
}
