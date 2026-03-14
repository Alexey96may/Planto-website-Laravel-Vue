import gsap from 'gsap';
import { Howl } from 'howler';

import cancelSrc from '@/../audio/sounds/cancel.mp3';
import clickSrc from '@/../audio/sounds/click.mp3';
import modalCloseSrc from '@/../audio/sounds/modalClose.mp3';
import modalOpenSrc from '@/../audio/sounds/modalOpen.mp3';
import moneySrc from '@/../audio/sounds/money.mp3';
import slideMoveSrc from '@/../audio/sounds/slideMove.mp3';
import successSrc from '@/../audio/sounds/success.mp3';

const clickSfx = new Howl({ src: [clickSrc], volume: 0.2 });
const cancelSfx = new Howl({ src: [cancelSrc], volume: 0.2 });
const moneySfx = new Howl({ src: [moneySrc], volume: 0.3 });
const modalOpenSfx = new Howl({ src: [modalOpenSrc], volume: 0.2 });
const modalCloseSfx = new Howl({ src: [modalCloseSrc], volume: 0.2 });
const slideMoveSfx = new Howl({ src: [slideMoveSrc], volume: 0.2 });
const successSfx = new Howl({ src: [successSrc], volume: 0.2 });

export function useSound() {
    const playClick = () => {
        clickSfx.rate(gsap.utils.random(0.9, 1.1));
        clickSfx.play();
    };

    const playCancel = () => {
        cancelSfx.rate(gsap.utils.random(0.9, 1.0));
        cancelSfx.play();
    };

    const moneyClick = () => {
        moneySfx.rate(gsap.utils.random(0.9, 1.1));
        moneySfx.play();
    };

    const playModalOpen = () => modalOpenSfx.play();
    const playModalClose = () => modalCloseSfx.play();
    const playSlideMove = () => slideMoveSfx.play();
    const playSuccess = () => successSfx.play();

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
