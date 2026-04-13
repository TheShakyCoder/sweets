<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout/Index.vue';
import { onMounted } from 'vue';

const page = usePage();


const props = defineProps({
    items: { type: Array, required: true },
});

function deleteItem(id) {
    if (!confirm('Delete this menu item?')) return;
    router.delete(route('internal.menu-items.destroy', id), {
        onSuccess: () => {
            console.log('Item deleted');
        }
    });
}

onMounted(() => {
    console.log('Can:', page.props.can);
    console.log('Route name:', route().current());

});
</script>

<template>
    <AuthenticatedLayout title="Menu Builder">
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-warm-900">Menu Builder</h1>
                    <p class="text-warm-500 mt-1">Manage the header navigation menu</p>
                </div>
                <Link href="/internal/menu-items/create"
                      class="px-4 py-2 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition-colors">
                    + New Item
                </Link>
            </div>
        </template>

        <!-- Flash message -->
        <div v-if="page.props.flash.success" class="mb-6 flex items-center gap-2 px-4 py-3 bg-brand-50 border border-brand-200 rounded-xl text-sm text-brand-700">
            {{ page.props.flash.success }}
        </div>

        <!-- Menu tree -->
        <div class="bg-white rounded-2xl border border-warm-200 overflow-hidden">
            <div v-if="items.length === 0" class="p-12 text-center">
                <p class="text-warm-500">No menu items yet. Create one to get started.</p>
            </div>

            <div v-else class="divide-y divide-warm-100">
                <!-- Top-level item -->
                <div v-for="item in items" :key="item.id" class="p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="font-semibold text-warm-900">{{ item.label }}</p>
                            <p class="text-xs text-warm-500 mt-1">
                                {{ item.href ? '🔗 ' + item.href : '📁 Submenu' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <Link :href="`/internal/menu-items/${item.id}/edit`"
                                  class="px-3 py-1.5 text-sm text-brand-600 hover:bg-brand-50 rounded-lg transition-colors">
                                Edit
                            </Link>
                            <button @click="deleteItem(item.id)"
                                    class="px-3 py-1.5 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                Delete
                            </button>
                        </div>
                    </div>

                    <!-- Child items (submenu) -->
                    <div v-if="item.children.length > 0" class="mt-4 ml-6 space-y-2 pt-4 border-l-2 border-warm-100 pl-4">
                        <div v-for="child in item.children" :key="child.id" class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-sm text-warm-700">{{ child.label }}</p>
                                <p class="text-xs text-warm-500">🔗 {{ child.href }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <Link :href="`/internal/menu-items/${child.id}/edit`"
                                      class="px-2 py-1 text-xs text-brand-600 hover:bg-brand-50 rounded transition-colors">
                                    Edit
                                </Link>
                                <button @click="deleteItem(child.id)"
                                        class="px-2 py-1 text-xs text-red-600 hover:bg-red-50 rounded transition-colors">
                                    Delete
                                </button>
                            </div>
                        </div>
                        <Link :href="`/internal/menu-items/create?parent_id=${item.id}`"
                              class="inline-flex text-xs text-brand-600 hover:text-brand-800 mt-2">
                            + Add submenu item
                        </Link>
                    </div>

                    <!-- Add submenu button for items without children -->
                    <div v-else class="mt-3">
                        <Link :href="`/internal/menu-items/create?parent_id=${item.id}`"
                              class="inline-flex text-xs text-brand-600 hover:text-brand-800">
                            + Add submenu
                        </Link>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
