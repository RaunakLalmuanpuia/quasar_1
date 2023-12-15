<template>
    <QuasarLayout>
        <Head title="Media"/>
        <h3>Upload Media</h3>
        
        <div class="q-pa-md q-gutter-sm">
            <q-btn label="Add Media" color="primary" @click="prompt = true" />
            <q-dialog v-model="prompt" persistent>
            <q-card style="min-width: 350px">
                <q-card-section>
                <div class="text-h6">New Media</div>
                </q-card-section>

                <q-card-section class="q-pt-none">
                <q-input label="Title" dense v-model="form.title" autofocus @keyup.enter="prompt = false" />
                </q-card-section>
                
                <q-card-section class="q-pt-none">
                <q-input label="About" dense v-model="form.about" autofocus @keyup.enter="prompt = false" />
                </q-card-section>

                
                <q-card-section class="q-pt-none">
                    <q-file outlined v-model="form.image">
                        <template v-slot:prepend>
                        <q-icon name="attach_file" />
                        </template>
                    </q-file>
                <!-- <q-input label="Image" dense v-model="form.about" autofocus @keyup.enter="prompt = false" /> -->
                </q-card-section>
                
                
                <q-card-section class="q-pt-none">
                <q-input label="Video id" dense v-model="form.video_id" autofocus @keyup.enter="prompt = false" />
                </q-card-section>

                
                <q-card-actions align="right" class="text-primary">
                <q-btn flat label="Cancel" v-close-popup @click="resetform" />
                <q-btn flat label="Add Media" v-close-popup   @click="onSubmit"/>
                </q-card-actions>
            </q-card>
            </q-dialog>
        </div>
        
        
        <main class="container w-full p-4 mx-auto">
            <div
                class="p-4 border border-gray-200 rounded-md shadow-sm dark:border-gray-800 dark:text-gray-300"
            >
                <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <div v-for="media in medias.data"
                        :key="media.id">
                       {{ media }}
                       <q-btn class="ml-40" round color="primary" icon="delete" @click="destroy(media.id)" />
                       <Link 
                        v-if="media.id"
                        :href="route('media.show', media.id)">
                            <q-card class="my-card">
                               
                                <q-img :src="'/storage/'  + media.image" />
           
                                <q-card-section>
                                    <div class="text-h6">{{ media.title }}</div>
                                    
                                </q-card-section>
                                <!-- <YouTube 
                                    :src="media.video_id" 
                                    width=""
                                    ref="youtube"/> -->
                                <q-card-section class="q-pt-none">
                                    {{media.about}}
                                </q-card-section>
                            </q-card>  
                        </Link>
                       
                    </div>
                </div>
            </div>
                
                


            <div class="flex justify-center mt-4">
                <div class="flex gap-1">
                    <Link
                        v-for="(link, index) in medias.links"
                        :key="index"
                        class="px-4 py-2 rounded-md"
                        :href="link.url || ''"
                        :class="{
                            'bg-indigo-500 dark:bg-indigo-800 text-gray-300':
                                link.active,
                        }"
                        v-html="link.label"
                    />
                </div>
            </div>
        </main>
        
    
    </QuasarLayout>
    
</template>

<script setup>
import QuasarLayout from "@/Layouts/QuasarLayout.vue";
import {Head, Link, useForm} from '@inertiajs/vue3'
import { ref } from 'vue'
import YouTube from 'vue3-youtube'

const prompt = ref(false);

const form = useForm({
    title: "",
    about: "",
    image: "",
    video_id: ""
});

const props = defineProps({
    medias: Object,
});

const onSubmit = () => {
    form.post(route("media.store"));
    form.title = "",
    form.about = "",
    form.image = "",
    form.video_id = ""
};

const resetform = () => {
    form.title = "",
    form.about = "",
    form.image = "",
    form.video_id = ""
};

function destroy(id) {
    if (confirm("Delete Media")) {
        form.delete(route("media.destroy", id));
    }
}
</script>