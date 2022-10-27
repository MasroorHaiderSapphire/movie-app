<template>
  <AdminLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit TV Show
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="w-full flex mb-4">
          <Link
            :href="route('admin.tv-shows.index')"
            class="px-4 py-2 bg-green-600 hover:bg-green-800 text-white rounded"
          >
            Back
          </Link>
        </div>
        <section class="flex content-center text-sm">
          <form
            @submit.prevent="updateTvShow"
            class="bg-white p-5 rounded shadow"
          >
            <div>
              <InputLabel for="title" value="TV Show Name" />
              <TextInput
                id="title"
                v-model="form.name"
                type="text"
                class="mt-1 block w-full"
                autofocus
                autocomplete="title"
              />
              <InputError class="mt-2" :message="form.errors.name" />
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
  name: props.tvShow.name,
});

const props = defineProps({
  tvShow: Object,
});

function updateTvShow() {
  form.put(route("admin.tv-shows.update", props.tvShow.id));
}
</script>
