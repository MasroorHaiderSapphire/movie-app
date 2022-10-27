<template>
  <AdminLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Cast
      </h2>
    </template>
    <section class="container mx-auto p-6 text-sm">
      <div class="w-full flex mb-4">
        <Link
          :href="route('admin.casts.index')"
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
        <form @submit.prevent="submitCast">
          <div>
            <InputLabel for="name" value="Name" />
            <TextInput
              id="name"
              v-model="form.name"
              type="text"
              class="mt-1 block w-full"
              autofocus
              autocomplete="name"
            />
            <InputError class="mt-2" :message="form.errors.name" />
          </div>

          <div class="mt-4">
            <InputLabel for="poster_path" value="Poster" />
            <TextInput
              id="poster_path"
              v-model="form.poster_path"
              type="text"
              class="mt-1 block w-full"
            />
            <InputError class="mt-2" :message="form.errors.poster_path" />
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
  name: props.cast.name,
  poster_path: props.cast.poster_path,
});

const props = defineProps({
  cast: Object,
});

function submitCast() {
  form.put(route("admin.casts.update", props.cast.id));
}
</script>

    <style>
</style>
