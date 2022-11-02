<template>
  <AdminLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Movie | {{ movie.title }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="w-full flex mb-4">
          <Link
            :href="route('admin.movies.index')"
            class="px-4 py-2 bg-green-600 hover:bg-green-800 text-white rounded"
          >
            Back
          </Link>
        </div>
        <section class="flex content-center text-sm">
          <form
            @submit.prevent="updateMovie"
            class="bg-white p-5 rounded shadow"
          >
            <div>
              <InputLabel for="title" value="Movie Title" />
              <TextInput
                id="title"
                v-model="form.title"
                type="text"
                class="mt-1 block w-full"
                autofocus
              />
              <InputError class="mt-2" :message="form.errors.title" />
            </div>
            <div>
              <InputLabel for="overview" value="Overview" />
              <TextInput
                id="overview"
                v-model="form.overview"
                type="text"
                class="mt-1 block w-full"
              />
              <InputError class="mt-2" :message="form.errors.overview" />
            </div>
            <div>
              <InputLabel for="runtime" value="Runtime" />
              <TextInput
                id="runtime"
                v-model="form.runtime"
                type="number"
                class="mt-1 block w-full"
              />
              <InputError class="mt-2" :message="form.errors.runtime" />
            </div>
            <div>
              <InputLabel for="poster_path" value="Poster" />
              <TextInput
                id="poster_path"
                v-model="form.poster_path"
                type="text"
                class="mt-1 block w-full"
              />
              <InputError class="mt-2" :message="form.errors.poster_path" />
            </div>
            <div class="flex space-x-2 my-2 items-center">
              <Checkbox v-model:checked="form.is_public" name="is_public" />
              <InputLabel for="is_public" value="Publish" />
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
        </section>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, useForm } from "@inertiajs/inertia-vue3";
import AdminLayout from "../../Layouts/AdminLayout.vue";
import InputError from "@/Components/InputError.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const form = useForm({
  title: props.movie.title,
  overview: props.movie.overview,
  poster_path: props.movie.poster_path,
  is_public: Boolean(props.movie.is_public),
  runtime: props.movie.runtime,
  poster_path: props.movie.poster_path,
});

const props = defineProps({
  movie: Object,
});

function updateMovie() {
  form.put(route("admin.movies.update", props.movie.id));
}
</script>
