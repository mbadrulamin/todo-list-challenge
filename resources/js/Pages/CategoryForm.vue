<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
  categories: {
    type: Array,
    default: null,
  }
})


const form = useForm({
  category_name: props.categories ? props.categories.category_name : null,
})


// function submit() {
//   form.post(route("categories.add"), {
//     preserveScroll: true,
//     onSuccess: () => {
//       form.category_name = null;
//       document.activeElement.blur();
//     },
//   });
// }

function submit(){
  props.categories
  ? form.put(route("categories.update", { category: props.categories.id }))
  : form.post(route("categories.add"))
}


</script>

<template>
  <AppLayout title="Category">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Category
      </h2>
    </template>
    <div class="font-sans text-gray-900 dark:text-gray-100 antialiased">
      <div class="pt-4 bg-gray-100 dark:bg-gray-900">
        <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
          <div class="max-w-md w-full bg-white rounded-lg shadow-md mt-12">
            <form class="p-6">
              <div class="mb-6">
                <label for="category_name" class="block text-sm font-medium text-gray-700">
                  Category Name
                </label>
                <input
                  type="text"
                  id="category_name"
                  v-model="form.category_name"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-black"
                />
                <div v-if="form.errors.category_name" class="text-red-500 mt-2 text-sm">
                  {{ form.errors.category_name }}
                </div>
              </div>

              <div class="flex justify-end">
                <button
                  @click="submit()"
                  :disabled="form.processing"
                  class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  {{ categories ? 'Update' : 'Create' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

