<script setup>
import Empty from "@/Components/Empty.vue";
import Task from "@/Components/Task.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import {DateTime} from "luxon"
import { useForm } from "@inertiajs/vue3";
import { useFocus } from "@vueuse/core";
import { computed, onMounted, ref } from "vue";

const props = defineProps({
  tasks: {
    type: Array,
    default: () => [],
  },
  categories: {
    type: Array,
    default: () => [],
  }
});

const newTaskInput = ref(null);
const { focused } = useFocus(newTaskInput);
const showCompletedTask = ref(false);
const formNewTask = useForm({
  title: null,
  due_date: null,
  category_id: null,

});

// const availableTasks = computed(() =>
//   props.tasks.filter((task) => {
//     return !task.is_completed;
//   })
// );

const availableTasks = computed(() =>
props.tasks
.filter((task) => !task.is_completed)
.sort((a, b) => {
  return DateTime.fromISO(b.created_at).toMillis() - DateTime.fromISO(a.created_at).toMillis();
  // return b.created_at - a.created_at;
}))

const completedTasks = computed(() =>
  props.tasks.filter((task) => {
    return task.is_completed;
  })
);

function enterPressedOnNewTask() {
  formNewTask.post(route("tasks.store"), {
    preserveScroll: true,
    onSuccess: () => {
      formNewTask.title = null;
      formNewTask.due_date = null;
      document.activeElement.blur();
    },
  });
}

function getCurrentDateTime() {
  const now = new Date();
  const year = now.getFullYear();
  const month = (now.getMonth() + 1).toString().padStart(2, '0');
  const day = now.getDate().toString().padStart(2, '0');
  const hours = now.getHours().toString().padStart(2, '0');
  const minutes = now.getMinutes().toString().padStart(2, '0');
  return `${year}-${month}-${day}T${hours}:${minutes}`;
}

onMounted(() => {
  if (props.tasks.length == 0) {
    focused.value = true;
  }
});
</script>

<template>
  <AppLayout title="Dashboard">
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
      >
        Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex mb-2">
          <div
            class="flex bg-gray-200 hover:bg-gray-200 rounded-lg transition p-1 dark:bg-gray-700 dark:hover:bg-gray-700"
          >
            <nav class="flex space-x-2" aria-label="Tabs" role="tablist">
              <button
                type="button"
                :class="{
                  'bg-white text-gray-700 shadow-sm dark:text-gray-400 dark:bg-gray-800':
                    !showCompletedTask,
                }"
                class="text-xs text-gray-500 hover:text-gray-700 font-medium rounded-md py-2 px-3 dark:text-gray-400 dark:hover:text-gray-300"
                role="tab"
                @click="showCompletedTask = false"
              >
                Available Tasks
              </button>
              <button
                type="button"
                :class="{
                  'bg-white text-gray-700 shadow-sm dark:text-gray-400 dark:bg-gray-800':
                    showCompletedTask,
                }"
                class="text-xs text-gray-500 hover:text-gray-700 font-medium rounded-md py-2 px-3 dark:text-gray-400 dark:hover:text-gray-300"
                role="tab"
                @click="showCompletedTask = true"
              >
                Completed Tasks
              </button>
            </nav>
          </div>
        </div>
        <div v-if="showCompletedTask" class="space-y-2">
          <template v-if="completedTasks.length > 0">
            <Task v-for="task in completedTasks" :key="task.id" :task="task" />
          </template>
          <div v-else class="flex flex-col items-center px-4 py-6">
            <!-- <img
              src="images/urban-683.png"
              class="dark:mix-blend-overlay dark:grayscale"
            />
            <h5 class="text-2xl dark:text-gray-50">
              Nothing has been completed.
            </h5>
            <p class="dark:text-gray-300">
              Be productive and start completing your tasks!!
            </p> -->
            <Empty
              imageSrc="images/urban-683.png"
              title="Nothing has been completed."
              description="Be productive and start completing your tasks!!"
            />
          </div>
        </div>
        <div v-else class="space-y-2">
          <div class="flex rounded-md shadow-sm">
            <input
              name="newTask"
              v-model="formNewTask.title"
              type="text"
              ref="newTaskInput"
              placeholder="Add a task - enter to submit"
              class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-l-md shadow-sm w-full"
              @keyup.enter="enterPressedOnNewTask()"
            />
            <input
              name="dueDate"
              v-model="formNewTask.due_date"
              type="datetime-local"
              ref="newTaskInput"
              class="border-gray-300 bg-white dark:bg-gray-200 text-gray-900 dark:text-gray-700 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-r-md shadow-sm px-3 py-2 w-72"
              :min="getCurrentDateTime()"
            />
            <select
              name="category"
              v-model="formNewTask.category_id"
              ref="newTaskInput"
              class="border-gray-300 bg-white dark:bg-gray-200 text-gray-900 dark:text-gray-700 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-r-none shadow-sm px-3 py-2 w-64"
            >
              <option value="" disabled selected>Select Category</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.category_name }}</option>
            </select>
            <button
              type="button"
              class="inline-flex flex-shrink-0 items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-r-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
              @click="enterPressedOnNewTask()"
            >
              Submit
            </button>
          </div>
          <template v-if="availableTasks.length > 0">
            <Task v-for="task in availableTasks" :key="task.id" :task="task" :categories="categories"/>
          </template>
          <div v-else class="flex flex-col items-center px-4 py-6">
            <!-- <img
              src="images/urban-683.png"
              class="dark:mix-blend-overlay dark:grayscale"
            />
            <h5 class="text-2xl dark:text-gray-50">There is nothing in here</h5>
            <p class="dark:text-gray-300">Add new task to start!</p> -->

            <Empty
              imageSrc="images/urban-683.png"
              title="There is nothing in here"
              description="Add new task to start!"
            />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
