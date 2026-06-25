import { ref } from 'vue';

import { fromMimeType, MediaType } from '@/lib/mediaType';

export interface DragAndDropOptions {
    acceptImages: boolean;
    acceptVideos: boolean;
    maxFiles: number;
    disabled?: boolean;
}

export function useDragAndDrop(options: DragAndDropOptions) {
    const isDragging = ref(false);

    const isValidFileType = (file: File): boolean => {
        const type = fromMimeType(file.type);

        if (type === MediaType.Image && !options.acceptImages) {
            return false;
        }
        if (type === MediaType.Video && !options.acceptVideos) {
            return false;
        }
        return type === MediaType.Image || type === MediaType.Video;
    };

    const handleDragOver = (e: DragEvent) => {
        e.preventDefault();
        if (!options.disabled) {
            isDragging.value = true;
        }
    };

    const handleDragLeave = (e: DragEvent) => {
        e.preventDefault();
        isDragging.value = false;
    };

    const handleDrop = (e: DragEvent, currentMediaCount: number, onUpload: (files: File[]) => void) => {
        e.preventDefault();
        isDragging.value = false;

        if (options.disabled || !e.dataTransfer?.files) {
            return;
        }

        const files = Array.from(e.dataTransfer.files);
        const validFiles = files.filter(isValidFileType);

        if (validFiles.length === 0) {
            return;
        }

        // Limit to remaining slots
        const remainingSlots = options.maxFiles - currentMediaCount;
        const filesToUpload = validFiles.slice(0, remainingSlots);

        if (filesToUpload.length > 0) {
            onUpload(filesToUpload);
        }
    };

    return {
        isDragging,
        handleDragOver,
        handleDragLeave,
        handleDrop,
    };
}
