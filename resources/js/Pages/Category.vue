<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
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
  <Head title="Category" />

  <div class="font-sans text-gray-900 dark:text-gray-100 antialiased">
    <div class="pt-4 bg-gray-100 dark:bg-gray-900">
      <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
        <div>
          <ApplicationLogo />
        </div>

        <button @click="addCategory">Add Category</button>
        <div class="py-12">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
              <ul>
                <li v-for="category in categories" :key="category.id" :value="category.id" class="text-rose-300 dark:text-rose-900 ">
                  {{ category.category_name }}
                  <button @click="editCategory(category.id)">Edit</button>
                  <button @click="deleteCategory(category.id)">Delete</button>
                </li>
              </ul>
            </div>
          </div>
        </div>



      </div>
    </div>
  </div>
</template>
