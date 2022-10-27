<template>
  <AdminLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Genre
      </h2>
    </template>
    <section class="container mx-auto p-6 text-sm">
      <div class="w-full flex mb-4">
        <Link
          :href="route('admin.genres.index')"
          class="
            bg-green-500
            hover:bg-green-700
            text-white
            px-4
            py-2
            rounded-lg
          "
        >
          Back
        </Link>
      </div>
      <div
        class="
          w-full
          sm:max-w-md
          mb-8
          p-6
          overflow-hidden
          bg-white
          rounded-lg
          shadow-lg
        "
      >
        <form @submit.prevent="submitGenre">
          <div>
            <InputLabel for="title" value="Title" />
            <TextInput
              id="title"
              v-model="form.title"
              type="text"
              class="mt-1 block w-full"
              autofocus
              autocomplete="title"
            />
            <InputError class="mt-2" :message="form.errors.title" />
          </div>

          <div class="flex items-center mt-4">
            <button
              type="submit"
              class="
                px-4
                py-2
                bg-indigo-500
                hover:bg-indigo-700
                text-white
                rounded
              "
            >
              Save
            </button>
          </div>
        </form>
      </div>
    </section>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from "../../Layouts/AdminLayout.vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import { ref } from "@vue/reactivity";
import { watch } from "@vue/runtime-core";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const form = useForm({
  title: props.genre.title,
});

const props = defineProps({
  genre: Object,
});

function submitGenre() {
  form.put(route("admin.genres.update", props.genre.id));
}
</script>
