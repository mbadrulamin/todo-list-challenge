<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import { DateTime } from "luxon";
import { router } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
  task: {
    type: Object,
    default: null,
  },
  categories: {
    type: Array,
    default: () => [],
  },
  category: {
    type: Array,
    default: () => [],
  },
});

console.log('tasks', props.task);
console.log('categories', props.categories);

const showDeleteConfirmation = ref(false);
const showDeleteButton = ref(false);

function updateTask(taskId) {
  router.put(
    route("tasks.update", taskId),
    {
      is_completed: !props.task.is_completed,
    },
    {
      preserveScroll: true,
      onSuccess: () => {
        document.activeElement.blur();
      },
    }
  );
}

function deleteTask(taskId) {
  router.delete(route("tasks.destroy", taskId), {
    preserveScroll: true,
    onSuccess: () => {
      document.activeElement.blur();
    },
  });
}

function formatDate(date) {
  if (!date) {
    return '';
  }
  const formattedDate = DateTime.fromISO(date).toLocaleString(DateTime.DATETIME_MED);
  return formattedDate;
}

function confirmDelete(taskId) {
  showDeleteConfirmation.value = true;
}


function getCategoryName(categoryId) {
  if(!categoryId){
    return 'No Category';
  }

  const categoryName = props.category[categoryId];
  return categoryName ? categoryName : 'No Category';
}

</script>

<template>
  <div
    class="p-4 rounded-md mb-2 shadow-sm"
    :class="task.is_completed ? 'bg-gray-50' : 'bg-white'"
    @mouseover="showDeleteButton = true"
    @mouseleave="showDeleteButton = false"
  >
    <ConfirmationModal
      :show="showDeleteConfirmation"
      maxWidth="sm"
      @close="showDeleteConfirmation = false"
    >
      <template #title>
        <h3 class="text-lg font-medium">Confirmation</h3>
      </template>
      <template #content>
        <p>Are you sure you want to delete this task?</p>
      </template>
      <template #footer>
        <button
          class="px-4 py-2 bg-red-500 text-white rounded-md"
          @click="deleteTask(task.id)"
        >
          Delete
        </button>
        <button
          class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md ml-2"
          @click="showDeleteConfirmation = false"
        >
          Cancel
        </button>
      </template>
    </ConfirmationModal>
    <div class="flex items-center justify-between space-x-3">
      <Checkbox
        :checked="task.is_completed"
        @change="updateTask(task.id)"
        :value="task.id"
      />
      <div class="grow">
        <label
          :for="task.id"
          class="text-gray-700"
          :class="{ 'line-through': task.is_completed }"
          >{{ task.title }}</label
        >
        <p v-if="task.is_completed" class="text-gray-500 text-sm">
          Category: {{ getCategoryName(task.category_id) }} <br>
          Completed on: {{ formatDate(task.updated_at) }}
        </p>
        <p v-else class="text-gray-500 text-sm" >
          Category: {{ getCategoryName(task.category_id) }} <br>
          Due on: {{task.due_date ? task.due_date : 'No Due Date'}}
        </p>
      </div>
      <button v-show="showDeleteButton" @click="confirmDelete(task.id)">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          height="1em"
          viewBox="0 0 448 512"
        >
          <path
            d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z"
          />
        </svg>
      </button>
    </div>
  </div>
</template>
