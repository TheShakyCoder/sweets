<script setup>
/**
 * MediaPicker — modal that lets you pick from the media library or upload new files.
 *
 * Usage:
 *   <MediaPicker v-model="form.thumbnail" v-model:url="thumbnailUrl" />
 *
 *   v-model        → the media ID (stored in DB as post.thumbnail_id)
 *   v-model:url    → the public URL (for preview, not persisted)
 */
import { ref, onMounted, watch } from "vue";
import axios from "axios";

const props = defineProps({
  modelValue: { default: null }, // media ID
  url: { default: null }, // preview URL
});

const emit = defineEmits(["update:modelValue", "update:url"]);

const open = ref(false);
const media = ref([]);
const loading = ref(false);
const page = ref(1);
const lastPage = ref(1);
const search = ref("");
const uploading = ref(false);
const selected = ref(props.modelValue);

watch(
  () => props.modelValue,
  (v) => (selected.value = v)
);

async function load(pg = 1) {
  loading.value = true;
  const { data } = await axios.get("/internal/media", {
    params: { page: pg, search: search.value },
    headers: { "X-Inertia": false, Accept: "application/json" },
  });
  if (pg === 1) media.value = data.media.data;
  else media.value.push(...data.media.data);
  lastPage.value = data.media.last_page;
  page.value = pg;
  loading.value = false;
}

function openPicker() {
  open.value = true;
  load(1);
}

function closePicker() {
  open.value = false;
}

function choose(item) {
  selected.value = item.id;
  emit("update:modelValue", item.id);
  emit("update:url", item.url);
  closePicker();
}

function clearSelection() {
  selected.value = null;
  emit("update:modelValue", null);
  emit("update:url", null);
}

let searchTimer;
function onSearch() {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(() => load(1), 300);
}

async function onUpload(e) {
  const files = [...e.target.files];
  console.log("Uploading files:", files);
  if (!files.length) return;
  uploading.value = true;
  const formData = new FormData();
  files.forEach((f) => formData.append("files[]", f));
  await axios.post("/internal/media", formData);
  e.target.value = "";
  uploading.value = false;
  load(1);
}

function isImage(mime) {
  return mime?.startsWith("image/");
}
function fileSize(bytes) {
  if (bytes < 1024) return bytes + " B";
  if (bytes < 1048576) return (bytes / 1024).toFixed(1) + " KB";
  return (bytes / 1048576).toFixed(1) + " MB";
}
</script>

<template>
  <!-- Trigger area -->
  <div>
    <!-- Preview -->
    <div
      v-if="url"
      class="relative rounded-xl overflow-hidden aspect-video bg-warm-100 mb-3"
    >
      <img :src="url" class="w-full h-full object-cover" alt="Selected media" />
      <button
        type="button"
        @click="clearSelection"
        class="absolute top-2 right-2 p-1 bg-black/50 rounded-full text-white hover:bg-black/70 transition-colors"
      >
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M6 18L18 6M6 6l12 12"
          />
        </svg>
      </button>
    </div>

    <button
      type="button"
      @click="openPicker"
      class="w-full flex flex-col items-center gap-2 p-4 border-2 border-dashed border-warm-200 rounded-xl cursor-pointer hover:border-brand-300 hover:bg-brand-50/50 transition-colors"
    >
      <svg
        class="w-6 h-6 text-warm-400"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="1.5"
          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
        />
      </svg>
      <span class="text-xs text-warm-500">{{
        url ? "Change image" : "Select from media library"
      }}</span>
    </button>
  </div>

  <!-- Modal -->
  <teleport to="body">
    <transition
      enter-from-class="opacity-0"
      enter-active-class="transition duration-150"
      leave-to-class="opacity-0"
      leave-active-class="transition duration-150"
    >
      <div
        v-if="open"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
      >
        <div
          class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[85vh] flex flex-col overflow-hidden"
        >
          <!-- Header -->
          <div
            class="flex items-center justify-between px-6 py-4 border-b border-warm-100"
          >
            <h2 class="font-semibold text-warm-900 text-sm">Media Library</h2>
            <div class="flex items-center gap-3">
              <!-- Upload button -->
              <label
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-brand-600 text-white rounded-lg hover:bg-brand-700 transition-colors cursor-pointer"
              >
                <svg
                  class="w-3.5 h-3.5"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"
                  />
                </svg>
                {{ uploading ? "Uploading…" : "Upload" }}
                <input
                  type="file"
                  multiple
                  accept="image/*"
                  class="sr-only"
                  @change="onUpload"
                  :disabled="uploading"
                />
              </label>
              <!-- Search -->
              <input
                v-model="search"
                @input="onSearch"
                type="search"
                placeholder="Search…"
                class="px-3 py-1.5 text-xs border border-warm-200 rounded-lg bg-white text-warm-900 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition w-40"
              />
              <!-- Close -->
              <button
                @click="closePicker"
                type="button"
                class="p-1.5 rounded-lg text-warm-400 hover:bg-warm-100 hover:text-warm-700 transition-colors"
              >
                <svg
                  class="w-4 h-4"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </button>
            </div>
          </div>

          <!-- Grid -->
          <div class="flex-1 overflow-y-auto p-6">
            <div
              v-if="loading && !media.length"
              class="grid grid-cols-4 sm:grid-cols-6 gap-3"
            >
              <div
                v-for="n in 12"
                :key="n"
                class="aspect-square rounded-xl bg-warm-100 animate-pulse"
              ></div>
            </div>

            <div
              v-else-if="!media.length"
              class="flex flex-col items-center justify-center py-16 text-center"
            >
              <svg
                class="w-10 h-10 text-warm-300 mb-2"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="1.5"
                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
              </svg>
              <p class="text-sm text-warm-500">No media found.</p>
            </div>

            <div v-else class="grid grid-cols-4 sm:grid-cols-6 gap-3">
              <button
                v-for="item in media"
                :key="item.id"
                type="button"
                @click="choose(item)"
                class="group relative aspect-square rounded-xl overflow-hidden border-2 transition-all"
                :class="
                  selected === item.id
                    ? 'border-brand-500 shadow-md'
                    : 'border-warm-200 hover:border-brand-300'
                "
              >
                <img
                  v-if="isImage(item.mime_type)"
                  :src="item.url"
                  :alt="item.alt ?? item.filename"
                  class="w-full h-full object-cover"
                  loading="lazy"
                />
                <div
                  v-else
                  class="w-full h-full bg-warm-50 flex items-center justify-center"
                >
                  <svg
                    class="w-6 h-6 text-warm-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.5"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                  </svg>
                </div>
                <!-- Selected tick -->
                <div
                  v-if="selected === item.id"
                  class="absolute top-1 right-1 w-5 h-5 bg-brand-600 rounded-full flex items-center justify-center"
                >
                  <svg
                    class="w-3 h-3 text-white"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="3"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                </div>
                <!-- Hover filename -->
                <div
                  class="absolute inset-x-0 bottom-0 bg-black/50 px-1.5 py-1 translate-y-full group-hover:translate-y-0 transition-transform"
                >
                  <p class="text-white text-[10px] truncate">{{ item.filename }}</p>
                </div>
              </button>
            </div>

            <!-- Load more -->
            <div v-if="page < lastPage" class="mt-6 flex justify-center">
              <button
                type="button"
                @click="load(page + 1)"
                :disabled="loading"
                class="px-5 py-2 text-sm font-medium border border-warm-200 text-warm-600 rounded-xl hover:bg-warm-50 transition-colors disabled:opacity-50"
              >
                {{ loading ? "Loading…" : "Load more" }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </teleport>
</template>
