<script setup>
import { ref, computed } from 'vue';
import { Cropper, Preview } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';
import { AlertDialog, AlertDialogContent, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogDescription, AlertDialogTrigger, AlertDialogAction, AlertDialogCancel } from '@/Components/ui/alert-dialog';
import { Avatar, AvatarImage } from '@/Components/ui/avatar';
import { Pencil, LoaderCircle } from 'lucide-vue-next';
import { Input, InputStatusMessage } from '@/Components/ui/input';
import { Button } from '@/components/ui/button';
import { Label } from '@/Components/ui/label';
import { router } from '@inertiajs/vue3';
import { useFlashMessages } from "@/composables/useFlashMessage";

// Props
const props = defineProps({
    user: {
        type: Object,
        required: true
    },
});

// Reactive data
const showUploadDialog = ref(false);
const showCropper = ref(false);
const isCroppingFinished = ref(false);
const validationErrors = ref(null);
const result = ref({
    coordinates: null,
    image: null
});
const loadingState = ref({
    validation: false,
    uploading: false
});
const croppedImageSrc = ref(null);
const croppedCanvas = ref(null);
const originalFileType = ref(null);

// Template ref
const cropper = ref(null);

// Computed
const userAvatar = computed(() => {
    if (showCropper.value && croppedImageSrc.value) {
        return croppedImageSrc.value;
    }
    return props.user.avatar || '';
});

// Methods
const defaultSize = () => {
    return {
        width: 300,
        height: 300
    }
};

const handleImageUpload = (event) => {
    const file = event.target.files[0];

    if (file) {
        // Store the original file type for later use
        originalFileType.value = file.type;

        // First, validate the file with server
        loadingState.value.validation = true;
        validationErrors.value = null;
        const formData = new FormData();
        formData.append('image', file);

        router.post(route('settings.profile.picture.validation'), formData, {
            onSuccess: () => {
                // Validation passed, proceed to cropper
                validationErrors.value = null;
                loadingState.value.validation = false;
                const reader = new FileReader();
                reader.onload = (e) => {
                    croppedImageSrc.value = e.target.result;
                    showCropper.value = true;
                }
                reader.readAsDataURL(file);
            },
            onError: (errors) => {
                loadingState.value.validation = false;
                // Show validation errors
                validationErrors.value = errors;
            },
        });
    }
};

const onChange = ({ coordinates, image }) => {
    result.value = {
        coordinates: coordinates,
        image: image
    }
};

const handleCancelCropper = () => {
    showCropper.value = false;
    showUploadDialog.value = true;
    isCroppingFinished.value = false;
    croppedImageSrc.value = null;
    croppedCanvas.value = null;
    validationErrors.value = null;
};

const handleCancelUpload = () => {
    showUploadDialog.value = false;
    validationErrors.value = null;
    isCroppingFinished.value = false;
};

const handleCrop = () => {
    showCropper.value = false;
    isCroppingFinished.value = true;
    const cropperResult = cropper.value.getResult();
    croppedImageSrc.value = cropperResult.canvas.toDataURL();
    croppedCanvas.value = cropperResult.canvas;
};

const handleUpdateAvatar = () => {
    const canvas = croppedCanvas.value;

    loadingState.value.uploading = true;

    if (canvas) {
        const formData = new FormData();

        canvas.toBlob(blob => {
            formData.append('image', blob);

            router.post(route('settings.profile.picture.store'), formData, {
                onProgress: (progress) => {
                    loadingState.value.uploading = true;
                },
                onSuccess: () => {
                    useFlashMessages('Profile picture status')
                    loadingState.value.uploading = false;
                    showUploadDialog.value = false;
                    isCroppingFinished.value = false;
                    showCropper.value = false;
                    validationErrors.value = null;
                },
                onError: (errors) => {
                    loadingState.value.uploading = false;
                    validationErrors.value = errors;
                    console.error('Upload errors:', errors);
                }
            });
        }, originalFileType.value); // Use the original file type 
    }
};
</script>
<template>
    <div class="relative">
        <Label for="profile-picture" class="block text-sm font-medium">Profile Picture</Label>

        <div class="relative">
            <Avatar class="w-40 h-40 mt-2">
                <AvatarImage :src="user.avatar" />
            </Avatar>
            <Button type="button" class="p-2 absolute left-0 bottom-0" variant="outline"
                @click="showUploadDialog = true">
                <Pencil />
            </Button>
        </div>

        <!-- Upload Dialog -->
        <AlertDialog :open="showUploadDialog && !showCropper" class="w-full">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>
                        Profile Picture
                    </AlertDialogTitle>
                </AlertDialogHeader>
                <div>
                    <Preview v-if="isCroppingFinished" class="w-44 h-44 mx-auto" :image="result.image"
                        :coordinates="result.coordinates" />
                    <img v-else class="w-44 h-44 mx-auto object-cover" :src="userAvatar" alt="user avatar">
                </div>
                <AlertDialogDescription v-if="!isCroppingFinished" class="w-full text-center">
                    JPG and PNG up to 5MB
                    <InputStatusMessage v-if="validationErrors" :message="validationErrors.image" />
                </AlertDialogDescription>
                <AlertDialogFooter class="sm:justify-center">
                    <AlertDialogCancel @click="handleCancelUpload">Cancel</AlertDialogCancel>
                    <AlertDialogAction v-if="isCroppingFinished" @click="handleUpdateAvatar"
                        :disabled="loadingState.uploading">
                        <span v-if="loadingState.uploading">
                            <LoaderCircle class="animate-spin" />
                        </span>
                        <span v-else>Save</span>
                    </AlertDialogAction>
                    <AlertDialogAction v-else class="relative" :disabled="loadingState.validation">
                        <template v-if="loadingState.validation">
                            <LoaderCircle class="animate-spin" />
                        </template>
                        <template v-else>
                            <Input type="file" class="absolute top-0 left-0 opacity-0 w-full h-full cursor-pointer" @change="handleImageUpload" />Upload a photo
                        </template>
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>

        <!-- Cropper Dialog -->
        <AlertDialog :open="showCropper" class="w-full">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle></AlertDialogTitle>
                    <AlertDialogDescription class="w-full">Adjust the crop area to select the part of the image you want
                        to use as your profile picture.</AlertDialogDescription>
                </AlertDialogHeader>
                <div>
                    <Cropper ref="cropper" class="cropper" :src="croppedImageSrc" :default-size="defaultSize"
                        @change="onChange" />
                </div>
                <AlertDialogFooter class="sm:justify-center">
                    <AlertDialogCancel @click="handleCancelCropper">Cancel</AlertDialogCancel>
                    <AlertDialogAction @click="handleCrop">Ok</AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </div>
</template>

<style scoped>
.cropper {
    width: 100%;
    max-width: 462px;
}
</style>