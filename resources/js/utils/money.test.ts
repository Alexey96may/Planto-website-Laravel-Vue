import { describe, expect, it } from 'vitest';

import { calculateTotal, formatUSD } from './money';

describe('Money Utils', () => {
    it('should calculate total correctly', () => {
        expect(calculateTotal(100, 2)).toBe(200);
        expect(calculateTotal('50.50', 3)).toBe(151.5);
    });

    it('should format currency for US locale', () => {
        expect(formatUSD(1000)).toContain('$');
        expect(formatUSD(1000)).toMatch(/1,000/);
    });
});
