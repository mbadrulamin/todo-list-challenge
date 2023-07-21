<template>
  <div class="flex flex-col items-center px-4 py-6">
    <img
      :src="imageSrc"
      class="dark:mix-blend-overlay dark:grayscale"
    />
    <h5 class="text-2xl dark:text-gray-50">{{ title }}</h5>
    <p class="dark:text-gray-300">{{ description }}</p>
  </div>
</template>

<script>
export default {
  props: {
    imageSrc: {
      type: String,
      required: true,
    },
    title: {
      type: String,
      required: true,
    },
    description: {
      type: String,
      required: true,
    },
  },
};
</script>
