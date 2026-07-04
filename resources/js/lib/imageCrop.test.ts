import { describe, expect, it } from 'vitest';

import {
    clampSelection,
    containScale,
    defaultSelection,
    resizeSelection,
    resolveOutputFileName,
    resolveOutputMime,
} from '@/lib/imageCrop';

const VIEWPORT = 512;

describe('containScale', () => {
    it('fits a landscape image by its width', () => {
        expect(containScale(1000, 500, VIEWPORT)).toBeCloseTo(VIEWPORT / 1000, 6);
    });

    it('fits a portrait image by its height', () => {
        expect(containScale(500, 1000, VIEWPORT)).toBeCloseTo(VIEWPORT / 1000, 6);
    });

    it('returns 1:1 when the image already matches the viewport', () => {
        expect(containScale(VIEWPORT, VIEWPORT, VIEWPORT)).toBe(1);
    });

    it('falls back to 1 for degenerate dimensions', () => {
        expect(containScale(0, 500, VIEWPORT)).toBe(1);
        expect(containScale(500, 0, VIEWPORT)).toBe(1);
        expect(containScale(-10, 500, VIEWPORT)).toBe(1);
    });
});

describe('defaultSelection', () => {
    it('is a centered square inset from the edges of a landscape image', () => {
        expect(defaultSelection(1000, 500)).toEqual({ sx: 300, sy: 50, sw: 400, sh: 400 });
    });

    it('is a centered square inset from the edges of a portrait image', () => {
        expect(defaultSelection(500, 1000)).toEqual({ sx: 50, sy: 300, sw: 400, sh: 400 });
    });

    it('stays inset even when the image is already square, so the crop box is always visible', () => {
        expect(defaultSelection(800, 800)).toEqual({ sx: 80, sy: 80, sw: 640, sh: 640 });
    });
});

describe('clampSelection', () => {
    it('caps the size at the shorter image side', () => {
        expect(clampSelection({ sx: 0, sy: 0, sw: 9999, sh: 9999 }, 1000, 500, 50)).toEqual({
            sx: 0,
            sy: 0,
            sw: 500,
            sh: 500,
        });
    });

    it('never shrinks below the minimum size', () => {
        expect(clampSelection({ sx: 0, sy: 0, sw: 10, sh: 10 }, 1000, 500, 50).sw).toBe(50);
    });

    it('pulls a selection that ran off the right edge back inside', () => {
        const clamped = clampSelection({ sx: 900, sy: 0, sw: 500, sh: 500 }, 1000, 500, 50);

        expect(clamped.sx).toBe(500);
        expect(clamped.sx + clamped.sw).toBe(1000);
    });

    it('pulls negative offsets back to the origin', () => {
        expect(clampSelection({ sx: -100, sy: -100, sw: 300, sh: 300 }, 1000, 500, 50)).toEqual({
            sx: 0,
            sy: 0,
            sw: 300,
            sh: 300,
        });
    });

    it('pulls a selection that ran off the bottom edge back inside', () => {
        const clamped = clampSelection({ sx: 0, sy: 900, sw: 400, sh: 400 }, 500, 1000, 50);

        expect(clamped.sy).toBe(600);
        expect(clamped.sy + clamped.sh).toBe(1000);
    });
});

describe('resizeSelection', () => {
    const base = { sx: 200, sy: 100, sw: 200, sh: 200 };

    it('grows from the anchored top-left corner when the vertical drag dominates (se)', () => {
        const resized = resizeSelection(base, 'se', 500, 500, 1000, 600, 50);

        expect(resized).toEqual({ sx: 200, sy: 100, sw: 400, sh: 400 });
    });

    it('sizes from the horizontal drag when it dominates (se)', () => {
        const resized = resizeSelection(base, 'se', 700, 150, 1000, 600, 50);

        expect(resized).toEqual({ sx: 200, sy: 100, sw: 500, sh: 500 });
    });

    it('anchors the top-right corner when dragging the bottom-left (sw)', () => {
        const resized = resizeSelection(base, 'sw', 50, 120, 1000, 600, 50);

        expect(resized).toEqual({ sx: 50, sy: 100, sw: 350, sh: 350 });
    });

    it('anchors the bottom-left corner when dragging the top-right (ne)', () => {
        const resized = resizeSelection(base, 'ne', 450, 50, 1000, 600, 50);

        expect(resized).toEqual({ sx: 200, sy: 50, sw: 250, sh: 250 });
    });

    it('anchors the bottom-right corner when dragging the top-left (nw)', () => {
        const resized = resizeSelection(base, 'nw', 100, 50, 1000, 600, 50);

        expect(resized).toEqual({ sx: 100, sy: 0, sw: 300, sh: 300 });
    });

    it('stays square whichever axis the pointer favours', () => {
        const resized = resizeSelection(base, 'se', 260, 900, 1000, 600, 50);

        expect(resized.sw).toBe(resized.sh);
    });

    it('honours the minimum size when collapsed', () => {
        const resized = resizeSelection(base, 'se', 210, 110, 1000, 600, 50);

        expect(resized).toEqual({ sx: 200, sy: 100, sw: 50, sh: 50 });
    });

    it('never escapes the image bounds under an extreme drag', () => {
        const resized = resizeSelection(base, 'ne', 5000, -5000, 1000, 600, 50);

        expect(resized.sx).toBeGreaterThanOrEqual(0);
        expect(resized.sy).toBeGreaterThanOrEqual(0);
        expect(resized.sx + resized.sw).toBeLessThanOrEqual(1000);
        expect(resized.sy + resized.sh).toBeLessThanOrEqual(600);
    });
});

describe('resolveOutputMime', () => {
    it('passes through mimes the canvas can encode', () => {
        expect(resolveOutputMime('image/jpeg')).toBe('image/jpeg');
        expect(resolveOutputMime('image/png')).toBe('image/png');
        expect(resolveOutputMime('image/webp')).toBe('image/webp');
    });

    it('coerces mimes the canvas cannot encode to png', () => {
        expect(resolveOutputMime('image/gif')).toBe('image/png');
        expect(resolveOutputMime('image/svg+xml')).toBe('image/png');
        expect(resolveOutputMime('image/avif')).toBe('image/png');
        expect(resolveOutputMime('')).toBe('image/png');
    });
});

describe('resolveOutputFileName', () => {
    it('swaps the extension to match the output mime', () => {
        expect(resolveOutputFileName('logo.gif', 'image/png')).toBe('logo.png');
        expect(resolveOutputFileName('photo.jpeg', 'image/jpeg')).toBe('photo.jpg');
        expect(resolveOutputFileName('shot.PNG', 'image/webp')).toBe('shot.webp');
    });

    it('handles names without a usable extension', () => {
        expect(resolveOutputFileName('photo', 'image/png')).toBe('photo.png');
        expect(resolveOutputFileName('photo.', 'image/png')).toBe('photo.png');
        expect(resolveOutputFileName('', 'image/png')).toBe('image.png');
    });
});
