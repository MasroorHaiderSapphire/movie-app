<template>
  <AdminLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Movie Attach | {{ movie.title }}
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
        <div
          class="
            w-full
            mb-8
            sm:max-w-md
            p-6
            overflow-hidden
            bg-white
            rounded-lg
            shadow-lg
          "
        >
          <div class="flex space-x-2">
            <div v-for="trailer in trailers" :key="trailer.id">
              <Link
                class="text-sm p-2 bg-red-500 hover:bg-red-700 rounded"
                :href="route('admin.trailers.destroy', trailer.id)"
                method="DELETE"
                as="button"
                type="button"
              >
                {{ trailer.name }}
              </Link>
            </div>
          </div>
          <form @submit.prevent="submitTrailer">
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
              <InputLabel for="embed_html" value="Embed" />
              <textarea
                id="embed_html"
                v-model="form.embed_html"
                class="mt-1 block w-full"
              ></textarea>
              <InputError class="mt-2" :message="form.errors.embed_html" />
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
                Add Trailer
              </button>
            </div>
          </form>
        </div>
        <div
          class="
            w-full
            mb-8
            sm:max-w-md
            p-6
            overflow-hidden
            bg-white
            rounded-lg
            shadow-lg
          "
        >
          <form @submit.prevent="addCast">
            <select
              v-model="castForm.casts"
              multiple
              class="w-full border-opacity-50 my-2"
            >
              <option
                v-for="cast in casts"
                :key="cast"
                :value="{ id: cast.id }"
              >
                {{ cast.name }}
              </option>
            </select>
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
              Add Cast
            </button>
          </form>
        </div>
        <div
          class="
            w-full
            mb-8
            sm:max-w-md
            p-6
            overflow-hidden
            bg-white
            rounded-lg
            shadow-lg
          "
        >
          <form @submit.prevent="addTags">
            <select
              v-model="tagsForm.tags"
              multiple
              class="w-full border-opacity-50 my-2"
            >
              <option v-for="tag in tags" :key="tag" :value="{ id: tag.id }">
                {{ tag.tag_name }}
              </option>
            </select>
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
              Add Tags
            </button>
          </form>
        </div>
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
import Multiselect from "vue-multiselect";
import { ref } from "@vue/reactivity";

const form = useForm({
  name: "",
  embed_html: "",
});

const castForm = useForm({
  casts: props.movieCasts,
});

const tagsForm = useForm({
  tags: props.movieTags,
});

const props = defineProps({
  movie: Object,
  trailers: Array,
  casts: Array,
  tags: Array,
  movieCast: Array,
  movieTags: Array,
});

function submitTrailer() {
  form.post(route("admin.movies.add.trailer", props.movie.id), {
    onSuccess: () => form.reset(),
  });
}

function addCast() {
  castForm.post(route("admin.movies.add.casts", props.movie.id), {
    preserveScroll: true,
    preserveState: true,
  });
}

function addTags() {
  tagsForm.post(route("admin.movies.add.tags", props.movie.id), {
    preserveScroll: true,
    preserveState: true,
  });
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
