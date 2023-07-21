<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";

const props = defineProps({
  categories: {
    type: Array,
    default: null,
  }
})

const form = useForm({
  category_name: props.categories ? props.categories.category_name : null,
})

function deleteCategory(categoryId) {
  router.delete(route("categories.destroy", categoryId), {
    preserveScroll: true,
    onSuccess: () => {
      document.activeElement.blur();
    },
  });

}

function addCategory(){
  router.get(route("categories.create"))
}

function editCategory(categoryId){
  router.get(route("categories.edit", categoryId))
}

</script>

<template>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <AppLayout title="Category">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Category
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <table class="table-auto ">
            <thead>
              <tr>
                <th class="px-4 py-2 w-full">Category</th>
                <th class="px-4 py-2 ">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="category in categories" :key="category.id">
                <td class="border px-4 py-2">{{ category.category_name }}</td>
                <td class="border px-4 py-2 flex items-center">
                  <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded" @click="deleteCategory(category.id)">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                  <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded ml-2" @click="editCategory(category.id)">
                    <i class="fas fa-edit"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="flex justify-end mt-4">
          <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" @click="addCategory">
            <i class="fas fa-plus-circle mr-2"></i> Add Category
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
