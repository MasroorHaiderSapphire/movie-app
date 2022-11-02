<template>
  <AdminLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ tvShow.name }} | {{ season.season_number }} | {{ episode.name }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="w-full flex mb-4">
          <Link
            :href="route('admin.episodes.index', [tvShow.id, season.id])"
            class="px-4 py-2 bg-green-600 hover:bg-green-800 text-white rounded"
          >
            Back
          </Link>
        </div>
        <section class="flex content-center text-sm">
          <form
            @submit.prevent="updateEpisode"
            class="bg-white p-5 rounded shadow"
          >
            <div>
              <InputLabel for="title" value="Episode Name" />
              <TextInput
                id="title"
                v-model="form.name"
                type="text"
                class="mt-1 block w-full"
                autofocus
              />
              <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <div>
              <InputLabel for="overview" value="Overview" />
              <textarea
                id="overview"
                v-model="form.overview"
                class="mt-1 block w-full"
                cols="40"
                rows="10"
              />
              <InputError class="mt-2" :message="form.errors.overview" />
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
                  hover:
                  bg-indigo-700
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
import AdminLayout from "@/Layouts/AdminLayout.vue";
import InputError from "@/Components/InputError.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const form = useForm({
  name: props.episode.name,
  overview: props.episode.overview,
  is_public: Boolean(props.episode.is_public),
});

const props = defineProps({
  tvShow: Object,
  season: Object,
  episode: Object,
});

function updateEpisode() {
  form.put(
    route("admin.episodes.update", [
      props.tvShow.id,
      props.season.id,
      props.episode.id,
    ])
  );
}
</script>
