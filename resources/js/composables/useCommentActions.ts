import { ref } from 'vue';

import { useForm } from '@inertiajs/vue3';

import { route } from 'ziggy-js';

import { useFlash } from '@/composables/useFlash';
import { useSound } from '@/composables/useSound';
import { Comment } from '@/types';

export function useCommentActions() {
    const { playSuccess, playCancel } = useSound();
    const { notify, notifyWithUndo } = useFlash();

    const isEditModalOpen = ref(false);
    const selectedComment = ref<Comment | null>(null);
    const deletingIds = ref(new Set<number>());

    const editForm = useForm({
        body: '',
        rating: 5.0,
    });

    const openEditModal = (comment: Comment) => {
        selectedComment.value = comment;
        editForm.body = comment.body;
        editForm.rating = comment.rating;
        isEditModalOpen.value = true;
    };

    const handleUpdate = () => {
        if (!selectedComment.value) return;

        editForm.patch(route('comments.update', selectedComment.value.id), {
            preserveScroll: true,
            onSuccess: () => {
                isEditModalOpen.value = false;
                playSuccess();
            },
            onError: (err) => {
                notify(Object.values(err)[0] as string, 'error');
                playCancel();
            },
        });
    };

    const deleteComment = async (id: number) => {
        deletingIds.value.add(id);
        const confirmed = await notifyWithUndo('System: Deletion scheduled...', 5000);

        if (confirmed) {
            editForm.delete(route('comments.destroy', id), {
                preserveScroll: true,
                onSuccess: () => playSuccess(),
                onFinish: () => deletingIds.value.delete(id),
            });
        } else {
            deletingIds.value.delete(id);
        }
    };

    return {
        isEditModalOpen,
        selectedComment,
        editForm,
        deletingIds,
        openEditModal,
        handleUpdate,
        deleteComment,
    };
}
