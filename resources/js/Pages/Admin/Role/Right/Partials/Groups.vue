<script setup>
defineProps({
    title: String,
    routes: Object,
    form: Object,
    methodMeta: Object,
    submit: Function,
});
</script>

<template>
    <form @submit.prevent="submit">

        <h2 class="text-2xl py-4 font-semibold text-warm-900">{{ title }}</h2>

        <!-- Groups -->
        <ul class="flex flex-col gap-4">
            <li v-for="(routes, groupName) in routes" :key="groupName">
                <h3 class="text-lg font-semibold text-warm-900">{{ groupName.toUpperCase().replaceAll('_', ' ') }}</h3>
                <ul class="flex flex-row gap-6">
                    <li v-for="r in routes" :key="r.route" class="">

                        <div class="flex items-center justify-between">
                            <label id="" class="">
                                <input type="checkbox" v-model="form.controller_method_names[r.route]"
                                    :class="methodMeta[r.method].colour" />
                                {{ r.method }}
                            </label>
                        </div>

                    </li>
                </ul>
            </li>
        </ul>

    </form>
</template>