import { beforeEach, describe, expect, it, vi } from 'vitest';

import { debounce } from './helpers';

describe('debounce', () => {
    beforeEach(() => {
        vi.useFakeTimers();
    });

    it('should call the function only once after a series of shortcuts', () => {
        const func = vi.fn();
        const debouncedFunc = debounce(func, 500);

        debouncedFunc();
        debouncedFunc();
        debouncedFunc();

        expect(func).not.toHaveBeenCalled();

        vi.advanceTimersByTime(499);
        expect(func).not.toHaveBeenCalled();

        vi.advanceTimersByTime(1);

        expect(func).toHaveBeenCalledTimes(1);
    });

    it('must pass arguments to the original function', () => {
        const func = vi.fn();
        const debouncedFunc = debounce(func, 500);

        debouncedFunc('hello', 123);
        vi.runAllTimers();

        expect(func).toHaveBeenCalledWith('hello', 123);
    });
});
