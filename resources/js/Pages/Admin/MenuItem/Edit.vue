<script setup>
import { Link, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    menuItem: { type: Object, required: true },
    topLevelItems: { type: Array, required: true },
});

const itemType = ref(props.menuItem.href ? 'link' : 'submenu');

const form = useForm({
    label: props.menuItem.label,
    href: props.menuItem.href || '',
    parent_id: props.menuItem.parent_id || null,
    type: itemType.value,
});

const showHref = computed(() => form.data().type === 'link');

function submit() {
    if (form.data().type === 'submenu') {
        form.href = null;
    }
    form.patch(route('admin.menu-items.update', props.menuItem.id));
}

function deleteItem() {
    if (!confirm('Delete this menu item?')) return;
    router.delete(route('admin.menu-items.destroy', props.menuItem.id));
}
</script>

<template>
    <AuthenticatedLayout title="Menu Builder">
        <template #header>
            <h1 class="text-3xl font-bold text-warm-900">Edit Menu Item</h1>
        </template>

        <!-- Form -->
        <form @submit.prevent="submit" class="max-w-2xl bg-white rounded-2xl border border-warm-200 p-8 space-y-6">

            <!-- Label -->
            <div>
                <label class="block text-sm font-semibold text-warm-900 mb-2">Label *</label>
                <input v-model="form.label" type="text" placeholder="e.g., Services"
                       class="w-full px-4 py-2.5 border border-warm-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition"
                       />
                <p v-if="form.errors.label" class="text-red-600 text-xs mt-1">{{ form.errors.label }}</p>
            </div>

            <!-- Type selector -->
            <div>
                <label class="block text-sm font-semibold text-warm-900 mb-3">Type *</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input v-model="form.type" type="radio" value="link" class="w-4 h-4" />
                        <span class="text-sm text-warm-700">Link</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input v-model="form.type" type="radio" value="submenu" class="w-4 h-4" />
                        <span class="text-sm text-warm-700">Submenu Container</span>
                    </label>
                </div>
            </div>

            <!-- URL (conditionally shown) -->
            <div v-if="showHref">
                <label class="block text-sm font-semibold text-warm-900 mb-2">URL *</label>
                <input v-model="form.href" type="text" placeholder="e.g., /services or https://example.com"
                       class="w-full px-4 py-2.5 border border-warm-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition"
                       />
                <p v-if="form.errors.href" class="text-red-600 text-xs mt-1">{{ form.errors.href }}</p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
                <button type="submit" :disabled="form.processing"
                        class="px-6 py-2.5 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition-colors disabled:opacity-50">
                    Update Item
                </button>
                <button type="button" @click="deleteItem"
                        class="px-6 py-2.5 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-colors">
                    Delete
                </button>
                <Link href="/admin/menu-items"
                      class="px-6 py-2.5 border-2 border-warm-200 text-warm-700 font-semibold rounded-xl hover:border-warm-300 transition-colors">
                    Cancel
                </Link>
            </div>

        </form>

    </AuthenticatedLayout>
</template>
